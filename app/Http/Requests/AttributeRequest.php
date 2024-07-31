<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttributeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', Rule::unique('attributes', 'name')],
            'values' => [
                'required',
                'array',
                'min:1', // Ensure values is an array and has at least one element
                function ($attribute, $value, $fail) {
                    $nonEmptyValues = array_filter($value, function ($item) {
                        return $item !== '';
                    });

                    if (empty($nonEmptyValues)) {
                        $fail('Must have atleast one attribute value.');
                    }
                },
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The attribute name is required.',
        ];
    }
}
