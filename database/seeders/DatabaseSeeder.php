<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use PHPUnit\Exception;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PrefectureSeeder::class,
            AgencySeeder::class,
             FacilitySeeder::class,
             CompanySeeder::class,
            UserSeeder::class,
            BuildingTypeSeeder::class,
            BuildingConstructionTypeSeeder::class,
            EnergyTypeSeeder::class,
            MasterConfigureSeeder::class,
            BuildingSeeder::class,
            EquipmentSeeder::class,
            equipmentTypeSeeder::class,
//            MaintainHistorySeeder::class,
            RoleSeeder::class
        ]);
    }
}
