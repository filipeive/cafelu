<?php

namespace App\Observers;

use App\Models\User;
use App\Services\PermissionCacheService;

class UserObserver
{
    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        // Limpar cache de permissões quando role do usuário mudar
        if ($user->wasChanged('role')) {
            PermissionCacheService::clearUserCache($user->id);
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        PermissionCacheService::clearUserCache($user->id);
    }
}
