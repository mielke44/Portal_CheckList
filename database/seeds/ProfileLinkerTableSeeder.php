<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ProfileLinkerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('profile_linker')->insert([
            'profile_id' => "1",
            'checklist_id' => "1",
            'created_at' => Carbon::now()
            //'created_at' => new DateTime()
        ]);
        DB::table('profile_linker')->insert([
            'profile_id' => "2",
            'checklist_id' => "1",
            'created_at' => Carbon::now()
            //'created_at' => new DateTime()
        ]);
    }
}
