<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // tambahkan user admin
        User::updateOrCreate([
            'username' => 'admin'
        ], [
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin'
        ]);

        // tambahkan user rt
        User::updateOrCreate([
            'username' => 'rt'
        ], [
            'name' => 'RT',
            'email' => 'rt@rt.com',
            'password' => Hash::make('12345678'),
            'role' => 'rt'
        ]);

        // tambahkan user psm
        User::updateOrCreate([
            'username' => 'psm'
        ], [
            'name' => 'PSM',
            'email' => 'psm@psm.com',
            'password' => Hash::make('12345678'),
            'role' => 'psm'
        ]);

        // tambahkan user kelurahan
        User::updateOrCreate([
            'username' => 'kelurahan'
        ], [
            'name' => 'Kelurahan',
            'email' => 'kelurahan@kelurahan.com',
            'password' => Hash::make('12345678'),
            'role' => 'kelurahan'
        ]);
    }
}
