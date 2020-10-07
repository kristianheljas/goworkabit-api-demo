<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Laravel\Passport\ClientRepository;

class PassportClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clientRepository = new ClientRepository();

        $client = $clientRepository->createPersonalAccessClient(
            null, 'Personal Access Client Demo', 'http://localhost'
        );
        $client->secret = 'dikZM4VrAK6TKpRH72r40YoK1pvnlN7iQVqrF2F4';
        $client->save();
    }
}
