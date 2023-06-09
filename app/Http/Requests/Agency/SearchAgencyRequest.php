<?php

namespace App\Http\Requests\Agency;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SearchAgencyRequest extends FormRequest
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
            'keyword' => 'nullable|string|max:255',
            'sort_field' => 'nullable'
        ];
    }

}
