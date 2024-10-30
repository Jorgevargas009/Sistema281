<?php

namespace App\Http\Controllers;

use App\Models\Direccione;
use App\Models\Pedido;
use App\Models\Notificacione; // Asegúrate de importar el modelo de notificaciones
use Illuminate\Http\Request;

class DireccioneController extends Controller
{
    public function __construct()
    {
        // Permiso para ver direcciones
        $this->middleware('permission:ver-direccion')->only('index');

        // Permiso para crear direcciones
        $this->middleware('permission:crear-direccion')->only('create', 'guardar', 'store');

        // Permiso para asociar dirección a un pedido
        $this->middleware('permission:asociar-direccion')->only('asociarDireccion');

        // Permiso para buscar repartidor
        $this->middleware('permission:editar-direccion')->only('buscarRepartidor');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function guardar(Request $request)
    {
        // Validar los datos enviados
        $validated = $request->validate([
            'direccion' => 'required|string|max:255',
            'ciudad' => 'required|string|max:255',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
        ]);

        // Crear una nueva dirección asociada al usuario autenticado
        $direccion = Direccione::create([
            'user_id' => auth()->id(),  // Asocia la dirección al usuario autenticado
            'direccion' => $validated['direccion'],
            'ciudad' => $validated['ciudad'],
            'latitud' => $validated['latitud'],
            'longitud' => $validated['longitud'],
        ]);

        // Crear una notificación para el usuario
        Notificacione::crearNotificacion(auth()->id(), "Se ha guardado una nueva dirección: {$direccion->direccion}");

        // Redirigir o responder con éxito
        return redirect()->back()->with('success', 'Dirección guardada correctamente');
    }

    public function asociarDireccion(Request $request, $pedidoId)
    {
        $pedido = Pedido::findOrFail($pedidoId);
    
        // Verificar que no haya repartidor asignado
        if ($pedido->repartidor) {
            return redirect()->back()->with('error', 'No puedes cambiar la dirección porque ya hay un repartidor asignado.');
        }
    
        // Validar que la dirección pertenece al usuario actual
        $direccion = Direccione::where('id', $request->direccion_id)
            ->where('user_id', auth()->id())
            ->first();
    
        if (!$direccion) {
            return redirect()->back()->with('error', 'Dirección no válida.');
        }
    
        // Asociar la nueva dirección al pedido
        $pedido->direccione_id = $request->direccion_id;
        $pedido->save();
    
        // Crear una notificación para el usuario
        Notificacione::crearNotificacion(auth()->id(), "La dirección ha sido asociada al pedido ID: {$pedido->id}");
    
        // Mensaje de éxito
        session()->flash('direccion_asociada', 'La dirección ha sido asociada.');
        
        return redirect()->back()->with('success', 'Dirección asociada correctamente.');
    }

    public function buscarRepartidor(Request $request)
    {
        // Lógica para buscar repartidor
        // ...

        // Mensaje si se está buscando un repartidor
        session()->flash('message', 'Buscando un repartidor...');

        return redirect()->back(); // Regresa a la vista anterior
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
