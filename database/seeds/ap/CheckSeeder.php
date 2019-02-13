<?php

use Illuminate\Database\Seeder;

class CheckSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $check_from = '10100';
        $check_to = '10500';
        $created_at = now();
        $updated_at = now();
        for ($check_no = $check_from; $check_no <= $check_to; $check_no++) {
            \App\Models\Ap\Check::create([
                'bank_account_id' => 1,
                'check_from' => $check_from,
                'check_to' => $check_to,
                'check_no' => $check_no,
                'logs' => 'Created by: Test',
                'created_at' => $created_at,
                'updated_at' => $updated_at,
            ]);
        }
    }
}
