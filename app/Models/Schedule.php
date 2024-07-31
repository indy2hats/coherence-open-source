<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'recruitment_id',
        'machine_test1',
        'machine_test2',
        'technical_interview',
        'hr_interview',
        'machine_test1_status',
        'machine_test2_status',
        'technical_interview_status',
        'hr_interview_status'
    ];

    public function candidate()
    {
        return $this->belongsTo('App\Models\Recruitment', 'recruitment_id', 'id');
    }

    //machine test 1
    public function getMachineTestOneDateAttribute($date)
    {
        return $this->machine_test1 ? ucfirst(date_format(new DateTime($this->machine_test1), 'd/m/Y')) : '';
    }

    public function getMachineTestOneTimeAttribute($date)
    {
        return $this->machine_test1 ? ucfirst(date_format(new DateTime($this->machine_test1), 'H:m:s')) : '';
    }

    public function getMachineTestOneTimeFormatAttribute($date)
    {
        return $this->machine_test1 ? ucfirst(date_format(new DateTime($this->machine_test1), 'h:i A')) : '';
    }

    public function getMachineTestOneFormatAttribute($date)
    {
        return ucfirst(date_format(new DateTime($this->machine_test1), 'M d, Y'));
    }

    //machine test 2
    public function getMachineTestTwoDateAttribute($date)
    {
        return $this->machine_test2 ? ucfirst(date_format(new DateTime($this->machine_test2), 'd/m/Y')) : '';
    }

    public function getMachineTestTwoTimeAttribute($date)
    {
        return $this->machine_test2 ? ucfirst(date_format(new DateTime($this->machine_test2), 'H:m:s')) : '';
    }

    public function getMachineTestTwoTimeFormatAttribute($date)
    {
        return $this->machine_test2 ? ucfirst(date_format(new DateTime($this->machine_test2), 'h:i A')) : '';
    }

    public function getMachineTestTwoFormatAttribute($date)
    {
        return ucfirst(date_format(new DateTime($this->machine_test2), 'M d, Y'));
    }

    //Technical
    public function getTechnicalInterviewDateAttribute($date)
    {
        return $this->technical_interview ? ucfirst(date_format(new DateTime($this->technical_interview), 'd/m/Y')) : '';
    }

    public function getTechnicalInterviewTimeAttribute($date)
    {
        return $this->technical_interview ? ucfirst(date_format(new DateTime($this->technical_interview), 'H:m:s')) : '';
    }

    public function getTechnicalInterviewTimeFormatAttribute($date)
    {
        return $this->technical_interview ? ucfirst(date_format(new DateTime($this->technical_interview), 'h:i A')) : '';
    }

    public function getTechnicalInterviewFormatAttribute($date)
    {
        return $this->technical_interview ? ucfirst(date_format(new DateTime($this->technical_interview), 'M d, Y')) : '';
    }

    //hr
    public function getHrInterviewDateAttribute($date)
    {
        return $this->hr_interview ? ucfirst(date_format(new DateTime($this->hr_interview), 'd/m/Y')) : '';
    }

    public function getHrInterviewTimeAttribute($date)
    {
        return $this->hr_interview ? ucfirst(date_format(new DateTime($this->hr_interview), 'H:m:s')) : '';
    }

    public function getHrInterviewTimeFormatAttribute($date)
    {
        return $this->hr_interview ? ucfirst(date_format(new DateTime($this->hr_interview), 'h:i A')) : '';
    }

    public function getHrInterviewFormatAttribute($date)
    {
        return $this->hr_interview ? ucfirst(date_format(new DateTime($this->hr_interview), 'M d, Y')) : '';
    }
}
