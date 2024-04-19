<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'phone_number' => [
                'required',
                'max:255',
                'unique' => Rule::unique('users', 'phone_number')->ignore(request('user') , 'phone_number')
            ],
            'email' => [
                'required',
                'max:255',
                'unique' => Rule::unique('users', 'email')->ignore(request('user') , 'email')
            ],
            'location_id' => 'required|integer|exists:locations,id',
            'role_id' => 'required|integer|exists:roles,id'
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
            'first_name.required' => 'Please enter a first name',
            'first_name.max' => 'Please enter a first name with less than 255 characters',
            'last_name.required' => 'Please enter a last name',
            'last_name.max' => 'Please enter a last name with less than 255 characters',
            'phone_number.required' => 'Please enter a phone number',
            'phone_number.max' => 'Please enter a phone number with less than 255 characters',
            'phone_number.unique' => 'Please enter a unique phone number',
            'email.required' => 'Please enter an email',
            'email.max' => 'Please enter an email with less than 255 characters',
            'email.unique' => 'Please enter a unique email',
            'location_id.required' => 'Please enter a location id',
            'location_id.integer' => 'Please enter a whole number as location id',
            'location_id.exists' => 'Please enter an existing id for location id',
            'role_id.required' => 'Please enter a role id',
            'role_id.integer' => 'Please enter a whole number as role id',
            'role_id.exists' => 'Please enter an existing id for role id'
        ];
    }
}
