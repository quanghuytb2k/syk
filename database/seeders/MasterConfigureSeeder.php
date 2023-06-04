<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterConfigureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('master_configures')->insert([
            [
                'name' => 'm_energy_types'
            ],
            [
                'name' => 'm_building_types'
            ],
            [
                'name' => 'm_prefectures'
            ],
            [
                'name' => 'm_building_construction_types'
            ],
            [
                'name' => 'm_drawing_types'
            ],
            [
                'name' => 'm_maintain_company_types'
            ],
            [
                'name' => 'm_maintain_company_detail_types'
            ]
        ]);
    }
}
