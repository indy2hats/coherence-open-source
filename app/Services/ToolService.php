<?php

namespace App\Services;

use App\Repository\ToolRepository;
use App\Traits\GeneralTrait;
use Illuminate\Support\Carbon;

class ToolService
{
    use GeneralTrait{
        GeneralTrait::updateGuideline as traitUpdateGuideline;
        GeneralTrait::createGuideLine as traitCreateGuideLine;
    }

    protected $toolRepository;

    public function __construct(ToolRepository $toolRepository)
    {
        $this->toolRepository = $toolRepository;
    }

    public function createWorkNote()
    {
        return $this->toolRepository->createWorkNote();
    }

    public function updateWorkNote($id)
    {
        return $this->toolRepository->updateWorkNote($id);
    }

    public function deleteWorkNote($id)
    {
        return $this->toolRepository->deleteWorkNote($id);
    }

    public function getUserNotClients()
    {
        return $this->toolRepository->getUserNotClients();
    }

    public function getCategories()
    {
        return $this->toolRepository->getCategories();
    }

    public function getFromDate()
    {
        $firstDay = $this->getFirstDay();

        return $firstDay->format('d/m/Y');
    }

    public function getFirstDay()
    {
        return Carbon::today()->startOfYear();
    }

    public function getLastDay()
    {
        return Carbon::today()->endOfMonth();
    }

    public function getToDate()
    {
        $lastDay = $this->getLastDay();

        return $lastDay->format('d/m/Y');
    }

    public function createIssue($request)
    {
        return $this->toolRepository->createIssue($request);
    }

    public function updateIssue($request, $id)
    {
        return $this->toolRepository->updateIssue($request, $id);
    }

    public function getIssuesForIssueRecordSearch($request)
    {
        return $this->toolRepository->getIssuesForIssueRecordSearch($request);
    }

    public function createCategory($request)
    {
        return $this->toolRepository->createCategory($request);
    }

    public function createProjectCredentials($request)
    {
        return $this->toolRepository->createProjectCredentials($request);
    }

    public function updateProjectCredentials($request, $id)
    {
        return $this->toolRepository->updateProjectCredentials($request, $id);
    }

    public function createUserCredentials($request)
    {
        return $this->toolRepository->createUserCredentials($request);
    }

    public function getUserCredentials($id)
    {
        return $this->toolRepository->getUserCredentials($id);
    }

    public function updateUserCredentials($request, $id)
    {
        return $this->toolRepository->updateUserCredentials($request, $id);
    }

    public function addEasyAccess($request)
    {
        $list = $this->toolRepository->getEasyAccess();
        array_push($list, ['name' => request('name'), 'link' => request('link')]);
        $list = serialize($list);
        $this->toolRepository->updateEasyAccess($list);

        return $this->toolRepository->easyAccessList();
    }

    public function deleteEasyAccess()
    {
        $list = $this->toolRepository->getEasyAccess();
        array_splice($list, request('delete_item_id'), 1);
        $list = serialize($list);
        $this->toolRepository->updateEasyAccess($list);

        return $this->toolRepository->easyAccessList();
    }

    public function editEasyAccess()
    {
        $list = $this->toolRepository->getEasyAccess();
        array_splice($list, request('item_id'), 1);
        array_push($list, ['name' => request('edit_name'), 'link' => request('edit_link')]);
        $list = serialize($list);
        $this->toolRepository->updateEasyAccess($list);

        return $this->toolRepository->easyAccessList();
    }

    public function createDataSet($type = null)
    {
        $list = $this->getGuidelines();
        $dataset = [];

        foreach ($list as $item) {
            if ($type != null) {
                if (in_array($type, unserialize($item->type))) {
                    array_push($dataset, ['id' => $item->id, 'title' => $item->title, 'type' => unserialize($item->type)]);
                }
            } else {
                array_push($dataset, ['id' => $item->id, 'title' => $item->title, 'type' => unserialize($item->type)]);
            }
        }

        return $dataset;
    }

    public function getTaxonomyList()
    {
        return $this->toolRepository->getTaxonomyList();
    }

    public function createGuideLine()
    {
        $data = $this->getGuidelineData();
        $this->traitCreateGuideLine($data);
    }

    public function updateGuideline($id)
    {
        $data = $this->getGuidelineData();
        $this->traitUpdateGuideline($id, $data);
    }

    public function getGuidelineData()
    {
        $tag = request('category') ? request('category') : [];

        if (request('new_tag')) {
            array_push($tag, request('new_tag'));
            $this->createTaxonomyList(['taxonomy_id' => $this->getGuidelineTagsId(), 'title' => request('new_tag'), 'slug' => str_slug(request('new_tag'))]);
        }

        $data = [
            'type' => serialize($tag),
            'title' => request('title'),
            'content' => request('content')
        ];

        return $data;
    }

    public function addTag()
    {
        $this->toolRepository->addTag();
    }

    public function getGuidelineTagsId()
    {
        return $this->toolRepository->getGuidelineTagsId();
    }
}
