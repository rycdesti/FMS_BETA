<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateApwithholdingTaxesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ap.withholding_taxes', function(Blueprint $table)
		{
            $table->bigInteger('id', true);
            $table->string('description', 60);
            $table->bigInteger('tax');
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
		Schema::drop('ap.withholding_taxes');
	}

}
