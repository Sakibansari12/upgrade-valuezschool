<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reminders', function (Blueprint $table) {
            $table->string('school_reminder_noty')->default(0)->after('description');
            $table->string('teacher_reminder_noty')->default(0)->after('school_reminder_noty');
            $table->string('student_reminder_noty')->default(0)->after('teacher_reminder_noty');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reminders', function (Blueprint $table) {
            $table->dropColumn('school_reminder_noty');
            $table->dropColumn('teacher_reminder_noty');
            $table->dropColumn('student_reminder_noty');
        });
    }
}
