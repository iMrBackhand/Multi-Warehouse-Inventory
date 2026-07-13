<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductCategoryRequest extends FormRequest
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
            'category_name' => 'required|string|max:255|unique:product_categories,category_name',
            'category_slug' => 'required|string|max:255|unique:product_categories,category_slug',
        ];
    }

     public function messages(): array
    {
        return [
            'category_name.required' => 'Category name is required.',
            'category_name.unique'   => 'Category name already exists.',

            'category_slug.required' => 'Category slug is required.',
            'category_slug.unique'   => 'Category slug already exists.',
        ];
    }
}
