<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
       Schema::create('group_user', function (Blueprint $table) {
           $table->increments('id');
           $table->integer('user_id')->unsigned();
           $table->integer('group_id')->unsigned();
           $table->timestamps();

           $table->foreign('user_id')->references('id')->on('users');
           $table->foreign('group_id')->references('id')->on('groups');

           $table->unique(['user_id', 'group_id']);

       });
     }

     /**
      * Reverse the migrations.
      *
      * @return void
      */
     public function down()
     {
         Schema::dropIfExists('group_user');
     }
}
