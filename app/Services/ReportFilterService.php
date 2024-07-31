<?php

namespace App\Services;

use App\Repository\ReportFilterRepository;

class ReportFilterService
{
    protected $repository;

    /**
     * Constructs a new instance of the ReportFilterService class.
     *
     * @param  ReportFilterRepository  $repository  The repository for the report filters.
     */
    public function __construct(ReportFilterRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Creates a new record in the database using the provided attributes.
     *
     * @param  array  $attributes  The attributes to be used for creating the record.
     * @return \Illuminate\Database\Eloquent\Model The newly created record.
     */
    public function create(array $attributes)
    {
        return $this->repository->create($attributes);
    }

    /**
     * Retrieves the saved filters for a given report name.
     *
     * @param  string  $reportName  The name of the report.
     * @return \Illuminate\Database\Eloquent\Collection The collection of saved filters.
     */
    public function getSavedFilters($reportName)
    {
        return $this->repository->fetchSavedFilters($reportName);
    }

    /**
     * Retrieves the filter data based on the provided filter ID.
     *
     * @param  int  $filterId  The ID of the filter to retrieve data for.
     * @return mixed The filter data corresponding to the provided ID.
     */
    public function getFilterData($filterId)
    {
        return $this->repository->fetchFilterData($filterId);
    }

    /**
     * Deletes a saved filter based on the provided filter ID.
     *
     * @param  int  $filterId  The ID of the filter to be deleted.
     * @return int The number of records deleted.
     */
    public function deleteSavedFilter($filterId)
    {
        return $this->repository->deleteSavedFilter($filterId);
    }
}
