<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequisitionPurchaseOrderSpecification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requisition.purchase_order_specification', function (Blueprint $table) {
            $table->bigInteger('id',true);
            $table->bigInteger('purchase_order_id');
            $table->string('ponumber' , 20);
            $table->string('scope_of_work' , 100);
            $table->longText('description');
            $table->string('status',1)->default('O');
            $table->string('logs',70);
            $table->string('last_modified',70)->nullable();
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
        Schema::dropIfExists('requisition.purchase_order_specification');
    }
}
