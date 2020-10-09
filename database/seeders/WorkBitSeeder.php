<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WorkBit;
use Illuminate\Database\Seeder;

class WorkBitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->company()
            ->count(5)
            ->has(WorkBit::factory()->count(mt_rand(10, 30)))
            ->create();
    }
}
