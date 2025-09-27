<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Chamar método custom
            $this->notifySupport($e);
        });
    }

    /**
     * Notificar o suporte técnico (log + email).
     */
    protected function notifySupport(Throwable $e): void
    {
        try {
            $errorDetails = [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'url'     => request()->fullUrl() ?? 'N/A',
                'method'  => request()->method() ?? 'N/A',
                'user'    => auth()->check() ? auth()->user()->email : 'Guest',
                'time'    => now()->format('d/m/Y H:i:s'),
            ];

            // Log especial para suporte
            Log::channel('support')->error('Erro capturado', $errorDetails);

            // Enviar email automático para ti
            Mail::raw("🚨 Novo erro detectado no sistema:\n\n" . print_r($errorDetails, true), function ($message) {
                $message->to('jvquelimane@gmail.com')
                        ->subject('🚨 Erro no Sistema - Notificação Automática');
            });

        } catch (\Exception $ex) {
            // Garantir que erro no envio não quebre o sistema
            Log::error("Falha ao notificar suporte: " . $ex->getMessage());
        }
    }
}
