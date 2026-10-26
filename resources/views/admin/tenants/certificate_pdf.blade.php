<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Certificado de Licenciamento - {{ $tenant->name }}</title>
    <style>
        @page {
            margin: 25mm 20mm;
            size: A4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1a1a1a;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }
        .border-frame {
            border: 4px double #b45309;
            padding: 30px;
            text-align: center;
            position: relative;
        }
        .header-tag {
            display: inline-block;
            background: #fef3c7;
            color: #92400e;
            padding: 4px 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        h1 {
            font-size: 26px;
            font-weight: 900;
            margin: 0 0 5px 0;
            color: #0f172a;
        }
        .subtitle {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 25px;
        }
        .divider {
            width: 100px;
            height: 2px;
            background: #b45309;
            margin: 0 auto 25px auto;
        }
        .statement {
            font-size: 12px;
            color: #334155;
            text-align: justify;
            margin: 0 20px 30px 20px;
            line-height: 1.6;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            background: #fffbeb;
            border: 1px solid #fde68a;
            margin-bottom: 30px;
            text-align: left;
        }
        .info-table td {
            padding: 12px 15px;
            vertical-align: top;
            font-size: 11px;
        }
        .label {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            color: #92400e;
            display: block;
            margin-bottom: 3px;
        }
        .val-title {
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
            margin: 0 0 3px 0;
        }
        .val-text {
            color: #475569;
            font-size: 11px;
        }
        .serial-box {
            background: #ffffff;
            border: 1px solid #d97706;
            padding: 6px 12px;
            font-family: monospace;
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
            display: inline-block;
            margin-top: 3px;
        }
        .signatures {
            width: 100%;
            margin-top: 40px;
            text-align: center;
        }
        .signatures td {
            width: 50%;
            padding: 0 20px;
            font-size: 11px;
        }
        .sig-line {
            border-bottom: 1px solid #94a3b8;
            margin-bottom: 8px;
            height: 40px;
        }
        .sig-name {
            font-weight: bold;
            color: #0f172a;
        }
        .sig-sub {
            font-size: 9px;
            color: #64748b;
        }
        .footer-note {
            margin-top: 35px;
            font-size: 8px;
            color: #94a3b8;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="border-frame">
        <div class="header-tag">Certificado Oficial de Licenciamento de Software</div>
        <h1>RestPOS Restaurant Management System</h1>
        <div class="subtitle">Documento de Autenticidade, Conformidade e Cessão de Uso</div>
        <div class="divider"></div>

        <div class="statement">
            Certifica-se pelo presente documento que o estabelecimento comercial abaixo qualificado está devidamente licenciado e autorizado a utilizar a plataforma de Gestão de Restauração e Frente de Caixa <strong>RestPOS (v1.3)</strong>, em estrita conformidade com os termos de licenciamento, requisitos de segurança de dados e limites operacionais estipulados.
        </div>

        <table class="info-table">
            <tr>
                <td style="width: 50%; border-right: 1px solid #fde68a; border-bottom: 1px solid #fde68a;">
                    <span class="label">Entidade Licenciada (Cliente):</span>
                    <div class="val-title">{{ $tenant->name }}</div>
                    <div class="val-text">
                        NUIT: {{ $tenant->nuit ?? 'Registado' }}<br>
                        Localização: {{ $tenant->address ?? $tenant->city }} ({{ $tenant->city }})<br>
                        Contacto: {{ $tenant->phone ?? 'N/A' }}
                    </div>
                </td>
                <td style="width: 50%; border-bottom: 1px solid #fde68a;">
                    <span class="label">Plano & Modalidade de Operação:</span>
                    <div class="val-title">{{ strtoupper($license->license_type) }}</div>
                    <div class="val-text">
                        Ambiente: {{ strtoupper($tenant->installation_mode) }}<br>
                        Capacidade Licenciada: {{ $license->max_tables }} Mesas • {{ $license->max_users }} Utilizadores<br>
                        Estado: {{ strtoupper($tenant->status) }}
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table style="width: 100%;">
                        <tr>
                            <td style="padding: 0;">
                                <span class="label">Chave Serial Oficial de Ativação:</span>
                                <div class="serial-box">{{ $license->key_code }}</div>
                            </td>
                            <td style="text-align: right; padding: 0;">
                                <span class="label">Validade Operacional:</span>
                                <div style="font-size: 13px; font-weight: bold; color: #0f172a; margin-top: 5px;">
                                    @if($license->expires_at)
                                        Até {{ $license->expires_at->format('d/m/Y') }}
                                    @else
                                        Licença Vitalícia (Sem Expiração)
                                    @endif
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table class="signatures">
            <tr>
                <td>
                    <div class="sig-line"></div>
                    <div class="sig-name">Pelo Fornecedor da Plataforma</div>
                    <div class="sig-sub">RestPOS Systems Mozambique</div>
                </td>
                <td>
                    <div class="sig-line"></div>
                    <div class="sig-name">Pelo Restaurante / Cliente</div>
                    <div class="sig-sub">{{ $tenant->name }}</div>
                </td>
            </tr>
        </table>

        <div class="footer-note">
            Documento emitido eletronicamente em {{ now()->format('d/m/Y H:i:s') }} • Código de Verificação: {{ substr($license->key_hash ?? md5($license->key_code), 0, 16) }}
        </div>
    </div>
</body>
</html>
