<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('users')->insert([
            'email' => 'admin',
            'password' => Hash::make('password'),
            'first_name' => 'Test',
            'last_name' => 'Admin',
            'first_name_kana' => 'Test',
            'last_name_kana' => 'Admin',
            'role' => 'admin',
            'last_login_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
