<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SupplierValidation extends FormRequest
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
            'supplier_name' => 'required|string|max:255',
            'email'         => 'required|email|max:255',
            'phone'         => 'required|string|max:20',
            'address'       => 'required|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'supplier_name.required' => 'Supplier name is required.',
            'email.required'         => 'Email is required.',
            'email.email'            => 'Please enter a valid email address.',
            'phone.required'         => 'Phone number is required.',
            'address.required'       => 'Address is required.',
        ];
    }
}
