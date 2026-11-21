<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRiderProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'bike_type' => ['sometimes', Rule::in(['motorcycle', 'bicycle', 'tricycle'])],
            'bike_plate' => ['sometimes', 'nullable', 'string', 'max:20'],
            'bike_color' => ['sometimes', 'nullable', 'string', 'max:50'],
            'current_lat' => ['sometimes', 'nullable', 'numeric', 'between:-90,90'],
            'current_lng' => ['sometimes', 'nullable', 'numeric', 'between:-180,180'],
        ];
    }
}
