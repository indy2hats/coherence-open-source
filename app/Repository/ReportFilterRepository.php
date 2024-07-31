<?php

namespace App\Repository;

use App\Models\ReportFilter;

class ReportFilterRepository
{
    protected $model;

    /**
     * Constructor for the ReportFilterRepository class.
     *
     * @param  ReportFilter  $model  The model to be injected.
     */
    public function __construct(ReportFilter $model)
    {
        $this->model = $model;
    }

    /**
     * Create a new record in the database using the provided attributes.
     *
     * @param  array  $attributes  The attributes to be used for creating the record.
     * @return \Illuminate\Database\Eloquent\Model The newly created record.
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * Fetches saved filters for a given report name.
     *
     * @param  string  $reportName  The name of the report.
     * @return \Illuminate\Database\Eloquent\Collection The collection of saved filters.
     */
    public function fetchSavedFilters($reportName)
    {
        return $this->model
            ->select('id', 'name')
            ->where('user_id', auth()->user()->id)
            ->where('report_name', $reportName)
            ->get();
    }

    /**
     * Fetches filter data based on the provided filter ID.
     *
     * @param  datatype  $filterId  description
     * @return Some_Return_Value
     *
     * @throws Some_Exception_Class description of exception
     */
    public function fetchFilterData($filterId)
    {
        return $this->model
            ->where('id', $filterId)
            ->first();
    }

    /**
     * Deletes a saved filter based on the provided filter ID.
     *
     * @param  int  $filterId  The ID of the filter to be deleted.
     * @return int The number of records deleted.
     */
    public function deleteSavedFilter($filterId)
    {
        return $this->model
            ->where('id', $filterId)
            ->delete();
    }
}
