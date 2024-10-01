<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Sistema de Venta y Artesanías</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/log.png') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7c6;
        }

        #sideNav {
            width: 200px;
            background-color: #755858;
            padding-top: 20px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
        }

        #sideNav img {
            width: 150px;
            border-radius: 50%;
        }

        #sideNav .navbar-nav .nav-link {
            color: white;
            padding: 10px 15px;
            text-align: center;
        }

        #sideNav .navbar-nav .nav-link:hover {
            background-color: #755858;
        }

        .content-section {
            margin-left: 220px;
            padding: 20px;
            top: 0;
        }

        .content-section img {
            width: 300px;
            border-radius: 20%;
        }

        .resume-section {
            padding: 60px 0;
            top: 0;
        }

        .custom-btn {
            background-color: #dc3545;
            border: none;
            color: white;
        }

        .custom-btn:hover {
            background-color: #c82333;
        }

        hr {
            border: 0;
            border-top: 1px solid #e9ecef;
            margin: 40px 0;
        }
    </style>
</head>

<body data-bs-spy="scroll" data-bs-target="#sideNav" data-bs-offset="0" tabindex="0">

    <!-- Navigation -->
    <nav class="navbar navbar-dark bg-dark flex-column" id="sideNav">
        <a class="navbar-brand text-center" href="#page-top">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" class="img-fluid">
            <span class="d-block text-white mt-2"><strong>JAND ARTE</strong></span>
        </a>
        <div class="navbar-nav mt-4">
            <a class="nav-link js-scroll-trigger" href="#about">Acerca de</a>
            <a class="nav-link js-scroll-trigger" href="#services">Servicios</a>
            <a class="nav-link js-scroll-trigger" href="#contact">Contactos</a>
            <a class="nav-link js-scroll-trigger" href="#login">Login</a>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="content-section">
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
                title: "Usuario registrado, Ya puedes iniciar sesion"
            });
        </script>

        @endif
        <!-- About Section -->
        <section class="resume-section" id="about">
            <div class="container text-center">
                <h1>Sistema de Venta de Artesanías</h1>
                <h2><strong>JAND ARTE</strong></h2>
                <img src="{{ asset('img/artesanias.jpg') }}" alt="img" class="img-fluid mb-4">
                <p><em>Manos que crean, comunidades que crecen. Ayudamos a impulsar el arte y la cultura artesanal.</em></p>
                <p>¿Eres artesano y buscas vender tus creaciones? ¿O tal vez te gustaría ser parte de nuestra red de entregas?</p>
                <p><strong>JAND ARTE</strong> te da la oportunidad de emprender con nosotros, expandir tu alcance y aumentar tus ingresos.</p>
                <p>Es muy sencillo. ¡Regístrate ahora y comienza tu camino hacia el éxito!</p>
                <a class="btn btn-primary btn-lg" href="#">¡REGÍSTRATE YA!</a>
                <hr>
                <h3>¿Por qué elegir <strong>JAND ARTE</strong>?</h3>
                <ul class="list-unstyled">
                    <li><i class="fas fa-hand-holding-heart"></i> <strong>Apoyo a la comunidad:</strong> Trabajamos con comunidades locales para impulsar el arte y la cultura.</li>
                    <li><i class="fas fa-users"></i> <strong>Red de vendedores:</strong> Únete a una comunidad de comerciantes exitosos.</li>
                    <li><i class="fas fa-shipping-fast"></i> <strong>Entrega eficiente:</strong> Contamos con un equipo de repartidores listos para llevar tus productos a todos los rincones del país.</li>
                    <li><i class="fas fa-money-bill-wave"></i> <strong>Ganancias aseguradas:</strong> Incrementa tus ventas con nuestra plataforma y soporte de marketing.</li>
                </ul>
            </div>
        </section>
        <hr>

        <!-- Services Section -->
        <section class="resume-section" id="services">
            <div class="container">
                <h2 class="text-center">Oportunidades de trabajo y beneficios</h2>
                <p class="text-center">¡Conviértete en parte de nuestro equipo y disfruta de los beneficios de trabajar con <strong>JAND ARTE</strong>!</p>
                <div class="row">
                    <div class="col-lg-6">
                        <h3><i class="fas fa-store"></i> Para Vendedores</h3>
                        <ul class="fa-ul">
                            <li><span class="fa-li"><i class="fas fa-tags"></i></span> Vende tus productos artesanales en nuestra plataforma.</li>
                            <li><span class="fa-li"><i class="fas fa-globe"></i></span> Expande tu alcance a nivel nacional.</li>
                            <li><span class="fa-li"><i class="fas fa-ad"></i></span> Publicidad y marketing incluidos para que tengas más visibilidad.</li>
                            <li><span class="fa-li"><i class="fas fa-handshake"></i></span> Soporte continuo para ayudarte a mejorar tus ventas.</li>
                        </ul>
                    </div>
                    <div class="col-lg-6">
                        <h3><i class="fas fa-shipping-fast"></i> Para Repartidores</h3>
                        <ul class="fa-ul">
                            <li><span class="fa-li"><i class="fas fa-route"></i></span> Conduce con rutas optimizadas para entregas rápidas y eficientes.</li>
                            <li><span class="fa-li"><i class="fas fa-wallet"></i></span> Gana dinero extra con cada entrega realizada.</li>
                            <li><span class="fa-li"><i class="fas fa-map-marker-alt"></i></span> Trabaja en las zonas más convenientes para ti.</li>
                            <li><span class="fa-li"><i class="fas fa-clock"></i></span> Flexibilidad horaria: ¡tú eliges cuándo trabajar!</li>
                        </ul>
                    </div>
                </div>
                <hr>
                <h2 class="text-center">¿Por qué elegirnos como cliente?</h2>
                <ul class="fa-ul">
                    <li><span class="fa-li"><i class="fas fa-shopping-basket"></i></span> Compra las mejores artesanías directamente de las manos que las crean.</li>
                    <li><span class="fa-li"><i class="fas fa-truck"></i></span> Recibe tus productos en tiempo récord, gracias a nuestras entregas optimizadas.</li>
                    <li><span class="fa-li"><i class="fas fa-shield-alt"></i></span> Paga de manera segura con nuestras pasarelas de pago certificadas.</li>
                    <li><span class="fa-li"><i class="fas fa-gift"></i></span> Descuentos exclusivos y ofertas personalizadas solo para usuarios registrados.</li>
                </ul>
                <a class="btn btn-success btn-lg mt-4" href="{{route('register')}}">¡Regístrate como Cliente!</a>
            </div>
        </section>
        <hr>

        <!-- Contact Section -->
        <section class="resume-section" id="contact">
            <div class="container">
                <h2 class="text-center mb-4">Contáctanos</h2>
                <p class="text-center">¿Tienes preguntas o necesitas más información? Estamos aquí para ayudarte. ¡No dudes en ponerte en contacto con nosotros a través de cualquiera de los siguientes medios!</p>
                <div class="row text-center">
                    <!-- Contact Phone -->
                    <div class="col-md-6 mb-4">
                        <h3><i class="fas fa-phone-alt"></i> Teléfonos (WhatsApp)</h3>
                        <ul class="fa-ul">
                            <li><span class="fa-li"><i class="fas fa-phone"></i></span> 78780178</li>
                            <li><span class="fa-li"><i class="fas fa-phone"></i></span> 72555435</li>
                        </ul>
                        <p>Resolvemos tus dudas vía WhatsApp rápidamente.</p>
                    </div>
                    <!-- Social Media -->
                    <div class="col-md-6 mb-4">
                        <h3><i class="fab fa-facebook-square"></i> Redes Sociales</h3>
                        <ul class="fa-ul">
                            <li><span class="fa-li"><i class="fab fa-facebook-square"></i></span> <a href="https://www.facebook.com/JANDARTE" target="_blank">JAND ARTE en Facebook</a></li>
                        </ul>
                        <p>Síguenos para conocer nuestras últimas novedades.</p>
                    </div>
                </div>
                <div class="row text-center">
                    <!-- Email Contact -->
                    <div class="col-md-12">
                        <h3><i class="far fa-envelope"></i> Correo Electrónico</h3>
                        <ul class="fa-ul">
                            <li><span class="fa-li"><i class="far fa-envelope"></i></span> <a href="mailto:jand_arte@gmail.com">jand_arte@gmail.com</a></li>
                        </ul>
                        <p>Envíanos un correo, responderemos en menos de 24 horas.</p>
                    </div>
                </div>
                <hr>
                <h4 class="text-center">¡Estamos listos para atenderte!</h4>
                <p class="text-center">No dudes en contactarnos si deseas más información sobre cómo vender tus productos o formar parte de nuestro equipo de repartidores.</p>
            </div>
        </section>
        <hr>

        <!-- Login Section -->
        <section class="resume-section" id="login">
            <div class="container">
                <h2 class="text-center mb-4">Inicia Sesión en <strong>JAND ARTE</strong></h2>
                <div class="row justify-content-center">
                    <!-- Logo Section -->
                    <div class="col-md-6 mb-4 text-center">
                        <img src="{{ asset('img/logo.png') }}" class="img-fluid" alt="Logo de JAND ARTE">
                    </div>
                    <!-- Login Form Section -->
                    <div class="col-md-6">
                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <!-- Email Field -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Introduzca su correo electrónico" required>
                            </div>
                            <!-- Password Field -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Introduzca su contraseña" required>
                            </div>
                            <!-- Remember Me Checkbox -->
                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Recordar contraseña</label>
                            </div>
                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary btn-lg btn-block">Ingresar</button>
                            <!-- Forgot Password & Register Links -->
                            <div class="mt-3">
                                <a href="#">¿Olvidaste tu contraseña?</a>
                                <span class="mx-2">|</span>
                                <a href="#">¿No tienes cuenta? Regístrate</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.js-scroll-trigger').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                document.getElementById(targetId).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>

</html>