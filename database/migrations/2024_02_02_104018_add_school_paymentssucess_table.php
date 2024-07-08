<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSchoolPaymentssucessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schools_payments', function (Blueprint $table) {
            $table->string('upi_id')->after('payment_link_id');
            $table->dateTimeTz('payment_sucessfull_time')->nullable()->after('upi_id'); 
            $table->integer('number_of_subscription')->default(0)->after('payment_sucessfull_time'); 
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
            $table->dropColumn('upi_id');
            $table->dropColumn('payment_sucessfull_time');
            $table->dropColumn('number_of_subscription');
        });
    }
}
