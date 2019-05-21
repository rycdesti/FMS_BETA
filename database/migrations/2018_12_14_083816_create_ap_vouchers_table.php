<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateApvouchersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ap.vouchers', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->string('voucher_no', 20);
			$table->bigInteger('recurring_payment_id');
			$table->date('date')->nullable();
			$table->char('document_type', 1)->nullable();
			$table->string('document_no', 30)->nullable();
			$table->string('explanation', 150)->nullable();
			$table->bigInteger('bank_account_id');
			$table->bigInteger('check_id');
			$table->date('check_date')->nullable();
			$table->decimal('amount', 18)->nullable();
			$table->char('tax_id', 1)->nullable();
			$table->date('last_updated')->nullable();
			$table->char('status', 1)->nullable();
			$table->string('prepared_by', 50)->nullable();
			$table->string('checked_by', 50)->nullable();
			$table->string('reviewed_by', 50)->nullable();
			$table->string('noted_by', 50)->nullable();
			$table->string('approved_by', 50)->nullable();
			$table->date('date_cancelled')->nullable();
			$table->string('cancelled_by', 50)->nullable();
			$table->string('logs', 60)->nullable();
			$table->string('last_modified', 60)->nullable();
            $table->timestamps();

            $table->foreign('recurring_payment_id')
                ->references('id')
                ->on('ap.recurring_payments')
                ->onDelete('cascade');

            $table->foreign('bank_account_id')
                ->references('id')
                ->on('ap.bank_accounts')
                ->onDelete('cascade');

            $table->foreign('check_id')
                ->references('id')
                ->on('ap.checks');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ap.vouchers');
	}

}
