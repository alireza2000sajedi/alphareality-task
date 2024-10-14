<?php

namespace App\Http\Requests;

use App\Models\Kyc;
use App\Rules\NationalCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreKycRequest extends FormRequest
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
            'national_code' => ['required', new NationalCode, Rule::unique(Kyc::class)],
            'birth_date' => ['required', 'date', 'date_format:Y-m-d'],
            'selfie_image' => ['required', 'image', 'max:2048', 'mimes:jpeg,png,jpg'],
        ];
    }
}
