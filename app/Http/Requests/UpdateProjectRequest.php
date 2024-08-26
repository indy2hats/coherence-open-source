<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
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
            'edit_project_name' => ['required', Rule::unique('projects', 'project_name')
                ->whereNull('deleted_at')->ignore($id)],
            'edit_client' => 'required',
            'edit_category' => 'required',
            'edit_priority' => 'required',
            'edit_start_date' => 'required|date_format:d/m/Y',
            'edit_cost_type' => 'required',
            'edit_rate' => 'required|numeric',
            'site_url' => 'url|max:190',
            'edit_end_date' => 'date_format:d/m/Y',
            'estimated_hours' => 'regex:/^\d*(\.\d{1,2})?$/',
        ];
    }
}
