<?php

namespace Database\Seeders;

use App\Models\Chef;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ChefSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Chef::factory()->count(40)->create();
    }
}
