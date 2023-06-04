<?php

namespace App\Http\Requests\EnergyContract;

use App\Models\Agency;
use App\Models\Building;
use App\Models\Company;
use App\Models\EnergyType;
use App\Models\Facility;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EnergyContractRequest extends FormRequest
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
                'nullable',
                'integer',
                Rule::exists(Facility::class, 'id')
            ],
            'building_id' => [
                'nullable',
                'integer',
                Rule::exists(Building::class, 'id')
            ],
            'energy_type_id' => [
                'required',
                'integer',
                Rule::exists(EnergyType::class, 'id')
            ],
            'co2_convert_coefficient' => 'nullable|numeric',
            'contract_company_name' => 'nullable|string'
        ];
    }
}
