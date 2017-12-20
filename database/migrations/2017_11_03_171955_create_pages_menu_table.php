<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages_menu', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pages_code');
            $table->string('parents_code');
            $table->string('plans');
            $table->tinyInteger('main')->unsigned()->nullable(false)->default(0);
            $table->tinyInteger('pos')->unsigned()->nullable(false)->default(0);
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
        Schema::dropIfExists('pages_menu');
    }
}
