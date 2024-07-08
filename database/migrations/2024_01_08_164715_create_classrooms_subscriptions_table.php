<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassroomsSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classrooms_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->json('classrooms_subscription')->nullable();
            $table->integer('school_id')->default(0);
            $table->integer('school_admin_id')->default(0);
            $table->integer('subscription_status')->default(0);
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
        Schema::dropIfExists('classrooms_subscriptions');
    }
}
