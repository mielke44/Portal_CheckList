<?php

use Illuminate\Database\Seeder;

class LinkerChecklistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('linker_checklist')->insert([
            'task_id' => "1",
            'checklist_template_id' => "1",
        ]);
        DB::table('linker_checklist')->insert([
            'task_id' => "2",
            'checklist_template_id' => "1",
        ]);
        DB::table('linker_checklist')->insert([
            'task_id' => "3",
            'checklist_template_id' => "1",
        ]);
        DB::table('linker_checklist')->insert([
            'task_id' => "4",
            'checklist_template_id' => "1",
        ]);

        DB::table('linker_checklist')->insert([
            'task_id' => "5",
            'checklist_template_id' => "2",
        ]);
        DB::table('linker_checklist')->insert([
            'task_id' => "6",
            'checklist_template_id' => "2",
        ]);
        DB::table('linker_checklist')->insert([
            'task_id' => "7",
            'checklist_template_id' => "2",
        ]);
        DB::table('linker_checklist')->insert([
            'task_id' => "8",
            'checklist_template_id' => "2",
        ]);






        DB::table('linker_checklist')->insert([
            'task_id' => "1",
            'checklist_template_id' => "3",
        ]);
        DB::table('linker_checklist')->insert([
            'task_id' => "2",
            'checklist_template_id' => "3",
        ]);
        DB::table('linker_checklist')->insert([
            'task_id' => "3",
            'checklist_template_id' => "3",
        ]);
        DB::table('linker_checklist')->insert([
            'task_id' => "4",
            'checklist_template_id' => "3",
        ]);

        DB::table('linker_checklist')->insert([
            'task_id' => "5",
            'checklist_template_id' => "4",
        ]);
        DB::table('linker_checklist')->insert([
            'task_id' => "6",
            'checklist_template_id' => "4",
        ]);
        DB::table('linker_checklist')->insert([
            'task_id' => "7",
            'checklist_template_id' => "4",
        ]);
        DB::table('linker_checklist')->insert([
            'task_id' => "8",
            'checklist_template_id' => "4",
        ]);




    }
}
