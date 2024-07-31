<?php

namespace App\Repository;

use App\Models\ChecklistReport;
use App\Models\ChecklistUpdate;
use App\Models\Taxonomy;
use App\Models\TaxonomyList;
use App\Models\User;
use App\Traits\GeneralTrait;
use Auth;

class ChecklistRepository
{
    use GeneralTrait{
        GeneralTrait::createTaxonomyList as traitCreateTaxonomyList;
    }

    public function getMyChecklists()
    {
        $currentUserId = $this->getCurrentUserId();

        return TaxonomyList::with('children')->where('taxonomy_id', Taxonomy::select('id')->where('slug', 'checklist')->first()->id)->where('user_id', $currentUserId)->where('parent_id', null)->get();
    }

    public function createTaxonomyList()
    {
        $data = [
            'taxonomy_id' => $this->getTaxonomyId(),
            'user_id' => $this->getCurrentUserId(),
            'title' => request('title'),
            'slug' => str_slug(request('title'), '_'),
        ];

        if (request('item_id')) {
            $data += ['parent_id' => request('item_id')];
        }

        $id = $this->traitCreateTaxonomyList($data)->id;

        if (request('item_id')) {
            $id = request('item_id');
        }

        if (request('items')) {
            $this->createMultiple(request('items'), $id);
        }
    }

    public function createMultiple($items, $id)
    {
        foreach ($items as $item) {
            if ($item) {
                $data = [
                    'taxonomy_id' => $this->getTaxonomyId(),
                    'user_id' => $this->getCurrentUserId(),
                    'title' => $item,
                    'slug' => str_slug($item, '_'),
                    'parent_id' => $id
                ];

                $this->traitCreateTaxonomyList($data);
            }
        }
    }

    public function updateTaxonomyList($id)
    {
        $data = [
            'title' => request('edit_title'),
            'slug' => str_slug(request('edit_title'), '_'),
        ];

        TaxonomyList::find($id)->update($data);
    }

    public function checkListUpdate()
    {
        return ChecklistUpdate::where('user_id', $this->getCurrentUserId())->get();
    }

    public function getTaxonomyList($id)
    {
        return TaxonomyList::with('children')->where('id', $id)->first();
    }

    public function getCheckListReport()
    {
        return ChecklistReport::with('user')->where('user_id', request('user_id'))->where('title', 'like', 'Shared-%')->get();
    }

    public function getExistingTaxonomyList($title, $user)
    {
        return TaxonomyList::with('children')->where('title', $title)->where('parent_id', null)->where('user_id', $user)->first();
    }

    public function getUsersNotClients()
    {
        return User::active()->notClients()->select('id', 'first_name', 'last_name')->orderBy('first_name', 'ASC')->get();
    }

    public function getChecklists()
    {
        $checklists = TaxonomyList::where('taxonomy_id', Taxonomy::select('id')->where('slug', 'checklist')->first()->id)->where('parent_id', null);

        if (Auth::user()->hasAnyRole(['employee', 'hr-manager', 'project-manager'])) {
            $checklists = $checklists->where('user_id', $this->getCurrentUserId());
        }

        return $checklists->get();
    }

    public function getCheckListUpdate()
    {
        return ChecklistUpdate::find(request('save_id'));
    }

    public function getChecklist($id)
    {
        return TaxonomyList::with('children')->where('id', $id)->first();
    }

    public function checklistReportCreate($data)
    {
        return ChecklistReport::create($data);
    }

    public function checkListDelete()
    {
        return ChecklistUpdate::find(request('save_id'))->delete();
    }

    public function getCheckListUpdateByUserId()
    {
        return ChecklistUpdate::where('user_id', $this->getCurrentUserId())->get();
    }

    public function getTaxonomyListWithChildren()
    {
        return TaxonomyList::with('children')->where('id', request('cat_id'))->first();
    }

    public function createChecklistUpdate($data)
    {
        ChecklistUpdate::create($data);
    }

    public function updateChecklistUpdate($id, $data)
    {
        ChecklistUpdate::find($id)->update($data);
    }
}
