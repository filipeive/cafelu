<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>403 — Acesso Negado</title>
<style>
  body{background:#0f1724;color:#e6eef8;font-family:Inter,system-ui,Arial;padding:40px}
  .box{max-width:720px;margin:80px auto;padding:36px;background:rgba(255,255,255,0.03);border-radius:12px;border:1px solid rgba(255,255,255,0.04);text-align:center}
  h1{font-size:48px;color:#ffb547;margin:0 0 8px}
  p{color:#cbd5e1;margin:0 0 18px}
  .btn{display:inline-block;margin:6px 8px;padding:10px 16px;border-radius:8px;text-decoration:none;font-weight:600}
  .primary{background:#ffb547;color:#0f1724}
  .secondary{background:transparent;border:1px solid rgba(255,255,255,0.06);color:#e6eef8}
</style>
</head>
<body>
  <div class="box">
    <h1>403</h1>
    <p><strong>Acesso negado.</strong><br>Você não tem permissão para acessar este recurso.</p>
    <div>
      <a href="{{ url('/dashboard') }}" class="btn primary">Ir para Início</a>
      <a href="{{ url('/') }}" class="btn secondary">Página Inicial</a>
      <a href="javascript:history.back()" class="btn secondary">Voltar</a>
    </div>
  </div>
</body>
</html>