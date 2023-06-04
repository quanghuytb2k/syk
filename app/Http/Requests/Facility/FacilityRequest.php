<?php

namespace App\Http\Requests\Facility;

use App\Enums\FacilityEnums;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FacilityRequest extends FormRequest
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
            'name' => 'required|max:255|unique:facilities,name,'.Request()->id,
            'prefecture_id' => 'required|integer|min:1',
            'agency_id' => 'required|integer|min:1',
            'company_id' => 'required|integer|min:1',
            'address' => 'nullable|string|max:225',
            'size_of_employee' => 'integer|min:1',
            'concern' => 'nullable|string|max:1000',
            'memo' => 'nullable|string|max:1000',
            'contact_person_name' => 'string|max:225',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:225',
            'job_title' => 'string|max:45',
            'status' => [
                'integer',
                Rule::in([FacilityEnums::INACTIVE_STATUS, FacilityEnums::ACTIVE_STATUS])
            ]
        ];
    }
}
