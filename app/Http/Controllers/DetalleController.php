<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Carro_compra;
use App\Models\Detalle_compra;
use App\Models\Notificacion; // Asegúrate de importar el modelo de Notificación
use App\Models\Notificacione;
use App\Models\Producto;
use App\Models\Promocione;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetalleController extends Controller
{
    public function __construct()
    {
        // Requiere permiso para ver el carrito
        $this->middleware('permission:ver-carro')->only('index');

        // Requiere permiso para añadir productos al carrito
        $this->middleware('permission:editar-detalle')->only('store');

        // Requiere permiso para actualizar las cantidades de los productos en el carrito
        $this->middleware('permission:editar-detalle')->only('update');

        // Requiere permiso para eliminar productos del carrito
        $this->middleware('permission:eliminar-detalle')->only('destroy');
    }

    // Aquí van las demás funciones del controlador...

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Supongamos que el carrito activo del usuario está relacionado por el campo 'user_id'
        $userId = auth()->id(); // Obtiene el ID del usuario autenticado

        $carro = Carro_compra::where('user_id', $userId)
            ->whereRaw('not exists (select * from pedidos where pedidos.carro_compra_id = carro_compras.id)')
            ->first();
        if ($carro) {
            return view('detalle_compra.index', compact('carro'));
        } else {
            return redirect()->back()->with('error', 'No tienes un carro de compras activo.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('detalle_compras.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Producto $producto)
    {
        DB::beginTransaction(); // Iniciar la transacción

        try {
            $user = auth()->user();

            // Verificar si el usuario ya tiene un carrito de compras activo (sin fecha de pedido)
            $carro = Carro_compra::where('user_id', $user->id)
                ->whereRaw('not exists (select * from pedidos where pedidos.carro_compra_id = carro_compras.id)')
                ->first();

            // Si no existe un carro de compras activo, se crea uno nuevo
            if (!$carro) {
                $carro = Carro_compra::create([
                    'user_id' => $user->id,
                    'total' => 0,
                    'fecha_creacion' => now(),
                ]);
            }

            // Verificar si el producto tiene alguna promoción activa
            $promocion = Promocione::where('producto_id', $producto->id)
                ->where('fecha_inicio', '<=', now())
                ->where('fecha_fin', '>=', now())
                ->first();

            // Calcular el precio final del producto (si hay descuento)
            $precioFinal = $promocion
                ? $producto->precio_venta - ($producto->precio_venta * $promocion->descuento / 100)
                : $producto->precio_venta;

            // Verificar si el producto ya está en el detalle del carrito
            $detalle = Detalle_compra::where('carro_compra_id', $carro->id)
                ->where('producto_id', $producto->id)
                ->first();

            // Sumar la cantidad solicitada al detalle existente o crear un nuevo registro si no existe
            if ($detalle) {
                // Verificar que la cantidad solicitada no exceda el stock disponible
                $nuevaCantidad = $detalle->cantidad + $request->cantidad;
                if ($nuevaCantidad > $producto->stock) {
                    return redirect()->back()->withErrors(['error' => 'La cantidad solicitada excede el stock disponible.']);
                }

                // Actualizar la cantidad del producto en el carrito
                $detalle->cantidad = $nuevaCantidad;
                $detalle->save();
            } else {
                // Verificar que la cantidad solicitada no exceda el stock disponible
                if ($request->cantidad > $producto->stock) {
                    return redirect()->back()->withErrors(['error' => 'La cantidad solicitada excede el stock disponible.']);
                }

                // Crear el detalle de compra
                Detalle_compra::create([
                    'carro_compra_id' => $carro->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $request->cantidad,
                ]);
            }

            // Actualizar el total del carro de compras
            $carro->total += $precioFinal * $request->cantidad;
            $carro->save();
            // Crear notificación para el usuario
            Notificacione::crearNotificacion($user->id, "Producto '{$producto->nombre}' añadido al carrito.");
            DB::commit(); // Confirmar la transacción

            return redirect()->back()->with('success', 'Producto añadido al carrito');
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir la transacción si ocurre un error
            return redirect()->back()->withErrors(['error' => 'Ocurrió un error al añadir el producto al carrito.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request, $detalle_compra)
    {
        $validated = $request->validate([
            'cantidad' => 'required|integer|min:1',
        ]);

        $detalle = Detalle_compra::findOrFail($detalle_compra);

        // Verifica si hay suficiente stock
        if ($request->cantidad > $detalle->producto->stock) {
            return redirect()->back()->withErrors(['error' => 'No hay suficiente stock disponible.']);
        }

        // Almacena la cantidad anterior
        $cantidadAnterior = $detalle->cantidad;

        // Actualiza la cantidad
        $detalle->cantidad = $request->cantidad;
        $detalle->save();

        // Recalcular el total del carrito
        $carro = $detalle->carro_compra;

        // Calcular el precio final considerando promociones
        $promocion = Promocione::where('producto_id', $detalle->producto->id)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->first();

        $precioFinal = $promocion
            ? $detalle->producto->precio_venta - ($detalle->producto->precio_venta * $promocion->descuento / 100)
            : $detalle->producto->precio_venta;

        // Actualiza el total del carro
        // Total anterior menos el precio del detalle anterior más el nuevo precio del detalle
        $carro->total += ($request->cantidad * $precioFinal) - ($cantidadAnterior * $precioFinal);
        $carro->save();

        // Crear notificación para el usuario
        Notificacione::crearNotificacion(auth()->id(), "La cantidad del producto '{$detalle->producto->nombre}' se ha actualizado.");

        return redirect()->route('detalle_compras.index')->with('success', 'Cantidad actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($detalle_compra)
    {
        // Encuentra el detalle de compra
        $detalle = Detalle_compra::findOrFail($detalle_compra);

        // Obtiene el carrito asociado
        $carro = $detalle->carro_compra;

        // Calcular el precio final considerando promociones
        $promocion = Promocione::where('producto_id', $detalle->producto->id)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->first();

        $precioFinal = $promocion
            ? $detalle->producto->precio_venta - ($detalle->producto->precio_venta * $promocion->descuento / 100)
            : $detalle->producto->precio_venta;

        // Actualiza el total del carro
        $carro->total -= ($detalle->cantidad * $precioFinal);
        $carro->save();

        // Elimina el detalle de compra
        $detalle->delete();

        // Crear notificación para el usuario
        Notificacione::crearNotificacion(auth()->id(), "El producto '{$detalle->producto->nombre}' ha sido eliminado del carrito.");

        return redirect()->route('detalle_compras.index')->with('success', 'Producto eliminado del carrito exitosamente.');
    }
}
