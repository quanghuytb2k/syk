<?php

namespace App\Services;

use App\Models\MaintainHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AlarmService
{
    public function list(array $data): mixed
    {
        $today = Carbon::today();
        $query = MaintainHistory::query();
        if (!empty($data)) {
            $query->when(isset($data['time_from']), function ($query) use ($data) {
                $query->whereDate('maintain_histories.next_maintenance_date', '>=', $data['time_from']);
            })->when(isset($data['time_to']), function ($query) use ($data) {
                $query->whereDate('maintain_histories.next_maintenance_date', '<=', $data['time_to']);
            });
        } else {
            $query->whereDate('maintain_histories.next_maintenance_date', '>=', $today);
        }

        if ($data && (isset($data['agency_id']) || isset($data['company_id']) || isset($data['facility_id']))) {
            $query->join('equipments', 'equipments.id', '=', 'maintain_histories.equipment_id')
                ->where(function ($query) use ($data) {
                $query->when(isset($data['agency_id']), function ($query) use ($data) {
                        $query->where('equipments.agency_id', $data['agency_id']);
                    })
                    ->when(isset($data['company_id']), function ($query) use ($data) {
                        $query->where('equipments.company_id', $data['company_id']);
                    })
                    ->when(isset($data['facility_id']), function ($query) use ($data) {
                        $query->where('equipments.facility_id', $data['facility_id']);
                    });
            });
        }

        return $query->with(['equipments' => function ($item) {
            $item->select('id', 'name', 'installation_detail_area');
        }, 'maintainCompany' => function ($item) {
            $item->select('id', 'name');
        },
        ])->get();
    }
}
