<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateApbanksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ap.banks', function(Blueprint $table)
		{
            $table->bigInteger('id', true);
			$table->string('bank_code', 10);
			$table->string('bank_name', 70);
            $table->string('bank_prefix', 20)->nullable();
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
		Schema::drop('ap.banks');
	}

}
