<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReplyViewedColumnToDialogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dialogs', function (Blueprint $table) {
            $table->tinyInteger('reply_viewed')->after('reply')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dialogs', function (Blueprint $table) {
            $table->dropColumn('reply_viewed');
        });
    }
}
