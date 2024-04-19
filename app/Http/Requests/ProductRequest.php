<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
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
            'name' => [
                'required',
                'max:255',
                'unique' => Rule::unique('products', 'name')->ignore(request('product') , 'name')
            ],
            'barcode' => [
                'required',
                'integer',
                'unique' => Rule::unique('products', 'barcode')->ignore(request('product') , 'barcode')
            ],
            'category_id' => 'required|integer|exists:categories,id'
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please enter a name',
            'name.unique' => 'Please enter a unique name',
            'name.max' => 'Please enter a name with less than 255 characters',
            'barcode.required' => 'Please enter a barcode',
            'barcode.unique' => 'Please enter a unique barcode',
            'barcode.integer' => 'Please enter a whole number as barcode',
            'category_id.required' => 'Please enter a category id',
            'category_id.integer' => 'Please enter a whole number as category id',
            'category_id.exists' => 'Please enter an existing id for category id'
        ];
    }
}
