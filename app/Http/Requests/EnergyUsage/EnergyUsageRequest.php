<?php

namespace App\Http\Requests\EnergyUsage;

use App\Models\Agency;
use App\Models\Building;
use App\Models\Company;
use App\Models\EnergyContract;
use App\Models\Facility;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EnergyUsageRequest extends FormRequest
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
            'energy_contract_id' => [
                'required',
                'integer',
                Rule::exists(EnergyContract::class, 'id')
            ],
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
            'building_id' => [
                'required',
                'integer',
                Rule::exists(Building::class, 'id')
            ],
            'amount' => 'required|numeric',
            'usage_from_date' => 'nullable|date',
            'usage_to_date' => 'nullable|date|after:usage_from_date',
            'billing_date' => 'nullable|date',
            'invoice_date' => 'nullable|date',
            'money' => 'nullable|numeric',
            'converted_co2_amount' => 'required'
        ];
    }

    public function prepareForValidation()
    {
        $input = $this->all();

        if ($this->has('usage_from_date')) {
            $input['usage_from_date'] = Carbon::parse($this->get('usage_from_date'))->format('Y-m-d');
        }

        if ($this->has('usage_to_date')) {
            $input['usage_to_date'] = Carbon::parse($this->get('usage_to_date'))->format('Y-m-d');
        }

        if ($this->has('billing_date')) {
            $input['billing_date'] = Carbon::parse($this->get('billing_date'))->format('Y-m-d');
        }

        if ($this->has('invoice_date')) {
            $input['invoice_date'] = Carbon::parse($this->get('invoice_date'))->format('Y-m-d');
        }

        $this->replace($input);
    }
}
