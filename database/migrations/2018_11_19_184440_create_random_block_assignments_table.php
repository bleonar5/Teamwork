<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRandomBlockAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('random_block_assignments', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('group_id')->unsigned();
          $table->string('block');
          $table->timestamps();

          $table->foreign('group_id')->references('id')->on('groups');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('random_block_assignments');
    }
}
