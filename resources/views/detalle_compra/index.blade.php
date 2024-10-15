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
        timer: 2300,
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

    <!-- Botón para proceder al pago -->
    <div class="text-end mt-4">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#pagoModal">Proceder al Pago</button>
    </div>
    <!-- Modal para método de pago -->
    <div class="modal fade" id="pagoModal" tabindex="-1" aria-labelledby="pagoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('pagos.store') }}" method="POST" id="formPago">
                @csrf

                <input type="hidden" name="carro_compra_id" value="{{ $carro->id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="pagoModalLabel">Seleccionar Método de Pago</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Total a Pagar:</strong> {{ $carro->total }} Bs.</p>

                        <!-- Métodos de Pago -->
                        <div class="btn-group d-flex justify-content-between mb-3" role="group" aria-label="Métodos de Pago">
                            <input type="radio" class="btn-check" name="forma_pago" id="tarjeta" value="tarjeta" autocomplete="off" checked>
                            <label class="btn btn-outline-primary" for="tarjeta"><i class="fas fa-credit-card"></i> Tarjeta</label>

                            <input type="radio" class="btn-check" name="forma_pago" id="paypal" value="paypal" autocomplete="off">
                            <label class="btn btn-outline-info" for="paypal"><i class="fab fa-paypal"></i> PayPal</label>

                            <input type="radio" class="btn-check" name="forma_pago" id="qr" value="qr" autocomplete="off">
                            <label class="btn btn-outline-success" for="qr"><i class="fas fa-qrcode"></i> QR</label>

                            <input type="radio" class="btn-check" name="forma_pago" id="transferencia" value="transferencia" autocomplete="off">
                            <label class="btn btn-outline-secondary" for="transferencia"><i class="fas fa-university"></i> Transferencia</label>
                        </div>

                        <!-- Campos adicionales para Tarjeta -->
                        <div id="pago_tarjeta" class="pago-opcion">
                            <div class="mb-3">
                                <label for="numero_tarjeta" class="form-label">Número de Tarjeta</label>
                                <input type="text" class="form-control" id="numero_tarjeta" name="numero_tarjeta" maxlength="16" placeholder="1234 5678 9012 3456">
                            </div>
                            <div class="mb-3">
                                <label for="fecha_expiracion" class="form-label">Fecha de Expiración</label>
                                <input type="text" class="form-control" id="fecha_expiracion" name="fecha_expiracion" placeholder="MM/AA">
                            </div>
                            <div class="mb-3">
                                <label for="codigo_seguridad" class="form-label">Código de Seguridad (CVV)</label>
                                <input type="text" class="form-control" id="codigo_seguridad" name="codigo_seguridad" maxlength="3" placeholder="123">
                            </div>
                        </div>

                        <!-- Pago por QR -->
                        <div id="pago_qr" class="pago-opcion d-none">
                            <p>Escanea el siguiente código QR para realizar el pago:</p>
                            <img src="{{ asset('img/qr.jpg') }}" alt="Código QR de Pago" class="img-fluid">
                        </div>

                        <!-- PayPal -->
                        <div id="pago_paypal" class="pago-opcion d-none">
                            <p>Será redirigido a la página de PayPal para completar su pago.</p>
                        </div>

                        <!-- Transferencia Bancaria -->
                        <div id="pago_transferencia" class="pago-opcion d-none">
                            <p>Para pagar por transferencia bancaria, envíe el monto a la siguiente cuenta:</p>
                            <ul>
                                <li>Banco: UNION S.A.</li>
                                <li>Cuenta N°: 1000003456789</li>
                                <li>Titular: DANNY EMANUEL APAZA SIÑANI</li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="confirmarPago">Confirmar Pago</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
</div>

<script>
    // Función para manejar el cambio entre métodos de pago
    function cambiarMetodoPago() {
        const metodoPago = document.querySelector('input[name="metodo_pago"]:checked').value;
        const opcionesPago = {
            'tarjeta': document.getElementById('pago_tarjeta'),
            'qr': document.getElementById('pago_qr'),
            'paypal': document.getElementById('pago_paypal'),
            'transferencia': document.getElementById('pago_transferencia'),
        };

        // Ocultar todas las opciones
        Object.keys(opcionesPago).forEach(key => {
            opcionesPago[key].classList.add('d-none');
        });

        // Mostrar la opción seleccionada
        opcionesPago[metodoPago].classList.remove('d-none');
    }

    // Ejecutar al cargar el modal (para mostrar "Tarjeta" por defecto)
    document.addEventListener('DOMContentLoaded', function() {
        const metodoPagoInputs = document.querySelectorAll('input[name="forma_pago"]');
        const pagoTarjeta = document.getElementById('pago_tarjeta');
        const pagoQR = document.getElementById('pago_qr');
        const pagoPaypal = document.getElementById('pago_paypal');
        const pagoTransferencia = document.getElementById('pago_transferencia');

        metodoPagoInputs.forEach(input => {
            input.addEventListener('change', function() {
                pagoTarjeta.classList.add('d-none');
                pagoQR.classList.add('d-none');
                pagoPaypal.classList.add('d-none');
                pagoTransferencia.classList.add('d-none');

                if (this.value === 'tarjeta') {
                    pagoTarjeta.classList.remove('d-none');
                } else if (this.value === 'qr') {
                    pagoQR.classList.remove('d-none');
                } else if (this.value === 'paypal') {
                    pagoPaypal.classList.remove('d-none');
                } else if (this.value === 'transferencia') {
                    pagoTransferencia.classList.remove('d-none');
                }
            });
        });
    });

    // Cambiar automáticamente cuando se selecciona un método de pago diferente
    document.querySelectorAll('input[name="metodo_pago"]').forEach(radio => {
        radio.addEventListener('change', cambiarMetodoPago);
    });

    // Acción de confirmar pago
    document.getElementById('confirmarPago').addEventListener('click', function() {
        // Aquí iría la lógica para procesar el pago
        Swal.fire({
            icon: 'success',
            title: 'Pago realizado con éxito',
            text: 'Tu pedido ha sido registrado correctamente.',
            showConfirmButton: false,
            timer: 2000
        });
    });
</script>

</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script src=" {{ asset('js/datatables-simple-demo.js') }}"></script>
@endpush