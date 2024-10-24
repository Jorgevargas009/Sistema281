@extends('template')

@section('tittle', 'productos')

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
        title: "{{ session('success') }}"
    });
</script>
@endif

@if ($errors->has('error'))
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
        title: "{{ $errors->first('error') }}"
    });
</script>
@endif

@if (session('direccion_asociada'))
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
        icon: "info",
        title: "La dirección ha sido asociada. Buscando repartidor..."
    });
</script>
@endif
@if (session('Pedido'))
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
        icon: "info",
        title: "La dirección ha sido asociada. Buscando repartidor..."
    });
</script>
@endif

@if (session('direccion_nula'))
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
        icon: "warning",
        title: "Solo se asignará un repartidor después de seleccionar una dirección."
    });
</script>
@endif

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Productos</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Productos</li>
    </ol>

    <div class="mb-4">
        <a href="{{ route('productos.create') }}">
            <button type="button" class="btn-primary">Añadir nuevo registro</button>
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Productos
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Precio de venta</th>
                        <th>Stock</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productos as $item)
                    <tr>
                        <td>{{ $item->nombre }}</td>
                        <td>{{ $item->descripcion }}</td>
                        <td>{{ $item->precio }}</td>
                        <td>{{ $item->precio_venta }}</td>
                        <td>{{ $item->stock }}</td>
                        <td>
                            @if ($item->stock > 0)
                            <span class="fw-bolder rounder p-1 bg-success text-white">Disponible</span>
                            @else
                            <span class="fw-bolder rounder p-1 bg-danger text-white">Agotado</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                <form action="{{ route('productos.edit', ['producto' => $item]) }}" method="get">
                                    <button type="submit" class="btn btn-warning">Editar</button>
                                </form>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#verModal-{{ $item->id }}">Ver</button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#eliminarModal-{{ $item->id }}">Eliminar</button>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal Ver -->
                    <div class="modal fade" id="verModal-{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Detalles del producto</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mb-3">
                                        <label for=""><span class="fw-bolder">Descripción: </span> {{ $item->descripcion }}</label>
                                    </div>
                                    <div class="row mb-3">
                                        <label><span class="fw-bolder">Imagen: </span></label>
                                        <div>
                                            @if ($item->imagen_path != null)
                                            <img src="{{ Storage::url('public/productos/' . $item->imagen_path) }}" class="img-fluid img-thumbnail border border-4 rounded" alt="{{ $item->nombre }}">
                                            @else
                                            <img src="" alt="{{ $item->nombre }}">
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

                    <!-- Modal Eliminar -->
                    <div class="modal fade" id="eliminarModal-{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de confirmación</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    ¿Seguro quieres eliminar este producto?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    <form action="{{ route('productos.destroy', ['producto' => $item]) }}" method="post">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Confirmar</button>
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
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
@endpush
