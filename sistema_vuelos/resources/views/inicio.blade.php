@extends('layouts.app')

@section('title', 'Dashboard — Sincronía Aérea')
@section('page-title', 'Dashboard')

@section('content')

<div class="page-header">
    <div>
        <h1>Bienvenido, {{ $usuario }} ✈</h1>
        <p>Panel de control operativo — Sincronía Aérea BOA</p>
    </div>
</div>

{{-- Tarjetas de estadísticas --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">
            <span class="material-symbols-outlined">airlines</span>
        </div>
        <div class="stat-info">
            <div class="label">Aerolíneas</div>
            <div class="value">{{ \App\Models\Aerolinea::count() }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <span class="material-symbols-outlined">flight</span>
        </div>
        <div class="stat-info">
            <div class="label">Vuelos Activos</div>
            <div class="value">{{ \App\Models\Vuelo::count() }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange">
            <span class="material-symbols-outlined">people</span>
        </div>
        <div class="stat-info">
            <div class="label">Pasajeros</div>
            <div class="value">{{ \App\Models\Pasajero::count() }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon gold">
            <span class="material-symbols-outlined">delete</span>
        </div>
        <div class="stat-info">
            <div class="label">Vuelos Cancelados</div>
            <div class="value">{{ \App\Models\Vuelo::onlyTrashed()->count() }}</div>
        </div>
    </div>
</div>

{{-- Accesos rápidos --}}
<div style="display:grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">

    {{-- Últimos vuelos --}}
    <div class="card">
        <div class="card-header">
            <h3><span class="material-symbols-outlined" style="font-size:16px;vertical-align:middle">flight</span> Últimos Vuelos</h3>
            <a href="{{ route('vuelos.index') }}" class="btn btn-ghost btn-sm">Ver todos</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Destino</th>
                        <th>Aerolínea</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(\App\Models\Vuelo::with('aerolinea')->latest()->take(5)->get() as $vuelo)
                    <tr>
                        <td style="color:var(--muted)">{{ $vuelo->id }}</td>
                        <td><strong>{{ $vuelo->destino }}</strong></td>
                        <td>{{ $vuelo->aerolinea->nombre ?? '—' }}</td>
                        <td><span class="badge badge-active">Activo</span></td>
                    </tr>
                    @empty
                    <tr class="empty-row"><td colspan="4">Sin vuelos aún. <a href="{{ route('vuelos.create') }}" style="color:var(--accent)">Crear uno</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Últimos pasajeros --}}
    <div class="card">
        <div class="card-header">
            <h3><span class="material-symbols-outlined" style="font-size:16px;vertical-align:middle">people</span> Últimos Pasajeros</h3>
            <a href="{{ route('pasajeros.index') }}" class="btn btn-ghost btn-sm">Ver todos</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Vuelo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(\App\Models\Pasajero::with('vuelo')->latest()->take(5)->get() as $pasajero)
                    <tr>
                        <td style="color:var(--muted)">{{ $pasajero->id }}</td>
                        <td><strong>{{ $pasajero->nombre_completo }}</strong></td>
                        <td>→ {{ $pasajero->vuelo->destino ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr class="empty-row"><td colspan="3">Sin pasajeros aún.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Botones de acción rápida --}}
<div class="card">
    <div class="card-header">
        <h3>⚡ Acciones Rápidas</h3>
    </div>
    <div class="card-body" style="display:flex; gap:12px; flex-wrap:wrap;">
        <a href="{{ route('vuelos.create') }}" class="btn btn-primary">
            <span class="material-symbols-outlined">add</span> Nuevo Vuelo
        </a>
        <a href="{{ route('aerolineas.index') }}" class="btn btn-ghost">
            <span class="material-symbols-outlined">airlines</span> Gestionar Aerolíneas
        </a>
        <a href="{{ route('pasajeros.index') }}" class="btn btn-ghost">
            <span class="material-symbols-outlined">person_add</span> Registrar Pasajero
        </a>
        <a href="{{ route('vuelos.index') }}" class="btn btn-ghost">
            <span class="material-symbols-outlined">table_view</span> Ver todos los Vuelos
        </a>
    </div>
</div>

@endsection
