<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFollowupsDelayColumnsTypeInHomeadvisorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('homeadvisors', function (Blueprint $table) {
            $table->smallInteger('first_followup_delay')->change();
            $table->smallInteger('second_followup_delay')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('homeadvisors', function (Blueprint $table) {
            $table->tinyInteger('first_followup_delay')->after('first_followup_text')->unsigned();
            $table->tinyInteger('second_followup_delay')->after('second_followup_text')->unsigned();
        });
    }
}
