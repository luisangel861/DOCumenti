<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'nombre' =>'Sol',
            'apellido_paterno' =>'Gómez',
            'apellido_materno' =>'Ríos',
            'email' =>'solgomez2417@gmail.com',
            'email_verified_at'=>'2021-02-19',
            'password' =>Hash::make('12345678')
        ]);
    }
}
