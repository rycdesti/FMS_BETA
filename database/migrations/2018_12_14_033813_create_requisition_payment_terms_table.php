<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRequisitionpaymentTermsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('requisition.payment_terms', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->string('payment_term_name', 500);
			$table->string('percentage', 1000);
            $table->char('disabled', 1)->default('N');
            $table->timestamp('date_disabled')->nullable();
            $table->string('disabled_by', 60)->nullable();
            $table->string('logs', 60)->nullable();
            $table->string('last_modified', 60)->nullable();
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
		Schema::drop('requisition.payment_terms');
	}

}
