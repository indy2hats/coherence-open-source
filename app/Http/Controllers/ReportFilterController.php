<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReportFilterRequest;
use App\Services\ReportFilterService;
use Illuminate\Http\Request;

class ReportFilterController extends Controller
{
    protected $service;

    /**
     * Constructs a new instance of the class.
     *
     * @param  ReportFilterService  $service  The service used to interact with the report filter data.
     */
    public function __construct(ReportFilterService $service)
    {
        $this->service = $service;
    }

    /**
     * Retrieves saved filters based on the provided report name.
     *
     * @param  \Illuminate\Http\Request  $request  The incoming request containing the report name.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the saved filters.
     */
    public function index(Request $request)
    {
        $savedFilter = $this->service->getSavedFilters($request->filterReportName);

        return response()->json($savedFilter);
    }

    /**
     * Creates a new report filter based on the provided request data.
     *
     * @param  StoreReportFilterRequest  $request  The request containing the data for the new filter.
     * @return \Illuminate\Http\JsonResponse The JSON response indicating the success of the filter creation.
     */
    public function store(StoreReportFilterRequest $request)
    {
        $savedFilter = $this->service->create($request->validated());

        return response()->json(['message' => 'Filter created successfully', 'insertedFilterId' => $savedFilter->id]);
    }

    /**
     * Retrieves filter data based on the saved filter ID.
     *
     * @param  Request  $request  The incoming request containing the saved filter ID.
     * @return Some_Return_Value The retrieved filter data.
     */
    public function getFilterData(Request $request)
    {
        return $this->service->getFilterData($request->savedFilterId);
    }

    /**
     * Deletes a saved filter based on the provided filter ID.
     *
     * @param  \Illuminate\Http\Request  $request  The request object containing the filter ID.
     * @return \Illuminate\Http\Response The response indicating success or failure.
     */
    public function destroy(Request $request)
    {
        return  $this->service->deleteSavedFilter($request->savedFilterId);
    }
}
