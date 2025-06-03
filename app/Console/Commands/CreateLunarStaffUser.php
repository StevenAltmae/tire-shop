<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateLunarStaffUser extends Command
{
    protected $signature = 'make:lunar-admin {email} {first_name} {last_name} {password}';
    protected $description = 'Create a Lunar admin staff user';

    public function handle()
    {
        $email = $this->argument('email');
        $first_name = $this->argument('first_name');
        $last_name = $this->argument('last_name');
        $password = $this->argument('password');

        if (DB::table('lunar_staff')->where('email', $email)->exists()) {
            $this->error('Staff user already exists!');
            return 1;
        }

        DB::table('lunar_staff')->insert([
            'admin' => 1,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'password' => Hash::make($password),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->info('Lunar admin staff user created: ' . $email);
        return 0;
    }
} 