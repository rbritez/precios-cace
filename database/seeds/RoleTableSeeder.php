<?php

use App\Role;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role();
        $role->name = 'administrador';
        $role->description = 'Acceso a todo el sistema';
        $role->save();

        $role = new Role();
        $role->name = 'operador';
        $role->description = 'Acceso a credenciales';
        $role->save();
    }
}