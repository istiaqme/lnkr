<?php

namespace Database\Seeders;

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
        if(config('app.env') == 'local'){
            // seeds only in development phase
            $this->call(TestAppSeeder::class);
            dd('Development Phase Seeding Done.');
        }
    }
}
