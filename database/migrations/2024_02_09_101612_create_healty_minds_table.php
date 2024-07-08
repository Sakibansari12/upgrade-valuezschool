<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHealtyMindsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('healty_minds', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('video_url');
            $table->longText('red_guidance_desc')->nullable();
            $table->text('healty_mind_image')->nullable();
            $table->text('healty_mind_file')->nullable();
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('healty_minds');
    }
}
