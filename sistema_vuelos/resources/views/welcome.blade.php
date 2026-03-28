<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sincronía Aérea BOA — Acceso</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
    <style>
        :root {
            --navy:   #0b1120;
            --panel:  #111827;
            --border: rgba(255,255,255,0.08);
            --accent: #3b82f6;
            --text:   #f1f5f9;
            --muted:  #94a3b8;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--navy);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* Fondo animado con aviones */
        .bg-canvas {
            position: fixed;
            inset: 0;
            overflow: hidden;
            z-index: 0;
        }
        .bg-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(59,130,246,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(59,130,246,0.04) 1px, transparent 1px);
            background-size: 48px 48px;
        }
        .bg-glow {
            position: absolute;
            width: 600px; height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(59,130,246,0.12) 0%, transparent 70%);
            top: -200px; left: -200px;
            animation: drift 12s ease-in-out infinite alternate;
        }
        .bg-glow2 {
            position: absolute;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(99,102,241,0.1) 0%, transparent 70%);
            bottom: -100px; right: -100px;
            animation: drift2 10s ease-in-out infinite alternate;
        }
        @keyframes drift  { to { transform: translate(80px, 60px); } }
        @keyframes drift2 { to { transform: translate(-60px, -40px); } }

        /* Aviones flotantes decorativos */
        .plane {
            position: fixed;
            color: rgba(59,130,246,0.12);
            font-size: 2.5rem;
            animation: flyAcross linear infinite;
            z-index: 1;
        }
        .plane:nth-child(1) { top: 15%; animation-duration: 22s; animation-delay: 0s; }
        .plane:nth-child(2) { top: 40%; animation-duration: 28s; animation-delay: -8s; font-size: 1.8rem; }
        .plane:nth-child(3) { top: 68%; animation-duration: 18s; animation-delay: -14s; font-size: 3rem; }
        @keyframes flyAcross {
            from { transform: translateX(-120px) rotate(-5deg); }
            to   { transform: translateX(110vw) rotate(-5deg); }
        }

        /* Contenedor principal partido en 2 */
        .login-outer {
            position: relative;
            z-index: 10;
            display: flex;
            width: 900px;
            max-width: 96vw;
            min-height: 520px;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid var(--border);
            box-shadow: 0 32px 80px rgba(0,0,0,0.5);
        }

        /* Panel izquierdo — branding */
        .brand-panel {
            flex: 1;
            background: linear-gradient(135deg, #0f2044 0%, #0b1120 60%, #0d1a35 100%);
            padding: 52px 44px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }
        .brand-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='1' cy='1' r='1' fill='rgba(255,255,255,0.03)'/%3E%3C/svg%3E") repeat;
        }
        .brand-logo {
            position: relative;
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .brand-logo .icon-box {
            width: 48px; height: 48px;
            background: linear-gradient(135deg, var(--accent), #1d4ed8);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 8px 24px rgba(59,130,246,0.4);
        }
        .brand-logo .icon-box .material-symbols-outlined { color: #fff; font-size: 24px; }
        .brand-logo-text h1 {
            font-family: 'Syne', sans-serif;
            font-size: 1.3rem;
            font-weight: 800;
            color: #fff;
            line-height: 1.1;
        }
        .brand-logo-text span { font-size: 0.75rem; color: rgba(255,255,255,0.45); font-weight: 400; }

        .brand-tagline {
            position: relative;
        }
        .brand-tagline h2 {
            font-family: 'Syne', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            color: #fff;
            line-height: 1.2;
            margin-bottom: 14px;
        }
        .brand-tagline h2 em {
            font-style: normal;
            background: linear-gradient(90deg, #60a5fa, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .brand-tagline p { color: rgba(255,255,255,0.45); font-size: 0.875rem; line-height: 1.7; }

        .brand-stats {
            position: relative;
            display: flex;
            gap: 28px;
        }
        .brand-stat .val {
            font-family: 'Syne', sans-serif;
            font-size: 1.4rem;
            font-weight: 800;
            color: #60a5fa;
        }
        .brand-stat .lbl { font-size: 0.72rem; color: rgba(255,255,255,0.35); margin-top: 2px; }

        /* Panel derecho — formulario */
        .form-panel {
            width: 380px;
            background: var(--panel);
            padding: 48px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .form-panel h3 {
            font-family: 'Syne', sans-serif;
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 6px;
        }
        .form-panel .subtitle { color: var(--muted); font-size: 0.82rem; margin-bottom: 32px; }

        .input-group { margin-bottom: 18px; }
        .input-group label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--muted);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .input-wrap {
            position: relative;
        }
        .input-wrap input {
            width: 100%;
            padding: 11px 14px 11px 40px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 8px;
            color: var(--text);
            font-size: 0.9rem;
            font-family: 'DM Sans', sans-serif;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .input-wrap input:focus {
            outline: none;
            border-color: rgba(59,130,246,0.5);
            box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
        }
        .input-wrap input::placeholder { color: rgba(148,163,184,0.4); }
        .input-wrap .input-icon {
            position: absolute;
            left: 12px; top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
            color: var(--muted);
            pointer-events: none;
        }

        .error-msg { color: #fca5a5; font-size: 0.78rem; margin-top: 10px; display: none; }
        .error-msg.show { display: block; }

        .divider { display: flex; align-items: center; gap: 10px; margin: 20px 0; }
        .divider hr { flex: 1; border: none; border-top: 1px solid var(--border); }
        .divider span { font-size: 0.75rem; color: var(--muted); }

        .login-btn {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            font-family: 'Syne', sans-serif;
            cursor: pointer;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s;
            text-decoration: none;
            margin-bottom: 10px;
        }
        .login-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.3); }
        .login-btn .material-symbols-outlined { font-size: 18px; }
        .btn-admin  { background: linear-gradient(135deg, var(--accent), #1d4ed8); color: #fff; }
        .btn-guest  { background: rgba(255,255,255,0.06); color: var(--muted); border: 1px solid var(--border); }

        .form-footer { text-align: center; margin-top: 24px; font-size: 0.72rem; color: var(--muted); }

        @media (max-width: 700px) {
            .brand-panel { display: none; }
            .form-panel { width: 100%; padding: 40px 28px; }
        }
    </style>
</head>
<body>

<div class="bg-canvas">
    <div class="bg-grid"></div>
    <div class="bg-glow"></div>
    <div class="bg-glow2"></div>
</div>
<span class="plane material-symbols-outlined">flight</span>
<span class="plane material-symbols-outlined">flight</span>
<span class="plane material-symbols-outlined">flight</span>

<div class="login-outer">
    <!-- Panel izquierdo branding -->
    <div class="brand-panel">
        <div class="brand-logo">
            <div class="icon-box">
                <span class="material-symbols-outlined">flight_takeoff</span>
            </div>
            <div class="brand-logo-text">
                <h1>Sincronía Aérea</h1>
                <span>Boliviana de Aviación</span>
            </div>
        </div>
        <div class="brand-tagline">
            <h2>Gestiona cada vuelo con <em>precisión total</em></h2>
            <p>Sistema de control operativo para aerolíneas. Administra rutas, aerolíneas y pasajeros desde un solo panel centralizado.</p>
        </div>
        <div class="brand-stats">
            <div class="brand-stat">
                <div class="val">CRUD</div>
                <div class="lbl">Operaciones</div>
            </div>
            <div class="brand-stat">
                <div class="val">3</div>
                <div class="lbl">Módulos</div>
            </div>
            <div class="brand-stat">
                <div class="val">100%</div>
                <div class="lbl">Laravel</div>
            </div>
        </div>
    </div>

    <!-- Panel derecho login -->
    <div class="form-panel">
        <h3>Portal de Acceso</h3>
        <p class="subtitle">Ingresa tus credenciales o entra como invitado</p>

        <form id="loginForm">
            <div class="input-group">
                <label>Usuario Corporativo</label>
                <div class="input-wrap">
                    <span class="material-symbols-outlined input-icon">person</span>
                    <input type="text" id="username" placeholder="ej. j.perez (opcional en demo)">
                </div>
            </div>
            <div class="input-group">
                <label>Contraseña</label>
                <div class="input-wrap">
                    <span class="material-symbols-outlined input-icon">lock</span>
                    <input type="password" id="password" placeholder="•••••••• (opcional en demo)">
                </div>
            </div>
            <p id="errorMsg" class="error-msg">⚠ Credenciales incorrectas.</p>

            <button type="submit" class="login-btn btn-admin">
                <span class="material-symbols-outlined">domain</span>
                Entrar como Empresa (Admin)
            </button>
        </form>

        <div class="divider">
            <hr><span>o continúa sin cuenta</span><hr>
        </div>

        <a href="{{ url('/inicio') }}" class="login-btn btn-guest">
            <span class="material-symbols-outlined">person</span>
            Entrar como Cliente (Invitado)
        </a>

        <div class="form-footer">
            © 2026 BOA Boliviana de Aviación &nbsp;·&nbsp; Sistema Estudiantil
        </div>
    </div>
</div>

<script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const user = document.getElementById('username').value.trim();
        const pass = document.getElementById('password').value.trim();
        const err  = document.getElementById('errorMsg');
        // Demo: cualquier credencial o vacío → entra como admin
        if (user === '' && pass === '') {
            err.classList.add('show');
            err.textContent = '⚠ Ingresa un usuario o entra como invitado.';
        } else {
            window.location.href = '{{ url("/inicio") }}';
        }
    });
</script>
</body>
</html>
