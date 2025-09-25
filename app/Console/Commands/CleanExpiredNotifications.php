<?php
// app/Console/Commands/CleanExpiredNotifications.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use Carbon\Carbon;

class CleanExpiredNotifications extends Command
{
    protected $signature = 'notifications:clean';
    protected $description = 'Remove notificações expiradas';

    public function handle()
    {
        $deleted = Notification::where('expires_at', '<', Carbon::now())->delete();
        
        $this->info("{$deleted} notificações expiradas removidas.");
        
        return Command::SUCCESS;
    }
}