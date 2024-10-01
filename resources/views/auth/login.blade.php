<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario / Inicio de Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('css/auth.style.css') }}" />

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>


<body>
    @if (session('success'))
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
        Toast.fire({
            icon: "success",
            title: "Usuario Registrado"
        });
    </script>

    @endif

    <div class="container">
        <div class="forms-container">
            <div class="signin-signup">

                <!-- Formulario de Iniciar Sesión -->
                <form action="{{ route('login') }}" method="post" class="sign-in-form">
                    @csrf
                    <h2 class="title">Iniciar Sesión</h2>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" name="email" placeholder="E-mail" value="{{ old('email') }}" required>
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" placeholder="Contraseña" required>
                    </div>
                    <input type="submit" value="Iniciar Sesión" class="btn solid">

                </form>

                <!-- Formulario de Registro -->
                <form action="{{ route('users.store') }}" method="post" class="sign-up-form">
                    @csrf
                    <h2 class="title">Crear una Cuenta</h2>
                    <input type="hidden" name="source" value="registration"> <!-- Cambia esto según tu caso -->

                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" name="nombre" placeholder="Nombre" value="{{ old('nombre') }}" required>
                        @error('nombre')
                        <small class="text-danger">{{ '*'.$message }}</small>
                        @enderror
                    </div>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" name="apellido" placeholder="Apellido" value="{{ old('apellido') }}" required>
                        @error('apellido')
                        <small class="text-danger">{{ '*'.$message }}</small>
                        @enderror
                    </div>
                    <div class="input-field">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                        @error('email')
                        <small class="text-danger">{{ '*'.$message }}</small>
                        @enderror
                    </div>
                    <div class="input-field">
                        <i class="fas fa-phone"></i>
                        <input type="text" name="telefono" placeholder="Celular" value="{{ old('telefono') }}" required>
                        @error('telefono')
                        <small class="text-danger">{{ '*'.$message }}</small>
                        @enderror
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" placeholder="Contraseña" required>
                        @error('password')
                        <small class="text-danger">{{ '*'.$message }}</small>
                        @enderror
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password_confirm" placeholder="Confirmar Contraseña" required>
                        @error('password_confirm')
                        <small class="text-danger">{{ '*'.$message }}</small>
                        @enderror
                    </div>
                    <div class="input-field">
                        <i class="fas fa-user-tag"></i>
                        <select name="role" class="form-select">
                            <option value="" disabled selected>Seleccionar rol</option>
                            @foreach($roles as $role)

                            @if ($role->id != 1) <!-- Cambia 1 por el ID del rol que quieres ocultar -->
                            <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endif
                            @endforeach
                        </select>
                        @error('role')
                        <small class="text-danger">{{ '*'.$message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="comunidade" class="form-label">Comunidad</label>
                        <select class="form-select" id="comunidade" name="comunidade">
                            <option value="">Seleccione una comunidad</option>
                            @foreach($comunidades as $comunidad)
                            <option value="{{ $comunidad->id }}" @selected(old('comunidad_id')==$comunidad->id)>{{ $comunidad->nombre }}</option>
                            @endforeach
                        </select>
                        <small>Si no encuentra su comunidad, puede crear una nueva:</small>
                        <input type="text" class="form-control mt-2" name="nueva_comunidad" value="{{ old('nueva_comunidad') }}" placeholder="Nombre de la nueva comunidad">
                    </div>



                    <input type="submit" class="btn" value="Registrarse">

                </form>
            </div>
        </div>

        <!-- Paneles de alternar entre registro y login -->
        <div class="panels-container">
            <div class="panel left-panel">
                <div class="content">
                    <h3>¿Eres nuevo aquí?</h3>
                    <p>
                        Regístrate y únete a nuestra plataforma para disfrutar de más servicios.
                    </p>
                    <img src="img/logo.png" alt="Una bonita imagen" width="300" height="300">

                    <button class="btn transparent" id="sign-up-btn">
                        Registrarse
                    </button>
                </div>
                <img src="{{ asset('images/login.svg') }}" class="image" alt="">
            </div>
            <div class="panel right-panel">
                <div class="content">
                    <h3>¿Ya tienes una cuenta?</h3>
                    <p>
                        Inicia sesión para continuar.
                    </p>
                    <img src="img/logo.png" alt="Una bonita imagen" width="300" height="300">
                    <button class="btn transparent" id="sign-in-btn">
                        Iniciar Sesión
                    </button>
                </div>
                <img src="{{ asset('images/reg.svg') }}" class="image" alt="">
            </div>
        </div>
    </div>

    <script src="{{ asset('js/auth.app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>