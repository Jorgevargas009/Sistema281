<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

class ProductoController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver-productocomunidad')->only('ProductoComunidad');
        $this->middleware('permission:crear-producto')->only('create', 'store');
        $this->middleware('permission:editar-producto')->only('edit', 'update');
        $this->middleware('permission:eliminar-producto')->only('destroy');
        $this->middleware('permission:ver-miproducto')->only('index', 'ventas');
        $this->middleware('permission:ver-producto')->only('allProducts');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Producto::where('user_id', auth()->id())->get();
        return view('producto.index', compact('productos'));
    }

    public function allProducts()
    {
        // Obtener todos los productos
        $productos = Producto::all();

        return view('producto.all', compact('productos'));
    }

    public function ventas()
    {
        $productos = DB::table('detalle_compras')
            ->join('productos', 'detalle_compras.producto_id', '=', 'productos.id')
            ->where('productos.user_id', auth()->id())
            ->whereExists(function ($query) {
                $query->from('pedidos')
                    ->whereColumn('pedidos.carro_compra_id', 'detalle_compras.carro_compra_id');
            })
            ->select(
                'detalle_compras.producto_id',
                'productos.id as producto_id',
                'productos.nombre',
                'productos.descripcion',
                'productos.precio',
                'productos.precio_venta',
                DB::raw('SUM(detalle_compras.cantidad) as total_cantidad')
            )
            ->groupBy('detalle_compras.producto_id', 'productos.id', 'productos.nombre', 'productos.descripcion', 'productos.precio', 'productos.precio_venta')
            ->get();

        return view('producto.ventas', compact('productos'));
    }

    public function ProductoComunidad()
    {
        $userComunidad = auth()->user()->comunidad_id;

        $productos = Producto::whereHas('user', function ($query) use ($userComunidad) {
            $query->where('comunidad_id', $userComunidad);
        })->get();

        return view('producto.comunidad', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('producto.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductoRequest $request)
    {
        try {
            DB::beginTransaction();
            $producto = new Producto();
            if ($request->hasFile('imagen_path')) {
                $name = $producto->hanbleUploadImage($request->file('imagen_path'));
            } else {
                $name = null;
            }
            $producto->fill([
                'user_id' => auth()->id(),
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'imagen_path' => $name,
                'precio' => $request->precio,
                'precio_venta' => $request->precio_venta,
                'stock' => $request->stock,
            ]);

            $producto->save();
            DB::commit();

            // Enviar notificación de éxito
            Notification::success('Producto registrado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('productos.index')->with('success', 'Producto Registrado');
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
    public function edit(Producto $producto)
    {
        return view('producto.edit', compact('producto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductoRequest $request, Producto $producto)
    {
        try {
            DB::beginTransaction();

            if ($request->hasFile('imagen_path')) {
                $name = $producto->hanbleUploadImage($request->file('imagen_path'));
                if (Storage::disk('public')->exists('/producto' . $producto->imagen_path)) {
                    Storage::disk('public')->delete('/producto' . $producto->imagen_path);
                }
            } else {
                $name = $producto->imagen_path;
            }
            $producto->fill([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'imagen_path' => $name,
                'precio' => $request->precio,
                'precio_venta' => $request->precio_venta,
                'stock' => $request->stock,
            ]);

            $producto->save();
            DB::commit();

            // Enviar notificación de éxito
            Notification::success('Producto actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('productos.index')->with('success', 'Producto Actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        $producto->delete();

        // Enviar notificación de éxito
        Notification::success('Producto eliminado exitosamente.');

        return redirect()->route('productos.index')->with('success', 'Producto Eliminado');
    }
}
