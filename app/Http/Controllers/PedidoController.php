<?php

namespace App\Http\Controllers;

use App\Models\Carro_compra;
use App\Models\Direccione;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtienes el carro de compra del usuario autenticado
        $carroCompra = Carro_compra::where('user_id', Auth::id())->latest()->first();

        // Verifica si el carro de compra existe
        if (!$carroCompra) {
            return redirect()->back()->with('error', 'No tienes un carro de compra activo.');
        }

        // Obtienes solo los pedidos asociados al carro de compra
        $pedido = Pedido::where('carro_compra_id', $carroCompra->id)->get();
        $direcciones = Direccione::where('user_id', auth()->id())->get(); // Direcciones del usuario autenticado

        // Verifica si hay pedidos activos
        if ($pedido->isEmpty()) {
            return redirect()->back()->with('error', 'No tienes un pedido activo.');
        }

        // Retornas la vista junto con los pedidos filtrados
        return view('Pedido.index', compact('pedido', 'direcciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function all()
    {
        // Obtiene todos los pedidos junto con las relaciones necesarias
        $pedidos = Pedido::with(['carro_compra.detalle_compra.producto', 'user'])->get();

        return view('Pedido.all', compact('pedidos'));
    }


    public function aceptar(Request $request, $id)
    {
        // Acepta un pedido cambiando el estado a 'en camino' y estableciendo la fecha de entrega
        $pedido = Pedido::findOrFail($id);

        // Verificar que el usuario no tenga otros pedidos 'en camino'
        $otroPedido = Pedido::where('user_id', Auth::id())
            ->where('estado_entrega', 'en camino')
            ->first();

        if ($otroPedido) {
            return redirect()->back()->withErrors(['error' => 'Ya tienes un pedido en camino.']);
        }

        // Actualiza el estado, la fecha de entrega y asigna el usuario actual
        $pedido->estado_entrega = 'en camino';
        $pedido->fecha_entrega = $request->input('fecha_entrega'); // Asegúrate de que este campo existe en tu modelo
        $pedido->user_id = Auth::id(); // Asignar el ID del usuario actual al pedido
        $pedido->save();

        return redirect()->route('pedidos.all')->with('success', 'Pedido aceptado y en camino.');
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $pedido = Pedido::with('carro_compra.detalle_compra', 'direccion', 'repartidor')->find($id);
        $direcciones = Direccione::where('user_id', auth()->id())->get(); // Direcciones del usuario autenticado
        return view('pedidos.show', compact('pedido', 'direcciones'));
    }
    
    public function comunidad()
    {
        // Obtén los pedidos de la comunidad del usuario
        $user = Auth::user();
        $pedidos = Pedido::whereHas('user', function ($query) use ($user) {
            $query->where('comunidad_id', $user->comunidad_id);
        })->get();

        // Asegúrate de devolver la vista correcta
        return view('Pedido.comunidad', compact('pedidos'));
    }


    // En tu controlador
    public function confirmarRecepcion(Request $request, $id)
    {
        $pedido = Pedido::findOrFail($id); // Asegúrate de obtener el pedido existente

        // Cambia el estado de entrega
        $pedido->estado_entrega = 'entregado';

        // Establece la fecha de entrega
        $pedido->fecha_entrega = now();

        // Guarda los cambios
        $pedido->save();
        return redirect()->route('productos.comunidad')->with('success', 'Pedido');
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
