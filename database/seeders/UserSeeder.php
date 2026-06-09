<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Organization;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $org = Organization::first();

        $user = User::create([
            'organization_id' => $org->id,
            'name' => 'Admin User',
            'email' => 'firat_gurbuzer@yahoo.com',
            'password' => '12qw',
            'email_verified_at' => now()
        ]);

        $user->assignRole('super-admin');

        $user2 = User::create([
            'organization_id' => $org->id,
            'name' => 'Manager User',
            'email' => 'enrique.piskopat@gmail.com',
            'password' => '12qw',
            'email_verified_at' => now()
        ]);

        $user2->assignRole('manager');

        $user3 = User::create([
            'organization_id' => $org->id,
            'name' => 'Sales User',
            'email' => 'firat.gurbuzer@hotmail.com',
            'password' => '12qw',
            'email_verified_at' => now()
        ]);

        $user3->assignRole('sales');

        $user4 = User::create([
            'organization_id' => $org->id,
            'name' => 'Mary Jane',
            'email' => 'test@firatgurbuzer.com',
            'password' => '12qw',
            'email_verified_at' => now()
        ]);

        $user4->assignRole('client');
    }
}
