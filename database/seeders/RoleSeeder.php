<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'agency_owner']);
        Role::create(['name' => 'agency_staff']);
        Role::create(['name' => 'company_owner']);
        Role::create(['name' => 'company_staff']);
        Role::create(['name' => 'facility_staff']);
        $user = User::find(1);
        $user->assignRole('admin');
    }
}
