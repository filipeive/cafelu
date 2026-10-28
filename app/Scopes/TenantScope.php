<?php

namespace App\Scopes;

use App\Services\TenantContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $context = app(TenantContext::class);

        if ($context->isBypassed()) {
            return;
        }

        $tenantId = $context->getTenantId();

        // Fallback to authenticated user tenant if context hasn't been set yet
        if ($tenantId === null && auth()->check() && auth()->user()->tenant_id) {
            $tenantId = auth()->user()->tenant_id;
        }

        if ($tenantId !== null) {
            $builder->where($model->qualifyColumn('tenant_id'), $tenantId);
            return;
        }

        // Strict Tenant Isolation (ZBIZ Pattern):
        // If SuperAdmin is logged in outside Support Mode, never leak operational tenant records
        // unless explicitly bypassed via TenantContext::withoutTenant().
        if (auth()->check() && auth()->user()->isSuperAdmin() && !session('is_support_mode')) {
            $builder->whereRaw('1 = 0');
        }
    }
}

