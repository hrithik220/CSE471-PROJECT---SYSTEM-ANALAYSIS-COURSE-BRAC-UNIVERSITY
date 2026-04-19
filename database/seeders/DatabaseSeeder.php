<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create demo users
        User::updateOrCreate(['email' => 'admin@campus.edu'], [
            'name'              => 'Admin User',
            'password'          => Hash::make('password'),
            'role'              => 'admin',
            'campus'            => 'Main Campus',
            'credibility_score' => 5.0,
        ]);

        User::updateOrCreate(['email' => 'samantha@campus.edu'], [
            'name'              => 'Samantha',
            'password'          => Hash::make('password'),
            'role'              => 'user',
            'campus'            => 'Main Campus',
            'credibility_score' => 4.5,
        ]);

        User::updateOrCreate(['email' => 'hrithik@campus.edu'], [
            'name'              => 'Hrithik',
            'password'          => Hash::make('password'),
            'role'              => 'user',
            'campus'            => 'Main Campus',
            'credibility_score' => 4.2,
        ]);

        $this->call([
            BadgeSeeder::class,
        ]);
    }
}
