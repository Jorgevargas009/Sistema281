@extends('template')

@section('title', 'Delivery')

@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<!-- Leaflet Routing Machine -->
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
@endpush

@section('content')
@if (session('success'))
<script>
    Swal.fire({
        icon: "success",
        title: "Operación exitosa",
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 2300,
        timerProgressBar: true,
    });
</script>
@endif

@if (session('error'))
<script>
    Swal.fire({
        icon: "error",
        title: "{{ session('error') }}",
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true,
    });
</script>
@endif

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Delivery - Pedido #{{ $carro->id }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Delivery</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-truck me-1"></i>
            Detalles de la Entrega
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped">
                <thead>
                    <tr>
                        <th>Nombre del Producto</th>
                        <th>Precio Unitario</th>
                        <th>Cantidad</th>
                        <th>Total Producto</th>
                        <th>Detalles</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($carro->detalle_compra as $detalle)
                    <tr>
                        <td>{{ $detalle->producto->nombre }}</td>
                        <td>{{ $detalle->producto->precio_venta }}</td>
                        <td>{{ $detalle->cantidad }}</td>
                        <td>{{ $detalle->cantidad * $detalle->producto->precio_venta }}</td>
                        <td>
                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#verModal-{{ $detalle->id }}">Ver</button>
                        </td>
                    </tr>

                    <!-- Modal para Ver Detalles del Producto -->
                    <div class="modal fade" id="verModal-{{ $detalle->id }}" tabindex="-1" aria-labelledby="verModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="verModalLabel">Detalles del Producto</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    @if ($detalle->producto->imagen_path)
                                    <img src="{{ Storage::url('public/productos/' . $detalle->producto->imagen_path) }}" alt="{{ $detalle->producto->nombre }}" class="img-fluid mb-3" style="max-height: 200px;">
                                    @else
                                    <p>Sin imagen disponible</p>
                                    @endif
                                    <p><strong>Producto:</strong> {{ $detalle->producto->nombre }}</p>
                                    <p><strong>Descripción:</strong> {{ $detalle->producto->descripcion }}</p>
                                    <p><strong>Precio Unitario:</strong> {{ $detalle->producto->precio_venta }}</p>
                                    <p><strong>Cantidad:</strong> {{ $detalle->cantidad }}</p>
                                    <p><strong>Total:</strong> {{ $detalle->cantidad * $detalle->producto->precio_venta }}</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="text-end">
        <h4>Total: {{ $carro->total }}</h4>
    </div>


    <div class="card mb-4">
        <div class="card-header">
            <h4>Información del Cliente</h4>
        </div>
        <div class="card-body">
            <p><strong>Nombre:</strong> {{ $carro->user->nombre }} {{ $carro->user->apellido }}</p>
            <p><strong>Teléfono:</strong> {{ $carro->user->telefono }}</p>
            <p><strong>Email:</strong> {{ $carro->user->email }}</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h4>Detalles de la Dirección</h4>
        </div>
        <div class="card-body">
            <p><strong>Detalle:</strong> {{ $direccion->direccion }}</p>
            <p><strong>Ciudad:</strong> {{ $direccion->ciudad }}</p>
        </div>
    </div>

    <!-- Mapa de Ubicación Actual -->
    <div id="map" style="height: 350px; margin-top: 20px;"></div>


    <div class="text-end">
        <button class="btn btn-success" id="abrirModal" data-pedido-id="{{ $pedido->id }}">
            Pedido Entregado
        </button>
    </div>

    <!-- Modal de Confirmación -->
    <div class="modal fade" id="confirmarModal" tabindex="-1" role="dialog" aria-labelledby="confirmarModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmarModalLabel">Confirmar Recepción</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas marcar este pedido como recibido? ¡No podrás revertir esta acción!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="confirmarBtn">Confirmar</button>
                </div>
            </div>
        </div>
    </div>
</div>


@push('js')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('#abrirModal').on('click', function() {
            var pedidoId = $(this).data('pedido-id'); // Extraer el ID del pedido
            $('#confirmarModal').modal('show'); // Mostrar el modal

            // Al hacer clic en el botón de confirmar
            $('#confirmarBtn').off('click').on('click', function() {
                window.location.href = '/pedido/confirmar/' + pedidoId;
            });
        });
    });
</script>
<script>
    // Inicializar el mapa
    let map, userMarker, destinationMarker, routingControl;

    // Crear el mapa con vista por defecto
    function initMap() {
        const direccionLat = "{{ $direccionLat }}";
        const direccionLng = "{{ $direccionLng }}";

        map = L.map('map').setView([direccionLat, direccionLng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Añadir un marcador en la ubicación del pedido
        destinationMarker = L.marker([direccionLat, direccionLng], {
                icon: L.icon({
                    iconUrl: "{{ asset('img/pedido.png') }}",
                    iconSize: [30, 30],
                    iconAnchor: [15, 30]
                })
            }).addTo(map)
            .bindPopup('Entrega en: {{ $direccion->direccion }}').openPopup();

        // Obtener ubicación del usuario
        if (navigator.geolocation) {
            navigator.geolocation.watchPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                // Guardar el nivel de zoom actual
                const currentZoom = map.getZoom();

                // Actualizar la vista del mapa manteniendo el zoom
                map.setView([lat, lng], currentZoom);

                // Si el marcador del usuario ya existe, simplemente lo movemos
                if (userMarker) {
                    userMarker.setLatLng([lat, lng]);
                } else {
                    // Si no existe, lo creamos
                    userMarker = L.marker([lat, lng], {
                            icon: L.icon({
                                iconUrl: "{{ asset('img/repartidor.png') }}",
                                iconSize: [30, 30],
                                iconAnchor: [15, 30]
                            })
                        }).addTo(map)
                        .bindPopup('Tu ubicación').openPopup();
                }

                // Traza la ruta desde la ubicación del usuario hasta el destino
                if (routingControl) {
                    routingControl.setWaypoints([
                        L.latLng(lat, lng),
                        L.latLng(direccionLat, direccionLng)
                    ]);
                } else {
                    routingControl = L.Routing.control({
                        waypoints: [
                            L.latLng(lat, lng),
                            L.latLng(direccionLat, direccionLng)
                        ],
                        routeWhileDragging: true,
                        createMarker: function() {
                            return null;
                        }
                    }).addTo(map);
                }
            }, function(error) {
                console.error(error);
            });
        } else {
            alert("Geolocalización no es soportada por este navegador.");
        }
    }

    // Llama a la función de inicialización del mapa
    document.addEventListener('DOMContentLoaded', initMap);
</script>

@endpush
@endsection