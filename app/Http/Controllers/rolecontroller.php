<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class rolecontroller extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver-rol')->only('index', 'show');
        $this->middleware('permission:editar-rol')->only('edit', 'update');
        $this->middleware('permission:eliminar-rol')->only('destroy');
        $this->middleware('permission:crear-rol')->only('create', 'store');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return view('role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permisos = Permission::all();
        return view('role.create', compact('permisos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permission' => 'required'
        ]);
        try {
            DB::beginTransaction();
            //Crear rol
            $rol = Role::create(['name' => $request->name]);
            $permissions = array_map('intval', $request->permission);
            $existingPermissions = Permission::whereIn('id', $permissions)->pluck('id')->toArray();

            if (count($existingPermissions) !== count($permissions)) {
                // Manejo de error: algunos permisos no existen
                return redirect()->route('roles.index')->withErrors('Algunos permisos no existen.');
            }

            $rol->syncPermissions($existingPermissions);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('roles.index')->with('error', 'Rol no registrado');
        }


        return redirect()->route('roles.index')->with('success', 'Rol registrado');
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
    public function edit(Role $role)
    {
        $permisos = Permission::all();
        return view('role.edit', compact('role', 'permisos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permission' => 'required'
        ]);
    
        try {
            DB::beginTransaction();
    
            // Actualizar rol
            Role::where('id', $role->id)
                ->update([
                    'name' => $request->name
                ]);
    
            // Obtener y validar permisos
            $permissions = array_map('intval', $request->permission);
            $existingPermissions = Permission::whereIn('id', $permissions)->pluck('id')->toArray();
    
            if (count($existingPermissions) !== count($permissions)) {
                // Manejo de error: algunos permisos no existen
                return redirect()->route('roles.index')->withErrors('Algunos permisos no existen.');
            }
    
            // Actualizar permisos
            $role->syncPermissions($existingPermissions);
    
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('roles.index')->with('error', 'Rol no editado: ' . $e->getMessage());
        }
    
        return redirect()->route('roles.index')->with('success', 'Rol editado');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Role::where('id', $id)->delete();



        return redirect()->route('roles.index')->with('success', 'rol eliminado');
    }
}
