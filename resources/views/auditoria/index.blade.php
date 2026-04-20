@extends('layouts.app') {{-- Asumiendo que tienes tu layout de Bootstrap --}}

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Historial de Auditoría de Cambios</h2>
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
                        
                        {{-- Quién lo hizo (usando la relación) --}}
                        <td>
                            @if($log->user)
                                {{ $log->user->name }} 
                                <span class="badge bg-secondary">{{ ucfirst($log->user->role) }}</span>
                            @else
                                <span class="text-muted">Sistema / Desconocido</span>
                            @endif
                        </td>
                        
                        {{-- Acción con color de Bootstrap --}}
                        <td>
                            @if($log->accion == 'crear')
                                <span class="badge bg-success">Crear</span>
                            @elseif($log->accion == 'editar')
                                <span class="badge bg-warning text-dark">Editar</span>
                            @elseif($log->accion == 'eliminar')
                                <span class="badge bg-danger">Eliminar</span>
                            @endif
                        </td>
                        
                        <td>{{ $log->propiedad_id ?? 'N/A' }}</td>
                        
                        {{-- Detalles (Botón para colapsar) --}}
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
                                    @if($log->accion == 'editar')
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6><del class="text-danger">Valores Anteriores:</del></h6>
                                                <pre class="bg-white p-2 border rounded"><code>{{ json_encode($log->valores_anteriores, JSON_PRETTY_PRINT) }}</code></pre>
                                            </div>
                                            <div class="col-md-6">
                                                <h6><ins class="text-success">Valores Nuevos:</ins></h6>
                                                <pre class="bg-white p-2 border rounded"><code>{{ json_encode($log->valores_nuevos, JSON_PRETTY_PRINT) }}</code></pre>
                                            </div>
                                        </div>
                                    @else
                                        {{-- Para crear o eliminar, mostramos solo los valores nuevos/eliminados --}}
                                        <h6>Datos del registro ({{ $log->accion }}):</h6>
                                        <pre class="bg-white p-2 border rounded"><code>{{ json_encode($log->valores_nuevos ?? $log->valores_anteriores, JSON_PRETTY_PRINT) }}</code></pre>
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
    
    {{-- Paginación de Bootstrap --}}
    <div class="mt-3">
        {{ $logs->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection