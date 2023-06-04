<?php

namespace App\Http\Requests\Eqipment;

use App\Models\Agency;
use App\Models\Building;
use App\Models\Company;
use App\Models\EquipmentType;
use App\Models\Facility;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EquipmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'equipment_type_id' => [
                'required',
                Rule::exists(EquipmentType::class, 'id')
            ],
            'maker' => 'nullable',
            'model' => 'nullable',
            'capacity' => 'nullable|numeric',
            'installation_detail_area' => 'nullable',
            'installation_date' => 'nullable|date',
            'contract_type' => 'nullable',
            'memo' => 'nullable',
            'agency_id' => [
                'required',
                Rule::exists(Agency::class, 'id')
            ],
            'company_id' => [
                'required',
                Rule::exists(Company::class, 'id')
            ],
            'facility_id' => [
                'nullable',
                Rule::exists(Facility::class, 'id')
            ],
            'building_id' => [
                'nullable',
                Rule::exists(Building::class, 'id')
            ],
            'equipment_lease_company' => 'nullable',
            'equipment_buy_company' => 'nullable',
            'next_maintenance_period' => 'nullable',
            'maintenance_time' => 'nullable'

        ];
    }
}
