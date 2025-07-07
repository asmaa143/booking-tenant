<?php

namespace Modules\Teams\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class GenerateSlotsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
        ];
    }
}
