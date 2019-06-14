<?php

use Illuminate\Database\Seeder;

class BankAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Ap\BankAccount::create([
            'bank_id' => '1',
            'bank_address' => 'Cubao, Quezon City',
            'acct_code' => 'TESTCODE1',
            'acct_no' => '9827389211',
            'acct_type' => 'C',
            'currency_id' => '1',
            'branch_id' => '1',
            'logs' => 'Created by: Test'
        ]);
        \App\Models\Ap\BankAccount::create([
            'bank_id' => '2',
            'bank_address' => 'Sta Mesa, Quezon City',
            'acct_code' => 'TESTCODE1',
            'acct_no' => '9827389211',
            'acct_type' => 'C',
            'currency_id' => '2',
            'branch_id' => '2',
            'logs' => 'Created by: Test'
        ]);
        \App\Models\Ap\BankAccount::create([
            'bank_id' => '3',
            'bank_address' => 'Manila',
            'acct_code' => 'TESTCODE1',
            'acct_no' => '9827389211',
            'acct_type' => 'S',
            'currency_id' => '3',
            'branch_id' => '1',
            'logs' => 'Created by: Test'
        ]);
    }
}
