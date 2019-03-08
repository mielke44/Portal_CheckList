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
            'name' => "Formulário - Ficha cadastral",
            'description' => "Solicitar para o contratado o preenchimento e assinatura do documento",
            'type' => "Documento",
            'resp' => 6,
            'limit'=> 10,
            'created_at' => Carbon::now()
            //'created_at' => new DateTime()
        ]);
        DB::table('task')->insert([
            'name' => "Formulário - Plano de saúde",
            'description' => "Solicitar para o contratado o preenchimento e assinatura do documento",
            'type' => "Documento",
            'resp' => 6,
            'limit'=> 10,
            'created_at' => Carbon::now()
            //'created_at' => new DateTime()
        ]);
        DB::table('task')->insert([
            'name' => "Formulário - Seguro de vida",
            'description' => "Solicitar para o contratado o preenchimento e assinatura do documento",
            'type' => "Documento",
            'resp' => 6,
            'limit'=> 10,
            'created_at' => Carbon::now()
            //'created_at' => new DateTime()
        ]);
        DB::table('task')->insert([
            'name' => "Formulário - Vale transporte e vale refeição",
            'description' => "Solicitar para o contratado o preenchimento e assinatura dos documentos",
            'type' => "Documento",
            'resp' => 6,
            'limit'=> 10,
            'created_at' => Carbon::now()
            //'created_at' => new DateTime()
        ]);


        DB::table('task')->insert([
            'name' => "Devolução - Crachá",
            'description' => "Recolher o crachá do funcionário em desligamento",
            'type' => "Documento",
            'resp' => 6,
            'limit'=> 10,
            'created_at' => Carbon::now()
            //'created_at' => new DateTime()
        ]);
        DB::table('task')->insert([
            'name' => "Devolução - Notebook",
            'description' => "Recolher o equipamento do funcionário em desligamento",
            'type' => "Documento",
            'resp' => 6,
            'limit'=> 10,
            'created_at' => Carbon::now()
            //'created_at' => new DateTime()
        ]);
        DB::table('task')->insert([
            'name' => "Cancelamento da chave de Rede/Mainframe e-mail",
            'description' => "Solicitar o cancelamento através o link Service Desk - Exclusão de ID, na Intranet",
            'type' => "Solicitação",
            'resp' => 6,
            'limit'=> 10,
            'created_at' => Carbon::now()
            //'created_at' => new DateTime()
        ]);

        DB::table('task')->insert([
            'name' => "Termo de Confidencialidade",
            'description' => "Solicitar a assinatura funcionário em desligamento",
            'type' => "Documento",
            'resp' => 6,
            'limit'=> 10,
            'created_at' => Carbon::now()
        ]);
    }
}
