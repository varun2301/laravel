<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHeaderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('header_details', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('zoho_unique_id')->nullable();
            $table->integer('header_id')->unsigned();
            $table->foreign('header_id')->references('id')->on('headers')->onDelete('cascade');

            $table->text('title')->nullable();
            $table->text('description')->nullable();

            $table->integer('owner_id')->unsigned()->nullable();
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');

            /*$table->integer('assigned_to')->unsigned();
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('cascade');*/

            $table->integer('task_list_id')->unsigned()->nullable();
            $table->foreign('task_list_id')->references('id')->on('task_lists')->onDelete('cascade');

            $table->string('work')->nullable();
            $table->string('priority')->nullable();
            $table->string('status')->nullable();
            $table->integer('parent_id')->nullable();
            $table->integer('child')->nullable();
            $table->string('start_date')->useCurrent()->nullable();
            $table->string('end_date')->useCurrent()->nullable();
            /*$table->string('planned_time');
            $table->string('locked_time');
            $table->string('diff_time');*/
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
        Schema::dropIfExists('header_details');
    }
}
