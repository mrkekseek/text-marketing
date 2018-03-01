<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFollowupsColumnsToHomeadvisorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('homeadvisors', function (Blueprint $table) {
            $table->tinyInteger('first_followup_active')->after('text')->unsigned();
            $table->text('first_followup_text')->after('first_followup_active')->nullable();
            $table->tinyInteger('first_followup_delay')->after('first_followup_text')->unsigned();
            $table->tinyInteger('second_followup_active')->after('first_followup_delay')->unsigned();
            $table->text('second_followup_text')->after('second_followup_active')->nullable();
            $table->tinyInteger('second_followup_delay')->after('second_followup_text')->unsigned();
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
            $table->dropColumn(['first_followup_active', 'first_followup_text', 'first_followup_delay', 'second_followup_active', 'second_followup_text', 'second_followup_delay']);
        });
    }
}
