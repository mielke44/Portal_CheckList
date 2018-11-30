<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ProfileTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('profile')->insert([
            'name' => "Estagiário",
            'created_at' => Carbon::now()
            //'created_at' => new DateTime()
        ]);
        DB::table('profile')->insert([
            'name' => "Efetivado",
            'created_at' => Carbon::now()
            //'created_at' => new DateTime()
        ]);
    }
}
