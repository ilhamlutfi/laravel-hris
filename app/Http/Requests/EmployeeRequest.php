<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $idEmployee = $this->route()->parameter('employee');

        return [
            'team_id'       => 'required|integer|exists:teams,id',
            'role_id'       => 'required|integer|exists:roles,id',
            'name'          => 'required|min:3',
            'email'         => 'required|email|unique:employees,email'. ($idEmployee ? ",$idEmployee" : ''),
            'gender'        => 'required|in:Male,Female',
            'age'           => 'required|integer',
            'phone'         => 'required|numeric',
            'photo'         => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'is_verified'   => 'nullable|boolean',
            'verified_at'   => 'nullable|date'
        ];
    }
}
