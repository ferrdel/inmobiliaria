@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-warning text-dark d-flex justify-content-between">
            <h4 class="mb-0">Modificar Propiedad: {{ $propiedad->nombre_titulo }}</h4>
            <span class="badge bg-dark">ID: {{ $propiedad->id }}</span>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('propiedades.update', ['propiedad' => $propiedad->id]) }}" method="POST">
                @csrf
                @method('PUT') {{-- OBLIGATORIO para actualizaciones en Laravel --}}

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Nombre y Título</label>
                        <input type="text" name="nombre_titulo" class="form-control" value="{{ old('nombre_titulo', $propiedad->nombre_titulo) }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tipo</label>
                        <select name="tipo" class="form-select">
                            @foreach(['Casa', 'Departamento', 'Local', 'Terreno'] as $tipo)
                                <option value="{{ $tipo }}" {{ $propiedad->tipo == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Precio</label>
                        <input type="text" 
                            name="precio" 
                            class="form-control {{ auth()->user()->role !== 'admin' ? 'bg-light' : '' }}" 
                            value="{{ $propiedad->precio }}"
                            {{ auth()->user()->role !== 'admin' ? 'readonly' : '' }}>

                        @if(auth()->user()->role !== 'admin')
                            <small class="text-muted italic">
                                <i class="bi bi-info-circle"></i> Usted no tiene permisos para modificar el precio.
                            </small>
                        @endif
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="Disponible" {{ $propiedad->estado == 'Disponible' ? 'selected' : '' }}>Disponible</option>
                            <option value="Reservada" {{ $propiedad->estado == 'Reservada' ? 'selected' : '' }}>Reservada</option>
                            <option value="Vendida" {{ $propiedad->estado == 'Vendida' ? 'selected' : '' }}>Vendida</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Superficie (m2)</label>
                        <input type="number" name="superficie_m2" class="form-control" value="{{ old('superficie_m2', $propiedad->superficie_m2) }}" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Ambientes</label>
                        <input type="number" name="ambientes" class="form-control" value="{{ old('ambientes', $propiedad->ambientes) }}" required>
                    </div>
                    
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="direccion" class="form-control" placeholder="Dirección" value="{{ old('direccion', $propiedad->direccion) }}" required>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $propiedad->descripcion) }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Actualizar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection