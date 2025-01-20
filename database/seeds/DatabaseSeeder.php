<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeed::class);
        $this->call(PermissionSeeder::class);
        $this->call(UserSeed::class);
        $this->call(ProjectTypeSeeder::class);
        $this->call(LocationSeeder::class);

    }
}