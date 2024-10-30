<?php

namespace App\Http\Controllers;

use App\Models\Carro_compra;
use App\Models\Pago;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Notificacione; // Asegúrate de importar el modelo de Notificación
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver-pago')->only('index', 'show');
        $this->middleware('permission:aprobar-pago')->only('aprobarPago');
        $this->middleware('permission:rechazar-pago')->only('rechazarPago');
        $this->middleware('permission:crear-pago')->only('confirmarPago', 'store');
    }

    public function index()
    {
        $pagos = Pago::all();
        return view('Pago.index', compact('pagos'));
    }

    public function aprobarPago($id)
    {
        $pago = Pago::findOrFail($id);

        // Actualiza el estado del pago
        $pago->estado = 'aceptado';
        $pago->save();

        // Crear un nuevo pedido usando los datos del carro de compra
        $carroCompra = $pago->carroCompra;

        $pedido = Pedido::create([
            'carro_compra_id' => $carroCompra->id,
            'direccione_id' => $carroCompra->direccione_id,
            'fecha_pedido' => now(),
            'total' => $carroCompra->total,
            'estado_entrega' => 'pendiente',
        ]);

        // Actualizar el stock de cada producto en detalle_compras
        foreach ($carroCompra->detalle_compra as $detalle) {
            $producto = Producto::findOrFail($detalle->producto_id);
            $producto->stock -= $detalle->cantidad;
            $producto->save();
        }

        // Crear notificación al usuario sobre la aprobación del pago
        Notificacione::crearNotificacion($carroCompra->user_id, "Tu pago de {$pago->codigo} ha sido aprobado y se ha creado un nuevo pedido.");

        return redirect()->route('detalle_compras.index')->with('success', 'Pago aprobado y pedido creado.');
    }

    public function rechazarPago($id)
    {
        $pago = Pago::findOrFail($id);
        $pago->delete();

        // Crear notificación al usuario sobre el rechazo del pago
        Notificacione::crearNotificacion($pago->carroCompra->user_id, "Tu pago de {$pago->codigo} ha sido rechazado y eliminado.");

        return redirect()->route('pagos.index')->with('success', 'Pago rechazado y eliminado correctamente.');
    }

    public function confirmarPago(Request $request)
    {
        $request->validate([
            'carro_compra_id' => 'required|exists:carro_compras,id',
            'forma_pago' => 'required|in:tarjeta,paypal,transferencia,qr',
            'codigo' => 'required|numeric',
        ]);

        $carroCompra = Carro_compra::find($request->carro_compra_id);

        if (!$carroCompra) {
            return back()->withErrors(['error' => 'Carro de compra no encontrado']);
        }

        // Crear el pago
        $pago = Pago::create([
            'carro_compra_id' => $request->carro_compra_id,
            'forma_pago' => $request->forma_pago,
            'codigo' => $request->codigo,
            'estado' => 'pendiente',
        ]);

        // Crear notificación sobre el nuevo pago creado
        Notificacione::crearNotificacion($carroCompra->user_id, "Se ha registrado un nuevo pago de {$pago->codigo}, pendiente de confirmación.");

        return redirect()->route('pagos.show', $pago->id)->with('success', 'Pago registrado exitosamente, pendiente de confirmación.');
    }

    public function show($id)
    {
        $pago = Pago::findOrFail($id);
        return view('pagos.show', compact('pago'));
    }

    public function confirmar($id)
    {
        $pago = Pago::findOrFail($id);

        if ($pago->estado === 'pendiente') {
            $pago->estado = 'aceptado';
            $pago->save();

            // Crear notificación al usuario sobre la confirmación del pago
            Notificacione::crearNotificacion($pago->carroCompra->user_id, "Tu pago de {$pago->codigo} ha sido confirmado exitosamente.");

            return redirect()->route('pagos.show', $pago->id)->with('success', 'Pago confirmado exitosamente.');
        }

        return back()->withErrors(['error' => 'El pago ya fue confirmado o no está pendiente.']);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'forma_pago' => 'required|in:tarjeta,paypal,qr,transferencia',
            'numero_tarjeta' => 'required_if:forma_pago,tarjeta|nullable|digits:16',
            'codigo' => 'nullable',
        ]);

        $codigo = null;

        if ($request->forma_pago == 'tarjeta') {
            $codigo = $request->numero_tarjeta;
        }

        $existePago = Pago::where('carro_compra_id', $request->carro_compra_id)->exists();

        if ($existePago) {
            return redirect()->route('detalle_compras.index')->with('error', 'Ya existe un pago para este carro de compra.');
        }

        // Crear el pago
        $pago = Pago::create([
            'carro_compra_id' => $request->carro_compra_id,
            'forma_pago' => $request->forma_pago,
            'codigo' => $codigo,
            'estado' => 'pendiente',
        ]);

        // Crear notificación sobre el nuevo pago creado
        Notificacione::crearNotificacion($pago->carroCompra->user_id, "Se ha registrado un nuevo pago de {$pago->codigo}, pendiente de confirmación.");

        return redirect()->route('detalle_compras.index')->with('success', 'Procesando el pago.');
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
