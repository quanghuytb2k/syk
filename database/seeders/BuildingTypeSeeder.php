<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BuildingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = ['居宅', '店舗', '寄宿舎', '共同住宅', '事務所', '旅館', '料理店', '工場', '倉庫', '車庫', '発電所及び変電所', '校舎', '講堂', '研究所', '病院', '診療所', '集会所', '公会堂',    '停車場',    '劇場',    '映画館',    '遊技場',    '競技場',    '野球場',    '競馬場',    '公衆浴場',    '火葬場',    '守衛所',    '茶室',    '温室',    '蚕室',    '物置',    '便所',    '鶏舎',    '酪農舎',    '給油所',    'その他'];

        $insertData = [];

        foreach ($datas as $key => $data)
        {
            $insertData[] = [
                'name' => $data,
                'order' => $key + 1
            ];
        }

        DB::table('m_building_types')->insert($insertData);
    }
}
