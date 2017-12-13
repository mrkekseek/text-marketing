<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('links', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('users_id')->unsigned()->default(0);
            $table->integer('teams_id')->unsigned()->default(0);
            $table->string('code')->default('');
            $table->string('firstname')->default('');
            $table->string('lastname')->default('');
            $table->string('phone')->default('');
            $table->string('email')->default('');
            $table->string('url')->default('');
            $table->string('success')->default('');
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
        Schema::dropIfExists('links');
    }
}
