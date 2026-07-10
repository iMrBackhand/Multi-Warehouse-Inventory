<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PurchaseAddRequest extends FormRequest
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
                'purchase_date' => 'required|date',
                'warehouse_id'  => 'required',
                'supplier_id'   => 'required',
                'status'        => 'required',
                'product_id'    => 'required|array',
                'quantity'      => 'required|array',
                'unit_cost'     => 'required|array',
        ];
    }
}
