<?php

namespace App\Http\Requests\Company;

use App\Enums\CompanyEnums;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:255|unique:companies,name,'.Request()->id,
            'post_code' => 'required|string|max:10',
            'job_title' => 'nullable|string|max:45',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:225',
            'prefecture_id' => 'required|integer',
            'business_type' => [
                'required',
                Rule::in([CompanyEnums::TYPE_SPECIFIED, CompanyEnums::TYPE_GENERAL, CompanyEnums::TYPE_WELFARE_FACILITY, CompanyEnums::TYPE_SCHOOL, CompanyEnums::TYPE_OTHERS])
            ],
            'is_stock_listing' => [
                'required',
                Rule::in([CompanyEnums::NON_LIST, CompanyEnums::TABLE_LIST])
            ],
            'status' => [
                'nullable',
                Rule::in([CompanyEnums::INACTIVE_STATUS, CompanyEnums::ACTIVE_STATUS])
            ],
            'is_export_report' => [
                'nullable',
                Rule::in([CompanyEnums::NOT_EXPORT_REPORT, CompanyEnums::HAVE_EXPORT_REPORT])
            ],
            'agency_id' => 'required|integer',
            'memo' => 'nullable|max:1000',
            'building' => 'nullable|string|max:225',
            'municipality' => 'required|string|max:225',
            'street' => 'nullable|string|max:225',
            'contact_person_name' => 'nullable|string|max:255',
        ];
    }
}
