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
            'is_admin' => 2,
            'site' => "12",
            'token' => "",
            'created_at' => Carbon::now(),
            'group' => 1
            //'created_at' => new DateTime()
        ]);

        DB::table('users')->insert([
            'name' => "Gabriel Barbosa",
            'email' => "gabriel.barbosa@t-systems.com.br",
            'password' => bcrypt('secret'),
            'site' => "12",
            'token' => "",
            'is_admin' => 2,
            'created_at' => new DateTime(),
            'group' => 1
        ]);

        DB::table('users')->insert([
            'name' => "Willian Cavenagli",
            'email' => "willian.cavenagli@t-systems.com.br",
            'password' => bcrypt('secret'),
            'site' => "12",
            'token' => "",
            'is_admin' => 2,
            'created_at' => new DateTime(),
            'group' => 1
        ]);

        DB::table('users')->insert([
            'name' => "Christiano Oishi",
            'email' => "christiano.carvalho@t-systems.com.br",
            'password' => bcrypt('secret'),
            'site' => "12",
            'token' => "",
            'is_admin' => 2,
            'created_at' => new DateTime(),
            'group' => 1
        ]);

        DB::table('users')->insert([
            'name' => "Wilson Mielke",
            'email' => "wilson.mielke@t-systems.com.br",
            'password' => bcrypt('secret'),
            'site' => "12",
            'token' => "",
            'is_admin' => 2,
            'created_at' => new DateTime(),
            'group' => 1
        ]);


        DB::table('users')->insert([
            'name' => "RH padrão",
            'email' => "RH@t-systems.com.br",
            'password' => bcrypt('secret'),
            'site' => "12",
            'token' => bcrypt("id"."RH padrão"),
            'is_admin' => 0,
            'created_at' => new DateTime()
        ]);

        //############TESTE


        DB::table('users')->insert([
            'name' => "Bruno Malta - Conta ADM",
            'email' => "BRUNO.MALTA.ADM@T-SYSTEMS.COM.BR",
            'password' => bcrypt('tsbr123'),
            'site' => "4",
            'token' => "",
            'is_admin' => 2,
            'created_at' => new DateTime()
        ]);
        DB::table('users')->insert([
            'name' => "Bruno Malta - Conta Gestor",
            'email' => "BRUNO.MALTA.GESTOR@T-SYSTEMS.COM.BR",
            'password' => bcrypt('tsbr123'),
            'site' => "4",
            'token' => "",
            'is_admin' => 1,
            'created_at' => new DateTime()
        ]);
        DB::table('users')->insert([
            'name' => "Bruno Malta - Conta responsável",
            'email' => "BRUNO.MALTA.RESP@T-SYSTEMS.COM.BR",
            'password' => bcrypt('tsbr123'),
            'site' => "4",
            'token' => "",
            'is_admin' => 0,
            'created_at' => new DateTime()
        ]);


        DB::table('users')->insert([
            'name' => "Bruno Nunes de Oliveira - Conta ADM",
            'email' => "BRUNO.NUNES.ADM@T-SYSTEMS.COM.BR",
            'password' => bcrypt('tsbr123'),
            'site' => "4",
            'token' => "",
            'is_admin' => 2,
            'created_at' => new DateTime()
        ]);
        DB::table('users')->insert([
            'name' => "Bruno Nunes de Oliveira - Conta Gestor",
            'email' => "BRUNO.NUNES.GESTOR@T-SYSTEMS.COM.BR",
            'password' => bcrypt('tsbr123'),
            'site' => "4",
            'token' => "",
            'is_admin' => 1,
            'created_at' => new DateTime()
        ]);
        DB::table('users')->insert([
            'name' => "Bruno Nunes de Oliveira - Conta responsável",
            'email' => "BRUNO.NUNES.RESP@T-SYSTEMS.COM.BR",
            'password' => bcrypt('tsbr123'),
            'site' => "4",
            'token' => "",
            'is_admin' => 0,
            'created_at' => new DateTime()
        ]);

    }
}
