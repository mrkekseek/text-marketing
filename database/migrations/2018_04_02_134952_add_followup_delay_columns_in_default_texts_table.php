<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFollowupDelayColumnsInDefaultTextsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('default_texts', function (Blueprint $table) {
            $table->smallInteger('first_followup_delay')->after('first_followup')->unsigned();
            $table->smallInteger('second_followup_delay')->after('second_followup')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('default_texts', function (Blueprint $table) {
            $table->dropColumn('first_followup_delay');
            $table->dropColumn('second_followup_delay');
        });
    }
}
