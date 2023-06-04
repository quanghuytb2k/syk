<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BuildingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [[
            "facility_id" => 1,
            'name' => 'building 1',
            'floor_count' => 12,
            'agency_id' => 1,
            'company_id' => 1
        ],[
            "facility_id" => 1,
            'name' => 'building 2',
            'floor_count' => 10,
            'agency_id' => 1,
            'company_id' => 1
        ]];

        DB::table('buildings')->insert($data);
    }
}
