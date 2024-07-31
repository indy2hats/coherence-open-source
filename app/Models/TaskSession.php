<?php

namespace App\Models;

use Auth;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'user_id',
        'current_status',
        'start_time',
        'end_time',
        'total',
        'billed_today',
        'comments',
        'session_type',
        'created_at',
    ];

    /** Return date in the given format */
    public function getCreatedAtAttribute($date)
    {
        return date_format(new DateTime($date), 'd-m-Y');
    }

    /** Get task associated with task session */
    public function task()
    {
        return $this->belongsTo('App\Models\Task');
    }

    /**
     * Get user details associated with a tasksession.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /** Get the comment in 200. */
    public function getShortCommentAttribute()
    {
        return ucfirst(strlen($this->comments) > 200 ? substr($this->comments.' ...', start) : $this->comments);
    }

    public static function returnActiveUsers($users = null, $request = null)
    {
        $filter = null;
        if ($request) {
            $filter = $request->get('search')['value'];
        }

        $taskSession = TaskSession::with('user', 'task', 'task.project', 'user.users_project')
            ->when($users != null, function ($query) use ($users) {
                return $query->whereIn('user_id', $users);
            })
            ->whereHas('task.project', function ($query) {
                $query->where('category', '!=', 'Upskilling');
                $query->whereNull('deleted_at');
            })->whereIn('current_status', ['started', 'resume'])
            ->where('created_at', 'like', '%'.date('Y-m-d').'%')
            ->whereHas('user.role', function ($q) {
                $q->where('name', '!=', 'administrator');
                $q->where('name', '!=', 'consultant');
            });

        if ($filter) {
            $taskSession = $taskSession->whereRelation('user', function ($q) use ($filter) {
                $q->where('first_name', 'like', '%'.$filter.'%');
                $q->orWhere('last_name', 'like', '%'.$filter.'%');
            });
        }

        return $taskSession->get();
    }

    public static function returnUpskillingUsers($users = null, $request = null)
    {
        $filter = null;
        if ($request) {
            $filter = $request->get('search')['value'];
        }

        $taskSession = TaskSession::with('task', 'task.project')
            ->when($users != null, function ($query) use ($users) {
                return $query->whereIn('user_id', $users);
            })
            ->whereIn('current_status', ['started', 'resume'])
            ->whereHas('task.project', function ($query) {
                $query->where('category', 'Upskilling');
                $query->whereNull('deleted_at');
            })
            ->where('created_at', 'like', '%'.date('Y-m-d').'%')
            ->whereHas('user.role', function ($q) {
                $q->where('name', '!=', 'administrator');
            });
        if ($filter) {
            $taskSession = $taskSession->whereRelation('user', function ($q) use ($filter) {
                $q->where('first_name', 'like', '%'.$filter.'%');
                $q->orWhere('last_name', 'like', '%'.$filter.'%');
            });
        }

        return $taskSession->get();
    }

    public function scopeContractUsers($query)
    {
        return $query->whereHas('user', function ($q) {
            $q->where('contract', 1);
        });
    }

    public function scopeNonContractUsers($query)
    {
        return $query->whereHas('user', function ($q) {
            $q->where('contract', 0);
        });
    }

    public static function getPausedTasksofUser()
    {
        $tasks = TaskSession::with('task')
            ->where('user_id', Auth::user()->id)
            ->where('current_status', 'pause')
            ->has('task.project')
            ->get();

        return $tasks;
    }
}
