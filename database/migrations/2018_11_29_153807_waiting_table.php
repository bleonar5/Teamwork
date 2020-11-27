<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WaitingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
       Schema::create('waiting', function (Blueprint $table) {
           $table->increments('id');
           $table->integer('user_id')->unsigned()->nullable();
           $table->integer('group_id')->unsigned()->nullable();
           $table->integer('group_tasks_id')->unsigned()->nullable();
           $table->timestamps();

           $table->foreign('user_id')->references('id')->on('users');
           $table->foreign('group_id')->references('id')->on('groups');
           $table->foreign('group_tasks_id')->references('id')->on('group_tasks');

       });
     }

     /**
      * Reverse the migrations.
      *
      * @return void
      */
     public function down()
     {
         Schema::dropIfExists('waiting');
     }
}
