<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>419 — Página Expirada</title>
<style>
  body{background:#081827;color:#e6eef8;font-family:Inter,system-ui,Arial;padding:40px}
  .box{max-width:720px;margin:80px auto;padding:36px;background:rgba(255,255,255,0.02);border-radius:12px;border:1px solid rgba(255,255,255,0.03);text-align:center}
  h1{font-size:40px;color:#f97316;margin:0 0 8px}
  p{color:#cbd5e1;margin:0 0 18px}
  .btn{display:inline-block;margin:6px 8px;padding:10px 16px;border-radius:8px;text-decoration:none;font-weight:600}
  .primary{background:#f97316;color:#0f1724}
  .secondary{background:transparent;border:1px solid rgba(255,255,255,0.06);color:#e6eef8}
</style>
</head>
<body>
  <div class="box">
    <h1>419</h1>
    <p><strong>Token expirado.</strong><br>Sua sessão expirou — atualize a página e tente novamente.</p>
    <div>
      <a href="javascript:location.reload()" class="btn primary">Recarregar</a>
      <a href="{{ url('/login') }}" class="btn secondary">Entrar</a>
      <a href="{{ url('/') }}" class="btn secondary">Página Inicial</a>
    </div>
  </div>
</body>
</html>