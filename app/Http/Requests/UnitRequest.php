<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UnitRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'max:255',
                'unique' => Rule::unique('roles', 'name')->ignore(request('role') , 'name')
            ],
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
            'name.max' => 'Please enter a name with less than 255 characters'
        ];
    }
}
