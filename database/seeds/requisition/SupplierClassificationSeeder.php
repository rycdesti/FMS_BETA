<?php

use Illuminate\Database\Seeder;

class SupplierClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Requisition\SupplierClassification::create([
            'classification_code' => 'TC1111111111',
            'description' => 'Test Classification 1',
            'logs' => 'Created by: Test'
        ]);
        \App\Models\Requisition\SupplierClassification::create([
            'classification_code' => 'TC2222222222',
            'description' => 'Test Classification 2',
            'logs' => 'Created by: Test'
        ]);
        \App\Models\Requisition\SupplierClassification::create([
            'classification_code' => 'TC3333333333',
            'description' => 'Test Classification 3',
            'logs' => 'Created by: Test'
        ]);
    }
}
