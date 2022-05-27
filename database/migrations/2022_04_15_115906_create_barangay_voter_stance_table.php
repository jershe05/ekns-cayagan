<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangayVoterStanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barangay_voter_stances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('barangay_code');
            $table->integer('pro');
            $table->integer('non_pro');
            $table->integer('undecided');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('barangay_voter_stances');
    }
}
