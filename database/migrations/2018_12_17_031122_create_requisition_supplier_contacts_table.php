<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRequisitionsupplierContactsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('requisition.supplier_contacts', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
            $table->bigInteger('supplier_id');
			$table->string('contact_person', 250)->nullable();
			$table->string('phone_number1', 15)->nullable();
			$table->string('phone_number2', 15)->nullable();
			$table->string('phone_number3', 15)->nullable();
			$table->string('fax_number', 15)->nullable();
			$table->string('logs', 60)->nullable();
			$table->string('last_modified', 60)->nullable();

            $table->foreign('supplier_id')
                ->references('id')
                ->on('requisition.suppliers')
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
		Schema::drop('requisition.supplier_contacts');
	}

}
