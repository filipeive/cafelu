<?php

if (!function_exists('get_payment_icon_mdi')) {
    function get_payment_icon_mdi($payment_method)
    {
        switch ($payment_method) {
            case 'cash':
                return 'mdi-cash';
            case 'card':
                return 'mdi-credit-card';
            case 'mpesa':
                return 'mdi-phone';
            case 'emola':
                return 'mdi-cellphone-link';
            default:
                return 'mdi-credit-card'; // Default case
        }
    }
}
if (!function_exists('get_payment_icon_staradmin')) {
    function get_payment_icon_staradmin($payment_method)
    {
        switch ($payment_method) {
            case 'cash':
                return 'fas fa-money-bill-wave';
            case 'card':
                return 'fas fa-credit-card';
            case 'mpesa':
                return 'fas fa-mobile-alt';
            case 'emola':
                return 'fas fa-mobile-alt';
            default:
                return 'fas fa-credit-card'; // Default case
        }
    }
}
if (!function_exists('get_status_icon_mdi')) {
    function get_status_icon_mdi($status)
    {
        switch ($status) {
            case 'completed':
                return 'mdi-check-circle';
            case 'pending':
                return 'mdi-clock';
            case 'canceled':
                return 'mdi-close-circle';
            default:
                return 'mdi-help-circle'; // Default case
        }
    }
}
// getStatusBadge
if (!function_exists('getStatusBadge')) {
    function getStatusBadge($status)
    {
        $colors = [
            'pending' => 'bg-warning',
            'preparing' => 'bg-primary',
            'ready' => 'bg-info',
            'delivered' => 'bg-success',
            'completed' => 'bg-success',
            'cancelled' => 'bg-danger',
        ];

        $color = $colors[$status] ?? 'bg-secondary';

        return '<span class="badge ' . $color . '">' . ucfirst($status) . '</span>';
    }
}
//use a logica de funcao: get_status_class_staradmin($sale->status

if (!function_exists('get_status_class_staradmin')) {
    function get_status_class_staradmins($status)
    {
        switch ($status) {
            case 'completed':
                return 'text-success';
            case 'pending':
                return 'text-warning';
            case 'canceled':
                return 'text-danger';
                case 'active':
                return 'text-warning';
            case 'paid':
                return 'text-success';
            case 'preparing':
                return 'text-primary';
            case 'ready':
                return 'text-info';
            case 'delivered':
                return 'text-success';
            case 'cancelled':
                return 'text-danger';
            default:
                return 'text-secondary'; // Default case
        }
    }
}
function get_role_class($role) {
    return match($role) {
        'superadmin' => 'dark',
        'admin' => 'danger',
        'manager' => 'warning',
        'waiter' => 'info',
        default => 'secondary'
    };
}

if (!function_exists('tenant_context')) {
    function tenant_context(): \App\Services\TenantContext
    {
        return app(\App\Services\TenantContext::class);
    }
}

if (!function_exists('current_tenant')) {
    function current_tenant(): ?\App\Models\Tenant
    {
        return tenant_context()->getTenant();
    }
}

if (!function_exists('current_tenant_id')) {
    function current_tenant_id(): ?int
    {
        return tenant_context()->getTenantId();
    }
}

if (!function_exists('is_impersonating')) {
    function is_impersonating(): bool
    {
        return session()->has('is_support_mode') && session('is_support_mode') === true;
    }
}

if (!function_exists('get_receipt_logo_data')) {
    function get_receipt_logo_data(): ?string
    {
        // 1. Check custom system_logo from settings
        $logo = \App\Models\Setting::get('system_logo');
        if ($logo) {
            $storagePaths = [
                storage_path('app/public/' . $logo),
                public_path('storage/' . $logo),
                public_path($logo),
            ];
            foreach ($storagePaths as $filePath) {
                if (file_exists($filePath) && is_file($filePath)) {
                    $mime = @mime_content_type($filePath) ?: 'image/png';
                    $data = base64_encode(file_get_contents($filePath));
                    return "data:{$mime};base64,{$data}";
                }
            }
            return asset('storage/' . $logo);
        }

        // 2. Fallback to default logo
        $defaultPaths = [
            public_path('assets/images/logo.png'),
            public_path('assets/images/Logo.png'),
            public_path('assets/images/favicon.png'),
        ];
        foreach ($defaultPaths as $filePath) {
            if (file_exists($filePath) && is_file($filePath)) {
                $mime = @mime_content_type($filePath) ?: 'image/png';
                $data = base64_encode(file_get_contents($filePath));
                return "data:{$mime};base64,{$data}";
            }
        }

        return asset('assets/images/logo.png');
    }
}