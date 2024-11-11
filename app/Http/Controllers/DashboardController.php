<?php

namespace App\Http\Controllers;

use App\Models\Carro_compra;
use App\Models\Detalle_compra;
use App\Models\Producto;
use App\Models\Comunidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // Método para obtener la información del dashboard
    public function index()
    {
        // Obtener los productos más vendidos
        $productosVendidos = Detalle_compra::select('producto_id', DB::raw('SUM(cantidad) as total_vendido'))
            ->join('carro_compras', 'detalle_compras.carro_compra_id', '=', 'carro_compras.id')
            ->join('pedidos', 'carro_compras.id', '=', 'pedidos.carro_compra_id') // Relación correcta entre pedido y carro_compra
            ->whereNotNull('pedidos.id') // Asegura que haya un pedido asociado
            ->groupBy('producto_id')
            ->orderByDesc('total_vendido')
            ->limit(5) // Limita a los 5 productos más vendidos
            ->get();

        // Obtener los artesanos que más han vendido
        $artesanosMasVentas = Producto::select('productos.user_id', DB::raw('SUM(detalle_compras.cantidad) as total_vendido'))
            ->join('detalle_compras', 'productos.id', '=', 'detalle_compras.producto_id')
            ->join('carro_compras', 'detalle_compras.carro_compra_id', '=', 'carro_compras.id')
            ->join('pedidos', 'carro_compras.id', '=', 'pedidos.carro_compra_id') // Relación correcta entre pedido y carro_compra
            ->whereNotNull('pedidos.id') // Asegura que haya un pedido asociado
            ->groupBy('productos.user_id') // Asegura que estamos agrupando por el user_id de productos
            ->orderByDesc('total_vendido')
            ->limit(5) // Limita a los 5 artesanos que más venden
            ->get();

        // Obtener las comunidades con más usuarios
        $comunidadesMasUsuarios = Comunidade::select('comunidades.id', 'comunidades.nombre', DB::raw('COUNT(users.id) as total_usuarios'))
            ->join('users', 'comunidades.id', '=', 'users.comunidad_id')
            ->groupBy('comunidades.id', 'comunidades.nombre') // Incluir 'comunidades.nombre' en el GROUP BY
            ->orderByDesc('total_usuarios')
            ->limit(5) // Limita a las 5 comunidades con más usuarios
            ->get();

        // Retornar la vista con los datos obtenidos
        return view('panel/index', compact('productosVendidos', 'artesanosMasVentas', 'comunidadesMasUsuarios'));
    }
}
