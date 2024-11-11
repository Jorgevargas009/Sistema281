@extends('template')

@section('title', 'Panel')

@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
@endpush

@section('content')

@if (session('success'))
<script>
    let message = "{{ session('success') }}";
    Swal.fire({
        title: message,
        showClass: {
            popup: `animate__animated animate__fadeInUp animate__faster`
        },
        hideClass: {
            popup: `animate__animated animate__fadeOutDown animate__faster`
        }
    });
</script>
@endif

<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>

    <div class="row">
        <!-- Top 5 Productos Más Vendidos -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">Top 5 Productos Más Vendidos</div>
                <ul class="list-group list-group-flush">
                    @foreach($productosVendidos as $producto)
                        <li class="list-group-item text-black">
                            {{ $producto->producto->nombre }} - Vendido: {{ $producto->total_vendido }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Top 5 Artesanos con Más Ventas -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">Top 5 Artesanos con Más Ventas</div>
                <ul class="list-group list-group-flush">
                    @foreach($artesanosMasVentas as $artesano)
                        <li class="list-group-item text-black">
                            {{ $artesano->user->nombre }} - Vendido: {{ $artesano->total_vendido }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Top 5 Comunidades con Más Usuarios -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">Top 5 Comunidades con Más Usuarios</div>
                <ul class="list-group list-group-flush">
                    @foreach($comunidadesMasUsuarios as $comunidad)
                        <li class="list-group-item text-black">
                            {{ $comunidad->nombre }} - Usuarios: {{ $comunidad->total_usuarios }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-area me-1"></i> Productos Más Vendidos
                </div>
                <div class="card-body">
                    <canvas id="myAreaChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i> Artesanos con Más Ventas
                </div>
                <div class="card-body">
                    <canvas id="myBarChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>

<script>
    <?php
        $productosLabels = json_encode($productosVendidos->pluck('producto.nombre'));
        $productosData = json_encode($productosVendidos->pluck('total_vendido'));

        $artesanosLabels = json_encode($artesanosMasVentas->pluck('user.nombre'));
        $artesanosData = json_encode($artesanosMasVentas->pluck('total_vendido'));
    ?>
    
    var productosLabels = <?= $productosLabels ?>;
    var productosData = <?= $productosData ?>;

    var artesanosLabels = <?= $artesanosLabels ?>;
    var artesanosData = <?= $artesanosData ?>;
</script>

<script>
    // Gráfico de Área: Productos Más Vendidos
    var ctxArea = document.getElementById('myAreaChart').getContext('2d');
    var myAreaChart = new Chart(ctxArea, {
        type: 'line',
        data: {
            labels: productosLabels,  // Nombres de los productos
            datasets: [{
                label: 'Cantidad Vendida',
                data: productosData,  // Cantidad vendida
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        }
    });

    // Gráfico de Barras: Artesanos con Más Ventas
    var ctxBar = document.getElementById('myBarChart').getContext('2d');
    var myBarChart = new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: artesanosLabels,  // Nombres de los artesanos
            datasets: [{
                label: 'Ventas Totales',
                data: artesanosData,  // Ventas totales
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        }
    });
</script>



<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
@endpush
