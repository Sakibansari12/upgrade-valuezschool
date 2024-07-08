<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoPlayReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_play_reports', function (Blueprint $table) {
                $table->id();
                $table->integer('lesson_plan')->default(0);
                $table->integer('user_id')->default(0);
                $table->integer('school_id')->default(0);
                $table->integer('class_id')->default(0);
                $table->integer('video_play_status')->default(0);
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
        Schema::dropIfExists('video_play_reports');
    }
}
