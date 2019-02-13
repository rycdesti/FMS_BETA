<?php

use Illuminate\Database\Seeder;

class SupplierContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Requisition\SupplierContact::create([
            'supplier_id' => '1',
            'contact_person' => 'Royce Christoffer Taeza',
            'phone_number1' => '09568418780',
            'fax_number' => '19237891',
            'logs' => 'Created by: Test'
        ]);
        \App\Models\Requisition\SupplierContact::create([
            'supplier_id' => '2',
            'contact_person' => 'Paolo Uriel Enriquez',
            'phone_number1' => '09568418780',
            'phone_number2' => '09812763816',
            'fax_number' => '827354672',
            'logs' => 'Created by: Test'
        ]);
        \App\Models\Requisition\SupplierContact::create([
            'supplier_id' => '3',
            'contact_person' => 'Kaiser Torevillas',
            'phone_number1' => '09568418780',
            'phone_number2' => '09279387198',
            'phone_number3' => '09781263711',
            'fax_number' => '9938465783',
            'logs' => 'Created by: Test'
        ]);
    }
}
