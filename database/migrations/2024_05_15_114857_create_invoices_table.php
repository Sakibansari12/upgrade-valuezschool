<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number');
            $table->date('invoice_date')->nullable();
            $table->text('address');
            $table->string('hsn_code');
            $table->double('cgst')->nullable();
            $table->double('sgst')->nullable();
            $table->double('igst')->nullable();
            $table->text('description');
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('invoices');
    }
}
