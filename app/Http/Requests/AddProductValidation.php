<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AddProductValidation extends FormRequest
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
         $productId = $this->route('id');

        return [
            'product_name' => 'required|string|max:255|unique:products,product_name,' . $productId,
            'code'         => 'required|string|max:100|unique:products,code,' . $productId,

            'category_id'  => 'required|exists:product_categories,id',
            'brand_id'     => 'required|exists:brands,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'supplier_id'  => 'required|exists:suppliers,id',

            'price'        => 'required|numeric|min:0',
            'quantity'     => 'required|integer|min:0',
            'stock_alert'  => 'nullable|integer|min:0',

            'status'       => 'required',

            'notes'        => 'nullable|string',

            'images.*'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

     public function messages(): array
    {
        return [
            'product_name.required' => 'Product name is required.',
            'product_name.unique'   => 'Product name already exists.',

            'code.required'         => 'Product code is required.',
            'code.unique'           => 'Product code already exists.',

            'category_id.required'  => 'Please select a category.',
            'brand_id.required'     => 'Please select a brand.',
            'warehouse_id.required' => 'Please select a warehouse.',
            'supplier_id.required'  => 'Please select a supplier.',

            'price.required'        => 'Price is required.',
            'price.numeric'         => 'Price must be a number.',

            'quantity.required'     => 'Quantity is required.',

            'status.required'       => 'Please select a status.',

            'images.*.image'        => 'Each file must be an image.',
            'images.*.mimes'        => 'Images must be jpg, jpeg, png or webp.',
            'images.*.max'          => 'Each image must not exceed 2MB.',
        ];
    }
}
