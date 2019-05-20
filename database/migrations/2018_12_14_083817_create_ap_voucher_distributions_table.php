<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateApvoucherDistributionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ap.voucher_distributions', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('voucher_id');
			$table->bigInteger('chart_of_account_id');
			$table->char('typical_balance', 1)->nullable();
			$table->decimal('amount', 18)->nullable();
			$table->string('logs', 60)->nullable();
			$table->string('last_modified', 60)->nullable();
            $table->timestamps();

            $table->foreign('voucher_id')
                ->references('id')
                ->on('ap.vouchers')
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
		Schema::drop('ap.voucher_distributions');
	}

}
