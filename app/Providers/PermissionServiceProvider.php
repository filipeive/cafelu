<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use App\Http\Middleware\PermissionHelper;

class PermissionServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Registrar helper global para views
        View::composer('*', function ($view) {
            $view->with('permissions', new PermissionHelper());
        });

        // Registrar Blade directives
        $this->registerBladeDirectives();
    }

    private function registerBladeDirectives()
    {
        // @can('permission')
        Blade::if('can', function ($permission) {
            return PermissionHelper::can($permission);
        });

        // @canany(['perm1', 'perm2'])
        Blade::if('canany', function ($permissions) {
            return PermissionHelper::canAny($permissions);
        });

        // @canall(['perm1', 'perm2'])
        Blade::if('canall', function ($permissions) {
            return PermissionHelper::canAll($permissions);
        });

        // @role('admin')
        Blade::if('role', function ($role) {
            return auth()->check() && auth()->user()->role === $role;
        });

        // @hasrole(['admin', 'manager'])
        Blade::if('hasrole', function ($roles) {
            if (!auth()->check()) return false;
            $roles = is_array($roles) ? $roles : [$roles];
            return in_array(auth()->user()->role, $roles);
        });

        // @module('products')
        Blade::if('module', function ($module) {
            return PermissionHelper::canAccessModule($module);
        });
    }
}
