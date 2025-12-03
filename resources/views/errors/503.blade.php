<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>503 — Em manutenção</title>
<style>
  body{background:#061025;color:#e6eef8;font-family:Inter,system-ui,Arial;padding:40px}
  .box{max-width:820px;margin:80px auto;padding:36px;background:rgba(255,255,255,0.02);border-radius:12px;border:1px solid rgba(255,255,255,0.03);text-align:center}
  h1{font-size:44px;color:#60a5fa;margin:0 0 8px}
  p{color:#cbd5e1;margin:0 0 18px}
  .btn{display:inline-block;margin:6px 8px;padding:10px 16px;border-radius:8px;text-decoration:none;font-weight:600}
  .primary{background:#60a5fa;color:#062034}
  .secondary{background:transparent;border:1px solid rgba(255,255,255,0.06);color:#e6eef8}
</style>
</head>
<body>
  <div class="box">
    <h1>503</h1>
    <p><strong>Serviço indisponível.</strong><br>Estamos em manutenção — volte em alguns minutos.</p>
    <div>
      <a href="{{ url('/') }}" class="btn primary">Página Inicial</a>
      <a href="javascript:location.reload()" class="btn secondary">Tentar novamente</a>
    </div>
  </div>
</body>
</html>