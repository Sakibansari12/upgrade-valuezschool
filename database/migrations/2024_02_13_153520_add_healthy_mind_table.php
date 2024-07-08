<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHealthyMindTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('healty_minds', function (Blueprint $table) {
            $table->json('healty_mind_upload_file')->nullable()->after('red_guidance_desc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('healty_minds', function (Blueprint $table) {
            $table->dropColumn('healty_mind_upload_file');
        });
    }
}
