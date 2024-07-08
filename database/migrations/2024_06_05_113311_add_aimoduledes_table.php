<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAimoduledesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aimodules', function (Blueprint $table) {
            $table->text('description')->after('display_name');
            $table->text('own_description')->after('description');
            $table->text('own_placeholder')->after('own_description');
            $table->text('hello_there_description')->after('own_placeholder');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('aimodules', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('own_description');
            $table->dropColumn('own_placeholder');
            $table->dropColumn('hello_there_description');
        });
    }
}
