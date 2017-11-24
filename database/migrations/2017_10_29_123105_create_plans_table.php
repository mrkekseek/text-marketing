<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('plans_id')->default('');
            $table->string('name')->default('');
            $table->double('amount', 8, 2)->unsigned()->default(0);
            $table->string('interval')->default('');
            $table->integer('reviews')->unsigned()->default(0);
            $table->integer('tms')->unsigned()->default(0);
            $table->integer('emails')->unsigned()->default(0);
            $table->tinyInteger('trial')->unsigned()->default(0);
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
        Schema::dropIfExists('plans');
    }
}
