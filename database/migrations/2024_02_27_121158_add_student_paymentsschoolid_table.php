<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStudentPaymentsschoolidTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_payments', function (Blueprint $table) {
            $table->integer('school_id')->default(0)->after('student_id');
            $table->json('payment_failed_info')->nullable()->after('school_id');  
            $table->string('upi_id')->after('payment_failed_info');
            $table->dateTimeTz('payment_sucessfull_time')->nullable()->after('upi_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_payments', function (Blueprint $table) {
            $table->dropColumn('school_id');
            $table->dropColumn('payment_failed_info');
            $table->dropColumn('upi_id');
            $table->dropColumn('payment_sucessfull_time');
        });
    }
}
