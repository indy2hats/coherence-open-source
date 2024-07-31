<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravelista\Comments\Commentable;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Task extends Model implements Searchable
{
    use Commentable;
    use HasFactory;
    use SoftDeletes;

    /** The attributes that are mass assignable */
    protected $fillable = [
        'project_id',
        'code',
        'title',
        'priority',
        'estimated_time',
        'actual_estimated_time',
        'time_spent',
        'description',
        'notes',
        'percent_complete',
        'task_url',
        'task_id',
        'start_date',
        'end_date',
        'status',
        'parent_id',
        'reviewer_id',
        'created_at',
        'tag',
        'created_by',
        'order_no',
        'add_to_board',
        'sub_task_order',
        'is_archived'
    ];

    public function getSearchResult(): SearchResult
    {
        $url = route('tasks.show', $this->id);

        return new SearchResult(
            $this,
            $this->title.' ('.$this->project->project_name.') ',
            $url
        );
    }

    /** returns date format for start date */
    public function getCreatedAtAttribute($date)
    {
        return date_format(new DateTime($date), 'Y-m-d');
    }

    /** returns date format for start date */
    public function getStartDateAttribute($date)
    {
        return date_format(new DateTime($date), 'Y-m-d');
    }

    /** returns date format for start date */
    public function getEndDateAttribute($date)
    {
        return date_format(new DateTime($date), 'Y-m-d');
    }

    /** Get users that belongs to project */
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'task_assigned_users', 'task_id', 'user_id')->withTimestamps();
    }

    /** Get users who created the task */
    public function creator()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }

    /** Get user sessions of task */
    public function users_session()
    {
        return $this->belongsToMany('App\Models\User', 'task_sessions', 'task_id', 'user_id');
    }

    public function users_rejections()
    {
        return $this->belongsToMany('App\Models\User', 'task_rejections', 'task_id', 'user_id');
    }

    /** Get checklists that belongs to task */
    public function checklists()
    {
        return $this->belongsToMany('App\Models\Checklist', 'task_checklists', 'task_id', 'checklist_id')->withPivot('id', 'developer_status', 'reviewer_status');
    }

    /** Get user sessions of task */
    public function tasks_session()
    {
        return $this->hasMany('App\Models\TaskSession');
    }

    public function total_session()
    {
        return $this->tasks_session();
    }

    /** Get user task sessions of task */
    public function user_tasks_session()
    {
        return $this->hasMany('App\Models\TaskSession')->where('user_id', auth()->user()->id);
    }

    /** Get project asscoiated with task */
    public function project()
    {
        return $this->belongsTo('App\Models\Project');
    }

    /** Get branches associated with a task */
    public function branches()
    {
        return $this->hasMany('App\branches');
    }

    /** Get the date in day-month-Year. */
    public function getStartDateFormatAttribute()
    {
        return ucfirst(date_format(new DateTime($this->start_date), 'M d, Y'));
    }

    /** Get the date in day-month-Year. */
    public function getEndDateFormatAttribute()
    {
        return ucfirst(date_format(new DateTime($this->end_date), 'M d, Y'));
    }

    /** Get the date in day-month-Year. */
    public function getCreatedAtFormatAttribute()
    {
        return ucfirst(date_format(new DateTime($this->created_at), 'M d, Y'));
    }

    /** Get the date in day/month/Year. */
    public function getStartDateShowAttribute()
    {
        return ucfirst(date_format(new DateTime($this->start_date), 'd/m/Y'));
    }

    /** Get the date in day/month/Year. */
    public function getEndDateShowAttribute()
    {
        return ucfirst(date_format(new DateTime($this->end_date), 'd/m/Y'));
    }

    /** Get the date in day-month-Year. */
    public function getEndDateSubTaskFormatAttribute()
    {
        return ucfirst(date_format(new DateTime($this->end_date), 'M d Y'));
    }

    /** Build query for getting all task of a project */
    public function scopeAlltask($query, $id = 0)
    {
        if ($id != 0) {
            return $query->with(['project' => function ($q) use ($id) {
                $q->where('id', '=', $id);
            }])->whereHas('project', function ($query) use ($id) {
                $query->where('id', '=', $id);
            })->whereIn('status', ['In Progress', 'Development Completed', 'Under QA'])
                ->where('is_archived', 0);
        } else {
            //return $query->with('users')->where('tasks.percent_complete', '=', 0);
            return $query->with('users')->whereIn('status', ['In Progress', 'Development Completed', 'Under QA'])
                ->where('is_archived', 0);
        }
    }

    /** Build query for getting upcoming tasks of a project */
    public function scopeUpcomingtask($query, $id = 0)
    {
        if ($id != 0) {
            return $query->with(['project' => function ($q) use ($id) {
                $q->where('id', '=', $id);
            }])->whereHas('project', function ($query) use ($id) {
                $query->where('id', '=', $id);
            })->where('tasks.status', '=', 'Backlog')
                ->where('is_archived', 0);
        } else {
            return $query->with('users')->where('tasks.status', '=', 'Backlog')
                ->where('is_archived', 0);
        }
    }

    /** Build query for getting ongoing tasks of a project */
    public function scopeOngoingtask($query, $id = 0)
    {
        if ($id != 0) {
            return $query->with(['project' => function ($q) use ($id) {
                $q->where('id', '=', $id);
            }])->whereHas('project', function ($query) use ($id) {
                $query->where('id', '=', $id);
            })->where('tasks.status', '=', 'In Progress')
                ->where('is_archived', 0);
        } else {
            return $query->with('users')->where('tasks.status', '=', 'In Progress')
                ->where('is_archived', 0);
        }
    }

    /** Build query for getting completed tasks of a project */
    public function scopeCompletedtask($query, $id = 0)
    {
        if ($id != 0) {
            return $query->with(['project' => function ($q) use ($id) {
                $q->where('id', '=', $id);
            }])->whereHas('project', function ($query) use ($id) {
                $query->where('id', '=', $id);
            })->where('tasks.status', '=', 'Done')
                ->where('is_archived', 0);
        } else {
            return $query->with('users')->where('tasks.status', '=', 'Done')
                ->where('is_archived', 0);
        }
    }

    /** Build query for getting archived tasks of a project */
    public function scopeArchivedTask($query, $id = 0)
    {
        if ($id != 0) {
            return $query->with(['project' => function ($q) use ($id) {
                $q->where('id', '=', $id);
            }])->whereHas('project', function ($query) use ($id) {
                $query->where('id', '=', $id);
            })->where('is_archived', 1);
        } else {
            return $query->where('is_archived', 1);
        }
    }

    /** Build query for getting overdue tasks of a project */
    public function scopeOverduetask($query, $id = 0)
    {
        if ($id != 0) {
            return $query->with(['project' => function ($q) use ($id) {
                $q->where('id', '=', $id);
            }])->whereHas('project', function ($query) use ($id) {
                $query->where('id', '=', $id);
            })->where(function ($query) {
                $query->where('end_date', '<', date('Y-m-d'))->orWhereRaw('cast(estimated_time AS DECIMAL(10,2)) < cast(time_spent AS DECIMAL(10,2))');
            })->orderBy('title', 'ASC')->where('status', '!=', 'Done');
        } else {
            return $query->with('users', 'project')->where(function ($query) {
                $query->where('end_date', '<', date('Y-m-d'))->orWhereRaw('cast(estimated_time AS DECIMAL(10,2)) < cast(time_spent AS DECIMAL(10,2))');
            })->orderBy('title', 'ASC')->where('status', '!=', 'Done');
        }
    }

    public static function returnOverdueTask($request = null)
    {
        $tasks = Task::with('users', 'project')->where(function ($query) {
            $query->where('end_date', '<', date('Y-m-d'))->orWhereRaw('cast(estimated_time AS DECIMAL(10,2)) < cast(time_spent AS DECIMAL(10,2))');
        })->orderBy('title', 'ASC')->where('status', '!=', 'Done')->whereIn('status', config('overdue-status'));

        if ($request) {
            if ($request->project_id) {
                $tasks = $tasks->where('project_id', $request->project_id);
            }

            if ($request->client_id) {
                $tasks = $tasks->whereRelation('project', 'client_id', '=', $request->client_id);
            }
            if ($request->category) {
                $tasks = $tasks->whereRelation('project', 'category', '=', $request->category);
            }
            if (isset($request->get('search')['value'])) {
                $tasks = $tasks->whereRelation('project', 'project_name', 'like', '%'.$request->get('search')['value'].'%')
                               ->orWhere('title', 'like', '%'.$request->get('search')['value'].'%');
            }
        }
        $tasks = Task::getTotalOverDueTaskHours($tasks->get());

        return $tasks;
    }

    public static function getTotalOverDueTaskHours($tasks)
    {
        $total = ['estimate_hours' => 0, 'spent_hours' => 0];
        foreach ($tasks as $task) {
            $total['estimate_hours'] += $task->estimated_time;
            $total['spent_hours'] += $task->time_spent;
        }
        $tasks->total = $total;

        return $tasks;
    }

    public static function getOverdueTask($request = null)
    {
        $filter = null;
        if ($request) {
            $filter = $request->get('search')['value'];
        }

        $tasks = Task::with('users', 'project')->has('project')->where(function ($query) {
            $query->where('end_date', '<', date('Y-m-d'))->orWhereRaw('cast(estimated_time AS DECIMAL(10,2)) < cast(time_spent AS DECIMAL(10,2))');
        })->orderBy('title', 'ASC')->where('status', '!=', 'Done')->whereIn('status', config('overdue-status'));

        if ($filter) {
            $tasks = $tasks->whereHas('project', function ($q) use ($filter) {
                $q->where('project_name', 'like', '%'.$filter.'%');
                $q->orWhere('title', 'like', '%'.$filter.'%');
            });
            //$tasks = $tasks->orWhere('title', 'like', '%'.$filter.'%');
            //dd($tasks->toSql());
        }

        return $tasks->get();
    }

    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    /** Get reviewer associated with task */
    public function reviewer()
    {
        return $this->belongsTo('App\Models\User');
    }

    /** Get user who create the task */
    public function task_creator()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    /** Get user sessions of task */
    public function documents()
    {
        return $this->hasMany('App\Models\TaskDocument');
    }

    /** Get approvers that belongs to project */
    public function approvers()
    {
        return $this->belongsToMany('App\Models\User', 'task_approvers', 'task_id', 'user_id')->withPivot('status');
    }

    /**Get tag of a task */
    public function taskTag()
    {
        return $this->belongsTo('App\Models\TaskTag', 'tag', 'slug');
    }

    public function scopeNotArchived($query)
    {
        return $query->where('is_archived', '0');
    }

    public function scopeIsArchived($query)
    {
        return $query->where('is_archived', '1');
    }

    public function taskAssignedUsersHours()
    {
        return $this->hasMany(TaskAssignedUsersHour::class);
    }
}
