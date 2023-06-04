<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [[
            'name' => 'Facility name1',
            'prefecture_id' => '1',
            'agency_id' => '1',
            'address' => 'municipality',
            'size_of_employee' => 12,
            'company_id' => 1
        ],[
            'name' => 'Facility name2',
            'prefecture_id' => '1',
            'agency_id' => '1',
            'address' => 'street',
            'size_of_employee' => 12,
            'company_id' => 2
        ]];
//        DB::table('facilities')->truncate();
        DB::table('facilities')->insert($data);
    }
}
