<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRequisitionsuppliersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('requisition.suppliers', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
            $table->bigInteger('supplier_classification_id');
            $table->bigInteger('currency_id')->nullable();
            $table->string('tin',15);
			$table->string('supplier_code', 15);
			$table->string('name', 65);
			$table->string('check_name', 65);
			$table->string('address');
			$table->string('city', 150);
			$table->string('state', 150);
			$table->string('zip_code', 10);
			$table->string('country', 150);
            $table->char('disabled', 1)->default('N');
            $table->timestamp('date_disabled')->nullable();
            $table->string('disabled_by', 60)->nullable();
			$table->string('logs', 60)->nullable();
            $table->string('last_modified', 60)->nullable();
            $table->timestamps();

            $table->foreign('supplier_classification_id')
                ->references('id')
                ->on('requisition.supplier_classifications')
                ->onDelete('cascade');

            $table->foreign('currency_id')
                ->references('id')
                ->on('requisition.currencies')
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
		Schema::drop('requisition.suppliers');
	}

}
