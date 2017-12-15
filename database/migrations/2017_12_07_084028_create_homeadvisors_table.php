<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeadvisorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homeadvisors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('users_id')->unsigned()->default(0);
            $table->text('text')->nullable();
            $table->string('additional_phones')->default('');
            $table->string('rep')->default('');
            $table->tinyInteger('send_request')->unsigned()->default(0);
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
        Schema::dropIfExists('homeadvisors');
    }
}
