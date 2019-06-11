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
			$table->bigInteger('supplier_id');
            $table->bigInteger('bank_account_id');
			$table->string('document_no', 30)->nullable();
			$table->date('duration_from')->nullable();
			$table->date('duration_to')->nullable();
			$table->char('is_duration', 1)->default('N')->nullable();
			$table->char('frequency', 1)->nullable();
			$table->string('remarks', 150)->nullable();
			$table->decimal('amount', 18)->nullable();
			$table->char('disabled', 1)->default('N')->nullable();
			$table->timestamp('date_disabled')->nullable();
			$table->string('disabled_by', 20)->nullable();
			$table->string('logs', 60)->nullable();
			$table->string('last_modified', 60)->nullable();
			$table->timestamps();

            $table->foreign('supplier_id')
                ->references('id')
                ->on('requisition.suppliers')
                ->onDelete('cascade');

            $table->foreign('bank_account_id')
                ->references('id')
                ->on('ap.bank_accounts');
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
