@extends('template')

@section('tittle', 'Carro de Compras')

@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@section('content')
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
        title: "Operación exitosa"
    });
</script>
@endif

@if (session('error'))
<script>
    Toast = Swal.mixin({
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
        icon: "error",
        title: "{{ session('error') }}"
    });
</script>
@endif

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Carro de Compras #{{ $carro->id }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Carro de Compras</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-shopping-cart me-1"></i>
            Detalles del Carro de Compras
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped">
                <thead>
                    <tr>
                        <th>Nombre del Producto</th>
                        <th>Precio Unitario</th>
                        <th>Cantidad</th>
                        <th>Total Producto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($carro->Detalle_compra as $detalle)
                    <tr>
                        <td>{{ $detalle->producto->nombre }}</td>
                        <td>{{ $detalle->producto->precio_venta }}</td>
                        <td>{{ $detalle->cantidad }}</td>
                        <td>{{ $detalle->cantidad * $detalle->producto->precio_venta }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <!-- Botón Ver -->
                                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#verModal-{{ $detalle->id }}">Ver</button>

                                <!-- Botón Editar -->
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editarModal-{{ $detalle->id }}">Editar</button>

                                <!-- Botón Eliminar -->
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#eliminarModal-{{ $detalle->id }}">
                                    Eliminar
                                </button>
                            </div>
                        </td>
                    </tr>
                    <!-- Modal Ver -->
                    <div class="modal fade" id="verModal-{{ $detalle->id }}" tabindex="-1" aria-labelledby="verModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="verModalLabel">Detalles del Producto</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mb-3">
                                        <label><strong>Descripción:</strong> {{ $detalle->producto->descripcion }}</label>
                                    </div>
                                    <div class="row mb-3">
                                        <label><strong>Stock disponible:</strong> {{ $detalle->producto->stock }}</label>
                                    </div>
                                    <div class="row mb-3">
                                        <label><strong>Precio de Venta:</strong> {{ $detalle->producto->precio_venta }}</label>
                                    </div>
                                    <div class="row mb-3">
                                        <label><strong>Imagen:</strong></label>
                                        <div>
                                            @if ($detalle->producto->imagen_path != null)
                                            <img src="{{ Storage::url('public/productos/' . $detalle->producto->imagen_path) }}" class="img-fluid img-thumbnail border border-4 rounded" alt="{{ $detalle->producto->nombre }}">
                                            @else
                                            <img src="" alt="{{ $detalle->producto->nombre }}">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal Editar -->
                    <div class="modal fade" id="editarModal-{{ $detalle->id }}" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editarModalLabel">Editar Cantidad</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('detalle_compras.update', ['producto' => $detalle->producto->id, 'detalle_compra' => $detalle->id]) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <label for="cantidad">Cantidad:</label>
                                        <input type="number" name="cantidad" value="{{ $detalle->cantidad }}" min="1" max="{{ $detalle->producto->stock }}" class="form-control">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Modal de Confirmación de Eliminación -->
                    <div class="modal fade" id="eliminarModal-{{ $detalle->id }}" tabindex="-1" aria-labelledby="eliminarModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="eliminarModalLabel">Confirmación de Eliminación</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    ¿Seguro que deseas eliminar este producto del carrito?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    <form action="{{ route('detalle_compras.destroy', ['detalle_compra' => $detalle->id]) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Total del Carro -->
    <div class="text-end">
        <h4>Total del Carro: {{ $carro->total }}</h4>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script src=" {{ asset('js/datatables-simple-demo.js') }}"></script>
@endpush