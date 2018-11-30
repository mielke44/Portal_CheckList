<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => "Dilermando Barbosa",
            'email' => "dilermando.barbosa@t-systems.com.br",
            'password' => bcrypt('secret'),
            'is_admin' => true,
            'site' => "12",
            'token' => "",
            'created_at' => Carbon::now()
            //'created_at' => new DateTime()
        ]);

        DB::table('users')->insert([
            'name' => "Gabriel Barbosa",
            'email' => "gabriel.barbosa@t-systems.com.br",
            'password' => bcrypt('secret'),
            'site' => "12",
            'token' => "",
            'is_admin' => true,
            'created_at' => new DateTime()
        ]);

        DB::table('users')->insert([
            'name' => "William Cavenagli",
            'email' => "william.cavenagli@t-systems.com.br",
            'password' => bcrypt('secret'),
            'site' => "12",
            'token' => "",
            'is_admin' => true,
            'created_at' => new DateTime()
        ]);

        DB::table('users')->insert([
            'name' => "Christiano Oishi",
            'email' => "christiano.carvalho@t-systems.com.br",
            'password' => bcrypt('secret'),
            'site' => "12",
            'token' => "",
            'is_admin' => true,
            'created_at' => new DateTime()
        ]);

        DB::table('users')->insert([
            'name' => "Wilson Mielke",
            'email' => "wilson.mielke@t-systems.com.br",
            'password' => bcrypt('secret'),
            'site' => "12",
            'token' => "",
            'is_admin' => true,
            'created_at' => new DateTime()
        ]);
    }
}
