<?php

namespace App\Http\Requests\Building;

use App\Enums\BuildingEnums;
use App\Models\Agency;
use App\Models\Company;
use App\Models\Facility;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BuildingRequest extends FormRequest
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
            'name' => 'required|string|unique:buildings,name,'.Request()->id,
            'agency_id' => [
                'required',
                'integer',
                Rule::exists(Agency::class, 'id')
            ],
            'company_id' => [
                'required',
                'integer',
                Rule::exists(Company::class, 'id')
            ],
            'facility_id' => [
                'required',
                'integer',
                Rule::exists(Facility::class, 'id')
            ],
            'is_direct_profit' => [
                'nullable',
                Rule::in([BuildingEnums::INDIRECT, BuildingEnums::DIRECT])
            ],
            'floor_count' => 'required|integer|min:1',
            'building_type_id' => 'nullable|integer',
            'building_construction_type_id' => 'nullable|integer',
            'build_area_square' => 'nullable|integer',
            'area_square' => 'nullable|integer',
            'employee_count' => 'nullable|integer',
            'memo' => 'nullable|string|max:1000',
            'status' => [
                'nullable',
                'integer',
                Rule::in([BuildingEnums::INACTIVE_STATUS, BuildingEnums::ACTIVE_STATUS])
            ]
        ];
    }
}
