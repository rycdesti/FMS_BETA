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
            'bank_account_id' => 1,
            'date_deposit' => date('Y-m-d', strtotime(now())),
            'time_deposit' => date('H:i:s', strtotime(now())),
            'ref_no' => 'REF' . rand(1000, 5000),
            'cash_deposit' => mt_rand(1000, 500000),
            'logs' => 'Created by: Test',
            'last_modified' => date('Y-m-d', strtotime(now())),
        ]);
    }
}
