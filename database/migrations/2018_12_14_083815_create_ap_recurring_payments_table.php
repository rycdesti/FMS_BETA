<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAprecurringPaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ap.recurring_payments', function(Blueprint $table)
		{
            $table->bigInteger('id', true);
			$table->string('supplier_id', 15)->nullable();
			$table->string('document_no', 30)->nullable();
			$table->dateTime('duration_from')->nullable();
			$table->dateTime('duration_to')->nullable();
			$table->char('is_duration', 1)->nullable();
			$table->char('frequency', 1)->nullable();
			$table->string('remarks', 150)->nullable();
			$table->decimal('amount', 18)->nullable();
			$table->char('disabled', 1)->default('N')->nullable();
			$table->timestamp('date_disabled')->nullable();
			$table->string('disabled_by', 20)->nullable();
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
		Schema::drop('ap.recurring_payments');
	}

}
