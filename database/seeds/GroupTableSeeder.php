<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class GroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('group')->insert([
            'name' => "Mobile Apps",
            'created_at' => Carbon::now()
        ]);
    }
}
