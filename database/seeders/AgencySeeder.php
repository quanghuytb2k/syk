<?php

namespace Database\Seeders;

use App\Models\Prefecture;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AgencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('agencies')->delete();

        DB::table('agencies')->insert([
            'id' => 1,
            'name' => 'Default',
            'prefecture_id' => Prefecture::inRandomOrder()->first()->id,
            'contact_person_name' => "contact1",
            'job_title' => 'title test',
            'phone' => "08096683888",
            'mail' => "agency1@gmail.com",
            'status' => 1,
        ]);

        $data = [];
        for ($i = 0; $i < 2; $i++) {
            $data[] = [
                'name' => "Agency name ".$i,
                'prefecture_id' => Prefecture::inRandomOrder()->first()->id,
                'contact_person_name' => "contact name".$i,
                'job_title' => "job name".$i,
                'phone' => "08000000".$i,
                'mail' => "test".$i."@gmail.com",
                'status' => 1,
            ];
        }
        DB::table('agencies')->insert($data);
    }
}
