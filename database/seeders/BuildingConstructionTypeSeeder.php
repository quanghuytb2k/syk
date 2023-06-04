<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BuildingConstructionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = [
            '木造(W造)',
            '軽量鉄骨造(S造)',
            '重量鉄骨造(S造)',
            '鉄筋コンクリート造(RC造)',
            '鉄骨鉄筋コンクリート造(SRC造)'
        ];

        $insertData = [];

        foreach ($datas as $key => $data)
        {
            $insertData[] = [
                'name' => $data,
                'order' => $key + 1
            ];
        }

        DB::table('m_building_construction_types')->insert($insertData);
    }
}
