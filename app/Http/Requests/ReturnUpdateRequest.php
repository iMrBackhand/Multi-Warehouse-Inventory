<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ReturnUpdateRequest extends FormRequest
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
        'warehouse_id.required' => 'Please select a warehouse.',
        'supplier_id.required'  => 'Please select a supplier.',
        'return_date.required'  => 'Return date is required.',
        'status.required'       => 'Please select a status.',
        'status.in'             => 'Invalid status selected.',

        'product_id.required'   => 'Please add at least one product.',
        'product_id.*.exists'   => 'One of the selected products does not exist.',

        'quantity.*.required'   => 'Quantity is required.',
        'quantity.*.integer'    => 'Quantity must be a whole number.',
        'quantity.*.min'        => 'Quantity must be at least 1.',
        ];
    }
}
