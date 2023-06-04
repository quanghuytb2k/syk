<?php

namespace App\Http\Requests\User;

use App\Enums\UserEnums;
use App\Models\Agency;
use App\Models\Company;
use App\Models\Facility;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class UpdateUserRequest extends FormRequest
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
            'name' => 'nullable|string|max:100',
            'name_kana' => 'nullable|string|max:100',
            'password' => [
                'nullable',
                'max:255',
                'ascii',
                Password::min(8)->mixedCase(),
            ],
            'department' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'memo' => 'nullable|string',


            'role' => 'nullable|string|max:45',
            'agency_id' => [
                'nullable',
                'integer',
                Rule::exists(Agency::class, 'id'),
            ],
            'company_id' => [
                'nullable',
                'integer',
                Rule::exists(Company::class, 'id'),
            ],
            'facility_id' => [
                'nullable',
                'integer',
                Rule::exists(Facility::class, 'id'),
            ],
            'energy_role' => [
                'nullable',
                Rule::in([UserEnums::ENERGY_ROLE_MANAGER, UserEnums::ENERGY_ROLE_USER, UserEnums::ENERGY_ROLE_OTHER])
            ]
        ];
    }
}
