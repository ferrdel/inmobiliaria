<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/dashboard') }}">Evolvere 2026</a>     
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/dashboard') }}">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/dashboard') }}">Ver Propiedades</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-success mt-1 ms-lg-2" href="{{ route('propiedades.create') }}">+ Cargar Nueva</a>
                </li>
                
                {{-- Solo ADMINISTRADOR puede ver historial --}}
                {{-- Usamos IF para ocultar este menú al Operario --}}
                @if(auth()->user()->role === 'admin')                    
                    <li class="nav-item">
                        <a class="nav-link text-warning" href="{{ route('auditoria.index') }}">
                            <i class="bi bi-clock-history"></i> Auditoria
                        </a>
                    </li>
                @endif
            </ul>
            
            <div class="d-flex align-items-center text-light">
                <span class="badge bg-secondary ms-1">{{ ucfirst(auth()->user()->role) }}</span>
                
                {{-- Tu formulario de logout manual --}}
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">Salir</button>
                </form>
            </div>
        </div>
    </div>
</nav>