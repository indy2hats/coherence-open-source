<?php

namespace App\Repository;

use App\Models\IssueCategory;
use App\Models\IssueRecord;
use App\Models\ProjectCredentials;
use App\Models\Taxonomy;
use App\Models\TaxonomyList;
use App\Models\User;
use App\Models\UserCredential;
use App\Models\WorkNote;
use App\Traits\GeneralTrait;
use Illuminate\Support\Carbon;

class ToolRepository
{
    use GeneralTrait;

    public function createWorkNote()
    {
        WorkNote::create();
    }

    public function updateWorkNote($id)
    {
        $data = [
            'content' => request('content')
        ];
        WorkNote::find($id)->update($data);
    }

    public function deleteWorkNote($id)
    {
        WorkNote::find($id)->delete();
    }

    public function getUserNotClients()
    {
        return User::notClients()->select('id', 'first_name', 'last_name')->orderBy('first_name')->orderBy('last_name')->get();
    }

    public function getCategories()
    {
        return IssueCategory::select('title', 'slug')->orderBy('title')->get();
    }

    public function createIssue($request)
    {
        $input = $request->except('_token');
        $input['added_by'] = $this->getCurrentUserId();

        $issue = IssueRecord::create($input);
    }

    public function updateIssue($request, $id)
    {
        $issue = $this->findIssue($id);
        $input = $request->except('_token');

        $issue->update($input);
    }

    public function getIssuesForIssueRecordSearch($request)
    {
        $issues = IssueRecord::with('project')->with('addedBy')->has('project');

        if ($request->from_date != '') {
            $fromDate = $this->getDate($request->from_date);
            $issues = $issues->whereDate('created_at', '>=', $fromDate);
        }

        if ($request->to_date != '') {
            $toDate = $this->getDate($request->to_date);
            $issues = $issues->whereDate('created_at', '<=', $toDate);
        }

        if ($request->added_by != '') {
            $issues = $issues->where('added_by', $request->added_by);
        }

        if ($request->project_id != '') {
            $issues = $issues->where('project_id', $request->project_id);
        }

        if ($request->category != '') {
            $issues = $issues->where('category', $request->category);
        }

        return $issues->get();
    }

    public function getDate($date)
    {
        return Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
    }

    public function createCategory($request)
    {
        $input = $request->except('_token');
        $input['slug'] = $input['title'];

        return IssueCategory::create($input);
    }

    public function createProjectCredentials($request)
    {
        $data = [
            'project_id' => request('project_id'),
            'type' => request('type'),
            'value' => request('value'),
        ];

        if ($request->hasFile('file')) {
            $data['path'] = $request->file('file')->store('projectcredentials');
        }

        ProjectCredentials::create($data);

        return $this->getProjectCredentialsByProjectId(request('project_id'));
    }

    public function updateProjectCredentials($request, $id)
    {
        $data = [
            'type' => request('type'),
            'value' => request('value'),
        ];

        if ($request->hasFile('file')) {
            $data['path'] = $request->file('file')->store('projectcredentials');
        }

        ProjectCredentials::find($id)->update($data);

        return $this->getProjectCredentialsByProjectId(request('project_id'));
    }

    public function createUserCredentials($request)
    {
        $data = $request->all();

        UserCredential::create($data);
    }

    public function getUserCredentials($id)
    {
        return UserCredential::where('id', $id)->first();
    }

    public function updateUserCredentials($request, $id)
    {
        $data = $request->except(['id']);
        UserCredential::find($id)->update($data);
    }

    public function updateEasyAccess($list)
    {
        User::find($this->getCurrentUserId())->update(['easy_access' => $list]);
    }

    public function getEasyAccess()
    {
        return unserialize($this->getCurrentUser()->easy_access);
    }

    public function easyAccessList()
    {
        return unserialize(User::where('id', $this->getCurrentUserId())->first()->easy_access);
    }

    public function getTaxonomyList()
    {
        return TaxonomyList::where('taxonomy_id', Taxonomy::where('title', 'Guideline Tags')->first()->id)->get();
    }

    public function addTag()
    {
        TaxonomyList::create(['taxonomy_id' => Taxonomy::where('title', 'Guideline Tags')->first()->id, 'title' => request('category'), 'slug' => str_slug(request('category'))]);
    }

    public function getGuidelineTagsId()
    {
        return Taxonomy::where('title', 'Guideline Tags')->first()->id;
    }
}
