<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToBarangayVoterStanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('barangay_voter_stances', function (Blueprint $table) {
            $table->string('city_code');
            $table->string('province_code');
            $table->string('region_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('barangay_voter_stances', function (Blueprint $table) {
            //
        });
    }
}
