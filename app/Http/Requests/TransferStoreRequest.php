<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TransferStoreRequest extends FormRequest
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
            'transfer_date'      => ['required', 'date'],

            'from_warehouse_id'  => [
                'required',
                'exists:warehouses,id',
                'different:to_warehouse_id',
            ],

            'to_warehouse_id'    => [
                'required',
                'exists:warehouses,id',
            ],

            'status'             => [
                'required',
                'in:Transfer,Pending,Received',
            ],

            'product_id'         => [
                'required',
                'array',
                'min:1',
            ],

            'product_id.*'       => [
                'required',
                'exists:products,id',
            ],

            'quantity'           => [
                'required',
                'array',
            ],

            'quantity.*'         => [
                'required',
                'numeric',
                'min:1',
            ],

            'unit_cost'          => [
                'required',
                'array',
            ],

            'unit_cost.*'        => [
                'required',
                'numeric',
                'min:0',
            ],

            'item_discount.*'    => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'discount'           => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'shipping'           => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'note'               => [
                'nullable',
                'string',
            ],
        ];
    }
       public function messages(): array
    {
        return [
            'transfer_date.required' => 'The transfer date is required.',

            'from_warehouse_id.required' => 'Please select the source warehouse.',
            'from_warehouse_id.exists' => 'The selected source warehouse is invalid.',
            'from_warehouse_id.different' => 'Source and destination warehouse must be different.',

            'to_warehouse_id.required' => 'Please select the destination warehouse.',
            'to_warehouse_id.exists' => 'The selected destination warehouse is invalid.',

            'status.required' => 'Please select a status.',
            'status.in' => 'The selected status is invalid.',

            'product_id.required' => 'Please add at least one product.',
            'product_id.min' => 'Please add at least one product.',
            'product_id.*.exists' => 'One or more selected products are invalid.',

            'quantity.*.required' => 'Quantity is required.',
            'quantity.*.numeric' => 'Quantity must be a number.',
            'quantity.*.min' => 'Quantity must be at least 1.',

            'unit_cost.*.required' => 'Unit cost is required.',
            'unit_cost.*.numeric' => 'Unit cost must be a number.',
            'unit_cost.*.min' => 'Unit cost cannot be negative.',

            'item_discount.*.numeric' => 'Item discount must be a number.',
            'item_discount.*.min' => 'Item discount cannot be negative.',

            'discount.numeric' => 'Discount must be a number.',
            'discount.min' => 'Discount cannot be negative.',

            'shipping.numeric' => 'Shipping must be a number.',
            'shipping.min' => 'Shipping cannot be negative.',

            'note.string' => 'The note must be a valid text.',
        ];
    }
}
