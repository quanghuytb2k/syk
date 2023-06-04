<?php

namespace Database\Seeders;

use App\Models\Equipment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class MaintainHistorySeeder extends Seeder
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
                'date' => Carbon::now('Asia/Ho_Chi_Minh'),
                'money' => 100000,
                'maintenance_person_name' => 'Người bảo trì',
                'content' => 'Nội dung bảo trì công trình xây dựng có thể bao gồm một, một số hoặc toàn bộ các công việc sau: kiểm tra, quan trắc, kiểm định chất lượng, bảo dưỡng và sửa chữa công trình',
                'files' => null,
                'next_maintenance_date' => Carbon::now('Asia/Ho_Chi_Minh')->addDay(),
                'next_alarm_date' => Carbon::now()->addWeek(),
                'maintain_company_id' => null,
                'equipment_id' => 1,
            ],
            [
                'date' => Carbon::now('Asia/Ho_Chi_Minh')->addDays(1),
                'money' => 100000,
                'maintenance_person_name' => 'Người bảo trì',
                'content' => 'Nội dung bảo trì công trình xây dựng có thể bao gồm một, một số hoặc toàn bộ các công việc sau: kiểm tra, quan trắc, kiểm định chất lượng, bảo dưỡng và sửa chữa công trình',
                'files' => null,
                'next_maintenance_date' => Carbon::now('Asia/Ho_Chi_Minh')->addDays(5),
                'next_alarm_date' => Carbon::now()->addWeeks(1),
                'maintain_company_id' => null,
                'equipment_id' => 1,
            ],
            [
                'date' => Carbon::now('Asia/Ho_Chi_Minh')->addDays(2),
                'money' => 100000,
                'maintenance_person_name' => 'Người bảo trì',
                'content' => 'Nội dung bảo trì công trình xây dựng có thể bao gồm một, một số hoặc toàn bộ các công việc sau: kiểm tra, quan trắc, kiểm định chất lượng, bảo dưỡng và sửa chữa công trình',
                'files' => null,
                'next_maintenance_date' => Carbon::now('Asia/Ho_Chi_Minh')->addDays(2),
                'next_alarm_date' => Carbon::now()->addWeeks(2),
                'maintain_company_id' => null,
                'equipment_id' => 1,
            ],
            [
                'date' => Carbon::now('Asia/Ho_Chi_Minh')->addDays(3),
                'money' => 100000,
                'maintenance_person_name' => 'Người bảo trì',
                'content' => 'Nội dung bảo trì công trình xây dựng có thể bao gồm một, một số hoặc toàn bộ các công việc sau: kiểm tra, quan trắc, kiểm định chất lượng, bảo dưỡng và sửa chữa công trình',
                'files' => null,
                'next_maintenance_date' => Carbon::now('Asia/Ho_Chi_Minh')->addDays(3),
                'next_alarm_date' => Carbon::now()->addWeeks(3),
                'maintain_company_id' => null,
                'equipment_id' => 1,
            ],
        ];
        Schema::disableForeignKeyConstraints();
        DB::table('maintain_histories')->truncate();
        Schema::enableForeignKeyConstraints();
        DB::table('maintain_histories')->insert($data);
    }
}
