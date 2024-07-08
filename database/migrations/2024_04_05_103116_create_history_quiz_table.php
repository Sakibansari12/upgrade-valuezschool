<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryQuizTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_quiz', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->string('selectedOption');
            $table->string('answer_checkbox');
            $table->json('answers')->nullable();
            $table->integer('user_id')->default(0);
            $table->integer('question_id')->default(0);
            $table->integer('quiz_title_id')->default(0);
            $table->boolean('status')->default(0);
            $table->softDeletesTz();
            $table->index('deleted_at');
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
        Schema::dropIfExists('history_quiz');
    }
}
