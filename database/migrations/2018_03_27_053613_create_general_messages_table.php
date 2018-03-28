<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneralMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('phone')->default('');
            $table->string('firstname')->default('');
            $table->string('lastname')->default('');
            $table->text('text');
            $table->tinyInteger('my')->unsigned()->default(0);
            $table->tinyInteger('new')->unsigned()->default(0);
            $table->tinyInteger('status')->unsigned()->default(0);
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
        Schema::dropIfExists('general_messages');
    }
}
