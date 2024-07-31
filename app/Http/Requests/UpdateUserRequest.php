<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        $id = $this->route('id');

        return [
            'first_name' => 'required|min:2|max:191',
            'last_name' => 'max:191',
            'email' => 'required|email|unique:users,email,'.$id,
            'role_id' => 'required',
            'employee_id' => 'exclude_if:role_id,4|required',
            'department' => 'exclude_if:role_id,4|required',
            'designation' => 'exclude_if:role_id,4|required',
            'bank_name' => 'required_with:account_number|required_with:branch|nullable',
            'account_number' => 'exclude_if:role_id,4|required_with:bank_name|nullable',
            'branch' => 'exclude_if:role_id,4|required_with:bank_name|max:30|nullable',
            'ifsc' => 'exclude_if:role_id,4|min:4|max:15|nullable',
            'pan_number' => 'exclude_if:role_id,4|min:9|max:15|nullable',
            'uan_number' => 'exclude_if:role_id,4|min:9|max:15|nullable',
            'address' => 'exclude_if:role_id,4|max:255|nullable',
        ];
    }
}
