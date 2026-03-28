@extends('layouts.app')

@section('title', 'Aerolíneas')
@section('page-title', 'Aerolíneas')

@section('content')

<div class="page-header">
    <div>
        <h1>🏢 Aerolíneas</h1>
        <p>Gestiona las aerolíneas del sistema.</p>
    </div>
</div>

{{-- Formulario rápido --}}
<div class="card" style="max-width:560px; margin-bottom:24px;">
    <div class="card-header"><h3>+ Agregar Aerolínea</h3></div>
    <div class="card-body">
        <form action="{{ route('aerolineas.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" placeholder="Ej: Boliviana de Aviación">
                @error('nombre') <p class="error-msg">⚠ {{ $message }}</p> @enderror
            </div>
            <button type="submit" class="btn btn-primary">
                <span class="material-symbols-outlined">save</span> Guardar
            </button>
        </form>
    </div>
</div>

{{-- Tabla --}}
<div class="card">
    <div class="card-header">
        <h3>Lista de Aerolíneas</h3>
        <span style="color:var(--muted);font-size:0.8rem">{{ $aerolineas->count() }} registros</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Vuelos</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($aerolineas as $a)
                <tr>
                    <td style="color:var(--muted)">{{ $a->id }}</td>
                    <td><strong>{{ $a->nombre }}</strong></td>
                    <td>{{ $a->vuelos->count() }}</td>
                    <td>
                        <form action="{{ route('aerolineas.destroy', $a) }}" method="POST" style="display:inline"
                              onsubmit="return confirm('¿Eliminar esta aerolínea?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <span class="material-symbols-outlined">delete</span> Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr class="empty-row"><td colspan="4">No hay aerolíneas. ¡Agrega la primera arriba!</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
