<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DailyStatusReportRequest extends FormRequest
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
            'todays_task' => 'required',
            'impediments' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'todays_task.required' => 'Enter what you had done today?',
            'impediments.required' => 'Enter if you had faced any issues today?',
        ];
    }
}
