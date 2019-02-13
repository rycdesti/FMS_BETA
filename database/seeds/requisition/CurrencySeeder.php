<?php

use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Requisition\Currency::create([
            'description' => 'Philippine Peso',
            'currency_code' => 'PHP',
            'symbol' => 'P',
            'logs' => 'Created by: Test'
        ]);
        \App\Models\Requisition\Currency::create([
            'description' => 'US Dollar',
            'currency_code' => 'USD',
            'symbol' => '$',
            'logs' => 'Created by: Test'
        ]);
        \App\Models\Requisition\Currency::create([
            'description' => 'Euro',
            'currency_code' => 'EU',
            'symbol' => '€',
            'logs' => 'Created by: Test'
        ]);
    }
}
