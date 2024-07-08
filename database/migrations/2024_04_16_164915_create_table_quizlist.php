<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableQuizlist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quizlist', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('question_text')->nullable();
            $table->text('question_audurl')->nullable();
            $table->longText('question_title')->nullable();
            $table->text('question_url')->nullable();
            $table->text('question_mcq_opt')->nullable();
            $table->text('question_image')->nullable();
            $table->string('crct_answer')->nullable();
            $table->text('crct_feedback')->nullable();
            $table->text('incrct_feedback')->nullable();
            $table->text('quiz_title_id')->nullable();
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
        Schema::dropIfExists('quizlist');
    }
}
