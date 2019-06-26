<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequisitionPurchaseOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requisition.purchase_order', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('campus' , 5);
            $table->bigInteger('branch_id');
            $table->string('ponumber' , 20);
            $table->dateTime('podate');
            $table->longText('ponotes');
            $table->bigInteger('supplier_id');
            $table->bigInteger('currency_id')->nullable();
            $table->string('purchased_by' , 20);
            $table->dateTime('promised_date');
            $table->string('shiptoaddressid' , 20);
            $table->string('approved_by' ,20)->nullable();
            $table->dateTime('date_approved')->nullable();
            $table->string('is_approved' , 1)->default('N');
            $table->string('status' , 1)->default('O');
            $table->double('amount' , 18 ,2)->nullable();
            $table->string('logs',70);
            $table->string('last_modified',70)->nullable();
            $table->timestamps();

            $table->foreign('supplier_id')
                ->references('id')
                ->on('requisition.suppliers')
                ->onDelete('cascade');

            $table->foreign('currency_id')
                ->references('id')
                ->on('requisition.currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('requisition.purchase_order');
    }
}
