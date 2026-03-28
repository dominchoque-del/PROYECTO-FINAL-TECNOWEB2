@extends('layouts.app')

@section('title', 'Vuelos — Sincronía Aérea')
@section('page-title', 'Gestión de Vuelos')

@section('content')

<div class="page-header">
    <div>
        <h1>✈ Vuelos</h1>
        <p>Administra y monitorea todos los vuelos del sistema.</p>
    </div>
    <a href="{{ route('vuelos.create') }}" class="btn btn-primary">
        <span class="material-symbols-outlined">add</span> Nuevo Vuelo
    </a>
</div>

{{-- Tabla vuelos activos --}}
<div class="card">
    <div class="card-header">
        <h3>Vuelos Activos</h3>
        <span style="color:var(--muted);font-size:0.8rem">{{ $vuelos->total() }} registros</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Destino</th>
                    <th>Aerolínea</th>
                    <th>Pasajeros</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vuelos as $vuelo)
                <tr>
                    <td style="color:var(--muted)">{{ $vuelo->id }}</td>
                    <td><strong>{{ $vuelo->destino }}</strong></td>
                    <td>{{ $vuelo->aerolinea->nombre ?? '—' }}</td>
                    <td>{{ $vuelo->pasajeros->count() }}</td>
                    <td><span class="badge badge-active">Activo</span></td>
                    <td>
                        <a href="{{ route('vuelos.edit', $vuelo) }}" class="btn btn-warning btn-sm">
                            <span class="material-symbols-outlined">edit</span> Editar
                        </a>
                        <form action="{{ route('vuelos.destroy', $vuelo) }}" method="POST" style="display:inline"
                              onsubmit="return confirm('¿Cancelar este vuelo?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <span class="material-symbols-outlined">delete</span> Cancelar
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr class="empty-row">
                    <td colspan="6">No hay vuelos activos. <a href="{{ route('vuelos.create') }}" style="color:var(--accent)">Programa uno ahora.</a></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="pagination-wrap">{{ $vuelos->links() }}</div>

{{-- Papelera --}}
@php $cancelados = \App\Models\Vuelo::onlyTrashed()->with('aerolinea')->get(); @endphp

@if($cancelados->count() > 0)
<div class="trash-section">
    <div class="section-title">
        <span class="material-symbols-outlined" style="font-size:16px">delete</span>
        Papelera — {{ $cancelados->count() }} vuelos cancelados
    </div>
    <div class="card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Destino</th>
                        <th>Aerolínea</th>
                        <th>Cancelado el</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cancelados as $vuelo)
                    <tr>
                        <td style="color:var(--muted)">{{ $vuelo->id }}</td>
                        <td>{{ $vuelo->destino }}</td>
                        <td>{{ $vuelo->aerolinea->nombre ?? '—' }}</td>
                        <td style="color:var(--muted)">{{ $vuelo->deleted_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('vuelos.restaurar', $vuelo->id) }}" class="btn btn-success btn-sm">
                                <span class="material-symbols-outlined">restore</span> Restaurar
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@endsection
