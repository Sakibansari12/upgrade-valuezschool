<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('last_name');
            $table->string('phone_number')->nullable();
            $table->string('otp');
            $table->string('email')->nullable();
            $table->string('username');
            $table->enum('studenttype', ['student']); // adding this
            $table->enum('student_status', ['Demo','Paid', 'Pending']); // Status 
            $table->dateTimeTz('otp_verified_at')->nullable();
            $table->dateTimeTz('otp_verified_till')->nullable();
            $table->string('view_password');
            $table->binary('password');
            $table->binary('confirm_password');
            $table->string('grade');
            $table->integer('school_id')->default(0);
            $table->boolean('status')->default(0);
            $table->softDeletesTz();
            $table->index('deleted_at');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
}
