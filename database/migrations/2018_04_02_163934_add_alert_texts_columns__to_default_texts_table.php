<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlertTextsColumnsToDefaultTextsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('default_texts', function (Blueprint $table) {
            $table->string('lead_clicks_alert')->after('second_followup_delay')->default('');
            $table->string('lead_reply_alert')->after('lead_clicks_alert')->default('');
            $table->string('lead_clicks')->after('lead_reply_alert')->default('');
            $table->string('user_click_reminder')->after('lead_clicks')->default('');
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
            $table->dropColumn('lead_clicks_alert');
            $table->dropColumn('lead_reply_alert');
            $table->dropColumn('lead_clicks');
            $table->dropColumn('user_click_reminder');
        });
    }
}
