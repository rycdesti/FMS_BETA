<?php

use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Requisition\Supplier::create([
            'supplier_classification_id' => '1',
            'currency_id' => '1',
            'tin' => '432128876000',
            'supplier_code' => 'PTI' . date('ymdHis'),
            'name' => 'Pamco Trade Industry',
            'check_name' => 'Pamco Trade Industry',
            'address' => 'Sta Mesa',
            'city' => 'Quezon City',
            'state' => 'Metro Manila',
            'zip_code' => '2020',
            'country' => 'Philippines',
            'logs' => 'Created by: Test'
        ]);
        \App\Models\Requisition\Supplier::create([
            'supplier_classification_id' => '2',
            'currency_id' => '2',
            'tin' => '747432911000',
            'supplier_code' => 'SSS' . date('ymdHis'),
            'name' => 'Social Security System',
            'check_name' => 'Social Security System',
            'address' => 'Farmers Cubao',
            'city' => 'Quezon City',
            'state' => 'Metro Manila',
            'zip_code' => '2020',
            'country' => 'Philippines',
            'logs' => 'Created by: Test'
        ]);
        \App\Models\Requisition\Supplier::create([
            'supplier_classification_id' => '3',
            'currency_id' => '3',
            'tin' => '666544782000',
            'supplier_code' => 'TIP' . date('ymdHis'),
            'name' => 'Technological Institute of the Philippines',
            'check_name' => 'Technological Institute of the Philippines',
            'address' => '938 Aurora Blvd',
            'city' => 'Quezon City',
            'state' => 'Metro Manila',
            'zip_code' => '2020',
            'country' => 'Philippines',
            'logs' => 'Created by: Test'
        ]);
    }
}
