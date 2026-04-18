@extends('layouts.app')

@section('content')
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-body">
                <h1 class="card-title h3">Panel de Control</h1>
                <p class="text-muted">Bienvenido al sistema inmobiliario, {{ auth()->user()->name }}.</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header bg-white">
                <h5 class="mb-0">Propiedades Recientes</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Título</th>
                            <th>Precio</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($propiedades as $propiedad)
                        <tr>
                            {{-- Mostramos el título real --}}
                            <td>{{ $propiedad->nombre_titulo }}</td>

                            {{-- Mostramos el precio con formato de moneda --}}
                            <td>${{ number_format($propiedad->precio, 2, ',', '.') }}</td>

                            {{-- Mostramos el estado con un color dinámico de Bootstrap --}}
                            <td>
                                @if($propiedad->estado == 'Disponible')
                                    <span class="badge bg-success">Disponible</span>
                                @elseif($propiedad->estado == 'Reservada')
                                    <span class="badge bg-warning text-dark">Reservada</span>
                                @else
                                    <span class="badge bg-danger">{{ $propiedad->estado }}</span>
                                @endif
                            </td>

                            <td>
                                <div class="btn-group btn-group-sm">
                                    {{-- Botón Editar: Ahora lleva el ID de la propiedad --}}
                                    <a href="{{ route('propiedades.edit', $propiedad->id) }}" class="btn btn-btn-warning">Editar</a>

                                    {{-- Lógica de Roles para Eliminar --}}
                                    @if(auth()->user()->role === 'admin')
                                        {{-- Formulario para eliminar (necesario para enviar método DELETE) --}}
                                        <form action="{{ route('propiedades.destroy', $propiedad->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta propiedad?')">
                                                Eliminar
                                            </button>
                                        </form>
                                    @else
                                        {{-- El Operario ve el botón deshabilitado como tenías en tu captura --}}
                                        <button class="btn btn-outline-secondary" disabled title="Solo Administrador">
                                            Eliminar
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach

                        {{-- Si la base de datos está vacía, mostramos un mensaje --}}
                        @if($propiedades->isEmpty())
                            <tr>
                                <td colspan="4" class="text-center text-muted">No hay propiedades cargadas actualmente.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection