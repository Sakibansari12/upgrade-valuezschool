<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccesscodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accesscodes', function (Blueprint $table) {
            $table->id();
            $table->string('school_name');
            $table->string('teacher_name');
            $table->string('access_code');
            $table->string('roll_no');
            $table->integer('student_id')->default(0);
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
        Schema::dropIfExists('accesscodes');
    }
}
