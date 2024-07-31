<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
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
            'email' => 'required|email|unique:clients,email,'.$id,
            'country' => 'required',
            'company_name' => 'required|max:191',
            'state' => 'max:191',
            'city' => 'max:191',
            'address' => 'max:255',
            'post_code' => 'max:191',
            'currency' => 'required',
            'vat_gst_tax_label' => 'max:191',
            'vat_gst_tax_id' => 'max:191',
        ];
    }
}
