<?php

namespace Database\Seeders\Location;

use Illuminate\Database\Seeder;
use DB;

class PhilippineProvincesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!DB::table('provinces')->count()) {
            DB::unprepared(file_get_contents(__DIR__ . '/SQL/philippine_provinces.sql'));
        }
    }
}
