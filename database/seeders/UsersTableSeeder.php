<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::where('name', 'admin')->first();
        $salesRole = Role::where('name', 'sales')->first();

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password')
        ]);
        $admin->roles()->attach($adminRole);

        $sales = User::create([
            'name' => 'Sales User',
            'email' => 'sales@example.com',
            'password' => bcrypt('password')
        ]);
        $sales->roles()->attach($salesRole);
    }
}
