$(document).ready(function() {
    $(window).scroll(sticky_relocate);
    sticky_relocate();
    adjustHeight();
    fetchProjects();
    $('#start_date').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        format: "dd/mm/yyyy",
        autoclose: true,
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#end_date').datepicker('setStartDate', minDate);
    });
    $('#end_date').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        format: "dd/mm/yyyy",
        autoclose: true,
        startDate: $('#start_date').datepicker('getDate')
    }).on('changeDate', function (selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('#start_date').datepicker('setEndDate', maxDate);
    });
    fetchFilterProject();
});


function sticky_relocate() {
    var containerWidth = $(".fixed-sec").width();
    var window_top = $(window).scrollTop();
    var div_top = $('#sticky-anchor').offset().top;
    if (window_top > div_top) {
       $('.fixed-sec').addClass('stick');
       $('#sticky-anchor').addClass('height-set');
       $('.stick').css('width', containerWidth);
    } else {
       $('.fixed-sec').removeClass('stick');
       $('.fixed-sec').css('width', 'auto');
       $('#sticky-anchor').removeClass('height-set');
    }
 }

$(document).on('click', 'thead', function () {
    editDisable();
});

$(document).on('mousedown', function(event) {
    var target = $(event.target);
    if (!target.closest('table').length) {
        editDisable();
    }
});

function editDisable() {
    $(".task-text").css('display', 'block');
    $(".task-update").css('display', 'none');
}

$(document).on('click', '.pagination a', function (e) {
    e.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    fetchProjects(page);
});
function collapseProjectBox(id) {
    $(".project-sub-table-" + id).toggleClass('collase-active');
    $("#project-leftbar" + id).toggleClass('active-row');
    $("#project-rightbar" + id).toggleClass('active-row');
}

function adjustHeight(){
    $('.mainrow').each(function() {
        var id = $(this).attr('data-id');
        if (id) {
            var height = $(this).height()
            $('#mainrow' + id).height(height);
        }
    });
}

function filterProjects(){
    fetchFilterProject();
    fetchProjects();
}

function fetchProjects(page=1){
    var start_date=$("#start_date").val();
    var end_date=$("#end_date").val();
    var project=$("#project").val();
    openLoader();
    $.ajax({
        url: "/gantt-project-lists",
        data: {
            'start_date': start_date,
            'end_date': end_date,
            'project': project,
            'page': page
        },
        type: 'POST',
        success: function(response) {
            $("#gantt-project-list").html(response.data);
            adjustHeight();
            closeLoader();
        },
        error: function(error) {
            closeLoader();
            toastr.warning('Sorry, Please reload this page.', 'Failed');
        }
    });
}

function fetchFilterProject(){
    var start_date=$("#start_date").val();
    var end_date=$("#end_date").val();
    openLoader();
    $.ajax({
        url: "/fetch-filter-project",
        data: {
            'start_date': start_date,
            'end_date': end_date
        },
        type: 'POST',
        success: function(response) {
            $("#project").html(response);
            $("#project").trigger("chosen:updated");
            closeLoader();
        },
        error: function(error) {
            closeLoader();
            toastr.warning('Sorry, Please reload this page.', 'Failed');
        }
    });
}


function editField(id, type){
    $(".task-text").css('display', 'block');
    $(".task-update").css('display', 'none');
    $("#" + type + "-text-" + id).css('display', 'none');
    $("#" + type + "-input-" + id).css('display', 'block');
    
    editLoadFunction();
    adjustHeight();
}
function editLoadFunction(){
    $('.datetimepickernew').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        format: "dd/mm/yyyy",
        autoclose: true
    });
    $('.chosen-select').chosen({
        width:"100%"
    });
}

function updateProject(id, type) {
    var startdate = $('#project-startdate' + id).val();
    var enddate = $('#project-enddate' + id).val();
    var resources = $('#assigned_users' + id).val();
    var category = $('#project-category' + id).val();
    var priority = $('#project-priority' + id).val();
    var client = $('#project-client' + id).val();
    openLoader();
    $.ajax({
        url: "/gantt-project-update",
        data: {
            'project_id': id,
            'type': type,
            'resources': resources,
            'startdate': startdate,
            'enddate': enddate,
            'client': client,
            'category': category,
            'priority': priority
        },
        type: 'POST',
        success: function(response) {
            closeLoader();
            toastr.success('Task has been updated successfully', 'Task updated');
            $("#" + type + "-text-" + id).html(response);
            $(".task-text").css('display', 'block');
            $(".task-update").css('display', 'none');
            adjustHeight();
            if (type == "startdate" || type == "enddate") {
                fetchProjects();
            }
        },
        error: function(error) {
            closeLoader();
            toastr.warning('Sorry, Please reload this page.', 'Failed');
        }
    });
}
