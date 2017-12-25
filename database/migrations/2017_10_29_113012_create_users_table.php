<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admins_id')->unsigned()->default(0);
            $table->string('company_name')->default('');
            $table->string('company_status')->default('');
            $table->string('plans_id')->default('');
            $table->integer('teams_id')->unsigned()->default(0);
            $table->tinyInteger('teams_leader')->unsigned()->default(0);
            $table->tinyInteger('owner')->unsigned()->default(0);
            $table->tinyInteger('type')->unsigned()->default(2);
            $table->string('email')->default('');
            $table->string('password')->default('');
            $table->string('firstname')->default('');
            $table->string('lastname')->default('');
            $table->string('phone')->default('');
            $table->string('view_phone')->default('');
            $table->string('additional_phones')->default('');
            $table->tinyInteger('active')->unsigned()->default(0);
            $table->tinyInteger('offset')->default(0);
            $table->string('remember_token')->default('');
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
        Schema::dropIfExists('users');
    }
}
