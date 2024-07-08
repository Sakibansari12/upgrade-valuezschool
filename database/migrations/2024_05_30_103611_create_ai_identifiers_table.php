<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAiIdentifiersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ai_identifiers', function (Blueprint $table) {
            $table->id();
            $table->string('display_name');
            $table->string('module_page_title');
            $table->string('module_page_overview');
            $table->string('type');
            $table->string('video_url');
            $table->string('thumbnail');
            $table->string('grade_id')->default(0);
            $table->integer('course_id')->default(0);
            $table->integer('status')->default(0);
            $table->integer('aimodule_status')->default(0);
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
        Schema::dropIfExists('ai_identifiers');
    }
}
