<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PasswordUpdateRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [            
            'current_password' => 'required|current_password', 
            'password' => 'required|min:8|confirmed',
        ];
    }

    public function messages(): array {
        return [
            'current_password.current_password' => 'Incorrect password',
            'password.min' => 'Your password must be at least 8 characters long.', 
            'password.confirmed' => 'Password does not match', 
        ];
    }
}
