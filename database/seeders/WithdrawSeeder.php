<?php

namespace Database\Seeders;

use App\Models\Withdraw;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WithdrawSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Withdraw::factory()->count(40)->create();
    }
}
