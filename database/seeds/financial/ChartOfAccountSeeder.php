<?php

use Illuminate\Database\Seeder;

class ChartOfAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Financial\ChartOfAccount::create([
            'account_category_id' => 1,
            'acct_code' => 'TEST1',
            'description' => 'Test Chart 1',
            'posting_type' => ['B', 'P'][rand(0, 1)],
            'typical_balance' => ['C', 'D'][rand(0, 1)],
            'logs' => 'Created by: Test'
        ]);
        \App\Models\Financial\ChartOfAccount::create([
            'account_category_id' => 2,
            'acct_code' => 'TEST2',
            'description' => 'Test Chart 2',
            'posting_type' => ['B', 'P'][rand(0, 1)],
            'typical_balance' => ['C', 'D'][rand(0, 1)],
            'logs' => 'Created by: Test'
        ]);
        \App\Models\Financial\ChartOfAccount::create([
            'account_category_id' => 3,
            'acct_code' => 'TEST3',
            'description' => 'Test Chart 3',
            'posting_type' => ['B', 'P'][rand(0, 1)],
            'typical_balance' => ['C', 'D'][rand(0, 1)],
            'logs' => 'Created by: Test'
        ]);
    }
}
