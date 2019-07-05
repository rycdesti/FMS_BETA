<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApCheckDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ap.check_deposits', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('bank_deposit_id');
            $table->bigInteger('bank_id');
            $table->string('check_no', 15);
            $table->double('amount');
            $table->string('logs', 60)->nullable();
            $table->string('last_modified', 60)->nullable();
            $table->timestamps();

            $table->foreign('bank_deposit_id')
                ->references('id')
                ->on('ap.bank_deposits');

            $table->foreign('bank_id')
                ->references('id')
                ->on('ap.banks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ap.check_deposits');
    }
}
