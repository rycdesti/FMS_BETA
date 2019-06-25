<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // financial
        $this->call(AccountCategorySeeder::class);
        $this->call(ChartOfAccountSeeder::class);

        // requisition
        $this->call(CurrencySeeder::class);
        $this->call(SupplierClassificationSeeder::class);
        $this->call(SupplierSeeder::class);
        $this->call(SupplierContactSeeder::class);

        // ap
        $this->call(BranchSeeder::class);
        $this->call(BankSeeder::class);
        $this->call(BankAccountSeeder::class);
        $this->call(CheckSeeder::class);
//        $this->call(BankDepositSeeder::class);
    }
}
