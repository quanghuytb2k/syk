<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnergyUsageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [[
            'energy_type_id' => 'energy_type_id1',
            'usage_year' => '2022',
            'usage_month' => '12',
            'amount' => 12,
            'money' => 1000,
            'converted_co2_amount' => '100g',
        ],[
            'energy_type_id' => 'energy_type_id2',
            'usage_year' => '2022',
            'usage_month' => '12',
            'amount' => 121,
            'money' => 2000,
            'converted_co2_amount' => '200g',
        ]];
        DB::table('energy_usages')->truncate();
        DB::table('energy_usages')->insert($data);
    }
}
