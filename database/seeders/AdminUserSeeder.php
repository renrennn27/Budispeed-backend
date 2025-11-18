<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::where('email', 'budispeed@gmail.com')->delete();

        User::create([
            'name' => 'Admin BudiSpeed',
            'email' => 'budispeed@gmail.com',
            'password' => Hash::make('budispeed12345'),
        ]);

    }
}
