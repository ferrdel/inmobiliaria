<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Login - Inmobiliaria</title>
</head>
<body style="background-color: #b3d9ff !important;">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-lg border-0" style="max-width: 900px; width: 100%; overflow: hidden;">
            <div class="row g-0">
                <div class="col-md-6 d-none d-md-block" style="background: url('https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=1073&q=80') center/cover no-repeat;">
                    <div class="h-100 d-flex flex-column justify-content-center align-items-center text-white" style="background: rgba(0,0,0,0.3);">
                        <h2 class="fw-bold">Evolvere 2026</h2>
                        <p>Gestion Inmobiliaria Profesional</p>
                    </div>
                </div>

                <div class="col-md-6 p-5 bg-white">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold text-primary">Acceso Inmobiliaria</h3>

                        <form action="{{ url('/login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Usuario</label>
                                <input type="text" name="name" class="form-control" id="name" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" name="password" class="form-control" id="password" required>
                            </div>
                                
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    {{ $errors->first() }}
                                </div>
                            @endif

                            <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                        </form>
                    </div>   
                </div>
            </div>
        </div>
    </div>
</body>
</html>