<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('header_detail_id')->unsigned();
            $table->foreign('header_detail_id')->references('id')->on('header_details')->onDelete('cascade');

            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('project_id')->unsigned()->nullable();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            
            $table->bigInteger('zoho_log_id')->nullable();
            $table->string('log_time')->nullable();
            $table->datetime('start_date_time')->nullable();
            $table->datetime('end_date_time')->nullable();
            $table->string('approval_status')->nullable();
            $table->string('bill_status')->nullable();
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
        Schema::dropIfExists('logs');
    }
}
