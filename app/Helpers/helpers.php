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
        switch ($status) {
            case 'completed':
                return 'badge badge-success';
            case 'pending':
                return 'badge badge-warning';
            case 'canceled':
                return 'badge badge-danger';
            default:
                return 'badge badge-secondary'; // Default case
        }
    }
}

// get_status_class_staradmin($sale->status
if (!function_exists('get_status_class_staradmin')) {
    function get_status_class_staradmin2($status)
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
            default:
                return 'text-secondary'; // Default case
        }
    }
}
if (!function_exists('get_status_class_mdi')) {
    function get_status_class_mdi($status)
    {
        switch ($status) {
            case 'completed':
                return 'text-success';
            case 'pending':
                return 'text-warning';
            case 'canceled':
                return 'text-danger';
            default:
                return 'text-secondary'; // Default case
        }
    }
}
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
        'admin' => 'danger',
        'manager' => 'warning',
        'waiter' => 'info',
        default => 'secondary'
    };
}