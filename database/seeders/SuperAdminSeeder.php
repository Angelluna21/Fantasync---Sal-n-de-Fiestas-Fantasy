<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@fantasync.com'],
            [
                'name' => 'Super Administrador',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'superadmin',
            ]
        );
    }
}
