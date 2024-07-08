<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStudentPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_packages', function (Blueprint $table) {
            $table->text('this_package_includes')->nullable()->after('packages');
            $table->string('msg_discount')->after('this_package_includes');
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
            $table->dropColumn('this_package_includes');
        });
    }
}
