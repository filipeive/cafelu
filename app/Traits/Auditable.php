<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    protected static function bootAuditable()
    {
        static::created(function ($model) {
            $model->logAudit('created');
        });

        static::updated(function ($model) {
            $model->logAudit('updated');
        });

        static::deleted(function ($model) {
            $model->logAudit('deleted');
        });
    }

    protected function logAudit($action)
    {
        $oldValues = null;
        $newValues = null;

        if ($action === 'updated') {
            $newValues = $this->getDirty();
            $oldValues = array_intersect_key($this->getOriginal(), $newValues);

            // Don't log if no changes (except timestamps)
            unset($newValues['updated_at']);
            unset($oldValues['updated_at']);
            if (empty($newValues)) {
                return;
            }
        } elseif ($action === 'created') {
            $newValues = $this->getAttributes();
            unset($newValues['created_at'], $newValues['updated_at']);
        } elseif ($action === 'deleted') {
            $oldValues = $this->getAttributes();
        }

        $tenantId = $this->getAttribute('tenant_id')
            ?? (function_exists('current_tenant_id') ? current_tenant_id() : null)
            ?? Auth::user()?->tenant_id;

        AuditLog::create([
            'tenant_id'      => $tenantId,
            'user_id'        => Auth::id(),
            'action'         => $action,
            'auditable_type' => get_class($this),
            'auditable_id'   => $this->id,
            'old_values'     => $oldValues,
            'new_values'     => $newValues,
            'ip_address'     => Request::ip(),
            'user_agent'     => Request::userAgent(),
        ]);
    }
}
