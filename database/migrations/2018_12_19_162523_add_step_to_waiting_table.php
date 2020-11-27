<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStepToWaitingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
       Schema::table('waiting', function(Blueprint $table) {
         $table->integer('step')->after('group_tasks_id');
       });
     }

     /**
      * Reverse the migrations.
      *
      * @return void
      */
     public function down()
     {
       Schema::table('waiting', function(Blueprint $table) {
         $table->dropColumn('step');
       });
     }
}
