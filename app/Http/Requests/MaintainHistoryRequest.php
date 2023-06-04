<?php

namespace App\Http\Requests;

use App\Models\Equipment;
use App\Models\MaintainCompany;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MaintainHistoryRequest extends FormRequest
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
            'equipment_id' => [
                'required',
                Rule::exists(Equipment::class, 'id')
            ],
            'maintain_company_id' => [
                'required',
                Rule::exists(MaintainCompany::class, 'id')
            ],
            'date' => 'nullable|date',
            'next_maintenance_date' => 'nullable|date',
            'next_alarm_date' => 'nullable|date',
            'money' => 'nullable|numeric',
            'maintenance_person_name' => 'nullable',
            'content' => 'nullable'
        ];
    }
}
