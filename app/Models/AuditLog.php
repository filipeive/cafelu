<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'action',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function auditable()
    {
        return $this->morphTo();
    }

    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'created'            => 'Criação',
            'updated'            => 'Atualização',
            'deleted'            => 'Eliminação',
            'login'              => 'Sessão Iniciada',
            'logout'             => 'Sessão Terminada',
            'impersonate_start'  => 'Suporte Iniciado',
            'impersonate_end'    => 'Suporte Terminado',
            'tenant_created'     => 'Restaurante Registado',
            'tenant_suspended'   => 'Restaurante Suspenso',
            'tenant_reactivated' => 'Restaurante Reativado',
            default              => ucfirst(str_replace('_', ' ', (string) $this->action)),
        };
    }

    public function getActionBadgeClassesAttribute(): string
    {
        return match ($this->action) {
            'created', 'tenant_created', 'tenant_reactivated' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300',
            'updated'                                         => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
            'deleted', 'tenant_suspended'                     => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
            'login'                                           => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300',
            'logout'                                          => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
            'impersonate_start', 'impersonate_end'            => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300',
            default                                           => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
        };
    }

    public function getActionIconAttribute(): string
    {
        return match ($this->action) {
            'created', 'tenant_created' => 'mdi-plus-circle-outline',
            'updated'                   => 'mdi-pencil-outline',
            'deleted'                   => 'mdi-delete-outline',
            'login'                     => 'mdi-login-variant',
            'logout'                    => 'mdi-logout-variant',
            'impersonate_start'         => 'mdi-shield-account',
            'impersonate_end'           => 'mdi-shield-off-outline',
            'tenant_suspended'          => 'mdi-pause-octagon-outline',
            'tenant_reactivated'        => 'mdi-check-decagram-outline',
            default                     => 'mdi-history',
        };
    }

    public function getModelLabelAttribute(): string
    {
        if (!$this->auditable_type) {
            return match ($this->action) {
                'login', 'logout'                      => 'Autenticação',
                'impersonate_start', 'impersonate_end' => 'Modo Suporte SaaS',
                default                                => 'Sistema',
            };
        }

        $base = strtolower(class_basename($this->auditable_type));
        $translated = __('messages.' . $base);
        if ($translated !== 'messages.' . $base) {
            return $translated;
        }

        return match ($base) {
            'tenant'       => 'Restaurante (Tenant)',
            'licensekey'   => 'Licença SaaS',
            'quotation'    => 'Cotação',
            'invoice'      => 'Fatura / Recibo',
            'setting'      => 'Configuração',
            'websiteslide' => 'Banner Website',
            default        => class_basename($this->auditable_type),
        };
    }
}
