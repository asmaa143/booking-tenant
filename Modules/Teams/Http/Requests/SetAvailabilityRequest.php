<?php

namespace Modules\Teams\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetAvailabilityRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'availabilities' => 'required|array',
            'availabilities.*.day_of_week' => 'required|integer|between:0,6',
            'availabilities.*.start_time' => 'required|date_format:H:i',
            'availabilities.*.end_time' => 'required|date_format:H:i|after:availabilities.*.start_time',
        ];
    }
}
