<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sincronía Aérea BOA')</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
    <style>
        :root {
            --navy:    #0b1120;
            --navy2:   #111827;
            --panel:   #161f30;
            --border:  rgba(255,255,255,0.07);
            --accent:  #3b82f6;
            --accent2: #60a5fa;
            --gold:    #f59e0b;
            --danger:  #ef4444;
            --success: #22c55e;
            --warning: #f97316;
            --text:    #f1f5f9;
            --muted:   #94a3b8;
            --sidebar-w: 240px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--navy);
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--navy2);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
        }

        .sidebar-brand {
            padding: 24px 20px 20px;
            border-bottom: 1px solid var(--border);
        }
        .sidebar-brand .logo-row {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sidebar-brand .logo-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--accent), #1d4ed8);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
        }
        .sidebar-brand h2 {
            font-family: 'Syne', sans-serif;
            font-size: 1rem;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -0.3px;
        }
        .sidebar-brand p {
            font-size: 0.7rem;
            color: var(--muted);
            margin-top: 2px;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            overflow-y: auto;
        }
        .nav-section-label {
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: var(--muted);
            padding: 0 8px;
            margin: 16px 0 6px;
        }
        .nav-section-label:first-child { margin-top: 0; }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 10px;
            border-radius: 8px;
            text-decoration: none;
            color: var(--muted);
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.15s;
            margin-bottom: 2px;
        }
        .nav-link .material-symbols-outlined { font-size: 18px; }
        .nav-link:hover {
            background: rgba(255,255,255,0.06);
            color: var(--text);
        }
        .nav-link.active {
            background: rgba(59,130,246,0.15);
            color: var(--accent2);
            border-left: 3px solid var(--accent);
            padding-left: 7px;
        }
        .nav-link.active .material-symbols-outlined { color: var(--accent2); }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--border);
            font-size: 0.75rem;
            color: var(--muted);
            text-align: center;
        }

        /* ── MAIN CONTENT ── */
        .main-wrapper {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar {
            background: var(--navy2);
            border-bottom: 1px solid var(--border);
            padding: 0 28px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .topbar-left {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--muted);
            font-size: 0.85rem;
        }
        .topbar-left .page-title {
            color: var(--text);
            font-weight: 600;
            font-size: 0.95rem;
        }
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .topbar-badge {
            background: rgba(59,130,246,0.15);
            color: var(--accent2);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            border: 1px solid rgba(59,130,246,0.25);
        }

        .content-area {
            flex: 1;
            padding: 28px;
        }

        /* ── ALERTS ── */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .alert-success { background: rgba(34,197,94,0.1); color: #86efac; border: 1px solid rgba(34,197,94,0.2); }
        .alert-info    { background: rgba(59,130,246,0.1); color: #93c5fd; border: 1px solid rgba(59,130,246,0.2); }
        .alert-danger  { background: rgba(239,68,68,0.1);  color: #fca5a5; border: 1px solid rgba(239,68,68,0.2); }

        /* ── CARDS ── */
        .card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
        }
        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-header h3 {
            font-family: 'Syne', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text);
        }
        .card-body { padding: 20px; }

        /* ── TABLES ── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            padding: 11px 16px;
            text-align: left;
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--muted);
            border-bottom: 1px solid var(--border);
        }
        tbody tr { border-bottom: 1px solid rgba(255,255,255,0.04); transition: background 0.1s; }
        tbody tr:hover { background: rgba(255,255,255,0.03); }
        tbody tr:last-child { border-bottom: none; }
        tbody td { padding: 12px 16px; font-size: 0.875rem; }
        .empty-row td { text-align: center; color: var(--muted); padding: 40px; font-size: 0.875rem; }

        /* ── BADGES ── */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 600;
        }
        .badge-active   { background: rgba(34,197,94,0.1);  color: #86efac; border: 1px solid rgba(34,197,94,0.2); }
        .badge-canceled { background: rgba(239,68,68,0.1);  color: #fca5a5; border: 1px solid rgba(239,68,68,0.2); }
        .badge-neutral  { background: rgba(148,163,184,0.1); color: var(--muted); border: 1px solid rgba(148,163,184,0.15); }

        /* ── BUTTONS ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.15s;
            font-family: 'DM Sans', sans-serif;
        }
        .btn:hover { opacity: 0.85; transform: translateY(-1px); }
        .btn .material-symbols-outlined { font-size: 16px; }
        .btn-primary  { background: var(--accent);   color: #fff; }
        .btn-warning  { background: var(--warning);  color: #fff; }
        .btn-danger   { background: var(--danger);   color: #fff; }
        .btn-success  { background: var(--success);  color: #fff; }
        .btn-ghost    { background: rgba(255,255,255,0.06); color: var(--muted); border: 1px solid var(--border); }
        .btn-sm       { padding: 5px 10px; font-size: 0.78rem; }

        /* ── FORMS ── */
        .form-group { margin-bottom: 18px; }
        label { display: block; font-size: 0.8rem; font-weight: 600; color: var(--muted); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
        input[type="text"], input[type="email"], input[type="password"], select, textarea {
            width: 100%;
            padding: 10px 14px;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-size: 0.9rem;
            font-family: 'DM Sans', sans-serif;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: rgba(59,130,246,0.5);
            box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
        }
        select option { background: var(--panel); color: var(--text); }
        .error-msg { color: #fca5a5; font-size: 0.8rem; margin-top: 5px; display: flex; align-items: center; gap: 4px; }
        .form-actions { display: flex; gap: 10px; margin-top: 24px; }

        /* ── PAGE HEADER ── */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        .page-header h1 {
            font-family: 'Syne', sans-serif;
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--text);
        }
        .page-header p { color: var(--muted); font-size: 0.85rem; margin-top: 2px; }

        /* ── PAGINATION ── */
        .pagination-wrap { display: flex; justify-content: center; margin-top: 20px; gap: 4px; }
        .pagination-wrap .page-item { list-style: none; }
        .pagination-wrap .page-link {
            display: block;
            padding: 6px 12px;
            border-radius: 6px;
            border: 1px solid var(--border);
            color: var(--muted);
            text-decoration: none;
            font-size: 0.82rem;
            transition: all 0.15s;
        }
        .pagination-wrap .page-item.active .page-link { background: var(--accent); color: #fff; border-color: var(--accent); }
        .pagination-wrap .page-link:hover { background: rgba(255,255,255,0.06); color: var(--text); }

        /* ── STAT CARDS ── */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }
        .stat-card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: border-color 0.15s;
        }
        .stat-card:hover { border-color: rgba(255,255,255,0.14); }
        .stat-icon {
            width: 44px; height: 44px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }
        .stat-icon.blue   { background: rgba(59,130,246,0.15); color: var(--accent2); }
        .stat-icon.green  { background: rgba(34,197,94,0.15);  color: #86efac; }
        .stat-icon.orange { background: rgba(249,115,22,0.15); color: #fdba74; }
        .stat-icon.gold   { background: rgba(245,158,11,0.15); color: #fcd34d; }
        .stat-info .label { font-size: 0.75rem; color: var(--muted); font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-info .value { font-family: 'Syne', sans-serif; font-size: 1.6rem; font-weight: 800; color: var(--text); line-height: 1.1; margin-top: 2px; }

        /* Trash section */
        .trash-section { margin-top: 32px; }
        .trash-section .section-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--muted);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
    </style>
    @yield('styles')
</head>
<body>

{{-- ── SIDEBAR ── --}}
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="logo-row">
            <div class="logo-icon">
                <span class="material-symbols-outlined" style="color:#fff;font-size:18px">flight_takeoff</span>
            </div>
            <div>
                <h2>Sincronía Aérea</h2>
                <p>BOA — Panel Operativo</p>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Principal</div>
        <a href="{{ url('/inicio') }}" class="nav-link {{ request()->is('inicio') ? 'active' : '' }}">
            <span class="material-symbols-outlined">dashboard</span> Dashboard
        </a>

        <div class="nav-section-label">Gestión</div>
        <a href="{{ route('vuelos.index') }}" class="nav-link {{ request()->is('vuelos*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">flight</span> Vuelos
        </a>
        <a href="{{ route('aerolineas.index') }}" class="nav-link {{ request()->is('aerolineas*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">airlines</span> Aerolíneas
        </a>
        <a href="{{ route('pasajeros.index') }}" class="nav-link {{ request()->is('pasajeros*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">people</span> Pasajeros
        </a>
    </nav>

    <div class="sidebar-footer">
        © 2026 BOA Bolivia
    </div>
</aside>

{{-- ── MAIN ── --}}
<div class="main-wrapper">
    <header class="topbar">
        <div class="topbar-left">
            <span class="material-symbols-outlined" style="font-size:16px">chevron_right</span>
            <span class="page-title">@yield('page-title', 'Dashboard')</span>
        </div>
        <div class="topbar-right">
            <span class="topbar-badge">
                <span class="material-symbols-outlined" style="font-size:13px">circle</span>
                Sistema Activo
            </span>
        </div>
    </header>

    <main class="content-area">
        @if(session('exito'))
            <div class="alert alert-success">
                <span class="material-symbols-outlined" style="font-size:18px">check_circle</span>
                {{ session('exito') }}
            </div>
        @endif
        @if(session('info'))
            <div class="alert alert-info">
                <span class="material-symbols-outlined" style="font-size:18px">info</span>
                {{ session('info') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>

</body>
</html>
