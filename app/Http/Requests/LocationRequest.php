<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LocationRequest extends FormRequest
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
            'address' => [
                'required',
                'max:255',
                'unique' => Rule::unique('locations', 'address')->ignore(request('location') , 'address')
            ]
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return string[]
     */
    public function messages()
    {
        return [
            'address.required' => 'Please enter an address',
            'address.unique' => 'Please enter a unique address',
            'address.max' => 'Please enter an address with less than 255 characters'
        ];
    }
}
