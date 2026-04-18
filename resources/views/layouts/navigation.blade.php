<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/dashboard') }}">Evolvere 2026</a>
        
        <button class="navbar-collapse" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/dashboard') }}">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Ver Propiedades</a>
                </li>

                {{-- REQUISITO IMAGEN: Ambos (Admin y Operario) pueden cargar --}}
                {{-- No necesitamos filtro de rol aquí, ambos lo ven --}}
                <li class="nav-item">
                    <a class="btn btn-sm btn-success mt-1 ms-lg-2" href="{{ route('propiedades.create') }}">+ Cargar Nueva</a>
                </li>
                
                {{-- REQUISITO IMAGEN: Solo ADMINISTRADOR puede ver historial --}}
                {{-- Usamos IF para ocultar este menú al Operario --}}
                @if(auth()->user()->role === 'admin')
                    <li class="nav-item dropdown ms-lg-4">
                        <a class="nav-link dropdown-toggle text-warning" href="#" role="button" data-bs-toggle="dropdown">
                            Administración
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="#">Gestión de Usuarios</a></li>
                            <li><hr class="dropdown-divider"></li>
                            {{-- Aquí está la funcionalidad de la imagen --}}
                            <li><a class="dropdown-item text-warning" href="#">Historial de Auditoría</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-warning" href="{{ route('auditoria.index') }}">
                            <i class="bi bi-clock-history"></i> Auditoría
                        </a>
                    </li>
                @endif
            </ul>
            
            <div class="d-flex align-items-center text-light">
                <span class="me-3">
                    {{ auth()->user()->name }} 
                    <span class="badge bg-secondary ms-1">{{ ucfirst(auth()->user()->role) }}</span>
                </span>
                
                {{-- Tu formulario de logout manual --}}
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">Salir</button>
                </form>
            </div>
        </div>
    </div>
</nav>