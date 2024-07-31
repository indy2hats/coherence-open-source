<div class="col-sm-2 col-md-2">
    <div class="form-group no-margins">
        <select class="chosen-select leave-filter" id="month" name="month">
            <option value="">Select Month</option>              
            @for($month=1; $month<=12; $month++)
            <?php $month_label = date('m', mktime(0, 0, 0, $month, 1)); ?>
            <option value="{{$month_label}}">{{date('F', mktime(0,0,0,$month, 1, date('Y')))}}</option>
            @endfor          
        </select>    
    </div> 
</div>


