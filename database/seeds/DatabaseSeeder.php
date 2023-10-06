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
        $this->call(RoleTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(PlatformTableSeeder::class);
        $this->call(SuggestionsTableSeeder::class);
        $this->call(EventStatusesTableSeeder::class);
    }
}