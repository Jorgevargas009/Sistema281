@extends('template')

@section('tittle','Pedidos')

@section('content')
@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Operación exitosa',
        timer: 1500,
        toast: true,
        position: 'top-end',
        showConfirmButton: false
    });
</script>
@endif

@if ($errors->has('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: '{{ $errors->first("error") }}',
        timer: 1500,
        toast: true,
        position: 'top-end',
        showConfirmButton: false
    });
</script>
@endif

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Pedidos</h1>
    <div class="card mb-4">
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped">
                <thead>
                    <tr>
                        <th>Fecha Pedido</th>
                        <th>Estado Entrega</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedidos as $pedido)
                    <tr>
                        <!-- Formato de la fecha de pedido -->
                        <td>{{ \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y H:i') }}</td>


                        <!-- Estado de entrega con colores -->
                        <td>
                            <span class="fw-bold rounded p-1 text-white 
            @if ($pedido->estado_entrega == 'pendiente') bg-warning 
            @elseif ($pedido->estado_entrega == 'en camino') bg-primary 
            @elseif ($pedido->estado_entrega == 'entregado') bg-success 
            @else bg-secondary @endif">
                                {{ ucfirst($pedido->estado_entrega) . ' ' }}

                            </span>
                            @if($pedido->direccione_id == null)
                            <span> - > esperando direccion...</span>
                            @endif
                        </td>

                        <!-- Formato del total como moneda -->
                        <td>{{ number_format($pedido->total, 2) }} Bs</td>

                        <!-- Acciones -->
                        <td>
                            <!-- Botón Ver para mostrar el modal con los detalles -->
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#verModal-{{ $pedido->id }}">Ver</button>

                            <!-- Botón Aceptar solo si el estado no es "en camino" -->
                            <!-- Botón Aceptar que abre el modal -->
                            @if($pedido->estado_entrega == 'pendiente' && $pedido->direccione_id != null)
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#aceptarModal-{{ $pedido->id }}">
                                Aceptar
                            </button>
                            @endif
                        </td>
                    </tr>
                    <!-- Modal para aceptar pedido -->
                    <div class="modal fade" id="aceptarModal-{{ $pedido->id }}" tabindex="-1" aria-labelledby="aceptarModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="aceptarModalLabel">Aceptar Pedido</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('pedidos.aceptar', $pedido->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="fecha_entrega" class="form-label">Selecciona la Fecha y Hora de Entrega:</label>
                                            <input type="datetime-local" class="form-control" id="fecha_entrega" name="fecha_entrega" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-primary">Aceptar Pedido</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal con los detalles del pedido -->
                    <div class="modal fade" id="verModal-{{ $pedido->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Detalles del Pedido #{{ $pedido->id }}</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mb-3">
                                        <p><strong>Usuario:</strong> {{ $pedido->carro_compra->user->nombre . ' ' . $pedido->carro_compra->user->apellido }}</p>
                                    </div>
                                    <div class="row mb-3">
                                        <p><strong>Email del Usuario:</strong> {{ $pedido->carro_compra->user->email }}</p>
                                    </div>
                                    <div class="row mb-3">
                                        <p><strong>Comunidad:</strong> {{ $pedido->carro_compra->user->comunidad->nombre ?? 'No asignada' }}</p>
                                    </div>
                                    <div class="row mb-3">
                                        <p><strong>ID del Carro de Compra:</strong> {{ $pedido->carro_compra_id }}</p>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <p><strong>Productos en el Pedido:</strong></p>
                                            <ul class="list-group">
                                                @foreach($pedido->carro_compra->detalle_compra as $detalle)
                                                <li class="list-group-item d-flex justify-content-between align-items-left">
                                                    <span>{{ $detalle->producto->nombre }} - {{ $detalle->cantidad }} unidades</span>
                                                    <span>{{ $detalle->producto->precio_venta }} Bs c/u</span>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <p><strong>Total del Pedido:</strong> {{ $pedido->total }} Bs</p>
                                    </div>
                                    <div class="row mb-3">
                                        <p><strong>Estado de Entrega:</strong>
                                            <span class="fw-bold rounded p-1 text-white 
                                                        @if ($pedido->estado_entrega == 'pendiente') bg-warning 
                                                        @elseif ($pedido->estado_entrega == 'en camino') bg-primary 
                                                        @elseif ($pedido->estado_entrega == 'entregado') bg-success 
                                                        @else bg-secondary @endif">
                                                {{ ucfirst($pedido->estado_entrega) }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="row mb-3">
                                        <p><strong>Fecha de Pedido:</strong> {{ \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <div class="row mb-3">
                                        <p><strong>Fecha de Entrega:</strong>
                                            {{ $pedido->fecha_entrega ? \Carbon\Carbon::parse($pedido->fecha_entrega)->format('d/m/Y H:i') : 'No asignada' }}
                                        </p>
                                    </div>
                                    
                                    @if ($pedido->user != null)
                                    <div class="row mb-3">
                                        <p><strong>Repartidor:</strong> {{ $pedido->user->nombre. ' '. $pedido->user->apellido }}</p>
                                    </div>
                                    @endif
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
</div>
@endsection