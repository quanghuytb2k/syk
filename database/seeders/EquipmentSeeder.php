<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [[
            'name' => 'name1',
            'building_id' => 1,
            'equipment_type_id'=>1,
            'agency_id'=>1,
            'company_id'=>1
        ],[
            'name' => 'name2',
            'building_id' => 2,
            'equipment_type_id'=>1,
            'agency_id'=>1,
            'company_id'=>1
        ]];
        Schema::disableForeignKeyConstraints();

        DB::table('equipments')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('equipments')->insert($data);
    }
}
