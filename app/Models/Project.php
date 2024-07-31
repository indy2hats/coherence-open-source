<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Project extends Model implements Searchable
{
    use SoftDeletes;
    use HasFactory;

    /** The attributes that are mass assignable */
    protected $fillable = [
        'project_name',
        'client_id',
        'project_id',
        'project_type',
        'start_date',
        'end_date',
        'cost_type',
        'rate',
        'estimated_hours',
        'priority',
        'description',
        'status',
        'site_url',
        'category',
        'created_at',
        'is_archived',
        'technology_id'
    ];

    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->task()->delete();
        });
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('projects.show', $this->id);

        return new SearchResult(
            $this,
            $this->project_name,
            $url
        );
    }

    /**
     * Get client details associated with a project.
     */
    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    /** Get users belongs to project */
    public function projectUsers()
    {
        return $this->belongsToMany('App\Models\User', 'project_assigned_users', 'project_id', 'user_id');
    }

    /** Get users belongs to task */
    public function task()
    {
        return $this->hasMany('App\Models\Task');
    }

    /** returns date format for start date */
    public function getStartDateAttribute($date)
    {
        return date_format(new DateTime($date), 'd/m/Y');
    }

    /** Get the date in day-month-Year. */
    public function getStartDateFormatAttribute()
    {
        return $this->start_date;
    }

    /** Get the date in day-month-Year. */
    public function getEndDateFormatAttribute()
    {
        return $this->end_date;
    }

    /** Get the date in day-month-Year. */
    public function getCreatedAtFormatAttribute()
    {
        return ucfirst(date_format(new DateTime($this->created_at), 'd/m/Y'));
    }

    /** returns date format for end date */
    public function getEndDateAttribute($date)
    {
        return $date != '0000-00-00 00:00:00' ? date_format(new DateTime($date), 'd/m/Y') : '';
    }

    /* public static function returnOverdueProjects()
    {
        return Project::with('projectUsers', 'client')->where('status', '!=', 'Closed')->where('end_date', '<', date('Y-m-d'))->orderBy('project_name', 'ASC')->get();
    }
 */
    public static function returnOverdueProjects($request = null)
    {
        $filter = null;
        if ($request) {
            $filter = $request->get('search')['value'];
        }

        $projects = Project::with('projectUsers', 'client')
                        ->where('status', '!=', 'Closed')
                        ->where('end_date', '<', date('Y-m-d'))
                        ->orderBy('project_name', 'ASC');
        if ($filter) {
            $projects = $projects->whereHas('client', function ($q) use ($filter) {
                $q->where('company_name', 'like', '%'.$filter.'%');
            })
            ->orWhere('project_name', 'like', '%'.$filter.'%');
        }

        return $projects->get();
    }

    /** Get the date in day/month/Year. */
    public function getStartDateShowAttribute()
    {
        return $this->start_date;
    }

    /** Get the date in day/month/Year. */
    public function getEndDateShowAttribute()
    {
        return $this->end_date;
    }

    public function scopeNotArchived($query)
    {
        return $query->where('is_archived', '0');
    }

    public function scopeIsArchived($query)
    {
        return $query->where('is_archived', '1');
    }

    public function technology()
    {
        return $this->belongsTo('App\Models\Technology');
    }
}
