<?php

namespace App\Http\Requests;

use App\Models\MaintainCompany;
use App\Models\MaintainCompanyDetailType;
use App\Models\MaintainCompanyType;
use App\Models\Prefecture;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MaintainCompanyRequest extends FormRequest
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
            'name' => 'required|string|unique:maintain_companies,name,' . Request()->maintain_id,
            'size_of_employee' => 'nullable|numeric',
            'ownership' => 'nullable',
            'license_number' => 'nullable',
            'construction_area' => 'nullable',
            'construction_range' => 'nullable',
            'contact_person' => 'nullable',
            'contact_person_role' => 'nullable',
            'contact_person_phone' => 'nullable',
            'memo' => 'nullable',
            'email' => 'nullable|email',
            'prefecture_id' => [
                'required',
                Rule::exists(Prefecture::class, 'id')
            ],
            'code' => 'nullable|max:255',
            'municipality' => 'required|max:255',
            'building' => 'nullable|max:255',
            'maintain_company_type_id' => [
                'required',
                Rule::exists(MaintainCompanyType::class, 'id')
            ],
            'maintain_company_detail_type_id' => [
                'required',
                Rule::exists(MaintainCompanyDetailType::class, 'id')
            ],
            'company_type' => ['required', Rule::in(MaintainCompany::COMPANY_TYPE)],
        ];
    }
}
