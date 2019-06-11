<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRequisitioncheckPreparationRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('requisition.check_preparation_requests', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->date('request_date')->nullable();
			$table->string('request_type', 50)->nullable();
			$table->bigInteger('po_id')->nullable();
			$table->bigInteger('supplier_id')->nullable();
            $table->string('supplier_name', 150)->nullable();
			$table->bigInteger('payment_term_id')->nullable();
			$table->string('particulars', 500)->nullable();
			$table->decimal('amount', 18)->nullable();
			$table->bigInteger('batch_code')->nullable();
			$table->string('requested_by', 60)->nullable();
			$table->char('status', 1)->nullable();
			$table->string('logs', 60)->nullable();
			$table->string('last_modified', 60)->nullable();
			$table->timestamps();

            $table->foreign('supplier_id')
                ->references('id')
                ->on('requisition.suppliers')
                ->onDelete('cascade');

            $table->foreign('payment_term_id')
                ->references('id')
                ->on('requisition.payment_terms')
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
		Schema::drop('requisition.check_preparation_requests');
	}

}
