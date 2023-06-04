<?php

namespace App\Http\Requests\Map;

use App\Models\DrawingType;
use App\Models\Facility;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MapRequest extends FormRequest
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
            'facility_id' => [
                'required',
                'integer',
                Rule::exists(Facility::class, 'id')
            ],
            'drawing_type_id' => [
                'required',
                'integer',
                Rule::exists(DrawingType::class, 'id')
            ],
            'code' => 'required',
            'storage_location' => 'nullable|string|max:255',
            'date_created' => 'nullable|string',
            'creator' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:255',
            'files' => 'required|max:5000',
        ];
    }

    public function prepareForValidation(): void
    {
        $input = $this->all();

        if ($this->has('facility_id')) {
            $input['facility_id'] = (int)$this->get('facility_id');
        }

        if ($this->has('drawing_type_id')) {
            $input['drawing_type_id'] = (int)$this->get('drawing_type_id');
        }

        if ($this->has('number_draw')) {
            $input['number_draw'] = (int)$this->get('number_draw');
        }

        $this->replace($input);
    }
}
