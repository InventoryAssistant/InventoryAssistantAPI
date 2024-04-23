<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
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
                'unique' => Rule::unique('roles', 'name')->ignore(request('role') , 'name')
            ],
            'abilities' => 'required|array|max:255',
            'abilities.*' => 'required|integer|exists:abilities,id'
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
            'abilities.required' => 'Please enter roles abilities',
            'abilities.array' => 'Please enter abilities as an array',
            'abilities.max' => 'Please limit abilities to 255 characters',
            'abilities.*.required' => 'Please enter ability id',
            'abilities.*.integer' => 'Please enter ability id as an integer',
            'abilities.*.exists' => 'Please enter an ability id that exists'
        ];
    }
}
