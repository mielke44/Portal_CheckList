<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class linker_checklistTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('linker_checklist')->insert([
            'checklist_id' => "1",
            'task_id' => "1",
            'created_at' => Carbon::now()
            //'created_at' => new DateTime()
        ]);

        DB::table('linker_checklist')->insert([
            'checklist_id' => "1",
            'task_id' => "2",
            'created_at' => Carbon::now()
            //'created_at' => new DateTime()
        ]);
    }
}
