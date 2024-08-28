<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjectRequest extends FormRequest
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
            'project_name' => ['required', 'max:190', Rule::unique('projects', 'project_name')
                ->whereNull('deleted_at')],
            'client' => 'required',
            'priority' => 'required',
            'start_date' => 'required|date_format:d/m/Y',
            'cost_type' => 'required',
            'rate' => 'required|numeric',
            'site_url' => 'url|max:190',
            'end_date' => 'date_format:d/m/Y',
            'estimated_hours' => 'regex:/^\d*(\.\d{1,2})?$/',
        ];
    }
}
