<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\TelephoneSenegalRule;

class VerifyOtpRequest extends FormRequest
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
            'telephone' => ['required', new TelephoneSenegalRule()],
            'otp' => 'required|string|size:6|regex:/^[0-9]+$/',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'telephone.required' => 'Le numéro de téléphone est obligatoire.',
            'otp.required' => 'Le code OTP est obligatoire.',
            'otp.size' => 'Le code OTP doit contenir exactement 6 chiffres.',
            'otp.regex' => 'Le code OTP ne doit contenir que des chiffres.',
        ];
    }
}
