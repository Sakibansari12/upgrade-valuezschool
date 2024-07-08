<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClassroomsSubscriptionsssTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classrooms_subscriptions', function (Blueprint $table) {
            $table->integer('package_row_count')->default(0)->after('school_id');
            $table->string('sr_number')->after('package_row_count'); 
            $table->integer('change_classroom_status')->default(0)->after('sr_number'); 
            $table->integer('change_payment_status')->default(0)->after('change_classroom_status'); 
            $table->integer('share_login_credential')->default(0)->after('change_payment_status'); 
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
            $table->dropColumn('package_row_count');
            $table->dropColumn('sr_number');
            $table->dropColumn('change_classroom_status');
            $table->dropColumn('change_payment_status');
            $table->dropColumn('share_login_credential');
        });
    }
}
