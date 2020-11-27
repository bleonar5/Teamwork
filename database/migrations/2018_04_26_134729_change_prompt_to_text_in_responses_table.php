<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePromptToTextInResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
         Schema::table('responses', function(Blueprint $table) {
           $table->text('prompt')->change();
         });
     }

     /**
      * Reverse the migrations.
      *
      * @return void
      */
     public function down()
     {

     }
}
