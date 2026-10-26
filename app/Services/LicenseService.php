<?php

namespace App\Services;

use App\Models\LicenseKey;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class LicenseService
{
    /**
     * Retorna a licença ativa da instalação
     */
    public function getCurrentLicense(): LicenseKey
    {
        $current = LicenseKey::where('is_current', true)->first();

        if (!$current) {
            $current = LicenseKey::where('status', 'active')->latest()->first();
        }

        if (!$current) {
            $current = $this->seedInitialLicense();
        }

        return $current;
    }

    /**
     * Gera e guarda uma nova licença no sistema
     */
    public function generateLicense(
        string $businessName,
        string $licenseType = 'annual',
        ?string $customerName = null,
        ?string $customerEmail = null,
        ?string $customerPhone = null,
        int $maxTables = 50,
        int $maxUsers = 10,
        ?string $notes = null
    ): LicenseKey {
        $startsAt = now();
        $expiresAt = match ($licenseType) {
            'trial'     => now()->addDays(15),
            'monthly'   => now()->addDays(30),
            'quarterly' => now()->addDays(90),
            'annual'    => now()->addDays(365),
            'lifetime'  => null,
            default     => now()->addDays(365),
        };

        return LicenseKey::create([
            'business_name'  => $businessName,
            'customer_name'  => $customerName ?: $businessName,
            'customer_email' => $customerEmail,
            'customer_phone' => $customerPhone,
            'license_type'   => $licenseType,
            'status'         => 'active',
            'starts_at'      => $startsAt,
            'expires_at'     => $expiresAt,
            'max_tables'     => $maxTables,
            'max_users'      => $maxUsers,
            'is_current'     => false,
            'notes'          => $notes,
        ]);
    }

    /**
     * Ativa uma chave serial nesta instalação
     */
    public function activateKey(string $keyCode): array
    {
        $cleanKey = strtoupper(trim($keyCode));
        $license = LicenseKey::where('key_code', $cleanKey)->first();

        if (!$license) {
            return [
                'success' => false,
                'message' => 'Chave serial de licença inválida ou inexistente.'
            ];
        }

        if (in_array($license->status, ['revoked', 'suspended'])) {
            return [
                'success' => false,
                'message' => 'Esta licença foi revogada ou suspensa pelo dono do sistema.'
            ];
        }

        if ($license->isExpired()) {
            return [
                'success' => false,
                'message' => 'Esta licença já se encontra expirada. Solicite uma renovação.'
            ];
        }

        // Desmarca a licença anterior
        LicenseKey::where('is_current', true)->update(['is_current' => false]);

        // Ativa a nova
        $license->update([
            'is_current'          => true,
            'activated_at'        => now(),
            'device_fingerprint'  => $this->getSystemFingerprint(),
            'status'              => 'active',
        ]);

        Cache::forget('system_active_license');

        return [
            'success' => true,
            'message' => 'Licença ativada com sucesso!',
            'license' => $license,
        ];
    }

    /**
     * Renova ou estende a validade de uma licença
     */
    public function extendLicense(LicenseKey $license, int $days): LicenseKey
    {
        $baseDate = ($license->expires_at && $license->expires_at->isFuture()) 
            ? $license->expires_at 
            : now();

        $license->update([
            'expires_at' => $baseDate->copy()->addDays($days),
            'status'     => 'active',
        ]);

        Cache::forget('system_active_license');

        return $license;
    }

    /**
     * Retorna a assinatura/fingerprint do hardware ou servidor local
     */
    public function getSystemFingerprint(): string
    {
        $host = gethostname() ?: 'cafelu-server';
        $ip = $_SERVER['SERVER_ADDR'] ?? '127.0.0.1';
        $base = php_uname() . '|' . $host . '|' . $ip;

        return strtoupper(substr(hash('sha256', $base), 0, 16));
    }

    /**
     * Cria licença inicial se o banco for novo
     */
    protected function seedInitialLicense(): LicenseKey
    {
        return LicenseKey::create([
            'business_name'      => config('app.name', 'Restaurante CAFELU'),
            'customer_name'      => 'Cliente Principal',
            'license_type'       => 'annual',
            'status'             => 'active',
            'starts_at'          => now(),
            'expires_at'         => now()->addDays(365),
            'activated_at'       => now(),
            'max_tables'         => 50,
            'max_users'          => 15,
            'device_fingerprint' => $this->getSystemFingerprint(),
            'is_current'         => true,
            'notes'              => 'Licença padrão inicial do sistema.',
        ]);
    }
}

