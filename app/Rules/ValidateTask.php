<?php

namespace App\Rules;

use App\Models\Task;
use Illuminate\Contracts\Validation\Rule;

class ValidateTask implements Rule
{
    private $projectId;

    private $jiraId;

    private $taskid;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($projId = null, $taskid = null, $jiraId = null)
    {
        $this->projectId = $projId;
        $this->taskid = $taskid;
        $this->jiraId = $jiraId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $taskId = $this->taskid;

        $jiraId = trim($this->jiraId);

        $task = Task::where('title', $value)
            ->where('project_id', $this->projectId)
            ->when($taskId != null, function ($query) use ($taskId) {
                return $query->where('id', '!=', $taskId);
            })->when($jiraId != null, function ($query) use ($jiraId) {
                return $query->where('task_id', $jiraId);
            })->count();

        if ($task == 0) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This task already exist under this project.';
    }
}
