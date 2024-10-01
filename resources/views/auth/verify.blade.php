<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Código</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #F2CA52; /* Color de fondo suave */
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #0D0D0D; /* Texto principal */
            text-align: center;
        }

        h1 {
            color: #0D0D0D; /* Color del título */
            margin-bottom: 30px;
            font-size: 2.5em; /* Tamaño de fuente grande */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1); /* Sombra suave */
        }

        form {
            background-color: #F2BC57; /* Fondo del formulario */
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            width: 300px;
            transition: transform 0.2s;
        }

        form:hover {
            transform: scale(1.02); /* Efecto de hover en el formulario */
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #0D0D0D; /* Color de la etiqueta */
            font-size: 1.1em; /* Tamaño de fuente ligeramente mayor */
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 2px solid #F29C50; /* Bordes del input */
            border-radius: 6px;
            font-size: 1em;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        input[type="text"]:focus {
            border-color: #F28B50; /* Color al enfocar el input */
            box-shadow: 0 0 5px rgba(242, 188, 87, 0.5); /* Sombra de enfoque */
            outline: none;
        }

        button {
            background-color: #F29C50; /* Fondo del botón */
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1.1em;
            width: 100%;
            transition: background-color 0.3s, transform 0.2s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        button:hover {
            background-color: #F28B50; /* Color del botón al pasar el mouse */
            transform: translateY(-2px); /* Efecto de elevación */
        }

        .error-messages {
            background-color: #F2BC57; /* Fondo de mensajes de error */
            color: #0D0D0D; /* Texto de error */
            border: 1px solid #F29C50; /* Borde de error */
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <h1>Ingresa tu código de verificación</h1>
    
    @if ($errors->any())
        <div class="error-messages">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('verify.code') }}" method="POST">
        @csrf
        <div>
            <label for="verification_code">Código de verificación:</label>
            <input type="text" name="verification_code" required>
        </div>
        <button type="submit">Verificar</button>
    </form>
</body>
</html>
