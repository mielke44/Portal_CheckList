<?php

use Illuminate\Database\Seeder;

class SitesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('site')->insert([
            'name' => "Matriz",
            'state' => "SP",
            'city' => "São Paulo",
        ]);
        DB::table('site')->insert([
            'name' => "Data Center Matrix",
            'state' => "SP",
            'city' => "São Paulo",
        ]);
        DB::table('site')->insert([
            'name' => "Planta Volskwagem",
            'state' => "SP",
            'city' => "São Bernado do Campo",
        ]);
        DB::table('site')->insert([
            'name' => "Pampas",
            'state' => "SP",
            'city' => "São Bernado do Campo",
        ]);
        DB::table('site')->insert([
            'name' => "Demarchi",
            'state' => "SP",
            'city' => "São Bernado do Campo",
        ]);
        DB::table('site')->insert([
            'name' => "Sede",
            'state' => "SP",
            'city' => "São Bernado do Campo",
        ]);
        DB::table('site')->insert([
            'name' => "Alphaville",
            'state' => "SP",
            'city' => "Barueri",
        ]);
        DB::table('site')->insert([
            'name' => "T-Center",
            'state' => "SP",
            'city' => "Barueri",
        ]);
        DB::table('site')->insert([
            'name' => "Planta Mercedez Benz",
            'state' => "SP",
            'city' => "Campinas",
        ]);
        DB::table('site')->insert([
            'name' => "Rio De Janeiro",
            'state' => "RJ",
            'city' => "Rio de Janeiro",
        ]);
        DB::table('site')->insert([
            'name' => "Planta Mercedes Benz",
            'state' => "MG",
            'city' => "Juiz de Fora",
        ]);
        DB::table('site')->insert([
            'name' => "Curitiba",
            'state' => "PR",
            'city' => "Curitiba",
        ]);
        DB::table('site')->insert([
            'name' => "Planta Volkswagen",
            'state' => "PR",
            'city' => "São José dos Pinhais",
        ]);
        DB::table('site')->insert([
            'name' => "Blumenau",
            'state' => "SC",
            'city' => "Blumenau",
        ]);

    }
}
