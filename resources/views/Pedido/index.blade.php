@extends('template')

@section('tittle', 'Detalle del Pedido')

@push('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
@endpush

@section('content')
<!-- Mensajes de estado -->
@if(session('message'))
<div class="alert alert-success">
    {{ session('message') }}
</div>
@elseif(session('warning'))
<div class="alert alert-warning">
    {{ session('warning') }}
</div>
@elseif(session('danger'))
<div class="alert alert-danger">
    {{ session('danger') }}
</div>
@endif

<div class="container-fluid px-4">
    @foreach ($pedido as $pedido)
    <h1 class="mt-4 text-center">Pedido #{{ $pedido->id }}</h1>
    @endforeach

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Detalle del Pedido</li>
    </ol>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-receipt me-1"></i>
                    Detalles del Pedido
                </div>
                <div class="card-body">
                    @if($pedido->carro_compra)
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Producto ID</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pedido->carro_compra->detalle_compra as $detalle)
                            <tr>
                                <td>{{ $detalle->producto_id }}</td>
                                <td>{{ $detalle->cantidad }}</td>
                                <td>{{ $detalle->producto->precio_venta }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p>No hay detalles de compra para este pedido.</p>
                    @endif

                    <div class="text-end mt-3">
                        <h4>Total del Pedido: <span class="text-success">{{ $pedido->total }}</span></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-map-marker-alt me-1"></i>
                    Dirección de Entrega
                </div>
                <div class="card-body">
                    <form action="{{ route('direcciones.asociar', $pedido->id) }}" method="POST">
                        @csrf
                        <label for="direccion_id" class="form-label">Seleccionar Dirección de Entrega</label>
                        <select id="direccion_id" name="direccion_id" class="form-select" required>
                            <option value="">Seleccionar dirección</option>
                            @if ($direcciones->isEmpty())
                            <option value="">No hay direcciones disponibles</option>
                            @else
                            @foreach ($direcciones as $direccion)
                            <option value="{{ $direccion->id }}" @if ($pedido->direccion_id == $direccion->id) selected @endif>
                                {{ $direccion->direccion }} - {{ $direccion->ciudad }}
                            </option>
                            @endforeach
                            @endif
                        </select>

                        <button id="asociar-direccion-btn" class="btn btn-primary mt-3" type="submit" disabled>
                            Seleccionar Dirección
                        </button>
                    </form>

                    <button class="btn btn-secondary mt-3" data-bs-toggle="modal" data-bs-target="#agregarDireccionModal">
                        Agregar Nueva Dirección
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar dirección -->
    <div class="modal fade" id="agregarDireccionModal" tabindex="-1" aria-labelledby="agregarDireccionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarDireccionModalLabel">Agregar Dirección</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-guardar-direccion" method="POST" action="{{ route('direcciones.guardar') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" required>
                        </div>
                        <div class="mb-3">
                            <label for="ciudad" class="form-label">Ciudad</label>
                            <input type="text" class="form-control" id="ciudad" name="ciudad" required>
                        </div>
                        <div id="map" style="height: 400px;"></div>
                        <input type="hidden" id="latitud" name="latitud">
                        <input type="hidden" id="longitud" name="longitud">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar Dirección</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Inicializar el mapa
        let map, marker;

        document.getElementById('agregarDireccionModal').addEventListener('shown.bs.modal', function() {
            if (!map) {
                map = L.map('map').setView([-16.5000, -68.1500], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap'
                }).addTo(map);
                marker = L.marker([-16.5000, -68.1500], {
                    draggable: true
                }).addTo(map);

                // Obtener ubicación del usuario
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        map.setView([lat, lng], 15);
                        marker.setLatLng([lat, lng]);
                        document.getElementById('latitud').value = lat;
                        document.getElementById('longitud').value = lng;
                    });
                }

                marker.on('dragend', function() {
                    const latLng = marker.getLatLng();
                    document.getElementById('latitud').value = latLng.lat;
                    document.getElementById('longitud').value = latLng.lng;
                });

                map.on('click', function(e) {
                    const {
                        lat,
                        lng
                    } = e.latlng;
                    marker.setLatLng([lat, lng]);
                    document.getElementById('latitud').value = lat;
                    document.getElementById('longitud').value = lng;
                });
            }
        });

        document.getElementById('direccion_id').addEventListener('change', function() {
            document.getElementById('asociar-direccion-btn').disabled = this.value === "";
        });
    </script>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-user-tie me-1"></i>
            Repartidor
        </div>
        <div class="card-body">
            <div class="pedido mb-3 p-3 ">

                @if (is_null($pedido->direccione_id))
                <div class="alert alert-danger" role="alert">
                    La dirección es nula. Solo se asignará un repartidor después de asociar una dirección.
                </div>
                @else
                <div class="mb-2">
                    @if ($pedido->user)
                    <span class="text-success">{{ $pedido->user->nombre. ' '.$pedido->user->apellido }}</span>

                    @else
                    <div class="alert alert-warning" role="alert">
                        Pendiente de asignación
                    </div>
                    @endif
                </div>

                @if ($pedido->user_id && $pedido->fecha_entrega)
                <div class="mb-2">
                    <strong>Fecha de Entrega:</strong>
                    <span class="text-success">
                        {{ $pedido->fecha_entrega ? \Carbon\Carbon::parse($pedido->fecha_entrega)->format('d/m/Y H:i') : 'No asignada' }}
                    </span>
                </div>
                @endif
                @endif
            </div>
        </div>


    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-info-circle me-1"></i>
            Estado del Pedido
        </div>
        <div class="card-body">
            <h5>
                Estado del Pedido:
                <span class="badge bg-info">{{ ucfirst($pedido->estado_entrega) }}</span>
            </h5>
        </div>
    </div>
    <div class="text-end">
        @if ($pedido->estado_entrega == 'entregado' && \Carbon\Carbon::parse($pedido->fecha_entrega)->diffInDays(now()) <= 1)
            <button class="btn btn-success" id="abrirResenas" data-pedido-id="{{ $pedido->id }}">
            Pedido Recibido
            </button>
            @else
            <span class="text-muted">El pedido ya ha sido entregado o no está disponible para calificar.</span>
            @endif
    </div>

    <!-- Modal de Reseñas -->
    <div class="modal fade" id="modalResenas" tabindex="-1" aria-labelledby="modalResenasLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalResenasLabel">Dejar Reseñas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-resenas" method="POST" action="{{ route('reseñas.guardar') }}">
                        @csrf
                        @foreach ($productos as $producto)
                        <div class="mb-3">
                            <label for="calificacion-{{ $producto->producto_id }}" class="form-label">Calificación para {{ $producto->producto->nombre }}</label>
                            <input type="hidden" name="producto_id[]" value="{{ $producto->producto_id }}">
                            <input type="hidden" id="calificacion-{{ $producto->producto_id }}" name="calificacion[]" value="">
                            <div class="rating" id="rating-{{ $producto->producto_id }}">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="star" data-value="{{ $i }}">&#9733;</span>
                                    @endfor
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="comentario-{{ $producto->producto_id }}" class="form-label">Comentario para {{ $producto->producto->nombre }}</label>
                            <textarea id="comentario-{{ $producto->producto_id }}" name="comentario[]" class="form-control" rows="3"></textarea>
                        </div>
                        @endforeach
                        <button type="submit" class="btn btn-primary">Enviar Reseñas</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Evento al hacer clic en "Pedido Recibido"
            $('#abrirResenas').on('click', function() {
                if (confirm("¿Estás seguro de que has recibido el pedido?")) {
                    // Si el usuario confirma, muestra el modal de reseñas
                    $('#modalResenas').modal('show');
                }
            });

            // Establecer la calificación al hacer clic en las estrellas
            $('.star').on('click', function() {
                var calificacion = $(this).data('value');
                var productoId = $(this).closest('.rating').attr('id').split('-')[1]; // Obtener el ID del producto

                // Marcar todas las estrellas hasta la seleccionada
                $(this).siblings().removeClass('selected');
                $(this).prevAll().addBack().addClass('selected');

                // Establecer el valor de calificación en el input oculto
                $('#calificacion-' + productoId).val(calificacion);
            });

            // Enviar el formulario de reseñas por AJAX
            $('#form-resenas').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        alert(response.message);
                        $('#form-resenas')[0].reset();
                        $('#modalResenas').modal('hide');
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON.errors;
                        alert('Error: ' + Object.values(errors).flat().join(', '));
                    }
                });
            });
        });
    </script>

    <style>
        .rating {
            display: flex;
            justify-content: flex-start;
            gap: 5px;
        }

        .star {
            font-size: 30px;
            color: gray;
            cursor: pointer;
        }

        .star.selected {
            color: gold;
        }
    </style>

</div>

@push('js')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
@endpush
@endsection