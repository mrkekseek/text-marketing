<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNexmoCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nexmo_calls', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->default('');
            $table->string('conversation_uuid')->default('');
            $table->string('from')->default('');
            $table->string('to')->default('');
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
        Schema::dropIfExists('nexmo_calls');
    }
}
