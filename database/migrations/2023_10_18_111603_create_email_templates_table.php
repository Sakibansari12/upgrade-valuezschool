<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('template')->unique();
            $table->string('module')->unique();
            $table->string('category');
            $table->string('group');
            $table->string('subject');
            $table->string('description')->nullable();
            $table->longText('admin_body')->nullable();
            $table->longText('school_body')->nullable();
            $table->longText('student_body')->nullable();
            $table->tinyInteger('school_id')->default(0)->unsigned()->comment('1 => Yes, 0 => No');
            $table->tinyInteger('student_id')->default(0)->unsigned()->comment('1 => Yes, 0 => No');
            $table->tinyInteger('admin')->default(0)->unsigned()->comment('1 => Yes, 0 => No');
            $table->tinyInteger('activated')->default(1)->unsigned()->comment('1 => Yes, 0 => No');
            $table->tinyInteger('sequence')->default(0)->unique();
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
        Schema::dropIfExists('email_templates');
    }
}
