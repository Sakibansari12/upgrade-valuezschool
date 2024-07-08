<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_report', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->integer('quiz_title_id')->default(0);
            $table->integer('total_attempt')->default(0);
            $table->integer('wrng_attempt')->default(0);
            $table->integer('right_attempt')->default(0);
            $table->string('start_time')->default(0);
            $table->timestamp('end_time')->useCurrent();
            $table->string('quiz_summary')->default(0);
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('quiz_report');
    }
}
