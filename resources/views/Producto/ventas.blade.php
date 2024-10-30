@extends('template')

@section('title', 'Ventas')

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

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Ventas</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Ventas</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Ventas
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped">
                <thead>
                    <tr>
                        <th>ID Producto</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Precio de Venta</th>
                        <th>Cantidad Vendida</th>
                        <th>Total por Producto</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $total = 0;
                    $inversion = 0;
                    @endphp
                    @foreach ($productos as $item)
                    <tr>
                        <td>{{ $item->producto_id }}</td>
                        <td>{{ $item->nombre }}</td>
                        <td>{{ $item->descripcion }}</td>
                        <td>{{ number_format($item->precio, 2) }} $</td>
                        <td>{{ number_format($item->precio_venta, 2) }} $</td>
                        <td>{{ $item->total_cantidad }}</td>
                        <td>{{ number_format($item->total_cantidad * $item->precio_venta, 2) }} $</td>
                    </tr>
                    @php
                    $total += $item->total_cantidad * $item->precio_venta;
                    $inversion += $item->total_cantidad * $item->precio;
                    @endphp
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                <h3>Total: <span class="text-success">{{ number_format($total, 2) }} $</span></h3>
                <h3>Inversión: <span class="text-danger">{{ number_format($inversion, 2) }} $</span></h3>
                <h3>Ganancia: <span class="text-primary">{{ number_format($total - $inversion, 2) }} $</span></h3>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
@endpush