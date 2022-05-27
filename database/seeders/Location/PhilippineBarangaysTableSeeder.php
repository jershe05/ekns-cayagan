<?php

namespace Database\Seeders\Location;

use DB;
use Illuminate\Database\Seeder;

class PhilippineBarangaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (! DB::table('barangays')->count()) {
            DB::unprepared(file_get_contents(__DIR__ . '/SQL/philippine_barangays-part-1.sql'));
            DB::unprepared(file_get_contents(__DIR__ . '/SQL/philippine_barangays-part-2.sql'));
            DB::unprepared(file_get_contents(__DIR__ . '/SQL/philippine_barangays-part-3.sql'));
        }
    }
}
