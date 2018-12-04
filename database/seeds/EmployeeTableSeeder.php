<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EmployeeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('employees')->insert([
            'name' => "Joãozinho",
            'email' => "joão@teste.com",
            'cpf' => "123.123.123-12",
            'site' => "12",
            'profile_id' => "1",
            'fone' => "+12(31)23123-1231",
            'token' => bcrypt(rand(100000,999999)),
            'created_at' => Carbon::now()
            //'created_at' => new DateTime()
        ]);
    }
}
