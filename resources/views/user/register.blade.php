<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #755858;
        }

        .register-container {
            max-width: 800px;
            margin: 0 auto;
            margin-top: 50px;
            padding: 20px;
            background-color: #f7f7c6;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: bold;
        }

        .form-text {
            font-size: 12px;
            color: #6c757d;
        }

        .btn-primary {
            width: 100%;
        }

        .text-center a {
            text-decoration: none;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <div class="container register-container">
        <h2 class="text-center mb-4">Crear una Cuenta</h2>
        <form action="{{route('users.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">

                <input type="hidden" name="tipo_registro" value="1">
                <div class="col-md-6">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" value="{{old('nombre')}}">
                    @error('nombre')
                    <small class="text-danger">{{'*'.$message}}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="apellido" class="form-label">Apellido:</label>
                    <input type="text" name="apellido" id="apellido" class="form-control" value="{{old('apellido')}}">
                    @error('apellido')
                    <small class="text-danger">{{'*'.$message}}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="email" class="form-label">E-mail:</label>
                    <input type="text" name="email" id="email" class="form-control" value="{{old('email')}}">
                    @error('email')
                    <small class="text-danger">{{'*'.$message}}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="telefono" class="form-label">Celular:</label>
                    <input type="text" name="telefono" id="telefono" class="form-control" value="{{old('telefono')}}">
                    @error('telefono')
                    <small class="text-danger">{{'*'.$message}}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="password" class="form-label">Contraseña:</label>
                    <input type="password" name="password" id="password" class="form-control">
                    <div class="form-text">Escriba una contraseña segura.</div>
                    @error('password')
                    <small class="text-danger">{{'*'.$message}}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="password_confirm" class="form-label">Confirmar Contraseña:</label>
                    <input type="password" name="password_confirm" id="password_confirm" class="form-control">
                    <div class="form-text">Vuelva a escribir su contraseña.</div>
                    @error('password_confirm')
                    <small class="text-danger">{{'*'.$message}}</small>
                    @enderror
                </div>

                <!-- Selección de rol -->
                <div class="col-12">
                    <label class="form-label">Seleccionar rol:</label>
                    <select name="role" id="role" class="form-select">
                        <option value="" selected disabled>Seleccione</option>
                        @foreach ($roles as $item)
                        <option value="{{$item->name}}" @selected(old('role')==$item->name)>{{$item->name}}</option>
                        @endforeach
                    </select>
                    @error('role')
                    <small class="text-danger">{{'*'.$message}}</small>
                    @enderror
                </div>

                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}">¿Ya tienes una cuenta? Inicia sesión</a><br>
            <a href="#">¿Olvidaste tu contraseña?</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>