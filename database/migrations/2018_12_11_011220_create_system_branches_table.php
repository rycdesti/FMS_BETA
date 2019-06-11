<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSystembranchesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dbo.branches', function(Blueprint $table)
		{
            $table->bigInteger('id', true);
			$table->string('branch_code', 5);
			$table->string('branch_name', 50);
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
		Schema::drop('dbo.branches');
	}

}
