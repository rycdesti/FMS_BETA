<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstallmentPaymentTerms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requisition.installment_payment_terms', function (Blueprint $table) {
            $table->bigInteger('purchase_order_id');
            $table->foreign('purchase_order_id')
                ->references('id')
                ->on('requisition.purchase_order')
                ->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requisition.installment_payment_terms');
    }
}
