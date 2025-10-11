<?php

namespace Database\Seeders;

use App\Models\branches;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //






  $accounts = [
    [
    'branch_code' => '001',
    'location' => '000',
    'branch_name' => 'Head Office',
    'company_id' => 1,
],[
    'branch_code' => '002',
    'location' => '001',
    'branch_name' => 'Store 1',
    'company_id' => 2,
]
    ];

    foreach ($accounts as $account) {
        Branches::create($account);
    }
    }
}
