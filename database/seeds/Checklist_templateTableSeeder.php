<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class Checklist_templateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {



        DB::table('checklist_template')->insert([
            'name' => "Lista de contratação",
            'profile_id' => "2",
            'created_at' => Carbon::now()
            //'created_at' => new DateTime()
        ]);

        DB::table('checklist_template')->insert([
            'name' => "Lista de desligamento",
            'profile_id' => "2",
            'created_at' => Carbon::now()
            //'created_at' => new DateTime()
        ]);

        DB::table('checklist_template')->insert([
            'name' => "Lista de contratação estagiário",
            'profile_id' => "1",
            'created_at' => Carbon::now()
            //'created_at' => new DateTime()
        ]);

        DB::table('checklist_template')->insert([
            'name' => "Lista de desligamento estagiário",
            'profile_id' => "1",
            'created_at' => Carbon::now()
            //'created_at' => new DateTime()
        ]);

    }
}
