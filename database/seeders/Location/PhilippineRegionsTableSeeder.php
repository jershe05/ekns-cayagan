<?php

namespace Database\Seeders\Location;

use Illuminate\Database\Seeder;
use DB;

class PhilippineRegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!DB::table('regions')->count()) {
            DB::unprepared(file_get_contents(__DIR__ . '/SQL/philippine_regions.sql'));
        }
    }
}
