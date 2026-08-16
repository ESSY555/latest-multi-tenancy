<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = [
            [
                'name' => 'HR',
                'code' => 'HR',
                'address' => '123 Education Street',
                'city' => 'City Center',
                'state' => 'State',
                'country' => 'Country',
                'phone' => '+1 (555) 123-4567',
            ],
        ];

        foreach ($branches as $branchData) {
            Branch::create($branchData);
        }
    }
}
