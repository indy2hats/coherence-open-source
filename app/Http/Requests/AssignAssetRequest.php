<?php

namespace App\Http\Requests;

use App\Models\Asset;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class AssignAssetRequest extends FormRequest
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
        Log::info($this->input('id'));

        return [
            'id' => 'required',
            'user_id' => 'required',
            'assigned_date' => [
                'required',
                function ($attribute, $value, $fail) {
                    $id = $this->input('id');
                    $asset = Asset::find($id);
                    if (! $asset) {
                        $fail('Asset with the provided ID not found.');

                        return;
                    }

                    $purchaseDate = $asset->purchased_date;

                    $purchaseDate = Carbon::parse($purchaseDate);
                    $value = Carbon::createFromFormat('d/m/Y', $value);

                    if ($value->lessThanOrEqualTo($purchaseDate)) {
                        $fail('The assigned date must be greater than the purchase date '.$purchaseDate->format('d/m/Y'));
                    }
                },
            ],
        ];
    }
}
