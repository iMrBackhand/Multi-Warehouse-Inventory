<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class WarehouseAddRequest extends FormRequest
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
            'warehouse_name' => 'required|string|max:255|unique:warehouses,warehouse_name',
            'email'          => 'required|email|unique:warehouses,email',
            'phone'          => 'required|string|max:20',
            'city'           => 'required|string|max:255',
        ];
    }
    public function messages()
    {
        return [
            'warehouse_name.unique' => 'Warehouse is already listed.',
        ];
    }
}
