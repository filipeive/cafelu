<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Order;
use App\Models\StockMovement;
use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncPushCommand extends Command
{
    protected $signature = 'restpos:sync-push
                            {--tenant= : ID ou Slug do Restaurante / Tenant específico para sincronizar}
                            {--limit=50 : Quantidade máxima de registos comerciais por lote}
                            {--dry-run : Apenas simula o envio sem contactar a nuvem ou alterar registos}';

    protected $description = 'Sincroniza vendas, pedidos, catálogo e estoque locais para o servidor central na nuvem de forma assíncrona e idempotente';

    public function handle(): int
    {
        $this->info("==============================================================================");
        $this->info("   RestPOS — SINCRONIZAÇÃO ASSÍNCRONA LOCAL -> NUVEM");
        $this->info("==============================================================================");

        $cloudUrl = config('services.sync.cloud_url', 'http://146.235.224.99/RestPOS/api/sync/ingest');
        $syncToken = config('services.sync.token', env('SYNC_TOKEN', env('LICENSE_KEY', 'restpos_sync_default_token')));
        $configuredSlug = config('services.sync.tenant_slug', env('SYNC_TENANT_SLUG'));
        $limit = (int)$this->option('limit');
        $dryRun = (bool)$this->option('dry-run');

        // Determinar Tenants a sincronizar
        $tenantOption = $this->option('tenant') ?: $configuredSlug;
        if ($tenantOption) {
            $tenants = Tenant::withoutGlobalScopes()
                ->where('id', $tenantOption)
                ->orWhere('slug', $tenantOption)
                ->get();
        } else {
            $tenants = Tenant::withoutGlobalScopes()->whereIn('status', ['active', 'trial'])->get();
        }

        if ($tenants->isEmpty()) {
            $this->warn("Nenhum restaurante ativo encontrado para sincronização.");
            return Command::SUCCESS;
        }

        $totalSyncedSales = 0;
        $totalSyncedOrders = 0;
        $totalSyncedMovements = 0;

        foreach ($tenants as $tenant) {
            $this->line("A verificar registos pendentes para: <comment>{$tenant->name}</comment> (Slug: {$tenant->slug})...");

            // 1. Buscar Categorias Locais
            $localCategories = Category::withoutGlobalScopes()
                ->where('tenant_id', $tenant->id)
                ->get()
                ->map(fn($c) => [
                    'name'        => $c->name,
                    'description' => $c->description,
                    'is_active'   => (bool)$c->is_active,
                ])->toArray();

            // 2. Buscar Produtos Locais
            $localProducts = Product::withoutGlobalScopes()
                ->where('tenant_id', $tenant->id)
                ->with('category')
                ->get()
                ->map(fn($p) => [
                    'name'            => $p->name,
                    'description'     => $p->description,
                    'category_name'   => $p->category?->name,
                    'price'           => (float)($p->price ?? $p->selling_price ?? 0),
                    'selling_price'   => (float)($p->selling_price ?? $p->price ?? 0),
                    'purchase_price'  => (float)($p->purchase_price ?? $p->cost_price ?? 0),
                    'stock_quantity'  => (int)($p->stock_quantity ?? 0),
                    'min_stock_level' => (int)($p->min_stock_level ?? 5),
                    'is_active'       => $p->is_active !== null ? (bool)$p->is_active : true,
                ])->toArray();

            // 3. Buscar Vendas Pendentes
            $pendingSales = Sale::withoutGlobalScopes()
                ->where('tenant_id', $tenant->id)
                ->whereNull('synced_at')
                ->with(['saleItems.product'])
                ->limit($limit)
                ->get();

            // 4. Buscar Pedidos Pendentes
            $pendingOrders = Order::withoutGlobalScopes()
                ->where('tenant_id', $tenant->id)
                ->whereNull('synced_at')
                ->with(['items.product', 'table'])
                ->limit($limit)
                ->get();

            // 5. Buscar Movimentações de Estoque Pendentes
            $pendingMovements = StockMovement::withoutGlobalScopes()
                ->where('tenant_id', $tenant->id)
                ->whereNull('synced_at')
                ->with(['product'])
                ->limit($limit)
                ->get();

            $this->line("   -> Catálogo Local: <info>" . count($localCategories) . " categorias</info>, <info>" . count($localProducts) . " produtos</info>.");

            if ($pendingSales->isEmpty() && $pendingOrders->isEmpty() && $pendingMovements->isEmpty()) {
                $this->line("   -> Sem transações pendentes. Enviando pacote de alinhamento de catálogo e status...");
            } else {
                $this->line("   -> Pendentes: <info>{$pendingSales->count()} vendas</info>, <info>{$pendingOrders->count()} pedidos</info>, <info>{$pendingMovements->count()} estoques</info>.");
            }

            // 6. Montar Carga Útil Idempotente de Vendas
            $salesPayload = [];
            foreach ($pendingSales as $sale) {
                $offlineId = $sale->offline_id ?: ('OFF-SALE-' . $sale->id . '-' . $sale->created_at->timestamp);
                if (!$sale->offline_id) {
                    $sale->updateQuietly(['offline_id' => $offlineId]);
                }

                $itemsPayload = [];
                foreach ($sale->saleItems as $item) {
                    $itemsPayload[] = [
                        'product_id'   => $item->product_id,
                        'product_name' => $item->product?->name,
                        'quantity'     => (int)$item->quantity,
                        'unit_price'   => (float)$item->unit_price,
                        'subtotal'     => (float)$item->subtotal,
                    ];
                }

                $salesPayload[] = [
                    'offline_id'     => $offlineId,
                    'customer_name'  => $sale->customer_name,
                    'total_amount'   => (float)$sale->total_amount,
                    'payment_method' => $sale->payment_method,
                    'cash_amount'    => (float)$sale->cash_amount,
                    'card_amount'    => (float)$sale->card_amount,
                    'mpesa_amount'   => (float)$sale->mpesa_amount,
                    'emola_amount'   => (float)$sale->emola_amount,
                    'notes'          => $sale->notes,
                    'status'         => $sale->status,
                    'created_at'     => $sale->created_at->toIso8601String(),
                    'items'          => $itemsPayload,
                ];
            }

            // 7. Montar Carga Útil Idempotente de Pedidos
            $ordersPayload = [];
            foreach ($pendingOrders as $order) {
                $offlineId = $order->offline_id ?: ('OFF-ORD-' . $order->id . '-' . $order->created_at->timestamp);
                if (!$order->offline_id) {
                    $order->updateQuietly(['offline_id' => $offlineId]);
                }

                $itemsPayload = [];
                foreach ($order->items as $item) {
                    $itemsPayload[] = [
                        'product_id'   => $item->product_id,
                        'product_name' => $item->product?->name,
                        'quantity'     => (int)$item->quantity,
                        'unit_price'   => (float)$item->unit_price,
                        'subtotal'     => (float)$item->subtotal,
                        'notes'        => $item->notes,
                    ];
                }

                $ordersPayload[] = [
                    'offline_id'     => $offlineId,
                    'table_number'   => $order->table?->table_number,
                    'customer_name'  => $order->customer_name,
                    'status'         => $order->status,
                    'total_amount'   => (float)$order->total_amount,
                    'payment_status' => $order->payment_status,
                    'payment_method' => $order->payment_method,
                    'notes'          => $order->notes,
                    'created_at'     => $order->created_at->toIso8601String(),
                    'items'          => $itemsPayload,
                ];
            }

            // 8. Montar Movimentos de Estoque
            $movementsPayload = [];
            foreach ($pendingMovements as $mov) {
                $offlineId = $mov->offline_id ?: ('OFF-STK-' . $mov->id . '-' . $mov->created_at->timestamp);
                if (!$mov->offline_id) {
                    $mov->updateQuietly(['offline_id' => $offlineId]);
                }

                $movementsPayload[] = [
                    'offline_id'     => $offlineId,
                    'product_name'   => $mov->product?->name,
                    'quantity'       => (int)$mov->quantity,
                    'type'           => $mov->type,
                    'reference_type' => $mov->reference_type,
                    'notes'          => $mov->notes,
                    'created_at'     => $mov->created_at->toIso8601String(),
                ];
            }

            $payload = [
                'tenant_id'       => $tenant->id,
                'tenant_slug'     => $tenant->slug,
                'source_instance' => gethostname() ?: 'restpos-local',
                'categories'      => $localCategories,
                'products'        => $localProducts,
                'sales'           => $salesPayload,
                'orders'          => $ordersPayload,
                'stock_movements' => $movementsPayload,
            ];

            if ($dryRun) {
                $this->warn("   [DRY-RUN] Simulação concluída. Nenhum dado foi enviado à nuvem.");
                continue;
            }

            $this->line("   A enviar pacote de sincronização para: <comment>{$cloudUrl}</comment>...");

            try {
                // Se o tenant tem chave de licença própria, utiliza como token prioritário
                $tokenToUse = $tenant->activeLicense?->key_code ?? $syncToken;

                $response = Http::withHeaders([
                    'X-Sync-Token' => $tokenToUse,
                    'Accept'       => 'application/json',
                ])->timeout(30)->post($cloudUrl, $payload);

                if (!$response->successful()) {
                    $this->error("   [ERRO HTTP {$response->status()}] Resposta: " . $response->body());
                    Log::error("Falha no Sync RestPOS para {$tenant->slug}: " . $response->body());
                    continue;
                }

                $resData = $response->json();

                if (!($resData['success'] ?? false)) {
                    $this->error("   [FALHA NA NUVEM] " . ($resData['message'] ?? 'Erro desconhecido.'));
                    continue;
                }

                // 9. Marcar Registos Locais como Sincronizados
                $syncedSaleIds = $resData['synced_sale_ids'] ?? [];
                if (!empty($syncedSaleIds)) {
                    Sale::withoutGlobalScopes()
                        ->where('tenant_id', $tenant->id)
                        ->whereIn('offline_id', $syncedSaleIds)
                        ->update(['synced_at' => now()]);
                    $totalSyncedSales += count($syncedSaleIds);
                }

                $syncedOrderIds = $resData['synced_order_ids'] ?? [];
                if (!empty($syncedOrderIds)) {
                    Order::withoutGlobalScopes()
                        ->where('tenant_id', $tenant->id)
                        ->whereIn('offline_id', $syncedOrderIds)
                        ->update(['synced_at' => now()]);
                    $totalSyncedOrders += count($syncedOrderIds);
                }

                $syncedMovementIds = $resData['synced_movement_ids'] ?? [];
                if (!empty($syncedMovementIds)) {
                    StockMovement::withoutGlobalScopes()
                        ->where('tenant_id', $tenant->id)
                        ->whereIn('offline_id', $syncedMovementIds)
                        ->update(['synced_at' => now()]);
                    $totalSyncedMovements += count($syncedMovementIds);
                }

                $tenant->updateQuietly(['last_synced_at' => now()]);

                $this->info("   [SUCESSO] Sincronização concluída com o servidor central!");
                $this->line("   -> Vendas transmitidas: <info>" . count($syncedSaleIds) . "</info>");
                $this->line("   -> Pedidos transmitidos: <info>" . count($syncedOrderIds) . "</info>");
                $this->line("   -> Movimentos de estoque: <info>" . count($syncedMovementIds) . "</info>");

            } catch (\Throwable $e) {
                $this->error("   [EXCEÇÃO] " . $e->getMessage());
                Log::error("Exceção no sync-push RestPOS: " . $e->getMessage());
            }
        }

        $this->info("==============================================================================");
        $this->info("   TOTAL SINCRONIZADO: {$totalSyncedSales} vendas, {$totalSyncedOrders} pedidos, {$totalSyncedMovements} movimentações");
        $this->info("==============================================================================");

        return Command::SUCCESS;
    }
}

