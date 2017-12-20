<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialUrlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_urls', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('users_id')->unsigned()->default(0);
            $table->string('name')->default('');
            $table->string('url')->default('');
            $table->string('social_id')->default('');
            $table->integer('active')->unsigned()->default(0);
            $table->integer('default')->unsigned()->default(0);
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
        Schema::dropIfExists('social_urls');
    }
}
