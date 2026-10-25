<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;

class SetEstablishmentNameCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setting:name 
                            {name? : Novo nome para o estabelecimento}
                            {--show : Apenas visualiza o nome atual}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exibe ou altera facilmente o nome do estabelecimento / restaurante';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentName = Setting::get('company_name', 'Não configurado');

        if ($this->option('show')) {
            $this->info("Nome atual do estabelecimento: {$currentName}");
            return Command::SUCCESS;
        }

        $newName = $this->argument('name');

        if (!$newName) {
            $this->line("Nome atual: <comment>{$currentName}</comment>");
            $newName = $this->ask('Qual é o novo nome do estabelecimento?');
        }

        if (empty(trim($newName))) {
            $this->error('O nome não pode ser vazio.');
            return Command::FAILURE;
        }

        $newName = trim($newName);

        Setting::set('company_name', $newName, 'general', 'text');

        $this->info("✓ Nome do estabelecimento atualizado com sucesso!");
        $this->table(
            ['Configuração', 'Valor Anterior', 'Novo Valor'],
            [
                ['Nome do Estabelecimento', $currentName, $newName]
            ]
        );

        return Command::SUCCESS;
    }
}
