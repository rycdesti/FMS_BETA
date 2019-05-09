<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAprecurringPaymentDatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ap.recurring_payment_dates', function(Blueprint $table)
		{
            $table->bigInteger('id', true);
			$table->bigInteger('recurring_payment_id')->nullable();
			$table->integer('month')->nullable();
			$table->integer('day')->nullable();
			$table->integer('weekday')->nullable();
			$table->char('is_current', 1)->nullable();
			$table->string('frequency_type', 50)->nullable();
			$table->string('logs', 50)->nullable();
            $table->timestamps();

            $table->foreign('recurring_payment_id')
                ->references('id')
                ->on('ap.recurring_payments')
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
		Schema::drop('ap.recurring_payment_dates');
	}

}
