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
            'classification_code' => 'TC' . date('ymdHis'),
            'description' => 'Test Classification 1',
            'logs' => 'Created by: Test'
        ]);
        \App\Models\Requisition\SupplierClassification::create([
            'classification_code' => 'TT' . date('ymdHis'),
            'description' => 'Test Classification 2',
            'logs' => 'Created by: Test'
        ]);
        \App\Models\Requisition\SupplierClassification::create([
            'classification_code' => 'CC' . date('ymdHis'),
            'description' => 'Test Classification 3',
            'logs' => 'Created by: Test'
        ]);
    }
}
