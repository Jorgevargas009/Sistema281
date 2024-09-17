<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class userController extends Controller
{
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
        return view( 'user.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        
        try {
            DB::beginTransaction();
            //Crear usuario
            $fieldhash = Hash::make($request->password);

            $request->merge(['password'=>$fieldhash]);

            //Asignar permisos
            $user = User::create($request->all());

            //asignar rol
            $user->assignRole($request->role);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }


        return redirect()->route('users.index')->with('success', 'Usuario registrado'); 
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
        return view( 'user.edit',compact('user','roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {   
            DB::beginTransaction();
            if(empty($request->password)) {
                $request=Arr::except($request,array('password'));
            } else {
                $fieldhash = Hash::make($request->password);
                $request->merge(['password'=>$fieldhash]);
            }
            $user->update($request->all());
            $user->syncRoles($request->role);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
        
        return redirect()->route('users.index')->with('success', 'Usuario actualizado'); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        $rolUser= $user->getRoleNames()->first();
        $user->removeRole($rolUser);

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado'); 
    
    }
}
