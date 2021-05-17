<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('participant_id');
            $table->string('type');
            $table->Integer('num_subsessions');
            $table->Integer('total_sessions');
            $table->string('group_ids');
            $table->string('group_role');
            $table->boolean('complete')->default(0);
            $table->boolean('eligible')->default(0);
            $table->boolean('paid')->default(0);
            $table->string('notes')->default('');
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
        Schema::dropIfExists('sessions');
    }
}
