@extends('layouts.app')

@section('title', 'Editar Vuelo')
@section('page-title', 'Editar Vuelo')

@section('content')

<div class="page-header">
    <div>
        <h1>✏ Editar Vuelo #{{ $vuelo->id }}</h1>
        <p>Modifica los datos del vuelo <strong>{{ $vuelo->destino }}</strong>.</p>
    </div>
    <a href="{{ route('vuelos.index') }}" class="btn btn-ghost">
        <span class="material-symbols-outlined">arrow_back</span> Volver
    </a>
</div>

<div class="card" style="max-width:520px">
    <div class="card-header"><h3>Datos del Vuelo</h3></div>
    <div class="card-body">
        <form action="{{ route('vuelos.update', $vuelo) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Aerolínea</label>
                <select name="aerolinea_id">
                    <option value="">— Selecciona —</option>
                    @foreach($aerolineas as $a)
                        <option value="{{ $a->id }}" {{ old('aerolinea_id', $vuelo->aerolinea_id) == $a->id ? 'selected' : '' }}>{{ $a->nombre }}</option>
                    @endforeach
                </select>
                @error('aerolinea_id') <p class="error-msg">⚠ {{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label>Destino</label>
                <input type="text" name="destino" value="{{ old('destino', $vuelo->destino) }}" placeholder="Ej: Cochabamba">
                @error('destino') <p class="error-msg">⚠ {{ $message }}</p> @enderror
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-warning">
                    <span class="material-symbols-outlined">save</span> Guardar Cambios
                </button>
                <a href="{{ route('vuelos.index') }}" class="btn btn-ghost">Cancelar</a>
            </div>
        </form>
    </div>
</div>

@endsection
