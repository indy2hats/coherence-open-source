<?php

namespace App\Services;

use App\Http\Controllers\TaskSessionController;
use App\Models\Task;
use App\Models\TaskSession;
use Auth;
use Illuminate\Http\Request;

class UserTimesheetService
{
    /**
     * Function to get add task session to timesheet.
     *
     * @param  Request  $request
     * @return mixed[] $response Array of response data
     */
    public function addTaskSession(Request $request)
    {
        $request->validate([
            'total' => 'required|numeric'
        ], [
            'total.required' => 'You have to enter time!',
            'total.numeric' => 'Invalid Time Entry!'
        ]);

        $min = floor(request('total') * 60);
        $taskController = new TaskSessionController();
        if ($this->isNewTaskSession()) {
            $taskData = [
                'task_id' => request('task_id'),
                'user_id' => Auth::user()->id,
                'current_status' => 'over',
                'created_at' => date('Y-m-d', strtotime(str_replace('/', '-', request('date')))),
                'total' => $min,
                'billed_today' => 0,
            ];

            $data = TaskSession::create($taskData);
            $session_id = $data->id;

            $day = date('N', strtotime(request('date')));
            $message = 'TaskSession created successfully';
        } else {
            $session_id = request()->session_id;
            $taskData = [
                'total' => $min,
                'billed_today' => 0,
            ];
            $day = date('N', strtotime(TaskSession::where('id', request()->session_id)->first()->created_at));
            $message = 'TaskSession details updated successfully';
            TaskSession::find($session_id)->update($taskData);
        }
        $taskController->updateTaskCompletion(request('task_id'));

        return response()->json(['success' => true,
            'message' => $message,
            'time' => $min,
            'day' => $day,
            'session_id' => $session_id
        ]);
    }

    /**
     * Function to check whether task session is new or not.
     *
     * @return bool
     */
    public function isNewTaskSession()
    {
        return request()->session_id == '';
    }

    /**
     * Function to validate if the task session date is less than task start date .
     *
     * @return bool
     */
    public function validateDate($task_id)
    {
        $task_date = Task::find($task_id)->start_date;
        $session_date = date('Y-m-d', strtotime(str_replace('/', '-', request('date'))));

        if ($session_date < $task_date) {
            return false;
        }

        return true;
    }
}
