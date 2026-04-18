<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest {
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
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^[\+\d\s\-\(\)]{7,20}$/'],
            'email' => 'required|email|unique:members,email,' . $this->user()->member_id . ',member_id',
        ];
    }

    public function messages(): array {
        return [
            'email.unique' => 'Email already registered.'
        ];
    }
}
