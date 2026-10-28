<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Table;
use App\Models\Tenant;
use App\Models\User;
use App\Services\TenantContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class WelcomeController extends Controller
{
    public function tableEntry(string $table)
    {
        $cleanNumber = preg_replace('/[^0-9]/', '', $table);
        if (!empty($cleanNumber)) {
            $matched = Table::where('number', (int) $cleanNumber)->first();
            if ($matched) {
                session([
                    'public_tenant_id' => $matched->tenant_id,
                    'self_service_table_number' => $cleanNumber,
                    'self_service_table_id' => $matched->id,
                ]);
                return redirect()->route('welcome', ['mesa' => $cleanNumber]);
            }

            // Fallback seguro se a mesa foi eliminada: abre o menu geral sem falhar
            session()->forget(['self_service_table_number', 'self_service_table_id']);
            return redirect()->route('welcome')->with(
                'qr_table_fallback',
                "A Mesa {$cleanNumber} não se encontra ativa no momento. O Menu Digital foi aberto no modo geral — selecione a sua mesa atual ou faça o pedido para levar."
            );
        }
        return redirect()->route('welcome');
    }

    public function tenantTableEntry(string $tenant, string $table)
    {
        $tenantModel = $this->resolveTenantOrFail($tenant);
        app(TenantContext::class)->setTenant($tenantModel);
        session(['public_tenant_id' => $tenantModel->id]);

        $cleanNumber = preg_replace('/[^0-9]/', '', $table);
        if (!empty($cleanNumber)) {
            $matched = Table::where('tenant_id', $tenantModel->id)
                ->where('number', (int) $cleanNumber)
                ->first();

            if ($matched) {
                session([
                    'self_service_table_number' => $cleanNumber,
                    'self_service_table_id' => $matched->id,
                ]);
                return redirect()->route('restaurant.menu', [
                    'tenant' => $tenantModel->slug,
                    'mesa' => $cleanNumber,
                ]);
            }

            session()->forget(['self_service_table_number', 'self_service_table_id']);
            return redirect()->route('restaurant.menu', ['tenant' => $tenantModel->slug])->with(
                'qr_table_fallback',
                "A Mesa {$cleanNumber} não se encontra ativa no momento em {$tenantModel->name}. O Menu Digital foi aberto no modo geral — selecione outra mesa ou faça o pedido para levar."
            );
        }

        return redirect()->route('restaurant.menu', ['tenant' => $tenantModel->slug]);
    }

    public function index(Request $request)
    {
        // Se vier de um QR Code de mesa (?mesa=), de um fallback de mesa, ou se especificar ?restaurante=
        if (
            $request->has('mesa') ||
            $request->has('table') ||
            $request->filled('restaurante') ||
            session()->has('qr_table_fallback')
        ) {
            $tenant = current_tenant();
            if ($request->filled('restaurante')) {
                $tenant = $this->resolveTenantOrFail((string) $request->query('restaurante'));
                app(TenantContext::class)->setTenant($tenant);
                view()->share('currentTenant', $tenant);
                session(['public_tenant_id' => $tenant->id]);
            }

            return $this->renderRestaurantMenu($request, $tenant);
        }

        return $this->network($request);
    }

    public function network(Request $request)
    {
        $context = app(TenantContext::class);

        return $context->withoutTenant(function () use ($request) {
            $search = trim((string) $request->query('q', ''));
            $city = trim((string) $request->query('cidade', ''));

            $tenantsQuery = Tenant::query()
                ->whereIn('status', ['active', 'trial'])
                ->when($search !== '', function ($q) use ($search) {
                    $q->where(function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%")
                            ->orWhere('slug', 'like', "%{$search}%")
                            ->orWhere('city', 'like', "%{$search}%")
                            ->orWhere('address', 'like', "%{$search}%");
                    });
                })
                ->when($city !== '', fn ($q) => $q->where('city', $city))
                ->orderBy('id');

            $restaurants = $tenantsQuery->get()->map(function (Tenant $tenant) {
                $productsCount = Product::withoutGlobalScopes()
                    ->where('tenant_id', $tenant->id)
                    ->where('is_active', true)
                    ->count();

                $categoriesCount = Category::withoutGlobalScopes()
                    ->where('tenant_id', $tenant->id)
                    ->count();

                $tablesCount = Table::withoutGlobalScopes()
                    ->where('tenant_id', $tenant->id)
                    ->count();

                $tenantSettings = Setting::withoutGlobalScopes()
                    ->where('tenant_id', $tenant->id)
                    ->pluck('value', 'key')
                    ->toArray();

                $sampleCategories = Category::withoutGlobalScopes()
                    ->where('tenant_id', $tenant->id)
                    ->take(4)
                    ->pluck('name');

                return [
                    'model'            => $tenant,
                    'id'               => $tenant->id,
                    'name'             => $tenantSettings['company_name'] ?? $tenant->name,
                    'slug'             => $tenant->slug,
                    'city'             => $tenant->city ?: 'Moçambique',
                    'address'          => $tenantSettings['company_address'] ?? $tenant->address ?: 'Endereço disponível no perfil',
                    'phone'            => $tenantSettings['company_phone'] ?? $tenant->phone,
                    'whatsapp'         => $tenantSettings['site_whatsapp'] ?? null,
                    'logo'             => !empty($tenantSettings['system_logo']) ? asset('storage/' . $tenantSettings['system_logo']) : ($tenant->logo ? asset('storage/' . $tenant->logo) : null),
                    'hero_badge'       => $tenantSettings['site_hero_badge'] ?? 'Cardápio Digital & Pedidos Online',
                    'hero_subtitle'    => $tenantSettings['site_hero_subtitle'] ?? 'Explore o nosso menu digital, faça pedidos na mesa via QR Code ou encomende para levar.',
                    'opening_hours'    => $tenantSettings['site_opening_hours'] ?? 'Aberto hoje',
                    'primary_color'    => $tenantSettings['primary_color'] ?? '#f97316',
                    'online_orders'    => ($tenantSettings['site_online_orders'] ?? '1') === '1',
                    'products_count'   => $productsCount,
                    'categories_count' => $categoriesCount,
                    'tables_count'     => $tablesCount,
                    'categories'       => $sampleCategories,
                    'url'              => route('restaurant.menu', ['tenant' => $tenant->slug]),
                    'short_url'        => url('/' . $tenant->slug),
                ];
            });

            $cities = Tenant::query()
                ->whereIn('status', ['active', 'trial'])
                ->whereNotNull('city')
                ->where('city', '!=', '')
                ->distinct()
                ->orderBy('city')
                ->pluck('city');

            return view('welcome-network', compact('restaurants', 'cities', 'search', 'city'));
        });
    }

    public function showRestaurant(Request $request, string $tenant)
    {
        $tenantModel = $this->resolveTenantOrFail($tenant);

        app(TenantContext::class)->setTenant($tenantModel);
        view()->share('currentTenant', $tenantModel);
        session(['public_tenant_id' => $tenantModel->id]);

        return $this->renderRestaurantMenu($request, $tenantModel);
    }

    protected function renderRestaurantMenu(Request $request, ?Tenant $tenant)
    {
        if ($tenant) {
            app(TenantContext::class)->setTenant($tenant);
            view()->share('currentTenant', $tenant);
            session(['public_tenant_id' => $tenant->id]);
        }

        $categories = Category::with([
            'products' => function ($query) {
                $query->where('is_active', true);
            }
        ])->get();

        $featuredProducts = Product::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // Tables
        $tables = Table::orderBy('number', 'asc')->get();

        // Detected table from QR code query parameter or session
        $explicitQueryTable = $request->has('mesa') || $request->has('table');
        $detectedTable = $request->get('mesa', $request->get('table', session('self_service_table_number', '')));
        $rawTableNumber = preg_replace('/[^0-9]/', '', (string) $detectedTable);

        $tableNumber = '';
        $detectedTableId = '';
        $qrFallbackNotice = session('qr_table_fallback');

        if (!empty($rawTableNumber)) {
            $matched = $tables->firstWhere('number', (int) $rawTableNumber);
            if ($matched) {
                $tableNumber = (string) $matched->number;
                $detectedTableId = $matched->id;
                session(['self_service_table_number' => $tableNumber, 'self_service_table_id' => $matched->id]);
            } else {
                // Table was deleted or does not exist: gracefully fall back to general menu
                session()->forget(['self_service_table_number', 'self_service_table_id']);
                if ($explicitQueryTable && !$qrFallbackNotice) {
                    $qrFallbackNotice = "A Mesa {$rawTableNumber} já não se encontra registada. Apresentamos o nosso Menu Geral — pode selecionar outra mesa ou pedir para levar.";
                }
            }
        }

        $slides = collect();
        try {
            $slides = \App\Models\WebsiteSlide::where('is_active', true)
                ->orderBy('sort_order')->orderBy('id')->get();
        } catch (\Throwable $e) {
            // Table may not exist yet on outdated installs
        }

        return view('welcome', compact('categories', 'featuredProducts', 'tables', 'tableNumber', 'detectedTableId', 'slides', 'qrFallbackNotice', 'tenant'));
    }

    protected function resolveTenantOrFail(string $identifier): Tenant
    {
        $tenant = Tenant::where('slug', $identifier)
            ->orWhere('subdomain', $identifier)
            ->when(is_numeric($identifier), fn ($q) => $q->orWhere('id', (int) $identifier))
            ->first();

        abort_if(!$tenant, 404, 'Restaurante não encontrado na rede RestPOS.');

        return $tenant;
    }

    public function storeSelfServiceOrder(Request $request)
    {
        $request->validate([
            'tenant_id'          => 'nullable',
            'customer_name'      => 'nullable|string|max:100',
            'phone'              => 'nullable|string|max:30',
            'table_id'           => 'nullable',
            'table_number'       => 'nullable',
            'service_type'       => 'nullable|string|in:dine_in,takeaway',
            'payment_method'     => 'nullable|string|max:50',
            'notes'              => 'nullable|string|max:500',
            'append_to_order_id' => 'nullable|integer',
            'items'              => 'required|array|min:1',
            'items.*.id'         => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.notes'      => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $tenantId = current_tenant_id();
            $adminId = User::where('tenant_id', $tenantId)->where('role', 'admin')->value('id')
                ?? User::where('role', 'admin')->value('id')
                ?? 1;
            $userId = auth()->id() ?? $adminId;

            // Resolve real table
            $tableId = null;
            $tableDisplay = 'Balcão / Levar';
            $serviceType = $request->input('service_type', 'dine_in');

            // 1. If explicit table_id provided
            if ($request->filled('table_id') && is_numeric($request->table_id)) {
                $table = Table::find($request->table_id);
                if ($table) {
                    $tableId = $table->id;
                    $tableDisplay = 'Mesa ' . $table->number;
                }
            }

            // 2. If table_number was provided (e.g. "1" or "Mesa 1")
            if (!$tableId && $request->filled('table_number')) {
                $cleanNum = preg_replace('/[^0-9]/', '', $request->table_number);
                if (!empty($cleanNum)) {
                    $table = Table::where('number', (int) $cleanNum)->first();
                    if ($table) {
                        $tableId = $table->id;
                        $tableDisplay = 'Mesa ' . $table->number;
                    } else {
                        $tableDisplay = 'Mesa ' . $cleanNum;
                    }
                } else {
                    $tableDisplay = trim($request->table_number);
                }
            }

            // Ensure the real table in DB is marked as occupied
            if ($tableId) {
                $table = Table::find($tableId);
                if ($table) {
                    $table->status = 'occupied';
                    $table->save();
                }
            }

            $customerName = trim($request->customer_name ?: '');
            if (empty($customerName)) {
                $customerName = auth()->check() ? auth()->user()->name : ($tableId ? $tableDisplay : 'Cliente no Balcão');
            }

            // Cumulative Comanda: Check for existing active order for this table or explicit append_to_order_id
            $existingOrder = null;
            if ($request->filled('append_to_order_id')) {
                $existingOrder = Order::where('id', $request->append_to_order_id)
                    ->whereIn('status', ['active', 'preparing'])
                    ->first();
            }

            if (!$existingOrder && $tableId) {
                $existingOrder = Order::where('table_id', $tableId)
                    ->whereIn('status', ['active', 'preparing'])
                    ->latest()
                    ->first();
            }

            $isAppended = false;
            $newItemsTotal = 0;

            if ($existingOrder) {
                // Append items to existing comanda
                $order = $existingOrder;
                $isAppended = true;

                // Add notes if provided
                if ($request->filled('notes')) {
                    $order->notes = ($order->notes ? $order->notes . "\n" : '') . '[Item Adicional]: ' . trim($request->notes);
                }

                // If order was already marked ready, revert to preparing for kitchen attention
                if ($order->status === 'ready') {
                    $order->status = 'preparing';
                }
            } else {
                // Build initial notes tag
                $notesPrefix = '[Digital / Autoatendimento]';
                if ($serviceType === 'takeaway') {
                    $notesPrefix .= ' [Para Levar / Takeaway]';
                }
                if ($request->filled('phone')) {
                    $notesPrefix .= ' [Tel: ' . trim($request->phone) . ']';
                }
                $initialNotes = $notesPrefix . ($request->filled('notes') ? "\n" . trim($request->notes) : '');

                $rawMethod = $request->input('payment_method', 'cash');
                $paymentMethod = match ($rawMethod) {
                    'counter' => 'cash',
                    'cash', 'card', 'mpesa', 'emola', 'mkesh' => $rawMethod,
                    default => 'cash'
                };

                $order = Order::create([
                    'tenant_id'      => $tenantId,
                    'user_id'        => $userId,
                    'table_id'       => $tableId,
                    'customer_name'  => $customerName,
                    'status'         => 'active',
                    'payment_status' => 'pending',
                    'payment_method' => $paymentMethod,
                    'notes'          => $initialNotes,
                    'total_amount'   => 0,
                ]);
            }

            foreach ($request->items as $itemData) {
                $product = Product::findOrFail($itemData['id']);
                $qty = (int) $itemData['quantity'];
                $unitPrice = $product->price ?? $product->selling_price;
                $itemTotal = $unitPrice * $qty;

                $itemNote = !empty($itemData['notes']) ? trim($itemData['notes']) : null;
                if ($isAppended) {
                    $itemNote = $itemNote ? '[Novo] ' . $itemNote : '[Novo Item Adicionado]';
                }

                OrderItem::create([
                    'order_id'    => $order->id,
                    'product_id'  => $product->id,
                    'quantity'    => $qty,
                    'unit_price'  => $unitPrice,
                    'total_price' => $itemTotal,
                    'notes'       => $itemNote,
                    'status'      => 'pending'
                ]);

                $newItemsTotal += $itemTotal;
            }

            $order->total_amount = (float) $order->total_amount + $newItemsTotal;
            $order->save();

            DB::commit();

            // Notify staff belonging to this tenant
            try {
                $staff = User::where('tenant_id', $tenantId)
                    ->whereIn('role', ['admin', 'manager', 'waiter', 'cashier'])
                    ->get();
                if ($staff->isNotEmpty()) {
                    Notification::send($staff, new \App\Notifications\NewOrderNotification($order));
                }
            } catch (\Exception $e) {
                // Ignore notification failure
            }

            $currency = Setting::get('currency_symbol', 'MT');

            return response()->json([
                'success'      => true,
                'is_appended'  => $isAppended,
                'message'      => $isAppended
                    ? 'Novos itens adicionados à sua comanda (' . $tableDisplay . ') com sucesso!'
                    : 'Pedido enviado para a cozinha com sucesso!',
                'order_id'     => $order->id,
                'status'       => $order->status,
                'table_name'   => $tableDisplay,
                'total'        => number_format($order->total_amount, 2, '.', ' ') . ' ' . $currency,
                'added_amount' => number_format($newItemsTotal, 2, '.', ' ') . ' ' . $currency,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar o pedido: ' . $e->getMessage()
            ], 500);
        }
    }

    public function orderStatus(Order $order)
    {
        $order->load(['table', 'items.product', 'user']);

        $statusStep = match ($order->status) {
            'active'    => 1, // Recebido
            'preparing' => 2, // Em Preparo
            'ready'     => 3, // Pronto
            'delivered', 'completed' => 4, // Entregue / Concluído
            'canceled'  => 0, // Cancelado
            default     => 1
        };

        $statusLabel = match ($order->status) {
            'active'    => 'Recebido na Cozinha',
            'preparing' => 'Em Preparação',
            'ready'     => 'Pronto para Servir',
            'delivered' => 'Entregue na Mesa',
            'completed' => 'Finalizado',
            'canceled'  => 'Cancelado',
            default     => ucfirst($order->status)
        };

        $currency = Setting::get('currency_symbol', 'MT', $order->tenant_id);

        $paymentMethodLabel = match ($order->payment_method) {
            'cash'    => 'No Balcão / Dinheiro',
            'mpesa'   => 'M-Pesa',
            'emola'   => 'E-Mola',
            'card'    => 'Cartão',
            default   => 'No Balcão'
        };

        return response()->json([
            'success'               => true,
            'id'                    => $order->id,
            'status'                => $order->status,
            'status_step'           => $statusStep,
            'status_label'          => $statusLabel,
            'table_name'            => $order->table ? $order->table->name : 'Balcão / Viagem',
            'table_id'              => $order->table_id,
            'customer_name'         => $order->customer_name ?: 'Cliente',
            'payment_method'        => $order->payment_method ?? 'cash',
            'payment_method_label'  => $paymentMethodLabel,
            'payment_status'        => $order->payment_status ?? 'pending',
            'total'                 => (float) $order->total_amount,
            'total_formatted'       => number_format((float) $order->total_amount, 2, '.', ' ') . ' ' . $currency,
            'elapsed_min'           => $order->created_at ? $order->created_at->diffInMinutes(now()) : 0,
            'created_at_fmt'        => $order->created_at ? $order->created_at->format('H:i') : '--:--',
            'preparing_at_fmt'      => $order->preparing_at ? \Carbon\Carbon::parse($order->preparing_at)->format('H:i') : null,
            'ready_at_fmt'          => $order->ready_at ? \Carbon\Carbon::parse($order->ready_at)->format('H:i') : null,
            'delivered_at_fmt'      => $order->delivered_at ? \Carbon\Carbon::parse($order->delivered_at)->format('H:i') : null,
            'items_count'           => $order->items->sum('quantity'),
            'items'                 => $order->items->map(function ($item) use ($currency) {
                return [
                    'id'              => $item->id,
                    'name'            => $item->product ? $item->product->name : ($item->notes ?? 'Item'),
                    'quantity'        => $item->quantity,
                    'unit_price'      => (float) $item->unit_price,
                    'total_price'     => (float) $item->total_price,
                    'total_formatted' => number_format((float) $item->total_price, 2, '.', ' ') . ' ' . $currency,
                    'notes'           => $item->notes,
                    'is_new'          => str_contains($item->notes ?? '', '[Novo]'),
                    'image_url'       => $item->product?->image_url,
                ];
            })
        ]);
    }

    public function callWaiter(Request $request)
    {
        $table = trim((string) $request->input('table', 'Balcão / Salão')) ?: 'Balcão / Salão';
        $name = trim((string) $request->input('name', 'Cliente')) ?: 'Cliente';
        $reason = trim((string) $request->input('reason', 'Solicitou atendimento na mesa')) ?: 'Solicitou atendimento na mesa';
        $tenantId = current_tenant_id();

        try {
            $staff = User::where('tenant_id', $tenantId)
                ->whereIn('role', ['admin', 'manager', 'waiter', 'cashier'])
                ->get();
            if ($staff->isNotEmpty()) {
                Notification::send($staff, new \App\Notifications\WaiterCalledNotification($table, $name, $reason));
            }
        } catch (\Throwable $e) {
            // ignore notification error
        }

        return response()->json([
            'success' => true,
            'message' => 'O garçom foi chamado para ' . $table . '! A nossa equipa já recebeu o alerta sonoro.'
        ]);
    }
}
