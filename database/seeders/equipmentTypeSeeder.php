<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class equipmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'parent' => null,
                'name' => '電気設備'
            ],
            [
                'parent' => 1,
                'name' => '強電設備'
            ],
            [
                'parent' => 2,
                'name' => '受変電設備'
            ],
            [
                'parent' => 3,
                'name' => 'キュービクル'
            ],
            [
                'parent' => 3,
                'name' => 'コンセント'
            ],
            [
                'parent' => 2,
                'name' => '分電盤'
            ],
            [
                'parent' => 6,
                'name' => 'ブルボックス'
            ],
            [
                'parent' => 6,
                'name' => 'ブレーカ(遮断機)'
            ],
            [
                'parent' => 1,
                'name' => '弱電設備'
            ],
            [
                'parent' => 9,
                'name' => '端子盤'
            ],
            [
                'parent' => 10,
                'name' => 'TV盤'
            ],
            [
                'parent' => 10,
                'name' => '分配器'
            ],
            [
                'parent' => 10,
                'name' => '電話用端子盤'
            ],
            [
                'parent' => 10,
                'name' => '端子台'
            ],
            [
                'parent' => 9,
                'name' => '非常時対策設備'
            ],
            [
                'parent' => 15,
                'name' => '受信機'
            ],
            [
                'parent' => 15,
                'name' => '報知器'
            ],
            [
                'parent' => 15,
                'name' => '機器収容箱'
            ],
            [
                'parent' => 9,
                'name' => '照明関係'
            ],
            [
                'parent' => 19,
                'name' => '照明'
            ],
            [
                'parent' => 19,
                'name' => '非常灯'
            ],
            [
                'parent' => 19,
                'name' => '誘導灯'
            ],
            [
                'parent' => null,
                'name' => '機械設備'
            ],
            [
                'parent' => 23,
                'name' => '給排水設備'
            ],
            [
                'parent' => 24,
                'name' => '受水槽'
            ],
            [
                'parent' => 24,
                'name' => '浄化槽'
            ],
            [
                'parent' => 24,
                'name' => '消火栓'
            ],
            [
                'parent' => 27,
                'name' => 'スプリンクラ―'
            ],
            [
                'parent' => 23,
                'name' => '空気調和設備'
            ],
            [
                'parent' => 29,
                'name' => '冷却塔'
            ],
            [
                'parent' => 30,
                'name' => '排煙ファン'
            ],
            [
                'parent' => 30,
                'name' => '排煙ダクト'
            ],
            [
                'parent' => 30,
                'name' => 'ダンパー'
            ],
            [
                'parent' => 30,
                'name' => '排煙口'
            ],
            [
                'parent' => 30,
                'name' => '手動解放装置'
            ],
            [
                'parent' => 23,
                'name' => '昇降機設備'
            ],
            [
                'parent' => 36,
                'name' => 'エレベータ'
            ],
            [
                'parent' => 36,
                'name' => 'エスカレータ'
            ],
        ];

        DB::table('m_equipment_types')->insert($data);
    }
}
