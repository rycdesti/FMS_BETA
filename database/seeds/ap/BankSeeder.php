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
            'bank_code' => 'BDO' . date('ymdHis'),
            'bank_name' => 'Banco de Oro',
            'bank_prefix' => 'BDO',
            'logs' => 'Created by: Test'
        ]);
        \App\Models\Ap\Bank::create([
            'bank_code' => 'BPI' . date('ymdHis'),
            'bank_name' => 'Bank of the Philippine Island',
            'bank_prefix' => 'BPI',
            'logs' => 'Created by: Test'
        ]);
        \App\Models\Ap\Bank::create([
            'bank_code' => 'CBB' . date('ymdHis'),
            'bank_name' => 'China Bank',
            'bank_prefix' => 'CBB',
            'logs' => 'Created by: Test'
        ]);
    }
}
