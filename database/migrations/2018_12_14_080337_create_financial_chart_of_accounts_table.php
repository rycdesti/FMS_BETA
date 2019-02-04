<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFinancialchartOfAccountsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('financial.chart_of_accounts', function(Blueprint $table)
		{
            $table->bigInteger('id', true);
            $table->bigInteger('account_category_id');
			$table->string('acct_code', 20);
			$table->string('description', 150)->nullable();
			$table->char('posting_type', 1);
			$table->string('typical_balance', 1)->nullable();
            $table->char('disabled', 1)->default('N');
            $table->timestamp('date_disabled')->nullable();
            $table->string('disabled_by', 60)->nullable();
            $table->string('logs', 60)->nullable();
            $table->string('last_modified', 60)->nullable();
			$table->timestamps();

            $table->foreign('account_category_id')
                ->references('id')
                ->on('financial.account_categories')
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
		Schema::drop('financial.chart_of_accounts');
	}

}
