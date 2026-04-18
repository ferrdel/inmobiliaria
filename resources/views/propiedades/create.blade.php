@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Cargar Nueva Propiedad</h4>
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
                    <form action="{{ route('propiedades.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Nombre y Título</label>
                                <input type="text" name="nombre_titulo" class="form-control" placeholder="Ej: Casa Quinta con Pileta" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tipo de Propiedad</label>
                                <select name="tipo" class="form-select" required>
                                    <option value="Casa">Casa</option>
                                    <option value="Departamento">Departamento</option>
                                    <option value="Local">Local</option>
                                    <option value="Terreno">Terreno</option>
                                    <option value="Galpon">Galpón</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Estado</label>
                                <select name="estado" class="form-select" required>
                                    <option value="Disponible">Disponible</option>
                                    <option value="Reservada">Reservada</option>
                                    <option value="Vendida">Vendida</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Precio</label>
                                <input type="number" name="precio" class="form-control" step="0.01" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Superficie (m2)</label>
                                <input type="number" name="superficie_m2" class="form-control" step="0.01" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Ambientes</label>
                                <input type="number" name="ambientes" class="form-control" required>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Dirección</label>
                                <input type="text" name="direccion" class="form-control" placeholder="Dirección completa" required>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Imágenes de la Propiedad</label>
                                <input type="file" name="imagenes[]" class="form-control" multiple accept="image/*">
                                <small class="text-muted">Puedes seleccionar varias fotos manteniendo presionada la tecla Ctrl.</small>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Descripción</label>
                                <textarea name="descripcion" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary me-2">Cancelar</a>
                            <button type="submit" class="btn btn-success px-4">Guardar Propiedad</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection