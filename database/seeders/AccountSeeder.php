<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = array(
            [
                'name' => 'Daw Kyi',
                'bank_type_id' => 1,
            ],
            [
                'name' => 'Daw Mal Thein',
                'bank_type_id' => 1,
            ],
            [
                'name' => 'U Tin Ko Win',
                'bank_type_id' => 1,
            ],
            [
                'name' => 'Zin Hlaing Htoo',
                'bank_type_id' => 1,
            ],
            [
                'name' => 'Daw Nyunt',
                'bank_type_id' => 1,
            ],
            [
                'name' => 'U Tin Soe',
                'bank_type_id' => 1,
            ],
            [
                'name' => 'Daw Ni Ni Win',
                'bank_type_id' => 1,
            ],
            [
                'name' => 'Thin Thin Soe',
                'bank_type_id' => 1,
            ],
            [
                'name' => 'Hnin Hnin Soe',
                'bank_type_id' => 1,
            ],
            [
                'name' => 'Khant Nyar Aung',
                'bank_type_id' => 1,
            ],
            [
                'name' => 'MibaGoneYe 1',
                'bank_type_id' => 2,
            ],
            [
                'name' => 'MibaGoneYe 2',
                'bank_type_id' => 2,
            ],
            [
                'name' => 'ANCT 1',
                'bank_type_id' => 2,
            ],
            [
                'name' => 'ANCT 2',
                'bank_type_id' => 2,
            ],
            [
                'name' => 'ANCT 3',
                'bank_type_id' => 2,
            ],
            [
                'name' => 'MibaGoneYe 2 RSV',
                'bank_type_id' => 2,
            ],
            [
                'name' => 'Zin Hlaing Htoo',
                'bank_type_id' => 3,
            ],
            [
                'name' => 'Hnin Hnin Soe',
                'bank_type_id' => 3,
            ],
            [
                'name' => 'Zin Hlaing Htoo',
                'bank_type_id' => 4,
            ],
            [
                'name' => 'Hnin Hnin Soe',
                'bank_type_id' => 4,
            ],
            [
                'name' => 'Khant Nyar Aung',
                'bank_type_id' => 4,
            ],
            [
                'name' => 'Pyone Pyone Myint',
                'bank_type_id' => 4,
            ],
            [
                'name' => 'Hnin Hnin Soe',
                'bank_type_id' => 5,
            ],
            [
                'name' => 'Khant Nyar Aung',
                'bank_type_id' => 5,
            ],
            [
                'name' => 'Zin Hlaing Htoo',
                'bank_type_id' => 6,
            ],
            [
                'name' => 'Zin Hlaing Htoo',
                'bank_type_id' => 7,
            ],
            [
                'name' => 'Khant Nyar Aung',
                'bank_type_id' => 7,
            ],
            [
                'name' => 'Hnin Hnin Soe',
                'bank_type_id' => 6,
            ],
            [
                'name' => 'Hnin Hnin Soe',
                'bank_type_id' => 7,
            ],
            [
                'name' => 'Zin Hlaing Htoo',
                'bank_type_id' => 8,
            ],
            [
                'name' => 'Hnin Hnin Soe',
                'bank_type_id' => 8,
            ],
            [
                'name' => 'Zin Hlaing Htoo',
                'bank_type_id' => 9,
            ],
            [
                'name' => 'Hnin Hnin Soe',
                'bank_type_id' => 9,
            ],
            [
                'name' => 'Khaing Moe Win',
                'bank_type_id' => 10,
            ],
            [
                'name' => 'Zin Hlaing Htoo',
                'bank_type_id' => 11,
            ],
            [
                'name' => 'Hnin Hnin Soe',
                'bank_type_id' => 11,
            ],
            [
                'name' => 'ZHH  Flexi',
                'bank_type_id' => 17,
            ],
            [
                'name' => 'ZHH Flexi Everyday',
                'bank_type_id' => 17,
            ],
            [
                'name' => 'HHS Flexi Everyday',
                'bank_type_id' => 17,
            ],
            [
                'name' => 'Zin Hlaing Htoo',
                'bank_type_id' => 18,
            ],
            [
                'name' => 'Zin Hlaing Htoo',
                'bank_type_id' => 19,
            ],
            [
                'name' => 'Hnin Hnin Soe',
                'bank_type_id' => 18,
            ],
            [
                'name' => 'Hnin Hnin Soe',
                'bank_type_id' => 19,
            ],
        );

        foreach ($data as $key => $account) Account::create($account);
    }
}
