<?php

namespace App\Repository;

use App\Models\Project;
use App\Models\Settings;
use App\Models\Taxonomy;
use App\Models\TaxonomyList;
use App\Models\Technology;

class SettingsRepository
{
    public function getTechnologies($pagination)
    {
        return Technology::orderBy('created_at', 'DESC')->paginate($pagination);
    }

    public function getProjectTechnologyCount($id)
    {
        return Project::where('technology_id', $id)->count();
    }

    public function getBase()
    {
        return TaxonomyList::where('taxonomy_id', Taxonomy::where('title', 'Base Currency')->first()->id)->first();
    }

    public function changeCurrency()
    {
        $data = ['title' => request('currency'), 'slug' => str_slug(request('currency'))];

        TaxonomyList::where('taxonomy_id', Taxonomy::where('title', 'Base Currency')->first()->id)->update($data);

        return $data;
    }

    public function getData($slugArray)
    {
        return Settings::whereIn('slug', $slugArray)->get(['slug', 'value', 'label']);
    }
}
