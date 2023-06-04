<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [[
            'name' => 'test company',
            'business_type' => 10,
            'prefecture_id' => 1,
            'is_stock_listing' => 1,
            'agency_id' => 1
        ]];
        DB::table('companies')->insert($data);
    }
}
