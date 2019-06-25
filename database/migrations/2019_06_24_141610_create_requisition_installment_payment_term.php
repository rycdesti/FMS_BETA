<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequisitionInstallmentPaymentTerm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('requisition.installment_payment_terms', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('purchase_order_id');
            $table->string('ponumber,' , 20);
            $table->string('payment_term_description,' , 250);
            $table->integer('percent' );
            $table->double('amount' , 18 ,2);
            $table->string('logs',70);
            $table->string('last_modified',70)->nullable();
            $table->timestamps();

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
        Schema::drop('requisition.installment_payment_terms');
    }
}
