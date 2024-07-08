<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotificationIconTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notification', function (Blueprint $table) {
            $table->string('school_admin_noty')->default(0)->after('description');
            $table->string('teacher_noty')->default(0)->after('school_admin_noty');
            $table->string('student_noty')->default(0)->after('teacher_noty');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notification', function (Blueprint $table) {
            $table->dropColumn('school_admin_noty');
            $table->dropColumn('teacher_noty');
            $table->dropColumn('student_noty');
        });
    }
}
