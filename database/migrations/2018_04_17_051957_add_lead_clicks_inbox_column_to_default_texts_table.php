<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLeadClicksInboxColumnToDefaultTextsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('default_texts', function (Blueprint $table) {
            $table->string('lead_clicks_inbox')->after('lead_clicks')->default('');
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
            $table->dropColumn('lead_clicks_inbox');
        });
    }
}
