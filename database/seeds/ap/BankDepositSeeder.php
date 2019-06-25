<?php

use Illuminate\Database\Seeder;

class BankDepositSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Ap\BankDeposit::create([
            'bank_account_id' => \App\Models\Ap\BankAccount::first(),
            'date_deposit' => date('Y-m-d', now()),
            'time_deposit' => date('H:i:s', now()),
            'ref_no' => rand(1000, 5000),
            'cash_deposit' => mt_rand(1000, 500000),
            'logs' => 'Created by: Test',
            'last_modified' => date('Y-m-d', now()),
        ]);
    }
}
