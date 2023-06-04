<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CorporationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [[
            'name' => 'Company name1',
            'prefecture' => 'prefecture1',
            'address' => 'municipality',
            'size_of_employee' => 12,
            'phone' => '033333331',
            'industry_category_id' => 2,
            'agencies_id' => 1
        ],[
            'name' => 'Company name2',
            'prefecture' => 'prefecture2',
            'address' => 'street',
            'size_of_employee' => 12,
            'phone' => '03333333',
            'industry_category_id' => 3,
            'agencies_id' => 2
        ]];
        DB::table('corporations')->truncate();
        DB::table('corporations')->insert($data);
    }
}
