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
        }
    }
}
