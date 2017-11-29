<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seances', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('users_id')->unsigned();
            $table->integer('clients_id')->unsigned();
            $table->integer('surveys_id')->unsigned();
            $table->string('code');
            $table->string('url');
            $table->string('date');
            $table->integer('completed')->unsigned()->default(0);
            $table->integer('social_show')->unsigned()->default(0);
            $table->string('social_tap')->default('');
            $table->string('type');
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
        Schema::dropIfExists('seances');
    }
}
