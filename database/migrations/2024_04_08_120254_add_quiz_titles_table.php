<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuizTitlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quiz_titles', function (Blueprint $table) {
            $table->string('quiz_title_grade')->default(0)->after('quiz_title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quiz_titles', function (Blueprint $table) {
            $table->dropColumn('quiz_title_grade');
        });
    }
}
