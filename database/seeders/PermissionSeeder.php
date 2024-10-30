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
           /* 'ver-producto',
            'ver-miproducto',
            'ver-productocomunidad',
            'crear-producto',
            'editar-producto',
            'eliminar-producto',

            //venta
            'ver-carro',
            'editar-detalle',
            'eliminar-detalle',

            //Pagos
            'ver-pago',
            'aprobar-pago',
            'rechazar-pago',

            //pedido
            'ver-pedido',
            'ver-pedidocomunidad',
            'ver-mipedido',
            'editar-mipedido',
            'aceptar-pedido',
            //roles
            'ver-rol',
            'crear-rol',
            'editar-rol',
            'eliminar-rol',
            //usuarios
            'ver-user',
            'editar-user',
            'eliminar-user',
            //direccion
            'ver-direccion',
            'crear-direccion',
            'asociar-direccion',*/
            'crear-pago'

        ];
        foreach ($permisos as $permiso) 
        {
            Permission::create(['name'=>$permiso]);
        }
    }
}
