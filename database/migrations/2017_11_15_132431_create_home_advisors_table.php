<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeAdvisorsTable extends Migration
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
            $table->integer('teams_id')->unsigned()->default(0);
            $table->string('links_code')->default('');
            $table->string('firstname')->default('');
            $table->string('lastname')->default('');
            $table->string('phone')->default('');
            $table->string('email')->default('');
            $table->string('link_for_ha')->default('');
            $table->string('sign_up_link')->default('');
            $table->string('success_string')->default('');
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
