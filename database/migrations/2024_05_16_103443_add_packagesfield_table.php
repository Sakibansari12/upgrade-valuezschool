<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPackagesfieldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_packages', function (Blueprint $table) {
            $table->integer('invoice_id')->default(0)->after('package_status');
            $table->string('set_pricing')->after('invoice_id');
            $table->string('total_price')->after('set_pricing');
            $table->string('name_of_package')->after('total_price');
            $table->string('duration_of_package')->after('name_of_package');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_packages', function (Blueprint $table) {
            $table->dropColumn('invoice_id');
            $table->dropColumn('set_pricing');
            $table->dropColumn('total_price');
            $table->dropColumn('name_of_package');
            $table->dropColumn('duration_of_package');
        });
    }
}
