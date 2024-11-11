<?php

namespace App\Http\Controllers;

use App\Models\Carro_compra;
use App\Models\Detalle_compra;
use App\Models\Producto;
use App\Models\Comunidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class homeController extends Controller
{
    public function index(){
        $productosVendidos = Detalle_compra::select('producto_id', DB::raw('SUM(cantidad) as total_vendido'))
            ->join('carro_compras', 'detalle_compras.carro_compra_id', '=', 'carro_compras.id')
            ->join('pedidos', 'carro_compras.id', '=', 'pedidos.carro_compra_id')
            ->whereNotNull('pedidos.id')
            ->groupBy('producto_id')
            ->orderByDesc('total_vendido')
            ->limit(5)
            ->get();
    
        $artesanosMasVentas = Producto::select('productos.user_id', DB::raw('SUM(detalle_compras.cantidad) as total_vendido'))
            ->join('detalle_compras', 'productos.id', '=', 'detalle_compras.producto_id')
            ->join('carro_compras', 'detalle_compras.carro_compra_id', '=', 'carro_compras.id')
            ->join('pedidos', 'carro_compras.id', '=', 'pedidos.carro_compra_id')
            ->whereNotNull('pedidos.id')
            ->groupBy('productos.user_id')
            ->orderByDesc('total_vendido')
            ->limit(5)
            ->get();
    
        $comunidadesMasUsuarios = Comunidade::select('comunidades.id', 'comunidades.nombre', DB::raw('COUNT(users.id) as total_usuarios'))
            ->join('users', 'comunidades.id', '=', 'users.comunidad_id')
            ->groupBy('comunidades.id', 'comunidades.nombre')
            ->orderByDesc('total_usuarios')
            ->limit(5)
            ->get();
    
        // Pasar los datos a la vista de forma adecuada
        return view('panel.index', compact('productosVendidos', 'artesanosMasVentas', 'comunidadesMasUsuarios'));
    }
    
}
