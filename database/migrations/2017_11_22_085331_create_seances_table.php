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
            $table->integer('review_id')->unsigned()->default(0);
            $table->integer('client_id')->unsigned()->default(0);
            $table->integer('url_id')->unsigned()->default(0);
            $table->string('code');
            $table->string('url');
            $table->string('date');
            $table->tinyInteger('completed')->unsigned()->default(0);
            $table->tinyInteger('show')->unsigned()->default(0);
            $table->string('type');
            $table->tinyInteger('finish')->unsigned()->default(0);
            $table->tinyInteger('success')->unsigned()->default(0);
            $table->text('message');
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
