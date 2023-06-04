<?php

namespace App\Http\Requests;

use App\Enums\FileEnums;
use App\Models\EnergyContract;
use App\Models\EnergyUsage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EnergyFileRequest extends FormRequest
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
            'file_type' => [
                'required',
                Rule::in([FileEnums::CONTRACT_FILE, FileEnums::USAGE_FILE])
            ],
            'contract_id' => [
                Rule::requiredIf(Request()->file_type == FileEnums::CONTRACT_FILE),
                Rule::exists(EnergyContract::class, 'id')
            ],
            'usage_id' => [
                Rule::requiredIf(Request()->file_type == FileEnums::USAGE_FILE),
                Rule::exists(EnergyUsage::class, 'id')
            ]
        ];
    }
}
