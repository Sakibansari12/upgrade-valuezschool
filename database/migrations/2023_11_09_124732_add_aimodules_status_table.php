<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAimodulesStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aimodules', function (Blueprint $table) {
            $table->string('type')->nullable()->after('thumbnail');
           
            $table->integer('status')->default(0)->after('type');
            $table->string('grade_id')->default(0)->after('status');
            $table->integer('course_id')->default(0)->after('grade_id');
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
            $table->dropColumn('type');
            $table->dropColumn('status');
            
            $table->dropColumn('grade_id');
            $table->dropColumn('course_id');
        });
    }
}
