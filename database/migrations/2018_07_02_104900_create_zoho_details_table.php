<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZohoDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zoho_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('access_token')->nullable();
            $table->string('refresh_token')->nullable();
            $table->integer('cron_executed')->nullable();
            $table->datetime('gen_time')->nullable();
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
        Schema::dropIfExists('zoho_details');
    }
}
