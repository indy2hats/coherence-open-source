<?php

namespace App\Http\Controllers\ItAssets;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttributeRequest;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Services\AttributeService;
use App\Traits\GeneralTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AttributesController extends Controller
{
    use GeneralTrait;

    public $pagination;
    protected $attributeService;

    public function __construct(AttributeService $attributeService)
    {
        $this->pagination = config('general.pagination');
        $this->attributeService = $attributeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $attributes = $this->attributeService->getAttributes($this->pagination);

        return view('assets.attributes.index', compact('attributes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AttributeRequest $request)
    {
        $attributeData = [
            'name' => $request->name
        ];

        try {
            $attributeId = $this->createAttribute($attributeData);
            $values = array_unique($request->values);

            foreach ($values as $value) {
                if ($value != '') {
                    $data = [
                        'attribute_id' => $attributeId,
                        'value' => trim($value)
                    ];

                    $this->createAttributeValues($data);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error storing attribute data : '.$e->getMessage());
        }
        $attributeValues = rtrim(implode(',', $values), ',');
        $res = [
            'status' => 'success',
            'data' => [
                'id' => $attributeId,
                'name' => $request->name,
                'values' => $attributeValues
            ],

        ];

        return response()->json($res);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $attribute = $this->attributeService->getAttributeForEdit($id);

        return view('assets.attributes.edit', compact('attribute'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->type == 'name') {
            $request->validate([
                'name' => ['required', Rule::unique('attributes', 'name')->ignore($id)],
            ]);
            try {
                $this->updateAttribute($id, ['name' => trim($request->name)]);
            } catch (Exception $e) {
                Log::error($e);
            }
        } elseif ($request->type == 'value') {
            $request->validate([
                'value' => [
                    'required',
                    Rule::unique('attribute_values')->where(function ($query) use ($id) {
                        $query->where('attribute_id', $id);
                    }),
                ],
            ]);

            $data = [
                'attribute_id' => $id,
                'value' => trim($request->value)
            ];
            $this->createAttributeValues($data);
        }

        $attributeValues = $this->attributeService->getAttributeValues($id);
        $res = [
            'status' => 'success',
            'data' => [
                'attributeValues' => $attributeValues,
                'attributeId' => $id
            ]

        ];

        return response()->json($res);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attribute $attribute)
    {
        $attribute->delete();
        $res = [
            'status' => 'success',
            'message' => 'Attribute deleted successfully',
        ];

        // if ($this->assetTypeService->typeAssetsCount($id) > 0) {
        //     $res = [
        //         'status' => 'error',
        //         'message' => 'Cannot delete this type as there are assets associated with this type.',
        //     ];
        // } else {
        //     $this->deleteAssestType($id);
        //     $res = [
        //         'status' => 'success',
        //         'message' => 'Asset type deleted successfully',
        //     ];
        // }
        return response()->json($res);
    }

    public function deleteAttributeValue(AttributeValue $attributeValue)
    {
        try {
            $attributeValue->delete();
        } catch (Exception $e) {
            Log::error('Error while deleting: '.$e->getMessage());
        }

        $attributeValues = $this->attributeService->getAttributeValues($attributeValue->attribute_id);
        $res = [
            'status' => 'success',
            'data' => [
                'attributeValues' => $attributeValues,
                'attributeId' => $attributeValue->attribute_id
            ]

        ];

        return response()->json($res);
    }
}
