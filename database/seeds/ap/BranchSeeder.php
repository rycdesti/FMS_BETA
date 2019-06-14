<?php

use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\System\Branch::create([
            'branch_code' => 'QC',
            'branch_name' => 'Quezon City',
            'logs' => 'Created by: Test'
        ]);
        \App\Models\System\Branch::create([
            'branch_code' => 'M',
            'branch_name' => 'Manila',
            'logs' => 'Created by: Test'
        ]);
    }
}
