<?php

namespace Database\Seeders\Location;

use Illuminate\Database\Seeder;
use DB;
class PhilippineCitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!DB::table('cities')->count()) {
            DB::unprepared(file_get_contents(__DIR__ . '/SQL/philippine_cities.sql'));
        }
    }
}
