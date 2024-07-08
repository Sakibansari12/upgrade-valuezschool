<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_payments', function (Blueprint $table) {
            $table->id();
            $table->string('orderid');
            $table->double('payment_amount', 15, 2)->default(0.00);
            $table->text('email')->nullable();
            $table->string('payment_url')->nullable();
            $table->string('otp')->nullable();
            $table->string('payment_link_id')->nullable();
            $table->dateTimeTz('link_created_at');
            $table->string('phone_number');
            $table->integer('payment_status')->default(0);
            $table->integer('chat_gpt_status')->default(0);
            $table->integer('dally_status')->default(0);
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
        Schema::dropIfExists('test_payments');
    }
}
