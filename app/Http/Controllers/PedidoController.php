<?php

namespace App\Http\Controllers;

use App\Models\Carro_compra;
use App\Models\Direccione;
use App\Models\Notificacione;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class PedidoController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver-mipedido')->only('index', 'show');
        $this->middleware('permission:ver-pedidocomunidad')->only('comunidad', 'aceptar', 'my', 'delivery');
        $this->middleware('permission:ver-pedido')->only('all');
        $this->middleware('permission:aceptar-pedido')->only('aceptar', 'delivery');
        $this->middleware('permission:editar-mipedido')->only('edit', 'update');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $carroCompra = Carro_compra::where('user_id', Auth::id())->latest()->first();

        if (!$carroCompra) {
            return redirect()->back()->with('error', 'No tienes un carro de compra activo.');
        }

        $pedido = Pedido::where('carro_compra_id', $carroCompra->id)->get();
        $direcciones = Direccione::where('user_id', auth()->id())->get();

        $productos = $carroCompra->detalle_compra;
        if ($pedido->isEmpty()) {
            return redirect()->back()->with('error', 'No tienes un pedido activo.');
        }

        return view('Pedido.index', compact('pedido', 'direcciones','productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function all()
    {
        $pedidos = Pedido::with(['carro_compra.detalle_compra.producto', 'user'])->get();

        return view('Pedido.all', compact('pedidos'));
    }

    public function aceptar(Request $request, $id)
    {
        $pedido = Pedido::findOrFail($id);

        $otroPedido = Pedido::where('user_id', Auth::id())
            ->where('estado_entrega', 'en camino')
            ->first();

        if ($otroPedido) {
            return redirect()->back()->withErrors(['error' => 'Ya tienes un pedido en camino.']);
        }

        $pedido->estado_entrega = 'en camino';
        $pedido->fecha_entrega = $request->input('fecha_entrega');
        $pedido->user_id = Auth::id();
        $pedido->save();

        // Enviar notificación de éxito
        Notificacione::crearNotificacion($pedido->carro_compra->user_id, "Pedido aceptado y en camino.");
        
        return redirect()->route('pedidos.all')->with('success', 'Pedido aceptado y en camino.');
    }

    public function show(string $id)
    {
        $pedido = Pedido::with('carro_compra.detalle_compra', 'direccion', 'repartidor')->find($id);
        $direcciones = Direccione::where('user_id', auth()->id())->get();

        return view('pedidos.show', compact('pedido', 'direcciones'));
    }

    public function comunidad()
    {
        $user = Auth::user();
        $comunidad_id = $user->comunidad_id;

        $pedidos = Pedido::whereHas('carro_compra.user', function ($query) use ($comunidad_id) {
            $query->where('comunidad_id', $comunidad_id);
        })
            ->with(['carro_compra.detalle_compra.producto', 'carro_compra.user'])
            ->get();

        return view('Pedido.comunidad', compact('pedidos'));
    }

    public function my()
    {
        $user = Auth::user();
        $pedidos = Pedido::where('user_id', $user->id)
            ->with(['carro_compra.detalle_compra.producto', 'user'])
            ->get();

        return view('Pedido.my', compact('pedidos'));
    }

    public function delivery()
    {
        $pedido = Pedido::with(['carro_compra.detalle_compra.producto', 'direccion'])
            ->where('user_id', auth()->id())
            ->where('estado_entrega', 'en camino')
            ->latest()
            ->first();

        if (!$pedido) {
            return redirect()->back()->with('error', 'No tienes un pedido asignado.');
        }

        $direccionLat = $pedido->direccion->latitud;
        $direccionLng = $pedido->direccion->longitud;
        return view('Pedido.delivery', [
            'pedido'=>$pedido,
            'carro' => $pedido->carro_compra,
            'direccion' => $pedido->direccion,
            'direccionLat' => $direccionLat,
            'direccionLng' => $direccionLng,
        ]);
    }
    public function confirmarRecepcion(Request $request, $id)
    {
        $pedido = Pedido::findOrFail($id);

        $pedido->estado_entrega = 'entregado';
        $pedido->fecha_entrega = now();
        $pedido->save();
        $productos = $pedido->carro_compra->detalle_compra;

        // Enviar notificación de éxito
        Notificacione::crearNotificacion($pedido->user_id, "Pedido recibido exitosamente.");

        return redirect()->route('pedidos.comunidad', compact('pedido', 'productos'))->with('success', 'Pedido recibido exitosamente.');
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
