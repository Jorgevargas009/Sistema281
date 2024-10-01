@extends('template')

@section('tittle','productos')

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
        title: "Producto agregado exitosamente"
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
        title: "{{ $errors->first('error') }}" // Muestra el primer error en la categoría 'error'
    });
</script>
@endif

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Productos</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{route('panel')}}">Inicio</a></li>
        <li class="breadcrumb-item active">Productos</li>
    </ol>

    <div class="mb-4"><a href="{{route('productos.create')}}"><button type="button" class="btn-primary">Añadir nuevo registro</button></a>
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
                        <th>Descripcion</th>
                        <th>Precio de venta</th>
                        <th>Stock disponible</th>
                        <th>Estado</th>
                        <th>Cantidad a pedir</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ( $productos as $item )
                    <tr>
                        <td>{{$item->nombre}}</td>
                        <td>{{$item->descripcion}}</td>
                        <td>{{$item->precio_venta}}</td>
                        <td>{{$item->stock}}</td>
                        <td>
                            @if ($item->stock>0)
                            <span class="fw-bolder rounder p-1 bg-success text-white">Disponible</span>
                            @else
                            <span class="fw-bolder rounder p-1 bg-danger text-white">Agotado</span>
                            @endif
                        </td>

                        <td>
                            <form action="{{ route('detalle_compras.store', ['producto' => $item]) }}" method="post">
                                @csrf
                                <input type="number" name="cantidad" class="form-control" id="cantidad-{{$item->id}}" value="1" min="1" max="{{ $item->stock }}" required {{ $item->stock == 0 ? 'disabled' : '' }}>
                                <small class="text-muted">Stock disponible: {{ $item->stock }}</small>
                        </td>

                        <td>
                            <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#verModal-{{$item->id}}">Ver</button>

                                <!-- Botón para abrir el modal de confirmación -->
                                <button type="button" class="btn btn-success" {{ $item->stock == 0 ? 'disabled' : '' }}
                                    data-bs-toggle="modal"
                                    data-bs-target="#confirmarModal-{{$item->id}}"
                                    onclick="setModalData({{ $item->id }}, {{ $item->precio_venta }}, {{ $item->stock }})">
                                    Añadir al carro
                                </button>
                                <form action="{{ route('detalle_compras.store', ['producto' => $item]) }}" method="POST">
                                    </form>
                            </div>

                        </td>
                    </tr>

                    <!-- Modal -->
                    <div class="modal fade" id="verModal-{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Detalles del producto</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mb-3">
                                        <label for=""><span class="fw-bolder">Descripción: </span> {{$item->descripcion}}</label>
                                    </div>
                                    <div class="row mb-3">
                                        <label><span class="fw-bolder">Imagen: </span></label>
                                        <div>
                                            @if ($item->imagen_path != null)
                                            <img src="{{Storage::url('public/productos/'.$item->imagen_path)}}" class="img-fluid img-thumbnail border border-4 rounded" alt="{{$item->nombre}}">

                                            @else
                                            <img src="" alt="{{$item->nombre}}">
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
                    <!-- Modal --><!-- Modal -->
                    <div class="modal fade" id="confirmarModal-{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmar Pedido</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Producto:</strong> {{$item->nombre}}</p>
                                    <p><strong>Descripción:</strong> {{$item->descripcion}}</p>
                                    <p><strong>Precio de venta:</strong> {{$item->precio_venta}}</p>
                                    <p><strong>Cantidad solicitada:</strong> <span id="cantidad-modal-{{$item->id}}">0</span></p>
                                    <p><strong>Stock disponible:</strong> {{$item->stock}}</p>
                                    <p><strong>Total:</strong> <span id="total-modal-{{$item->id}}">0</span></p>
                                    <p>¿Estás seguro que deseas añadir este producto al carrito?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    <form action="{{ route('detalle_compras.store', ['producto' => $item]) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="cantidad" id="cantidad-input-{{$item->id}}">
                                        <input type="hidden" name="producto" value="{{ $item->id }}">

                                        <button type="submit" class="btn btn-success">Confirmar</button>
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
<script src=" {{asset('js/datatables-simple-demo.js')}}"></script>

<script>
    function setModalData(productId, precioVenta, stock) {
        // Obtener la cantidad del campo de entrada utilizando el ID correcto
        const cantidadInput = document.querySelector(`#cantidad-${productId}`);
        const cantidad = cantidadInput ? cantidadInput.value : 0; // Asegurarse de que no sea null

        // Calcular el total
        const total = cantidad * precioVenta;

        // Actualizar los valores en el modal
        document.querySelector(`#cantidad-modal-${productId}`).innerText = cantidad;
        document.querySelector(`#total-modal-${productId}`).innerText = total;

        // Pasar la cantidad al campo oculto del formulario en el modal
        document.querySelector(`#cantidad-input-${productId}`).value = cantidad;
    }
</script>
@endpush