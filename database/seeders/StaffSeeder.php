<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        // test staff accounts
        $roles = ['admin', 'registrar', 'cashier'];
        
        foreach ($roles as $role) {
            User::create([
                'first_name' => 'System',
                'last_name'  => ucfirst($role),
                'email'      => "{$role}@gscab.edu",
                'password'   => Hash::make('password'),
                'role'       => $role,
            ]);
            
            // dummy staffs
            User::factory()->count(5)->create(['role' => $role]);
        }
    }
}