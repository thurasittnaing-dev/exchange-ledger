<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\AccountHistory;
use App\Models\CashBalance;
use App\Models\CashHistory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CashHistory::create([
            'reference_type' => 'opening',
            'reference_id' => 1,
            'action' => 'credit',
            'amount' => 1000000,
            'running_balance' => 0,
            'description' => 'Initial cash balance',
        ]);

        $cash = CashBalance::first();
        $cash->balance = 1000000;
        $cash->save();

        AccountHistory::create([
            'account_id' => 1,
            'reference_type' => 'manual_add',
            'reference_id' => null,
            'action' => 'credit',
            'amount' => 1000000,
            'running_balance' => 0,
            'description' => 'Initial account balance',
        ]);

        $account = Account::first();
        $account->balance = 1000000;
        $account->save();
    }
}
