<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateApbankAccountsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ap.bank_accounts', function(Blueprint $table)
		{
            $table->bigInteger('id', true);
			$table->bigInteger('bank_id');
			$table->string('bank_address', 70);
			$table->string('acct_code', 20);
			$table->string('acct_no', 20);
			$table->char('acct_type', 1);
			$table->string('currency', 10)->nullable();
			$table->decimal('beginning_balance', 18)->nullable();
			$table->date('as_of')->nullable();
			$table->char('disabled', 1)->default('N');
			$table->timestamp('date_disabled')->nullable();
			$table->string('disabled_by', 60)->nullable();
			$table->string('logs', 60)->nullable();
			$table->string('last_modified', 60)->nullable();
			$table->timestamps();

            $table->foreign('bank_id')
                ->references('id')
                ->on('ap.banks')
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
		Schema::drop('ap.bank_accounts');
	}

}
