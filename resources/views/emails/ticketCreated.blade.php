<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $subjectLine }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            background-color: #ffffff;
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #e2f4ea;
            color: #034d26;
            padding: 10px 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .body {
            padding: 20px;
            line-height: 1.5;
            color: #333;
        }
        .footer {
            text-align: center;
            padding: 10px 20px;
            background-color: #e2f4ea;
            color: #034d26;
            border-radius: 0 0 5px 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $subjectLine }}</h1>
        </div>
        <div class="body">
            <p><strong>ID del Ticket:</strong> {{ $ticketId }}</p>
            <p><strong>Fecha de Alta:</strong> {{ $fechaAlta }}</p>
            <p><strong>Creado Por:</strong> {{ $creadoPor }}</p>
        </div>
        <div class="footer">
            Desde el sistema de Tickets. Shades de Mexico.
        </div>
    </div>
</body>
</html>