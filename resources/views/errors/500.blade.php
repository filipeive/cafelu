<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>500 — Erro Interno</title>
<style>
  body{background:#0b0f1a;color:#f1f5f9;font-family:Inter,system-ui,Arial;padding:40px}
  .box{max-width:780px;margin:80px auto;padding:36px;background:rgba(255,255,255,0.02);border-radius:12px;border:1px solid rgba(255,255,255,0.03);text-align:center}
  h1{font-size:48px;color:#ff6b6b;margin:0 0 8px}
  p{color:#cbd5e1;margin:0 0 18px}
  .btn{display:inline-block;margin:6px 8px;padding:10px 16px;border-radius:8px;text-decoration:none;font-weight:600}
  .primary{background:#ff6b6b;color:#1a0f0f}
  .secondary{background:transparent;border:1px solid rgba(255,255,255,0.06);color:#f1f5f9}
</style>
</head>
<body>
  <div class="box">
    <h1>500</h1>
    <p><strong>Erro interno do servidor.</strong><br>Lamentamos, algo deu errado. Tente novamente mais tarde.</p>
    <div>
      <a href="{{ url('/dashboard') }}" class="btn primary">Ir para Início</a>
      <a href="{{ url('/') }}" class="btn secondary">Página Inicial</a>
      <a href="javascript:history.back()" class="btn secondary">Voltar</a>
    </div>
  </div>
</body>
</html>