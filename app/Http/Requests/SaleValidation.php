<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SaleValidation extends FormRequest
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
        'sale_date'   => 'required|date',
        'status'      => 'required|string',
        'paid_amount' => 'nullable|numeric|min:0',
        'shipping'    => 'nullable|numeric|min:0',
        'discount'    => 'nullable|numeric|min:0',
    ];
    }
}
