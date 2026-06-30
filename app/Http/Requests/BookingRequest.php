<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\Gender;
use Illuminate\Validation\Rule;

class BookingRequest extends FormRequest
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
            'appointment_date'  => ['required', 'date', 'after_or_equal:today'],
            'appointment_time'  => ['required', 'date_format:H:i:s'],
            'patient_name'      => ['required', 'string', 'max:100'],
            'patient_phone'     => ['required', 'regex:/^01[0-2,5]{1}[0-9]{8}$/'],
            'patient_address'   => ['required', 'string', 'max:255'],
            'patient_dob'       => ['nullable', 'date', 'before:today'],
            'patient_gender'    => ['nullable', Rule::enum(Gender::class)],
            'service_id'        => ['required', 'exists:services,id'],
            'patient_notes'     => ['nullable', 'string', 'max:1000'],
            'payment_method'    => ['required', Rule::enum(PaymentMethod::class)],
        ];

    }
    public function messages(): array
    {
        return [
            'patient_name.required' => 'ادخل اسم المريض.',
            'patient_phone.required' => 'ادخل رقم هاتف المريض.',
            'patient_phone.regex' => 'ادخل رقم هاتف صحيح.',
            'patient_address.required' => 'ادخل عنوان المريض.',
            'service_id.required' => 'ادخل الخدمة المطلوبة.',
            'payment_method.required' => 'ادخل طريقة الدفع.',
        ];
    }

}
