<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('users_id')->unsigned()->default(0);
            $table->integer('lists_id')->unsigned()->default(0);
            $table->text('text');
            $table->string('file')->default('');
            $table->tinyInteger('schedule')->unsigned()->default(0);
            $table->tinyInteger('switch')->unsigned()->default(0);
            $table->string('date')->default('');
            $table->tinyInteger('status')->unsigned()->default(0);
            $table->tinyInteger('active')->unsigned()->default(0);
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
        Schema::dropIfExists('messages');
    }
}
