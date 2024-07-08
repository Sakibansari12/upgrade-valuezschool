<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClassroomsSubscriptionsnotyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classrooms_subscriptions', function (Blueprint $table) {
            $table->integer('notify_subscription_status')->default(0)->after('subscription_status'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('classrooms_subscriptions', function (Blueprint $table) {
            $table->dropColumn('notify_subscription_status');
        });
    }
}
