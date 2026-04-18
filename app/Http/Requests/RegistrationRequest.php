<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^[\+\d\s\-\(\)]{7,20}$/'],
            'email' => 'required|email|unique:members,email',
            'password' => 'required|min:8|confirmed',
            'subscribe_events' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array {
        return [
            'password.min' => 'Your password must be at least 8 characters long.', 
            'password.confirmed' => 'Password does not match', 
            'email.unique' => 'Email already registered.'
        ];
    }
}
