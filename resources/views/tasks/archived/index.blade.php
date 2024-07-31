@extends('layout.main')
<!-- section starts -->
@section('content')
<div>
    <!-- Page Title -->
    <div class="row">
        <div class="col-md-12">
            <strong>
                <h2 class="page-title">Archived Tasks</h3>
            </strong>
        </div>
    </div>
        <div class="content-div animated fadeInUp">
        @include('tasks.archived.search')
        <div class="task ibox-content mt-15">
            @include('tasks.archived.list')
        </div>
    </div>
</div>

@include('tasks.destroy')
@endsection
@section('after_scripts')
<script src="{{ asset('js/plugins/iCheck/icheck.min.js') }}"></script>
<link href="{{ asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('js/resources/tasks/script-min.js') }}"></script>
<script type="text/javascript">
    inputsloader();
    function inputsloader() {
        $('body').on('click', "#filter-section", function() {
            $(".filter-area").toggle();
        });
        $('.chosen-select').chosen();
    }
        jQuery(document).on("change", "#filter_tasks", function(e) {

        searchTask(e);
    });
    jQuery(document).on("change", "#search_task_name", function(e) {
        searchTask(e);
    });
    jQuery(document).on("change", "#search_project_name", function(e) {
        searchTask(e);
    });
    jQuery(document).on("change", "#search_task_type", function(e) {
        searchTask(e);
    });
    jQuery(document).on("change", "#task_status", function(e) {
        searchTask(e);
    });

    $(document).on('change', '#search_project_company', function(e) {
        searchTask(e);
    });

    $(document).on('change', '#assigned_to', function(e) {
        searchTask(e);
    });

    //Task dropdown autocomplete
    $('#search_task_name_chosen .chosen-search input').autocomplete({
        search: function(event, ui) {
            /*keyCode will "undefined" if user presses any function keys*/
            if (event.keyCode) {
                event.preventDefault();
            }
        },
        source: function(request, response) {
            $.ajax({
                url: '/get-autocomplete-data-task',
                data: {
                    term: request.term
                },
                dataType: "json",
                success: function(data) {
                    $('#search_task_name').empty();
                    $('#search_task_name').append(
                        '<option value="">Select Task</option>');
                    if (data.length > 0) {
                        response($.map(data, function(item) {
                            $('#search_task_name').append(
                                '<option value="' + item.id + '">' +
                                item.title + '</option>');
                        }));
                    }
                    $("#search_task_name").trigger("chosen:updated");
                    $('#search_task_name_chosen .chosen-search input').val(request
                        .term);
                }
            });
        }
    });

    //Project dropdown autocomplete
    $('#search_project_name_chosen .chosen-search input').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: '/get-autocomplete-data-project',
                data: {
                    term: request.term
                },
                dataType: "json",
                success: function(data) {
                    $('#search_project_name').empty();
                    $('#search_project_name').append(
                        '<option value="">Select Project</option>');
                    if (data.length > 0) {
                        response($.map(data, function(item) {
                            $('#search_project_name').append(
                                '<option value="' + item.id + '">' +
                                item.project_name + '</option>');
                        }));
                    }
                    $("#search_project_name").trigger("chosen:updated");
                    $('#search_project_name_chosen .chosen-search input').val(request
                        .term);
                }
            });
        }
    });

function searchTask(e) {
    e.preventDefault();
    $('#search-task').submit();

}

$(document).on('change','.change-archive',function(e){
    var status = $(this).prop("checked");
     $.ajax({
            type: 'POST',
            url: '/change-archive',
            data: {
                'is_archived': $(this).prop("checked"),
                'id':$(this).data('id'),
            },
            success: function(response) {
                if(status){
                    toastr.success('Task Archived!', 'Arhived');
                    setTimeout(function () {
                        searchTask(e);
                    }, 1000);
                }
                else{
                    toastr.success('Task removed from Archived list!', 'Changed');
                    setTimeout(function () {
                        searchTask(e);
                    }, 1000);
                }
            },error: function(error) {
                toastr.error('Something Went Wrong!', 'Error');
            }
        });
});
</script>
@stop