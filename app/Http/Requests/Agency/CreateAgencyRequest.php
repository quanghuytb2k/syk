<?php

namespace App\Http\Requests\Agency;

use App\Enums\Constants;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CreateAgencyRequest extends FormRequest
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
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'prefecture_id' => 'required|integer|min:1',
            'municipality' => 'required_without_all:street,building|max:255',
            'street' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
            'contact_person_name' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:45',
            'phone' => 'nullable|string|max:20',
            'mail' => 'nullable|email|max:255',
            'status' => ['nullable', 'integer', Rule::in([Constants::AGENCY_STATUS_INACTIVE, Constants::AGENCY_STATUS_ACTIVE])],
            'memo' => 'nullable|string|max:1000',
            'post_code' => 'required|max:12'
        ];
    }

}
