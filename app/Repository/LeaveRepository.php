<?php

namespace App\Repository;

use App\Models\LeaveType;
use App\Models\User;
use App\Traits\GeneralTrait;

class LeaveRepository
{
    use GeneralTrait;

    protected $user;
    protected $leaveService;

    public function acceptLeave()
    {
        $id = request('leaveId');
        $this->updateLeave($id, ['status' => 'Approved', 'approved_by' => $this->getCurrentUserId()]);

        return $this->findLeaveById($id);
    }

    public function rejectLeave()
    {
        $id = request('leave_id');
        $this->updateLeave($id, ['status' => 'Rejected', 'reason_for_rejection' => request('reason'), 'approved_by' => $this->getCurrentUserId()]);

        return $this->findLeaveById($id);
    }

    public function getUsers()
    {
        return User::notClients()->active()->orderBy('first_name', 'ASC')->get();
    }

    public function getLeaveTypes()
    {
        return LeaveType::all();
    }
}
