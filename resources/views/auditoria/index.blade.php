@extends('layouts.app') 

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Auditoria de Cambios</h2>
    </div>

    <div class="card shadow">
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Fecha/Hora</th>
                        <th>Usuario/Tipo</th>
                        <th>Acción</th>
                        <th>Propiedad ID</th>
                        <th>Detalles de Cambios</th>
                    </tr>
                </thead>
                
                <tbody>
                    @foreach($logs as $log)
                    <tr>
                        {{-- created_at es la fecha del evento --}}
                        <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                        
                        {{-- Quien lo hizo (usando la relación) --}}
                        <td>
                            @if($log->user)
                            {{ $log->user->name }}
                            <span class="badge bg-secondary">{{ ucfirst($log->user->role) }}</span>
                            @else
                            <span class="text-muted">Sistema / Desconocido</span>
                            @endif
                        </td>

                        {{-- Tipo de accion --}}
                        <td>
                            @if($log->accion == 'crear')
                            <span class="badge bg-success">Crear</span>
                            @elseif($log->accion == 'editar')
                            <span class="badge bg-warning text-dark">Editar</span>
                            @elseif($log->accion == 'eliminar')
                            <span class="badge bg-danger">Eliminar</span>
                            @endif
                        </td>
                        
                        <td>{{ $log->propiedad_id }}</td>
                        {{-- Detalles (Boton para colapsar) --}}
                        <td>
                            <button class="btn btn-sm btn-outline-primary" type="button" 
                                    data-bs-toggle="collapse" data-bs-target="#details-{{ $log->id }}">
                                Ver Cambios
                            </button>
                        </td>
                    </tr>
                    
                    {{-- Fila Colapsable para los detalles --}}
                    <tr>
                        <td colspan="5" class="p-0 border-0">
                            <div class="collapse bg-light" id="details-{{ $log->id }}">
                                <div class="p-3">
                                    {{-- Campos que NO queremos mostrar una sola vez al principio --}}
                                    @php $ocultar = ['id', 'created_at', 'updated_at', 'user_id', 'imagenes_path']; @endphp

                                    @if($log->accion == 'editar')
                                        <div class="row">
                                            {{-- Lado Izquierdo: Antes --}}
                                            <div class="col-md-6">
                                                <h6 class="text-danger"><strong><i class="bi bi-arrow-left-circle"></i> Antes:</strong></h6>
                                                <div class="bg-white p-3 border rounded shadow-sm">
                                                    @foreach($log->valores_anteriores as $campo => $valor)
                                                        @if(in_array($campo, $ocultar)) @continue @endif 
                                                        <div class="mb-1">
                                                            <span class="badge bg-secondary-subtle text-dark">{{ ucfirst(str_replace('_', ' ', $campo)) }}:</span>
                                                            <span class="text-muted text-decoration-line-through">{{ $valor }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            {{-- Lado Derecho: Ahora --}}
                                            <div class="col-md-6">
                                                <h6 class="text-success"><strong><i class="bi bi-arrow-right-circle"></i> Ahora:</strong></h6>
                                                <div class="bg-white p-3 border rounded shadow-sm">
                                                    @foreach($log->valores_nuevos as $campo => $valor)
                                                        @if(in_array($campo, $ocultar)) @continue @endif
                                                        <div class="mb-1">
                                                            <span class="badge bg-primary-subtle text-dark">{{ ucfirst(str_replace('_', ' ', $campo)) }}:</span>
                                                            <span class="fw-bold">{{ $valor }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        {{-- Crear o Eliminar --}}
                                        <h6 class="text-primary"><strong>Datos del registro ({{ ucfirst($log->accion) }}):</strong></h6>
                                        <div class="bg-white p-3 border rounded shadow-sm">
                                            @php $datos = $log->valores_nuevos ?? $log->valores_anteriores; @endphp
                                            @foreach($datos as $campo => $valor)
                                                @if(in_array($campo, $ocultar)) @continue @endif {{-- FILTRO AQUÍ --}}
                                                @if(!is_array($valor))
                                                    <div class="mb-1">
                                                        <strong class="text-capitalize">{{ str_replace('_', ' ', $campo) }}:</strong> 
                                                        <span>{{ $valor }}</span>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    {{-- Botones de Paginación --}}
    <div class="mt-3">
        {{ $logs->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection