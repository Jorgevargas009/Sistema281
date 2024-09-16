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
            
        ]);*/
        //usuario administrador

        $rol = Role::find('1');
        $permisos=Permission::pluck('id','id')->all();
        $rol->syncPermissions($permisos);
        $user = User::find(1);
        $user->assignRole('administrador');
    }
}
