@extends('layouts.app')

@section('title', 'Pasajeros')
@section('page-title', 'Pasajeros')

@section('content')

<div class="page-header">
    <div>
        <h1>👤 Pasajeros</h1>
        <p>Administra los pasajeros registrados en vuelos.</p>
    </div>
</div>

{{-- Formulario rápido --}}
<div class="card" style="margin-bottom:24px;">
    <div class="card-header"><h3>+ Registrar Pasajero</h3></div>
    <div class="card-body">
        <form action="{{ route('pasajeros.store') }}" method="POST">
            @csrf
            <div style="display:flex; gap:14px; flex-wrap:wrap; align-items:flex-end;">
                <div class="form-group" style="flex:2; min-width:200px; margin-bottom:0">
                    <label>Nombre Completo</label>
                    <input type="text" name="nombre_completo" value="{{ old('nombre_completo') }}" placeholder="Ej: Juan Carlos Pérez">
                    @error('nombre_completo') <p class="error-msg">⚠ {{ $message }}</p> @enderror
                </div>
                <div class="form-group" style="flex:1; min-width:180px; margin-bottom:0">
                    <label>Vuelo</label>
                    <select name="vuelo_id">
                        <option value="">— Selecciona vuelo —</option>
                        @foreach($vuelos as $v)
                            <option value="{{ $v->id }}" {{ old('vuelo_id') == $v->id ? 'selected' : '' }}>
                                #{{ $v->id }} → {{ $v->destino }}
                            </option>
                        @endforeach
                    </select>
                    @error('vuelo_id') <p class="error-msg">⚠ {{ $message }}</p> @enderror
                </div>
                <div style="padding-bottom:1px">
                    <button type="submit" class="btn btn-primary">
                        <span class="material-symbols-outlined">person_add</span> Registrar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Tabla --}}
<div class="card">
    <div class="card-header">
        <h3>Lista de Pasajeros</h3>
        <span style="color:var(--muted);font-size:0.8rem">{{ $pasajeros->total() }} registros</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre Completo</th>
                    <th>Vuelo</th>
                    <th>Destino</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pasajeros as $p)
                <tr>
                    <td style="color:var(--muted)">{{ $p->id }}</td>
                    <td><strong>{{ $p->nombre_completo }}</strong></td>
                    <td><span class="badge badge-neutral">#{{ $p->vuelo_id }}</span></td>
                    <td>{{ $p->vuelo->destino ?? '—' }}</td>
                    <td>
                        <form action="{{ route('pasajeros.destroy', $p) }}" method="POST" style="display:inline"
                              onsubmit="return confirm('¿Eliminar pasajero?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <span class="material-symbols-outlined">delete</span>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr class="empty-row"><td colspan="5">No hay pasajeros registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="pagination-wrap">{{ $pasajeros->links() }}</div>

@endsection
