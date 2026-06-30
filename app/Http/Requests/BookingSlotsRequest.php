<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BookingSlotsRequest extends FormRequest
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
                'date' => ['required', 'date', 'after_or_equal:today'],
        ];
    }
    public function messages(): array
    {
        return [
            'date.required' => 'الرجاء إدخال التاريخ.',
            'date.date' => 'الرجاء إدخال تاريخ صحيح.',
            'date.after_or_equal' => 'الرجاء إدخال تاريخ بعد تاريخ اليوم الحالي او التاريخ الحالي.',
        ];
    }

}
