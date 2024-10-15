@extends('template')

@section('title', 'Lista de Pagos')

@push('css')
<!-- Puedes incluir aquí tus estilos CSS si es necesario -->
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Lista de Pagos</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Lista de Pagos</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Pagos
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nombre del Usuario</th>
                        <th>Apellido del Usuario</th>
                        <th>Carro de Compra ID</th>
                        <th>Total del Carro</th>
                        <th>Forma de Pago</th>
                        <th>Código</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pagos as $pago)
                    <tr>
                        <td>{{ $pago->carroCompra->user->nombre }}</td> <!-- Nombre del usuario -->
                        <td>{{ $pago->carroCompra->user->apellido }}</td> <!-- Apellido del usuario -->
                        <td>{{ $pago->carro_compra_id }}</td>
                        <td>{{ $pago->carroCompra->total }} Bs.</td> <!-- Total del carro -->
                        <td>{{ $pago->forma_pago }}</td>
                        <td>{{ $pago->codigo }}</td>
                        <td>
                            @if ($pago->estado == 'aceptado')
                            <span class="fw-bolder rounded p-1 bg-success text-white">Aceptado</span>
                            @else
                            <span class="fw-bolder rounded p-1 bg-warning text-white">Pendiente</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Acciones del Pago">
                                <!-- Botón para aprobar el pago con modal -->
                                <button type="button" class="btn btn-success {{ $pago->estado == 'aceptado' ? 'disabled' : '' }}" data-bs-toggle="modal" data-bs-target="#aprobarPagoModal{{ $pago->id }}">
                                    Aprobar
                                </button>

                                <!-- Botón para rechazar el pago, deshabilitado si está aceptado -->
                                <button type="button" class="btn btn-danger {{ $pago->estado == 'aceptado' ? 'disabled' : '' }}" data-bs-toggle="modal" data-bs-target="#rechazarPagoModal{{ $pago->id }}">
                                    Rechazar
                                </button>
                            </div>

                            <!-- Modal para confirmar aprobación -->
                            <div class="modal fade" id="aprobarPagoModal{{ $pago->id }}" tabindex="-1" aria-labelledby="aprobarPagoModalLabel{{ $pago->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="aprobarPagoModalLabel{{ $pago->id }}">Confirmar Aprobación</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            ¿Estás seguro de que deseas aprobar este pago?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <form action="{{ route('pagos.aprobar', $pago->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-success">Aprobar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal para confirmar rechazo -->
                            <div class="modal fade" id="rechazarPagoModal{{ $pago->id }}" tabindex="-1" aria-labelledby="rechazarPagoModalLabel{{ $pago->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="rechazarPagoModalLabel{{ $pago->id }}">Confirmar Rechazo</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            ¿Estás seguro de que deseas rechazar este pago?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <form action="{{ route('pagos.rechazar', $pago->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-danger" {{ $pago->estado == 'aceptado' ? 'disabled' : '' }}>Rechazar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('js')
<!-- Puedes incluir aquí tus scripts JS si es necesario -->
@endpush