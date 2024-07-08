<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CretaeAppSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type')->default(1)->comment('');
            $table->string('field');
            $table->text('value')->nullable();
            $table->bigInteger('entity')->default(0)->unsigned();
            $table->tinyInteger('autoload')->default(0);
            $table->index('type');
            $table->index('field');
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
        Schema::dropIfExists('app_settings');
    }
}
