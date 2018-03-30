<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDefaultTextsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_texts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('thank_you_signup')->default('');
            $table->string('two_days_not_active')->default('');
            $table->string('four_days_not_active')->default('');
            $table->string('new_user')->default('');
            $table->string('instant')->default('');
            $table->string('first_followup')->default('');
            $table->string('second_followup')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('default_texts');
    }
}
