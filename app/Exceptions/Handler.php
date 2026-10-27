<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception): Response
    {
        if ($exception instanceof \Illuminate\Http\Exceptions\PostTooLargeException) {
            $msg = 'O ficheiro enviado excede o tamanho máximo permitido pelo servidor. Por favor, selecione uma imagem mais leve (máx. recomendado: 4MB).';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $msg,
                ], 413);
            }

            return redirect()->back()
                ->withInput($request->except(['password', 'password_confirmation']))
                ->withErrors(['image' => $msg])
                ->with('error', $msg);
        }

        return parent::render($request, $exception);
    }
}
