<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class OwnerUserSeeder extends Seeder
{
    public function run()
    {
        // Update or create the owner user
        User::updateOrCreate(
            ['email' => 'balqish@gmail.com'], // find by email
            [
                'name' => 'balqish',
                'role' => 'owner',
                'password' => Hash::make('123456789'), // set password
            ]
        );

        $this->command->info('Owner user has been created/updated successfully!');
    }
}
