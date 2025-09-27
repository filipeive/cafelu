<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class SystemController extends Controller
{
    public function update(Request $request)
    {
        // Lista de comandos para atualizar o sistema
        $commands = [
            ['git', 'pull'],
            ['composer', 'install', '--no-interaction', '--prefer-dist', '--optimize-autoloader'],
            ['php', 'artisan', 'migrate', '--force'],
            ['php', 'artisan', 'optimize:clear'],
            ['php', 'artisan', 'config:cache'],
        ];

        $output = [];

        foreach ($commands as $command) {
            $process = new Process($command, base_path());
            $process->run();

            $output[] = [
                'command' => implode(' ', $command),
                'status' => $process->isSuccessful() ? '✅ OK' : '❌ Falhou',
                'output' => $process->getOutput() ?: $process->getErrorOutput()
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Sistema atualizado com sucesso!',
            'details' => $output,
        ]);
    }
}
