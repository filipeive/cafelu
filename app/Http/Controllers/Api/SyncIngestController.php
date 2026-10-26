<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StockMovement;
use App\Models\Table;
use App\Models\Tenant;
use App\Models\User;
use App\Models\LicenseKey;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SyncIngestController extends Controller
{
    /**
     * Valida uma Chave de Licença do RestPOS na Nuvem e devolve metadados do Restaurante, Limites e URL de Sync.
     */
    public function verifyLicense(Request $request): JsonResponse
    {
        $licenseKey = trim((string)($request->input('license_key') ?? $request->header('X-License-Key')));

        if (empty($licenseKey)) {
            return response()->json([
                'valid'   => false,
                'message' => 'Por favor forneça a chave de licença.',
            ], 422);
        }

        $license = LicenseKey::withoutGlobalScopes()
            ->with('tenant')
            ->where(function($q) use ($licenseKey) {
                $q->where('key_code', $licenseKey)
                  ->orWhere('key_hash', hash('sha256', $licenseKey));
            })
            ->first();

        if (!$license) {
            return response()->json([
                'valid'   => false,
                'message' => 'Chave de licença não encontrada no servidor central da nuvem.',
            ], 404);
        }

        if ($license->status === 'revoked') {
            return response()->json([
                'valid'   => false,
                'message' => 'Esta chave de licença foi revogada pelo administrador.',
            ], 403);
        }

        if ($license->isExpired()) {
            return response()->json([
                'valid'   => false,
                'message' => 'Esta chave de licença expirou em ' . ($license->expires_at ? $license->expires_at->format('d/m/Y') : 'data desconhecida') . '.',
            ], 403);
        }

        $tenant = $license->tenant;

        return response()->json([
            'valid'         => true,
            'message'       => 'Licença válida e autorizada no servidor central.',
            'license'       => [
                'id'         => $license->id,
                'key_code'   => $license->key_code,
                'status'     => $license->status,
                'starts_at'  => $license->starts_at?->toIso8601String(),
                'expires_at' => $license->expires_at?->toIso8601String(),
                'signature'  => $license->signature,
            ],
            'tenant'        => $tenant ? [
                'id'                => $tenant->id,
                'slug'              => $tenant->slug,
                'name'              => $tenant->name,
                'email'             => $tenant->email,
                'phone'             => $tenant->phone,
                'nuit'              => $tenant->nuit,
                'installation_mode' => $tenant->installation_mode,
                'plan'              => $tenant->plan,
                'max_tables'        => $tenant->max_tables,
                'max_users'         => $tenant->max_users,
            ] : null,
            'sync_config'   => [
                'ingest_url' => url('/api/sync/ingest'),
                'sync_token' => $license->key_code,
            ],
        ]);
    }

    /**
     * Endpoint central para ingestão de dados sincronizados da máquina local para a nuvem.
     * Estritamente idempotente via offline_id, com mapeamento automático de Tenant, Categorias, Produtos, Vendas e Pedidos.
     */
    public function ingest(Request $request): JsonResponse
    {
        // 1. Autenticação por Token (Master Token ou Chave Serial do Tenant)
        $providedToken = $request->header('X-Sync-Token')
            ?? $request->bearerToken()
            ?? $request->input('sync_token');

        $expectedToken = config('services.sync.token', env('SYNC_TOKEN', 'restpos_sync_default_token'));

        $isMasterToken = $providedToken && hash_equals((string)$expectedToken, (string)$providedToken);
        $licenseTenantId = null;

        if (!$isMasterToken && $providedToken) {
            $license = LicenseKey::withoutGlobalScopes()
                ->where('key_code', trim($providedToken))
                ->whereIn('status', ['active', 'issued'])
                ->first();
            if ($license) {
                $licenseTenantId = $license->tenant_id;
            }
        }

        if (!$isMasterToken && !$licenseTenantId) {
            return response()->json([
                'success' => false,
                'message' => 'Token de sincronização inválido ou ausente. Forneça o Sync Token da infraestrutura ou a Chave Serial da Licença.',
            ], 401);
        }

        // 2. Validação da Carga Útil
        $request->validate([
            'tenant_id'       => 'nullable|integer',
            'tenant_slug'     => 'nullable|string|max:100',
            'source_instance' => 'nullable|string|max:100',
            'categories'      => 'nullable|array',
            'products'        => 'nullable|array',
            'sales'           => 'nullable|array',
            'orders'          => 'nullable|array',
            'stock_movements' => 'nullable|array',
        ]);

        // Resolução de Tenant
        $tenantSlug = $request->input('tenant_slug');
        $tenantId = (int)$request->input('tenant_id');

        $tenant = null;
        if ($licenseTenantId) {
            $tenant = Tenant::withoutGlobalScopes()->find($licenseTenantId);
        }
        if (!$tenant && $tenantSlug) {
            $tenant = Tenant::withoutGlobalScopes()->where('slug', $tenantSlug)->first();
        }
        if (!$tenant && $tenantId) {
            $tenant = Tenant::withoutGlobalScopes()->find($tenantId);
        }

        if (!$tenant || !in_array($tenant->status, ['active', 'trial'])) {
            return response()->json([
                'success' => false,
                'message' => 'Restaurante / Tenant de destino não encontrado ou inativo na nuvem.',
            ], 422);
        }

        $resolvedTenantId = $tenant->id;

        // Atualizar heartbeat / última sincronização
        $tenant->updateQuietly([
            'last_synced_at' => now(),
            'installation_mode' => $tenant->installation_mode === 'offline' ? 'local_online' : $tenant->installation_mode,
        ]);

        // Pré-carregar utilizador padrão para FKs
        $defaultUser = User::withoutGlobalScopes()->where('tenant_id', $resolvedTenantId)->first();

        // 3. Processamento de Categorias (Auto-Upsert)
        $incomingCategories = $request->input('categories', []);
        $createdCategoriesCount = 0;
        foreach ($incomingCategories as $catData) {
            $catName = trim($catData['name'] ?? '');
            if (empty($catName)) continue;

            Category::withoutGlobalScopes()->updateOrCreate(
                ['tenant_id' => $resolvedTenantId, 'name' => $catName],
                [
                    'description' => $catData['description'] ?? null,
                    'is_active'   => (bool)($catData['is_active'] ?? true),
                ]
            );
            $createdCategoriesCount++;
        }

        // 4. Processamento de Produtos (Auto-Upsert)
        $incomingProducts = $request->input('products', []);
        $createdProductsCount = 0;
        foreach ($incomingProducts as $pData) {
            $name = trim($pData['name'] ?? '');
            if (empty($name)) continue;

            $catId = null;
            if (!empty($pData['category_name'])) {
                $c = Category::withoutGlobalScopes()->firstOrCreate(
                    ['tenant_id' => $resolvedTenantId, 'name' => trim($pData['category_name'])],
                    ['is_active' => true]
                );
                $catId = $c->id;
            }

            $product = Product::withoutGlobalScopes()
                ->where('tenant_id', $resolvedTenantId)
                ->where('name', $name)
                ->first();

            $pAttributes = [
                'tenant_id'      => $resolvedTenantId,
                'category_id'    => $catId ?? ($product?->category_id),
                'name'           => $name,
                'description'    => $pData['description'] ?? null,
                'price'          => (float)($pData['price'] ?? $pData['selling_price'] ?? $product?->price ?? 0),
                'selling_price'  => (float)($pData['selling_price'] ?? $pData['price'] ?? $product?->selling_price ?? 0),
                'purchase_price' => (float)($pData['purchase_price'] ?? $pData['cost_price'] ?? $product?->purchase_price ?? 0),
                'stock_quantity' => isset($pData['stock_quantity']) ? (int)$pData['stock_quantity'] : (isset($pData['stock']) ? (int)$pData['stock'] : ($product?->stock_quantity ?? 0)),
                'min_stock_level'=> (int)($pData['min_stock_level'] ?? $pData['min_stock'] ?? $product?->min_stock_level ?? 5),
                'is_active'      => isset($pData['is_active']) ? (bool)$pData['is_active'] : true,
            ];

            if ($product) {
                $product->update($pAttributes);
            } else {
                Product::create($pAttributes);
            }
            $createdProductsCount++;
        }

        // Helper para resolver ou criar Produto para itens de venda
        $resolveProduct = function(array $itemData) use ($resolvedTenantId) {
            $name = !empty($itemData['product_name']) ? trim($itemData['product_name']) : null;
            $prodId = !empty($itemData['product_id']) ? (int)$itemData['product_id'] : null;

            if ($name) {
                $p = Product::withoutGlobalScopes()
                    ->where('tenant_id', $resolvedTenantId)
                    ->where('name', $name)
                    ->first();
                if ($p) return $p;
            }

            if ($prodId) {
                $p = Product::withoutGlobalScopes()
                    ->where('tenant_id', $resolvedTenantId)
                    ->where('id', $prodId)
                    ->first();
                if ($p) return $p;
            }

            return Product::create([
                'tenant_id'   => $resolvedTenantId,
                'name'        => $name ?: ('Artigo Sincronizado #' . ($prodId ?: uniqid())),
                'price'       => (float)($itemData['unit_price'] ?? $itemData['price'] ?? 0),
                'stock'       => 0,
                'is_active'   => true,
            ]);
        };

        // 5. Ingestão Idempotente de Vendas
        $incomingSales = $request->input('sales', []);
        $syncedSaleOfflineIds = [];
        $createdSalesCount = 0;
        $skippedSalesCount = 0;

        foreach ($incomingSales as $sData) {
            $offlineId = $sData['offline_id'] ?? null;
            if (!$offlineId) {
                continue;
            }

            $existingSale = Sale::withoutGlobalScopes()
                ->where('tenant_id', $resolvedTenantId)
                ->where('offline_id', $offlineId)
                ->first();

            if ($existingSale) {
                $syncedSaleOfflineIds[] = $offlineId;
                $skippedSalesCount++;
                continue;
            }

            DB::beginTransaction();
            try {
                $sale = Sale::create([
                    'tenant_id'      => $resolvedTenantId,
                    'offline_id'     => $offlineId,
                    'user_id'        => $defaultUser?->id,
                    'customer_name'  => $sData['customer_name'] ?? null,
                    'total_amount'   => (float)($sData['total_amount'] ?? 0),
                    'payment_method' => $sData['payment_method'] ?? 'cash',
                    'cash_amount'    => (float)($sData['cash_amount'] ?? 0),
                    'card_amount'    => (float)($sData['card_amount'] ?? 0),
                    'mpesa_amount'   => (float)($sData['mpesa_amount'] ?? 0),
                    'emola_amount'   => (float)($sData['emola_amount'] ?? 0),
                    'notes'          => $sData['notes'] ?? null,
                    'status'         => $sData['status'] ?? 'completed',
                    'synced_at'      => now(),
                    'created_at'     => !empty($sData['created_at']) ? \Carbon\Carbon::parse($sData['created_at']) : now(),
                ]);

                // Itens da Venda
                $items = $sData['items'] ?? [];
                foreach ($items as $itemData) {
                    $prod = $resolveProduct($itemData);
                    SaleItem::create([
                        'tenant_id'   => $resolvedTenantId,
                        'sale_id'     => $sale->id,
                        'product_id'  => $prod->id,
                        'quantity'    => (int)($itemData['quantity'] ?? 1),
                        'unit_price'  => (float)($itemData['unit_price'] ?? $prod->price),
                        'subtotal'    => (float)($itemData['subtotal'] ?? (($itemData['quantity'] ?? 1) * ($itemData['unit_price'] ?? $prod->price))),
                    ]);
                }

                DB::commit();
                $syncedSaleOfflineIds[] = $offlineId;
                $createdSalesCount++;
            } catch (\Throwable $e) {
                DB::rollBack();
                Log::error("Erro ao sincronizar venda offline {$offlineId}: " . $e->getMessage());
            }
        }

        // 6. Ingestão Idempotente de Pedidos (Orders)
        $incomingOrders = $request->input('orders', []);
        $syncedOrderOfflineIds = [];
        $createdOrdersCount = 0;

        foreach ($incomingOrders as $oData) {
            $offlineId = $oData['offline_id'] ?? null;
            if (!$offlineId) {
                continue;
            }

            $existingOrder = Order::withoutGlobalScopes()
                ->where('tenant_id', $resolvedTenantId)
                ->where('offline_id', $offlineId)
                ->first();

            if ($existingOrder) {
                $syncedOrderOfflineIds[] = $offlineId;
                continue;
            }

            // Resolver mesa
            $tableId = null;
            if (!empty($oData['table_number'])) {
                $tbl = Table::withoutGlobalScopes()
                    ->where('tenant_id', $resolvedTenantId)
                    ->where('table_number', $oData['table_number'])
                    ->first();
                $tableId = $tbl?->id;
            }

            DB::beginTransaction();
            try {
                $order = Order::create([
                    'tenant_id'      => $resolvedTenantId,
                    'offline_id'     => $offlineId,
                    'table_id'       => $tableId,
                    'user_id'        => $defaultUser?->id,
                    'customer_name'  => $oData['customer_name'] ?? null,
                    'status'         => $oData['status'] ?? 'completed',
                    'total_amount'   => (float)($oData['total_amount'] ?? 0),
                    'payment_status' => $oData['payment_status'] ?? 'paid',
                    'payment_method' => $oData['payment_method'] ?? 'cash',
                    'notes'          => $oData['notes'] ?? null,
                    'synced_at'      => now(),
                    'created_at'     => !empty($oData['created_at']) ? \Carbon\Carbon::parse($oData['created_at']) : now(),
                ]);

                $items = $oData['items'] ?? [];
                foreach ($items as $itemData) {
                    $prod = $resolveProduct($itemData);
                    OrderItem::create([
                        'tenant_id'  => $resolvedTenantId,
                        'order_id'   => $order->id,
                        'product_id' => $prod->id,
                        'quantity'   => (int)($itemData['quantity'] ?? 1),
                        'unit_price' => (float)($itemData['unit_price'] ?? $prod->price),
                        'subtotal'   => (float)($itemData['subtotal'] ?? (($itemData['quantity'] ?? 1) * ($itemData['unit_price'] ?? $prod->price))),
                        'notes'      => $itemData['notes'] ?? null,
                    ]);
                }

                DB::commit();
                $syncedOrderOfflineIds[] = $offlineId;
                $createdOrdersCount++;
            } catch (\Throwable $e) {
                DB::rollBack();
                Log::error("Erro ao sincronizar pedido offline {$offlineId}: " . $e->getMessage());
            }
        }

        // 7. Ingestão Idempotente de Movimentos de Estoque
        $incomingMovements = $request->input('stock_movements', []);
        $syncedMovementOfflineIds = [];
        $createdMovementsCount = 0;

        foreach ($incomingMovements as $mData) {
            $offlineId = $mData['offline_id'] ?? null;
            if (!$offlineId) continue;

            $existing = StockMovement::withoutGlobalScopes()
                ->where('tenant_id', $resolvedTenantId)
                ->where('offline_id', $offlineId)
                ->first();

            if ($existing) {
                $syncedMovementOfflineIds[] = $offlineId;
                continue;
            }

            $prod = $resolveProduct($mData);
            StockMovement::create([
                'tenant_id'      => $resolvedTenantId,
                'offline_id'     => $offlineId,
                'product_id'     => $prod->id,
                'user_id'        => $defaultUser?->id,
                'quantity'       => (int)($mData['quantity'] ?? 0),
                'type'           => $mData['type'] ?? 'out',
                'reference_type' => $mData['reference_type'] ?? 'sale',
                'notes'          => $mData['notes'] ?? null,
                'synced_at'      => now(),
                'created_at'     => !empty($mData['created_at']) ? \Carbon\Carbon::parse($mData['created_at']) : now(),
            ]);

            $syncedMovementOfflineIds[] = $offlineId;
            $createdMovementsCount++;
        }

        return response()->json([
            'success'               => true,
            'message'               => 'Sincronização processada com sucesso no servidor central da nuvem.',
            'tenant'                => [
                'id'   => $tenant->id,
                'name' => $tenant->name,
                'slug' => $tenant->slug,
            ],
            'categories_processed'  => $createdCategoriesCount,
            'products_processed'    => $createdProductsCount,
            'sales_created'         => $createdSalesCount,
            'sales_skipped'         => $skippedSalesCount,
            'orders_created'        => $createdOrdersCount,
            'movements_created'     => $createdMovementsCount,
            'synced_sale_ids'       => $syncedSaleOfflineIds,
            'synced_order_ids'      => $syncedOrderOfflineIds,
            'synced_movement_ids'   => $syncedMovementOfflineIds,
            'server_time'           => now()->toIso8601String(),
        ]);
    }

    /**
     * Healthcheck do serviço de sincronização
     */
    public function status(): JsonResponse
    {
        return response()->json([
            'service'     => 'RestPOS Cloud Sync Service',
            'status'      => 'online',
            'version'     => 'v1.3',
            'server_time' => now()->toIso8601String(),
        ]);
    }
}

