<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /*$productos=Producto::with(['id artesano'])->latest()->get();*/
        $productos = Producto::where('user_id', auth()->id())->get();
        return view('producto.index', compact('productos'));
    }
    public function allProducts()
    {
        // Obtener todos los productos
        $productos = Producto::all();

        return view('producto.all', compact('productos'));
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
        $message = '';
        $producto->delete();

        return redirect()->route('productos.index')->with('success', 'Producto Eliminado');
    }
}
