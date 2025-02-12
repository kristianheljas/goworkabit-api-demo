<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'Example user',
            'email' => 'user@example.com'
        ]);

        User::factory()->company()->create([
            'name' => 'Example company',
            'email' => 'company@example.com'
        ]);
    }
}
