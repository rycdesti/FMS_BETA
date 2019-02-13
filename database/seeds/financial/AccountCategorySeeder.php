<?php

use Illuminate\Database\Seeder;

class AccountCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Financial\AccountCategory::create(['description' => 'Debit Account', 'logs' => 'Created by: Test']);
        \App\Models\Financial\AccountCategory::create(['description' => 'Credit Account', 'logs' => 'Created by: Test']);
        \App\Models\Financial\AccountCategory::create(['description' => 'Test Account', 'logs' => 'Created by: Test']);
    }
}
