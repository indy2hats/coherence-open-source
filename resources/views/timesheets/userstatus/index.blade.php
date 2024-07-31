@extends('layout.main')
@section('content')
<div id="tableContent">
    @include('timesheets.userstatus.timesheet')
</div>
<div class="text-center">
	<img src="{{asset('images/loading.gif')}}" width="200" class="loading hidden "/>
</div>
@endsection
@section('after_scripts')
<script src="{{ asset('js/resources/timesheets/userstatus/script-min.js') }}"></script>
<script>
    jQuery(document).on("change", ".new-task", function() {
      var name = $("option:selected",this).text();
      var id = $("option:selected",this).val();
      var start = $("option:selected",this).data('start');
      var start = convertDate(start);
      var option = '<option value="'+id+'" selected>'+name+'</option>';
      $('.new-task').empty().append(option).trigger("chosen:updated");
      inputObject = $(this).closest('tr');
      $(this).removeClass('new-task');

      var tdList = '';
      var currentDate = $('#search-head').attr('data-start-date');
      var date = [currentDate,createDate(currentDate,0,1),createDate(currentDate,1,1),createDate(currentDate,2,1),createDate(currentDate,3,1),createDate(currentDate,4,1),createDate(currentDate,5,1),createDate(currentDate,6,1)];
      for(var i=1;i<8;i++){
        tdList +='<td '+checkValue(i)+'><div><input class="form-control inputBox" data-start="'+start+'" data-task_id="'+id+'" data-date="'+date[i]+'" data-session_id="" type="text" value="" data-value="" style="text-align: center;height: 30px;border-radius: 5px;box-shadow: 0 0 5px rgba(81, 203, 238, 1); background:#fff;" data-toggle="tooltip" data-placement="bottom" title="Format: Eg- 1.5 = 1h 30m" readonly></div></td>';   
        
      }
      tdList +='<td id="td_'+id+'" style="text-align: center;padding-top: 15px;font-weight: bolder;font-size: 15px">0h 0m</td>';

      inputObject.append(tdList);
       $('table tbody').append('<tr ><td width="200"><select class="chosen-select new-project"><option value="">Select Project</option>@foreach ($projects as $project)<option value="{{$project->id}}" {{ request()->projectID ? 'selected': '' }}>{{$project->project_name}}</option> @endforeach</select></td></tr>');
      loadInputs();
    });</script>
@endsection