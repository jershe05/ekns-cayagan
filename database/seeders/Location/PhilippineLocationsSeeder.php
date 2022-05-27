<?php

namespace Database\Seeders\Location;

use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Seeder;

class PhilippineLocationsSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();
        // $this->call(PhilippineRegionsTableSeeder::class);
        // $this->call(PhilippineIslandRegionsTableSeeder::class);
        // $this->call(PhilippineProvincesTableSeeder::class);
        // $this->call(PhilippineCitiesTableSeeder::class);
        $this->call(PhilippineBarangaysTableSeeder::class);
        $this->enableForeignKeys();
    }
}
