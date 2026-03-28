<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Verificación — Sincronía Aérea</title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'DM Sans',sans-serif;background:#0b1120;color:#f1f5f9;min-height:100vh;display:flex;align-items:center;justify-content:center}
.card{background:#111827;border:1px solid rgba(255,255,255,0.07);border-radius:16px;padding:48px;text-align:center;max-width:460px;width:90%}
.icon{font-size:4rem;margin-bottom:16px}
h1{font-family:'Syne',sans-serif;font-size:1.6rem;font-weight:800;margin-bottom:12px}
p{color:#94a3b8;line-height:1.7;margin-bottom:24px}
a{display:inline-block;padding:11px 28px;background:#3b82f6;color:#fff;border-radius:8px;text-decoration:none;font-weight:600}
.ok h1{color:#22c55e} .err h1{color:#ef4444}
</style></head>
<body>
@if($ok)
<div class="card ok">
    <div class="icon">✅</div>
    <h1>¡Correo Verificado!</h1>
    <p>Hola <strong>{{ $nombre ?? '' }}</strong>, tu cuenta en Sincronía Aérea ha sido verificada exitosamente.</p>
    <a href="{{ url('/') }}">Iniciar Sesión</a>
</div>
@else
<div class="card err">
    <div class="icon">❌</div>
    <h1>Enlace Inválido</h1>
    <p>Este enlace de verificación ya fue usado o no es válido. Si crees que es un error, vuelve a registrarte.</p>
    <a href="{{ url('/') }}">Volver al inicio</a>
</div>
@endif
</body></html>
