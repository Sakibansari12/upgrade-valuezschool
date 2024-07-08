<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClassroomsSubscriptionsPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classrooms_subscriptions', function (Blueprint $table) {
            $table->integer('subscriptions_payment_status')->default(0)->after('school_id');
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
            $table->dropColumn('subscriptions_payment_status');
        });
    }
}
