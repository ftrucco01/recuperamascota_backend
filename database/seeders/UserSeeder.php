<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'SUPER_ADMIN')->first();
        $admin = User::create([
            'surnames' => 'Doe',
            'name' => 'John',
            'email' => 'admin@admin.com',
            'password' => 'admin',
            'phone_number' => '+1234567890',
            'gender' => 'male',
            'address' => '123 street',
        ]);
        $admin->assignRole($adminRole);
    }
}