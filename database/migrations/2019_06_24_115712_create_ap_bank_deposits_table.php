<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApBankDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ap.bank_deposits', function(Blueprint $table)
        {
            $table->bigInteger('id', true);
            $table->bigInteger('bank_account_id');
            $table->date('date_deposit')->nullable();
            $table->time('time_deposit')->nullable();
            $table->string('ref_no');
            $table->double('cash_deposit', 2);
            $table->string('logs', 60)->nullable();
            $table->string('last_modified', 60)->nullable();
            $table->timestamps();

            $table->foreign('bank_account_id')
                ->references('id')
                ->on('ap.bank_accounts');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ap.bank_deposits');
    }
}
