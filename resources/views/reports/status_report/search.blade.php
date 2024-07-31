   
    <div class="row">
        <div class="col-md-3">
            <h2 id="formatted_date"></h2>
        </div>
        <div class="col-md-7 " style="padding-left: 0px;">
            <button type="button" class="btn btn-outline btn-link arrow-back no-padding" data-date=""><i class="ri-arrow-left-double-line ri-2x"></i></button>


            <button type="button" data-date="{{$date}}" class="btn btn-outline btn-link todayBtn"><strong><i class="ri-calendar-2-line"></i> Last Working Day</strong></button>


            <button type="button" class="btn btn-outline btn-link arrow-front no-padding" data-date=""><i class="ri-arrow-right-double-line ri-2x"></i></button>

            <form id="task-search-form" style="display: inline-block;padding-left: 15px;">

                <div class="form-group">
                    <input class="form-control active datepicker" type="text" id="daterange" name="daterange" style="margin-bottom: 10px; " value="{{$date}}" data-searchdate="{{$date}}">
                </div>
                
            </form>
        </div>
        <div class="col-md-1">
        </div>
    </div>