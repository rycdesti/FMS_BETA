<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAprecurringPaymentDistributionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ap.recurring_payment_distributions', function(Blueprint $table)
		{
            $table->bigInteger('id', true);
			$table->bigInteger('recurring_payment_id');
			$table->bigInteger('chart_of_account_id');
			$table->char('typical_balance', 1)->nullable();
			$table->decimal('amount', 18, 1)->nullable();
			$table->string('logs', 60)->nullable();
			$table->string('last_modified', 60)->nullable();
			$table->unique(['recurring_payment_id','chart_of_account_id']);
			$table->timestamps();

            $table->foreign('recurring_payment_id')
                ->references('id')
                ->on('ap.recurring_payments')
                ->onDelete('cascade');

            $table->foreign('chart_of_account_id')
                ->references('id')
                ->on('financial.chart_of_accounts')
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
		Schema::drop('ap.recurring_payment_distributions');
	}

}
