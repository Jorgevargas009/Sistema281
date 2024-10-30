<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*$user = User::create([
            
            'nombre'=> 'Danny',
            'apellido'=> 'Apaza',
            'email'=> 'admin@gmail.com',
            'password'=> bcrypt('12345678'),
            
        ]);
        //usuario administrador

        $rol = Role::create(['name'=>'administrador']);
        $permisos=Permission::pluck('id','id')->all();
        $rol->syncPermissions($permisos);
        $user->assignRole('administrador');
*/
       /* 
       
        $rol = Role::create(['name'=>'administrador']);
        $rol = Role::create(['name' => 'artesano']);
        $rol = Role::create(['name' => 'cliente']);
        $rol = Role::create(['name' => 'repartidor']);
        */

        // Asignar permisos específicos a cada rol
      /*  $roles = [
            'artesano' => [
                'ver-miproducto', 'crear-producto', 'editar-producto', 'eliminar-producto',
            ],
            'cliente' => [
                'ver-productocomunidad', 'ver-mipedido', 'ver-carro', 'editar-carro', 'editar-mipedido', 'crear-direccion','asociar-direccion'
            ],
            'repartidor' => [
                'ver-pedidocomunidad', 'aceptar-pedido'
            ]
        ];
        */
        // Asignar todos los permisos al administrador
        $adminRol = Role::where('name', 'administrador')->first();
        if ($adminRol) {
            $allPermissions = Permission::all(); // Obtener todos los permisos
            $adminRol->syncPermissions($allPermissions); // Asignar todos los permisos al administrador
        }
        
        // Asignar permisos específicos a los demás roles
     /*   foreach ($roles as $rolName => $permisosRol) {
            $rol = Role::where('name', $rolName)->first(); // Buscar el rol
            if ($rol) {
                $permisos = Permission::whereIn('name', $permisosRol)->get(); // Buscar los permisos para ese rol
                $rol->syncPermissions($permisos); // Asignar los permisos al rol
            }
        }*/
    }
}
