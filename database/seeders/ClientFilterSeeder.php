<?php

namespace Database\Seeders;

use App\Enums\ClientFilterType;
use App\Models\ClientFilter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientFilterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filter = new ClientFilter(['name' => 'Allow All', 'enabled' => true]);
        $filter->save();

        $filter->items()->createMany([
            [
                'name' => 'IPv4',
                'type' => ClientFilterType::ALLOW,
                'ip_address' => '0.0.0.0/0'
            ],
            [
                'name' => 'IPv6',
                'type' => ClientFilterType::ALLOW,
                'ip_address' => '::/0'
            ]
        ]);
    }
}
