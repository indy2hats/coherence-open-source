<?php

namespace App\Repository;

use App\Models\SalaryComponent;

class SalaryComponentRepository
{
    protected $model;

    public function __construct(SalaryComponent $salaryComponent)
    {
        $this->model = $salaryComponent;
    }

    public function getSalaryComponents()
    {
        return $this->model::select('id', 'title', 'type', 'status')->orderBy('id', 'asc')->get();
    }

    public function getFullSalaryComponents()
    {
        return $this->model::orderBy('id', 'ASC')->get();
    }
}
