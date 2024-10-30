<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Inicio</div>
                <a class="nav-link" href="{{ route('panel') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Panel
                </a>
                <div class="sb-sidenav-menu-heading">Módulos</div>

                @can('ver-miproducto')
                <a class="nav-link" href="{{ route('productos.index') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-shirt"></i></div>
                    Mis Productos
                </a>
                <a class="nav-link" href="{{ route('productos.ventas') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-shirt"></i></div>
                    Mis Ventas
                </a>
                @endcan

                @can('ver-producto')
                <a class="nav-link" href="{{ route('productos.all') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-shirt"></i></div>
                    Productos
                </a>
                @endcan

                @can('ver-productocomunidad')
                <a class="nav-link" href="{{ route('productos.comunidad') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-shirt"></i></div>
                    Productos de mi comunidad
                </a>
                @endcan

                @can('ver-carro')
                <a class="nav-link" href="{{ route('detalle_compras.index') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-cart-shopping"></i></div>
                    Mi Carrito
                </a>
                @endcan

                @can('ver-pago')
                <a class="nav-link" href="{{ route('Pago.index') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-credit-card"></i></div>
                    Pagos
                </a>
                @endcan

                @can('ver-mipedido')
                <a class="nav-link" href="{{ route('pedidos.index') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-truck"></i></div>
                    Mi Pedido
                </a>
                @endcan

                @can('ver-pedido')
                <a class="nav-link" href="{{ route('pedidos.all') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-box"></i></div>
                    Pedidos
                </a>
                @endcan

                @can('ver-pedidocomunidad')
                <a class="nav-link" href="{{ route('pedidos.comunidad') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-box"></i></div>
                    Pedidos de mi comunidad
                </a>
                <a class="nav-link" href="{{ route('pedidos.my') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-box"></i></div>
                    Ver mis entregas
                </a>
                <a class="nav-link" href="{{ route('pedidos.delivery') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-box"></i></div>
                    Delivery
                </a>
                @endcan

                <div class="sb-sidenav-menu-heading">Otros</div>

                @can('ver-user')
                <a class="nav-link" href="{{ route('users.index') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-user"></i></div>
                    Usuarios
                </a>
                @endcan

                @can('ver-rol')
                <a class="nav-link" href="{{ route('roles.index') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-person-circle-plus"></i></div>
                    Roles
                </a>
                @endcan
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Bienvenido:</div>
            {{ auth()->user()->nombre . ' ' . auth()->user()->apellido }}
            <div class="small">Comunidad:</div>
            {{ auth()->user()->comunidad->nombre }}
        </div>
    </nav>
</div>
