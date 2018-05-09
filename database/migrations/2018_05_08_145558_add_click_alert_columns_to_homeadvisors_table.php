<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClickAlertColumnsToHomeadvisorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('homeadvisors', function (Blueprint $table) {
            $table->tinyInteger('click_alert_active')->after('second_followup_delay')->unsigned();
            $table->text('click_alert_text')->after('click_alert_active')->nullable();
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
            $table->dropColumn(['click_alert_active', 'click_alert_text']);
        });
    }
}
