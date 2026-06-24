<?php

namespace Database\Seeders;

use App\Models\BankType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BankTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = array(
            [
                'name' => 'KBZPay',
                'provider' => 'kbz',
            ],
            [
                'name' => 'KBZ Agent',
                'provider' => 'kbz',
            ],
            [
                'name' => 'KBZ Banking (Saving)',
                'provider' => 'kbz',
            ],
            [
                'name' => 'KBZ Banking (Special)',
                'provider' => 'kbz',
            ],
            [
                'name' => 'AYA Pay',
                'provider' => 'aya',
            ],
            [
                'name' => 'AYA Banking (Saving)',
                'provider' => 'aya',
            ],
            [
                'name' => 'AYA Banking (Special)',
                'provider' => 'aya',
            ],
            [
                'name' => 'AYA Agent',
                'provider' => 'aya',
            ],
            [
                'name' => 'Wave Money Agent',
                'provider' => 'wavemoney',
            ],
            [
                'name' => 'Wave Pay',
                'provider' => 'wavemoney',
            ],
            [
                'name' => 'True Money',
                'provider' => 'truemoney',
            ],
            [
                'name' => 'UAB',
                'provider' => 'uab',
            ],
            [
                'name' => 'UAB Wallets',
                'provider' => 'uab',
            ],
            [
                'name' => 'UAB Deposits',
                'provider' => 'uab',
            ],
            [
                'name' => 'OK Dollar',
                'provider' => 'okdollar',
            ],
            [
                'name' => 'Trusty',
                'provider' => 'trusty',
            ],
            [
                'name' => 'Yoma Banking',
                'provider' => 'yoma',
            ],
            [
                'name' => 'CB Banking (Saving)',
                'provider' => 'cb',
            ],
            [
                'name' => 'CB Banking (Special)',
                'provider' => 'cb',
            ],
            [
                'name' => 'MAB Banking (Saving)',
                'provider' => 'mab',
            ],
            [
                'name' => 'MAB Banking (Special)',
                'provider' => 'mab',
            ],
            [
                'name' => 'A Bank',
                'provider' => 'abank',
            ],
            [
                'name' => 'A Plus',
                'provider' => 'aplus',
            ],
            [
                'name' => 'Shwe Banking',
                'provider' => 'shwebanking',
            ],
            [
                'name' => 'MTB Banking',
                'provider' => 'mtb',
            ],
            [
                'name' => 'MCB Banking',
                'provider' => 'mcb',
            ],
        );

        foreach ($data as $service) BankType::create($service);
    }
}
