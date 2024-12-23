<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'username' => "adm1",
            'name' => 'Admin',
            'nim' => '0',
            'password' => Hash::make('admin'),
            'role' => 'admin',
        ]);
        
        User::create([
            'username' => "Mhs1",
            'name' => 'Mahasiswa 1',
            'nim' => '1010',
            'password' => Hash::make('mahasiswa'),
            'role' => 'student',
        ]);
        
        Schedule::create([
            'start_date' => '2024-12-01',
            'end_date' => '2024-12-15',
        ]);
    }
}
