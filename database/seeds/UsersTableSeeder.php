<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_admin = Role::where('name', 'administrador')->first();

        $user = new User();
        $user->name = 'Admin CACE';
        $user->email = 'admin@cace.com.ar';
        $user->password = bcrypt('4dminC4c3');
        $user->save();
        $user->asignarRol($role_admin);


        //Usuario con rol operador
        $role_operator = Role::where('name', 'operador')->first();

        $user = new User();
        $user->name = 'Operador CACE';
        $user->email = 'operator@cace.com.ar';
        $user->password = bcrypt('operat0rC4C3');
        $user->save();
        $user->asignarRol($role_operator);
    }
}