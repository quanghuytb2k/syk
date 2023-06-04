<?php

namespace App\Http\Requests\Auth;

use App\Enums\UserEnums;
use App\Models\Company;
use App\Models\Prefecture;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
            'user.name' => 'required|string|max:100',
            'user.name_kana' => 'required|string|max:100',
            'user.email' => [
                'required',
                'email',
                'max:255',
                'confirmed',
                Rule::unique(User::class, 'email'),
            ],
            'user.password' => [
                'required',
                'max:255',
                'ascii',
                Password::min(8)->mixedCase(),
            ],
            'user.department' => 'required|string|max:255',
            'user.job_title' => 'required|string|max:255',
            'user.phone' => 'nullable|string|max:20',
            'user.energy_role' => [
                'required',
                Rule::in([UserEnums::ENERGY_ROLE_MANAGER, UserEnums::ENERGY_ROLE_USER, UserEnums::ENERGY_ROLE_OTHER])
            ],


            'company.name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Company::class, 'name')->where(function ($query) {
                    $query
                        ->where('post_code', $this->input('company.post_code'))
                        ->where('phone', $this->input('company.phone'));
                }),
            ],
            'company.business_type' => 'required|integer',
            'company.insdustry' => 'required|string|max:255',
            'company.is_stock_listing' => 'required|integer|in:0,1',
            'company.post_code' => 'required|string|max:10',
            'company.prefecture_id' => [
                'required',
                'integer',
                Rule::exists(Prefecture::class, 'id'),
            ],
            'company.municipality' => 'required|string|max:255',
            'company.street' => 'nullable|string|max:255',
            'company.building' => 'nullable|string|max:255',
            'company.phone' => 'required|string|max:20',
            'company.homepage' => 'nullable|string|max:255',
        ];
    }
}
