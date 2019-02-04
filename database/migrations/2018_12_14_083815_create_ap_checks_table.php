<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateApchecksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ap.checks', function(Blueprint $table)
		{
            $table->bigInteger('id', true);
            $table->bigInteger('bank_account_id');
			$table->string('check_from', 15)->nullable();
			$table->string('check_to', 15)->nullable();
			$table->string('check_no', 15)->nullable();
			$table->string('voucher_no', 20)->nullable();
            $table->char('voided', 1)->default('N');
            $table->timestamp('date_voided')->nullable();
            $table->string('voided_by', 60)->nullable();
			$table->string('logs', 60)->nullable();
			$table->string('last_modified', 60)->nullable();
            $table->string('remarks', 150)->nullable();
			$table->timestamps();

            $table->foreign('bank_account_id')
                ->references('id')
                ->on('ap.bank_accounts')
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
		Schema::drop('ap.checks');
	}

}
