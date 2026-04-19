<!DOCTYPE html>
<html>
<head>
    <style>
        .card { border: 1px solid #eee; padding: 20px; font-family: sans-serif; }
        .titulo { color: #2d3748; font-size: 20px; font-weight: bold; }
        .info { margin-top: 10px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="titulo">Nueva propiedad cargada</div>
        <div class="info">
            <p><strong>Título:</strong> {{ $propiedad->nombre_titulo }}</p>
            <p><strong>Tipo:</strong> {{ $propiedad->tipo }}</p>
            <p><strong>Precio:</strong> ${{ number_format($propiedad->precio, 2) }}</p>
            <p><strong>Ubicación:</strong> {{ $propiedad->direccion }}</p>
        </div>
        <p>Registrado por: {{ auth()->user()->name }}</p>
    </div>
</body>
</html>