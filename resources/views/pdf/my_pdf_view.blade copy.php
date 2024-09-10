<!DOCTYPE html>
<html>
<head>
    <title>PDF de Cotización</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1, h2, h3, p {
            margin: 0 0 10px;
        }
        .section {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Cotización #{{ $cotizacion->id }}</h1>

    <div class="section">
        <h2>Detalles del Curso</h2>
        <p><strong>Nombre:</strong> {{ $course->name }}</p>
        <p><strong>Descripción:</strong> {{ $course->description }}</p>
        <!-- Añade más detalles del curso según sea necesario -->
    </div>

    <div class="section">
        <h2>Detalles del Curso Adicional</h2>
        <p><strong>Nombre:</strong> {{ $addCourse->name }}</p>
        <p><strong>Descripción:</strong> {{ $addCourse->description }}</p>
        <!-- Añade más detalles del curso adicional según sea necesario -->
    </div>

    <div class="section">
        <h2>Detalles de la Cotización</h2>
        <p><strong>Fecha:</strong> {{ $cotizacion->created_at->format('d/m/Y') }}</p>
        <p><strong>Precio:</strong> {{ $cotizacion->price }}</p>
        <!-- Añade más detalles de la cotización según sea necesario -->
    </div>
</body>
</html>
