<?php

namespace App\Http\Controllers;

use App\Models\Carro_compra;
use App\Models\Pago;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todos los pagos de la base de datos
        $pagos = Pago::all(); // Asegúrate de tener el modelo Pago importado

        return view('Pago.index', compact('pagos'));
    }
    public function aprobarPago($id)
    {
        $pago = Pago::findOrFail($id);

        // Actualiza el estado del pago
        $pago->estado = 'aceptado';
        $pago->save();

        // Crear un nuevo pedido usando los datos del carro de compra
        $carroCompra = $pago->carroCompra; // Obtén el carro de compra asociado

        $pedido = Pedido::create([
            'carro_compra_id' => $carroCompra->id,
            'user_id' => $carroCompra->user_id,
            'direccione_id' => $carroCompra->direccione_id,
            'fecha_pedido' => now(), // Fecha actual
            'total' => $carroCompra->total,
            'estado_entrega' => 'pendiente', // Estado inicial
        ]);

        // Actualizar el stock de cada producto en detalle_compras
        foreach ($carroCompra->detalle_compra as $detalle) {
            $producto = Producto::findOrFail($detalle->producto_id);

            // Resta la cantidad del stock
            $producto->stock -= $detalle->cantidad;

            // Guarda el producto actualizado
            $producto->save();
        }

        return redirect()->route('detalle_compras.index')->with('success', 'Pago aprobado y pedido creado.');
    }

    public function rechazarPago($id)
    {
        $pago = Pago::findOrFail($id);
        $pago->delete();

        return redirect()->route('pagos.index')->with('success', 'Pago rechazado y eliminado correctamente.');
    }


    public function confirmarPago(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'carro_compra_id' => 'required|exists:carro_compras,id',
            'forma_pago' => 'required|in:tarjeta,paypal,transferencia,qr',
            'codigo' => 'required|numeric',
        ]);

        // Obtener el carro de compra
        $carroCompra = Carro_Compra::find($request->carro_compra_id);

        if (!$carroCompra) {
            return back()->withErrors(['error' => 'Carro de compra no encontrado']);
        }

        // Crear el pago
        $pago = Pago::create([
            'carro_compra_id' => $request->carro_compra_id,
            'forma_pago' => $request->forma_pago,
            'codigo' => $request->codigo,
            'estado' => 'pendiente', // Inicialmente el estado es 'pendiente'
        ]);

        return redirect()->route('pagos.show', $pago->id)->with('success', 'Pago registrado exitosamente, pendiente de confirmación');
    }

    public function show($id)
    {
        $pago = Pago::findOrFail($id);
        return view('pagos.show', compact('pago'));
    }

    public function confirmar($id)
    {
        // Obtener el pago
        $pago = Pago::findOrFail($id);

        // Verificar que el pago esté en estado 'pendiente'
        if ($pago->estado === 'pendiente') {
            $pago->estado = 'aceptado';
            $pago->save();

            // Aquí puedes implementar la lógica adicional para procesar el pedido
            // Una vez que el pago esté confirmado, el carro de compras se convierte en un pedido

            return redirect()->route('pagos.show', $pago->id)->with('success', 'Pago confirmado exitosamente');
        }

        return back()->withErrors(['error' => 'El pago ya fue confirmado o no está pendiente']);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'forma_pago' => 'required|in:tarjeta,paypal,qr,transferencia',
            'numero_tarjeta' => 'required_if:forma_pago,tarjeta|nullable|digits:16',
            'codigo' => 'nullable',
        ]);

        $codigo = null;

        // Solo para tarjeta se guarda el código de la tarjeta
        if ($request->forma_pago == 'tarjeta') {
            $codigo = $request->numero_tarjeta; // O guarda de forma más segura si se requiere
        }

        // Verificar si ya existe un pago para el carro de compra actual
        $existePago = Pago::where('carro_compra_id', $request->carro_compra_id)->exists();

        if ($existePago) {
            return redirect()->route('detalle_compras.index')->with('error', 'Ya existe un pago para este carro de compra.');
        }

        // Crear el pago
        Pago::create([
            'carro_compra_id' => $request->carro_compra_id,
            'forma_pago' => $request->forma_pago,
            'codigo' => $codigo,
            'estado' => 'pendiente', // Por defecto pendiente
        ]);

        return redirect()->route('detalle_compras.index')->with('success', 'Procesando el pago.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
