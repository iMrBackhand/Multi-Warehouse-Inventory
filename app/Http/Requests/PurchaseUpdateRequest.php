<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseUpdateRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'purchase_date' => 'required|date',
            'shipping'      => 'required|numeric|min:0',
            'discount'      => 'required|numeric|min:0',
            'status'        => 'required|in:Pending,Received',
            'note'          => 'nullable|string|max:1000',
            'grand_total'   => 'required|numeric|min:0',

            'purchase_item_id'   => 'required|array|min:1',
            'purchase_item_id.*' => 'required|exists:purchase_items,id',

            'quantity'   => 'required|array|min:1',
            'quantity.*' => 'required|integer|min:1',

            'unit_cost'   => 'required|array|min:1',
            'unit_cost.*' => 'required|numeric|min:0',

            'item_discount'   => 'required|array|min:1',
            'item_discount.*' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'purchase_date.required' => 'Purchase date is required.',
            'purchase_date.date'     => 'Invalid purchase date.',

            'shipping.required' => 'Shipping fee is required.',
            'shipping.numeric'  => 'Shipping must be a valid number.',

            'discount.required' => 'Discount is required.',
            'discount.numeric'  => 'Discount must be a valid number.',

            'status.required' => 'Please select a status.',
            'status.in'       => 'Invalid purchase status.',

            'grand_total.required' => 'Grand total is required.',
            'grand_total.numeric'  => 'Grand total must be a valid number.',

            'purchase_item_id.required' => 'Purchase items are required.',
            'purchase_item_id.*.exists' => 'One or more purchase items are invalid.',

            'quantity.*.required' => 'Quantity is required.',
            'quantity.*.integer'  => 'Quantity must be a whole number.',
            'quantity.*.min'      => 'Quantity must be at least 1.',

            'unit_cost.*.required' => 'Unit cost is required.',
            'unit_cost.*.numeric'  => 'Unit cost must be a valid number.',
            'unit_cost.*.min'      => 'Unit cost cannot be negative.',

            'item_discount.*.required' => 'Item discount is required.',
            'item_discount.*.numeric'  => 'Item discount must be a valid number.',
            'item_discount.*.min'      => 'Item discount cannot be negative.',
        ];
    }
}
