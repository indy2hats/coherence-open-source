<div class="col-sm-2 col-md-2">
    <div class="form-group text-right">
        <div class="input-group date">
            <span class="input-group-addon">
                <i class="ri-calendar-2-line"></i>
            </span>
            @php 
                $date = $filter['date'] ??  date('d/m/Y');
            @endphp
            <input class="form-control timesheet-datepicker dateInput" type="text" name="filter[date]" id="date" value="{{ $date }}" />
        </div>                    
    </div>
</div>