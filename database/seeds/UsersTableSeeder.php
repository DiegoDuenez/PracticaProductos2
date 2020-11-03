<?php

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
        factory(App\User::class, 19)->create();

        App\User::create([ //USUARIO ADMINISTRADOR

            'name' => 'Diego',
            'years_old' => 18,
            'email' => '19170154@uttcampus.edu.mx',
            'password' => bcrypt('123'),
            'rol' => 'admin',

        ]);
    }
}
