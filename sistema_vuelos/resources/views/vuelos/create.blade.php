@extends('layouts.app')

@section('title', 'Nuevo Vuelo')
@section('page-title', 'Programar Vuelo')

@section('content')

<div class="page-header">
    <div>
        <h1>✈ Nuevo Vuelo</h1>
        <p>Completa los datos para programar un vuelo.</p>
    </div>
    <a href="{{ route('vuelos.index') }}" class="btn btn-ghost">
        <span class="material-symbols-outlined">arrow_back</span> Volver
    </a>
</div>

<div class="card" style="max-width:520px">
    <div class="card-header"><h3>Datos del Vuelo</h3></div>
    <div class="card-body">
        <form action="{{ route('vuelos.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Aerolínea</label>
                <select name="aerolinea_id">
                    <option value="">— Selecciona una aerolínea —</option>
                    @foreach($aerolineas as $a)
                        <option value="{{ $a->id }}" {{ old('aerolinea_id') == $a->id ? 'selected' : '' }}>{{ $a->nombre }}</option>
                    @endforeach
                </select>
                @error('aerolinea_id') <p class="error-msg">⚠ {{ $message }}</p> @enderror
                @if($aerolineas->isEmpty())
                    <p class="error-msg show">⚠ No hay aerolíneas. <a href="{{ route('aerolineas.index') }}" style="color:var(--accent)">Crea una primero.</a></p>
                @endif
            </div>
            <div class="form-group">
                <label>Destino</label>
                <input type="text" name="destino" value="{{ old('destino') }}" placeholder="Ej: La Paz">
                @error('destino') <p class="error-msg">⚠ {{ $message }}</p> @enderror
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <span class="material-symbols-outlined">save</span> Guardar Vuelo
                </button>
                <a href="{{ route('vuelos.index') }}" class="btn btn-ghost">Cancelar</a>
            </div>
        </form>
    </div>
</div>

@endsection
