<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStudentPaymentsDurationPackageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_payments', function (Blueprint $table) { 
            $table->string('duration_package')->after('payment_failed_info');
            $table->dateTimeTz('start_date_sub')->nullable()->after('duration_package');
            $table->dateTimeTz('start_end_sub')->nullable()->after('start_date_sub');
            $table->string('invoice_upload')->after('start_end_sub');
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
            $table->dropColumn('duration_package');
            $table->dropColumn('start_date_sub');
            $table->dropColumn('start_end_sub');
            $table->dropColumn('invoice_upload');
        });
    }
}
