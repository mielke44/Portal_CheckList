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
            'name' => "Curitiba NEX",
            'profile_id' => "1",
            'created_at' => Carbon::now()
            //'created_at' => new DateTime()
        ]);
    }
}
