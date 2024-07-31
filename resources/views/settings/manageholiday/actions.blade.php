
<div class="text-right holiday-actions">
    @can('manage-holidays')
        <button class="btn btn-success" data-toggle="modal" data-target="#add_holiday"><i class="ri-add-line"></i> Add Holiday</button>
        <button class="btn btn-info" data-toggle="modal" data-target="#weekly_holiday"><i class="ri-calendar-check-fill"></i> Weekly Holidays</button>
    @endcan
    <a href={{route('holiday.export',$date)}} class="btn btn-primary" id="export_holiday"><i class="ri-download-line"></i> Export Holidays</a>
</div>
