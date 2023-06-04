<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnergyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = [
            [
                'name' => '電気',
                'unit' => 'kWh'
            ],[
                'name' => 'ガス',
                'unit' => 'm3'
            ],[
                'name' => '上水道',
                'unit' => 'm3'
            ],[
                'name' => '下水道',
                'unit' => 'm3'
            ],[
                'name' => 'GS灯油',
                'unit' => 'm3'
            ],[
                'name' => '創エネ創',
                'unit' => null
            ],[
                'name' => 'その他',
                'unit' => null
            ]
        ];

        $insertData = [];

        foreach ($datas as $key => $data)
        {
            $insertData[] = array_merge($data, ['order' => $key + 1]);
        }

        DB::table('m_energy_types')->insert($insertData);
    }
}
