<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CretaeSchoolsPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schools_payments', function (Blueprint $table) {
            $table->id();
            $table->string('orderid', 100)->unique(); //orderid
            $table->double('payment_amount', 15, 2)->default(0.00);
            $table->text('description')->nullable();
            $table->text('email_template')->nullable();
            $table->integer('school_id')->default(0); //foreign_key
            $table->integer('payment_status')->default(0);
            $table->string('payment_url')->nullable();
            $table->string('school_name_billing')->nullable();
            $table->string('payment_link_id')->nullable();
            $table->text('upload_invoice')->nullable();
            $table->dateTimeTz('link_expiry_at'); //link_expiry_at    
            $table->dateTimeTz('link_created_at');
            $table->dateTimeTz('link_updated_at')->nullable(); 
            $table->dateTimeTz('payment_made_at')->nullable(); 
            $table->dateTimeTz('email_sent_at')->nullable(); 
            $table->dateTimeTz('sms_sent_at')->nullable();
            $table->json('email_sent')->nullable(); 
            $table->json('phone_number_sent')->nullable(); 
            $table->json('milestone')->nullable(); 
            $table->string('email');
            $table->string('phone_number');
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
        Schema::dropIfExists('schools_payments');
    }
}
