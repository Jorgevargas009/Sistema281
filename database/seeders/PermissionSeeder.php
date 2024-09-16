<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permisos=[
            //producto
            'ver-producto',
            'crear-producto',
            'editar-producto',
            'eliminar-producto',

            //venta
            'ver-venta',
            'crear-venta',
            'editar-venta',
            'eliminar-venta',
        ];
        foreach ($permisos as $permiso) 
        {
            Permission::create(['name'=>$permiso]);
        }
    }
}
