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
            'name' => 'required|max:255',
            'barcode' => [
                'required',
                'integer',
                'unique' => Rule::unique('products', 'barcode')->ignore(request('product'), 'barcode')
            ],
            'category_id' => 'required|integer|exists:categories,id',
            'content' => 'required|numeric|max:65536|decimal:0,2',
            'unit_id' => 'nullable|integer|exists:units,id',
            'locations' => 'required|array|max:65536',
            'locations.*.id' => 'required|integer|exists:locations,id',
            'locations.*.stock' => 'required|numeric|max:65536',
            'locations.*.shelf_amount' => 'required|numeric|max:65536',
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
            'category_id.exists' => 'Please enter an existing id for category id',
            'content.required' => 'Please enter a content size',
            'content.numeric' => 'Please enter a number for content size',
            'content.max' => 'Please limit content size to 65536',
            'content.decimal' => 'Please limit content to between 0 and 2 decimals',
            'locations.required' => 'Please enter locations',
            'locations.array' => 'Please enter locations as an array',
            'locations.max' => 'Please limit locations to 65536',
            'locations.*.id.required' => 'Please enter locations id',
            'locations.*.id.integer' => 'Please enter locations id as an integer',
            'locations.*.id.exists' => 'Please enter an locations id that exists',
            'locations.*.stock.required' => 'Please enter a stock value',
            'locations.*.stock.numeric' => 'Please enter a number for stock',
            'locations.*.stock.max' => 'Please limit stock to 65536',
            'locations.*.shelf_amount.required' => 'Please enter a shelf_amount value',
            'locations.*.shelf_amount.numeric' => 'Please enter a number for shelf_amount',
            'locations.*.shelf_amount.max' => 'Please limit shelf_amount to 65536',
        ];
    }
}
