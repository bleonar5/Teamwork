<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('signature',10000)->nullable();
            $table->timestamp('signature_date')->nullable();
            $table->boolean('waiting')->default(0);
            $table->string('email');
            $table->Integer('task_id')->default(0);
            $table->string('password');
            $table->string('group_role')->default('');
            $table->string('participant_id')->unique()->nullable();
            $table->string('status')->nullable();
            $table->Integer('current_session')->nullable();
            $table->Integer('max_sessions')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
