<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(SitesTableSeeder::class);
        $this->call(TaskTableSeeder::class);
        $this->call(Checklist_templateTableSeeder::class);
        $this->call(ProfileTableSeeder::class);
        $this->call(EmployeeTableSeeder::class);
        $this->call(GroupTableSeeder::class);
        $this->call(LinkerChecklistSeeder::class);
    }
}
