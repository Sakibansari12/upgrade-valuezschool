<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSchoolsPaymentsSubTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schools_payments', function (Blueprint $table) {
            $table->integer('classrooms_subscriptions_id')->default(0)->after('school_id');
            $table->json('payment_failed_info')->nullable()->after('classrooms_subscriptions_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schools_payments', function (Blueprint $table) {
            $table->dropColumn('classrooms_subscriptions_id');
            $table->dropColumn('payment_failed_info');
        });
    }
}
