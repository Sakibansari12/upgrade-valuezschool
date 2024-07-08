<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuizchnageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->string('question_text')->after('title');
            $table->string('question_url')->after('question_text');
            $table->string('question_image')->after('question_url');
            $table->string('feedback')->after('question_image');
            $table->integer('quiz_title_id')->default(0)->after('feedback');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('question_text');
            $table->dropColumn('question_url');
            $table->dropColumn('question_image');
            $table->dropColumn('feedback');
            $table->dropColumn('quiz_title_id');
        });
    }
}
