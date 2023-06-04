<?php

namespace Database\Seeders;

use App\Models\Agency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        $data = [[
            'email' => 'admin@admin.com',
            'password' => Hash::make('123456'),
            'name' => 'last_name',
            'name_kana' => 'first_name_kana',
            'role' => 'admin',
            'phone' => '02345676',
            'status' => 1,
            'department' => 'department1',
            'last_login_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),

        ]];
        DB::table('users')->insert($data);
    }
}
