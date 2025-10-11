<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\companies;


class CompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
             companies::insert([
    [
        'name' => 'Head Office',
    ],
    [
        'name' => 'Store 1',
    ]
]);
    }
}
