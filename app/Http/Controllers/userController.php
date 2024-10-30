<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Comunidade;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class userController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver-user', ['only' => ['index']]);
        $this->middleware('permission:crear-user', ['only' => ['create', 'store', 'register', 'storelogin']]);
        $this->middleware('permission:editar-user', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-user', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view("user.index", compact("users"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        $comunidades = Comunidade::all();
        return view('user.create', compact('roles', 'comunidades'));
    }

    public function register()
    {
        $roles = Role::all();
        return view('user.register', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            DB::beginTransaction();

            // Crear usuario
            $fieldhash = Hash::make($request->password);
            $request->merge(['password' => $fieldhash]);
            // Priorizar la comunidad seleccionada
            if ($request->comunidade != null) {
                $comunidad_id = Comunidade::find($request->comunidade);
            } elseif ($request->nueva_comunidad) {
                $comunidad_id = Comunidade::create([
                    'nombre' => $request->nueva_comunidad,
                ]);
            } else {
                // Lanzar un error si no se selecciona ni se ingresa una comunidad
                return redirect()->back()->withErrors(['comunidade' => 'Debe seleccionar o ingresar una comunidad.'])->withInput();
            }
            // Crear el usuario con la comunidad seleccionada o creada
            $user = User::create(array_merge($request->all(), [
                'comunidad_id' => $comunidad_id->id, // Asignar la comunidad al usuario
            ]));

            // Asignar rol
            $user->assignRole($request->role);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Ocurrió un error al registrar el usuario.'])->withInput();
        }

        // Verificar la fuente del request
        if ($request->source != 'registration') {
            return redirect()->route('users.index')->with('success', 'Usuario registrado');
        } else {
            return redirect()->route('login')->with('success', 'Usuario registrado');
        }
    }

    public function storelogin(StoreUserRequest $request)
    {
        try {
            DB::beginTransaction();

            // Crear usuario
            $fieldhash = Hash::make($request->password);
            $request->merge(['password' => $fieldhash]);

            // Priorizar la comunidad seleccionada
            if ($request->comunidade) {
                $comunidad = Comunidade::find($request->comunidad_id);
            } elseif ($request->nueva_comunidad) {
                $comunidad = Comunidade::create([
                    'nombre' => $request->nueva_comunidad,
                ]);
            } else {
                // Lanzar un error si no se selecciona ni se ingresa una comunidad
                return redirect()->back()->withErrors(['comunidad_id' => 'Debe seleccionar o ingresar una comunidad.'])->withInput();
            }

            // Crear el usuario con la comunidad seleccionada o creada
            $user = User::create(array_merge($request->all(), [
                'comunidad_id' => $comunidad->id, // Asignar la comunidad al usuario
            ]));

            // Asignar rol
            $user->assignRole($request->role);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Ocurrió un error al registrar el usuario.'])->withInput();
        }

        return redirect()->route('login')->with('success', 'Usuario registrado');
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
    public function edit(User $user)
    {
        $roles = Role::all();
        $comunidades = Comunidade::all();
        return view('user.edit', compact('user', 'roles', 'comunidades'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            DB::beginTransaction();

            // Actualizar la contraseña solo si se proporciona una nueva
            if (empty($request->password)) {
                $request = Arr::except($request, ['password']);
            } else {
                $fieldhash = Hash::make($request->password);
                $request->merge(['password' => $fieldhash]);
            }

            // Priorizar la comunidad seleccionada o crear una nueva
            if ($request->comunidade) {
                // Si se selecciona una comunidad existente
                $comunidad_id = $request->comunidade;
            } elseif ($request->nueva_comunidad) {
                // Si se ingresa una nueva comunidad
                $comunidad = Comunidade::create([
                    'nombre' => $request->nueva_comunidad,
                ]);
                $comunidad_id = $comunidad->id;
            } else {
                // Lanzar un error si no se selecciona ni se ingresa una comunidad
                return redirect()->back()->withErrors(['comunidad_id' => 'Debe seleccionar o ingresar una comunidad.'])->withInput();
            }

            // Actualizar el usuario con la comunidad seleccionada o creada
            $user->update(array_merge($request->all(), [
                'comunidad_id' => $comunidad_id,
            ]));

            // Sincronizar roles
            $user->syncRoles($request->role);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Ocurrió un error al actualizar el usuario.'])->withInput();
        }

        return redirect()->route('users.index')->with('success', 'Usuario actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        $rolUser = $user->getRoleNames()->first();
        $user->removeRole($rolUser);

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado');
    }
}
