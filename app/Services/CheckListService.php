<?php

namespace App\Services;

use App\Models\ChecklistReport;
use App\Models\TaxonomyList;
use App\Repository\ChecklistRepository;
use App\Traits\GeneralTrait;
use Carbon\Carbon;

class CheckListService
{
    use GeneralTrait{
        GeneralTrait::createTaxonomyList as traitCreateTaxonomyList;
    }

    protected $checklistRepository;

    public function __construct(ChecklistRepository $checklistRepository)
    {
        $this->checklistRepository = $checklistRepository;
    }

    public function getMyChecklists()
    {
        return $this->checklistRepository->getMyChecklists();
    }

    public function createTaxonomyList()
    {
        return $this->checklistRepository->createTaxonomyList();
    }

    public function updateTaxonomyList($id)
    {
        return $this->checklistRepository->updateTaxonomyList($id);
    }

    public function checkListUpdate()
    {
        return $this->checklistRepository->checkListUpdate();
    }

    public function getTaxonomyList($id)
    {
        return $this->checklistRepository->getTaxonomyList($id);
    }

    public function getCheckListReport()
    {
        return $this->checklistRepository->getCheckListReport();
    }

    public function getExistingTaxonomyList($title, $user)
    {
        return $this->checklistRepository->getExistingTaxonomyList($title, $user);
    }

    public function shareChecklist()
    {
        $list = $this->getTaxonomyList(request('list_id'));

        $title = 'Shared-'.$list->title;

        $slug = 'Shared-'.$list->slug;

        foreach (request('users') as $user) {
            $check_existing = $this->getExistingTaxonomyList($title, $user);
            if (empty($check_existing)) {
                $data = [
                    'taxonomy_id' => $this->getTaxonomyId(),
                    'user_id' => $user,
                    'title' => $title,
                    'slug' => $slug,
                ];

                $id = $this->traitCreateTaxonomyList($data)->id;

                foreach ($list->children as $item) {
                    $data = [
                        'taxonomy_id' => $this->getTaxonomyId(),
                        'user_id' => $user,
                        'title' => $item->title,
                        'slug' => $item->slug,
                        'parent_id' => $id
                    ];

                    $this->traitCreateTaxonomyList($data);
                }
            }
        }
    }

    public function getDate($request)
    {
        return Carbon::createFromFormat('d/m/Y', trim($request->daterange))->format('Y-m-d');
    }

    public function getDataForSearchChecklistReport($date)
    {
        $checklist_id = request('checklist_id');

        $data = ChecklistReport::with('user');

        $data->when(! empty($date), function ($q) use ($date) {
            return $q->where('added_on', $date);
        });

        $data->when(! empty($checklist_id), function ($q) use ($checklist_id) {
            return $q->where('title', TaxonomyList::where('id', $checklist_id)->first()->title);
        });

        $user_id = request('user_id');

        if ($this->getCurrentUser()->hasAnyRole(['employee', 'hr-manager', 'project-manager'])) {
            $user_id = $this->getCurrentUserId();
        }

        $data->when(! empty($user_id), function ($q) use ($user_id) {
            return $q->where('user_id', $user_id);
        });

        return $data->get();
    }

    public function getUsersNotClients()
    {
        return $this->checklistRepository->getUsersNotClients();
    }

    public function getChecklists()
    {
        return $this->checklistRepository->getChecklists();
    }

    public function saveChecklist()
    {
        $update = $this->checklistRepository->getCheckListUpdate();
        $checklist = $this->checklistRepository->getCheckList($update->parent_id);

        $data = [
            'user_id' => $this->getCurrentUserId(),
            'title' => $checklist->title,
            'added_on' => $this->addedOn(),
        ];

        if (empty(request('note'))) {
            $data += ['note' => '-'];
        } else {
            $data += ['note' => request('note')];
        }

        $array = unserialize($update->checklists);

        $set = [];

        foreach ($checklist->children as $item) {
            if ($array[$item->id]) {
                $set += [$item->title => 1];
            } else {
                $set += [$item->title => 0];
            }
        }

        $data += ['checklists' => serialize($set)];

        $this->checklistRepository->checklistReportCreate($data);
        $this->checklistRepository->checkListDelete();
        $myChecklists = $this->checklistRepository->getMyChecklists();
        $updatedList = $this->checklistRepository->checkListUpdate();

        return [
            'myChecklists' => $myChecklists,
            'updatedList' => $updatedList
        ];
    }

    public function addedOn()
    {
        return Carbon::createFromFormat('d/m/Y', trim(request('datepicker')))->format('Y-m-d');
    }

    public function getTaxonomyListWithChildren()
    {
        return $this->checklistRepository->getTaxonomyListWithChildren();
    }

    public function updateUserChecklist()
    {
        $set = [];

        if (empty(request('update_id'))) {
            $list = $this->getTaxonomyListWithChildren();
            foreach ($list->children()->get() as $item) {
                if (in_array($item->id, request('checklists'))) {
                    $set += [$item->id => 1];
                } else {
                    $set += [$item->id => 0];
                }
            }
            $data = [
                'user_id' => $this->getCurrentUserId(),
                'parent_id' => request('cat_id'),
                'checklists' => serialize($set),
            ];

            $this->checklistRepository->createChecklistUpdate($data);
        } else {
            $list = $this->checklistRepository->getTaxonomyListWithChildren();
            foreach ($list->children()->get() as $item) {
                if (in_array($item->id, request('checklists'))) {
                    $set += [$item->id => 1];
                } else {
                    $set += [$item->id => 0];
                }
            }
            $data = [
                'checklists' => serialize($set),
            ];

            $this->checklistRepository->updateChecklistUpdate(request('update_id'), $data);
        }

        $myChecklists = $this->checklistRepository->getMyChecklists();
        $updatedList = $this->checklistRepository->getCheckListUpdateByUserId();

        return [
            'myChecklists' => $myChecklists,
            'updatedList' => $updatedList
        ];
    }
}
