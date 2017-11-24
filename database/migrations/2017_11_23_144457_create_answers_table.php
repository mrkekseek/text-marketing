<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('users_id')->unsigned()->default(0);
            $table->integer('clients_id')->unsigned()->default(0);
            $table->integer('seances_id')->unsigned()->default(0);
            $table->integer('surveys_id')->unsigned()->default(0);
            $table->integer('questions_id')->unsigned()->default(0);
            $table->string('questions_type')->default('');
            $table->string('questions_text')->default('');
            $table->string('value')->default('');
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
        Schema::dropIfExists('answers');
    }
}
