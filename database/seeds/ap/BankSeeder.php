<?php

use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Ap\Bank::create([
            'bank_code' => 'BDO987123811',
            'bank_name' => 'Banco de Oro',
            'bank_prefix' => 'BDO',
            'logs' => 'Created by: Test'
        ]);
        \App\Models\Ap\Bank::create([
            'bank_code' => 'BPI867327154',
            'bank_name' => 'Bank of the Philippine Island',
            'bank_prefix' => 'BPI',
            'logs' => 'Created by: Test'
        ]);
        \App\Models\Ap\Bank::create([
            'bank_code' => 'CBB987638217',
            'bank_name' => 'China Bank',
            'bank_prefix' => 'CBB',
            'logs' => 'Created by: Test'
        ]);
    }
}
