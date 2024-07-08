<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSupportsreplyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supports', function (Blueprint $table) {
            $table->text('support_reply')->nullable()->after('query');
            $table->integer('support_reply_noty')->default(0)->after('support_reply');
            $table->integer('user_id')->default(0)->after('support_reply_noty');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supports', function (Blueprint $table) {
            $table->dropColumn('support_reply');
            $table->dropColumn('support_reply_noty');
            $table->dropColumn('user_id');
        });
    }
}
