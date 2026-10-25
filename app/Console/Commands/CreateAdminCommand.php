<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create 
                            {--username=admin : Nome de utilizador para login}
                            {--password=admin1234 : Palavra-passe (mínimo 8 caracteres)}
                            {--email=admin@cafelu.test : Endereço de e-mail}
                            {--name=Administrador : Nome completo}
                            {--role=admin : Papel no sistema (admin)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cria ou atualiza um utilizador administrador para testes do sistema';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $username = $this->option('username') ?: 'admin';
        $password = $this->option('password') ?: 'admin1234';
        $email = $this->option('email') ?: 'admin@cafelu.test';
        $name = $this->option('name') ?: 'Administrador';
        $role = $this->option('role') ?: 'admin';

        $user = User::updateOrCreate(
            ['username' => $username],
            [
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'role' => $role,
                'status' => 'active',
            ]
        );

        $this->info("Administrador configurado com sucesso!");
        $this->table(
            ['Campo', 'Valor'],
            [
                ['ID', $user->id],
                ['Nome', $user->name],
                ['Username (Login)', $user->username],
                ['E-mail', $user->email],
                ['Password', $password],
                ['Role', $user->role],
                ['Status', $user->status],
            ]
        );

        return Command::SUCCESS;
    }
}
