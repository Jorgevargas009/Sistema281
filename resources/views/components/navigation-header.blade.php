<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <a class="navbar-brand ps-3" href="{{ route('panel') }}">Sistema venta de artesanias</a>
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
        <i class="fas fa-bars"></i>
    </button>
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <div class="input-group">
            <input class="form-control" type="text" placeholder="Buscar por..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
            <button class="btn btn-primary" id="btnNavbarSearch" type="button">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="notificationDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <span class="badge bg-danger">{{ auth()->user()->notificaciones()->where('leida', false)->count() }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                @forelse(auth()->user()->notificaciones as $notificacion)
                    <li>
                        <a class="dropdown-item" href="{{ route('notificaciones.marcarComoLeida', $notificacion->id) }}">
                            <strong>{{ $notificacion->mensaje }}</strong> 
                            <small class="text-muted">{{ \Carbon\Carbon::parse($notificacion->fecha_envio)->diffForHumans() }}</small>
                        </a>
                    </li>
                @empty
                    <li><a class="dropdown-item" href="#!">No hay notificaciones</a></li>
                @endforelse
                <li><hr class="dropdown-divider" /></li>
                <li><a class="dropdown-item" href="#!">Ver todas las notificaciones</a></li>
            </ul>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user fa-fw"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="#!">Configuraciones</a></li>
                <li><a class="dropdown-item" href="#!">Registro de actividad</a></li>
                <li><hr class="dropdown-divider" /></li>
                <li><a class="dropdown-item" method="POST" href="{{ route('logout') }}">Cerrar sesión</a></li>
            </ul>
        </li>
    </ul>
</nav>
