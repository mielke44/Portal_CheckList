<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TaskTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('task')->insert([
            'name' => "Crachá",
            'description' => "Criar crachá",
            'type' => "Solicitação",
            'resp' => 0,
            'created_at' => Carbon::now()
            //'created_at' => new DateTime()
        ]);

        DB::table('task')->insert([
            'name' => "CPF",
            'description' => "Entregar CPF",
            'type' => "Documento",
            'resp' => 5,
            'created_at' => Carbon::now()
            //'created_at' => new DateTime()
        ]);

        DB::table('task')->insert([
            'name' => "Criar E-Mail",
            'description' => "Criar E-Mail interno da empresa para o empregado",
            'type' => "Solicitação",
            'resp' => 'group1',
            'created_at' => Carbon::now()
            //'created_at' => new DateTime()
        ]);
    }
}
