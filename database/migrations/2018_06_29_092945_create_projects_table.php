<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            //$table->bigIncrements('id');
            $table->bigInteger('zoho_project_id')->nullable();
            $table->string('project_name')->nullable();
            $table->string('project_slug')->nullable();
            $table->text('project_desc')->nullable();
            $table->string('owner_name')->nullable();
            $table->string('group_name')->nullable();
            $table->string('project_start_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
