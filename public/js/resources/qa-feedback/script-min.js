
    jQuery(document).ready(function() {
        loadInputs();
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
    });
     function loadInputs() {


        $('.chosen-select').chosen({
          width: "100%"
        });


        $('#daterange').daterangepicker({
                    opens: 'left',
                      locale: {
                        format: 'MMM DD, YYYY'
                      },
                    startDate: moment().subtract(1, 'day'),
                    maxDate: moment(),
                    ranges: {
                       'Today': [moment(), moment()],
                       'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                       'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                       'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                       'This Month': [moment().startOf('month'), moment().endOf('month')],
                       'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    }
                }, cb);
        }
        $('.rejectionTable').dataTable();

        $(document).on('click', '.delete-feedback', function() {
        var deleteFeedbackId = $(this).data('id');
        $('#delete_feedback #delete_feedback_id').val(deleteFeedbackId);
    });

        
         $('.summernote').summernote();

   

     $(document).on('click', '.create-feedback', function(e) {

        $('.field-error').html('');
        toasterOption();
        $("body").append(
            '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
        );
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: $('#add_feedback_form').attr('action'),
            data: $('#add_feedback_form').serialize(),
            success: function(response) {
                 $('#create_feedback').modal('hide');
                $(".overlay").remove();
                toastr.success(response.message, 'Created');
                $('.qaFeedback').DataTable().ajax.reload();
                loadInputs();
            },
            error: function(error) {
                 $(".overlay").remove();
                if (error.responseJSON.errors) {
                    console.log(error.responseJSON.errors);
                    $.each(error.responseJSON.errors, function(field, error) {
                        $('#label_' + field).html(error);
                    });
                }
            }
        });
    });

     /**
     * Removing validation errors and reset form on model window close
     */
    $('#create_feedback').on('hidden.bs.modal', function() {
        
        $(this).find('.text-danger').html('');
        $('#create_feedback').trigger('reset');
        $('.chosen-select').val('').trigger('chosen:updated');
        $('.summernote').summernote('reset');
    });


    // $(document).on('click', '.search', function(e) {
    //     loadFeedbacks();
    // });


    function loadFeedbacks() {
        openLoader();
        table.draw();
        // $.ajax({
        //     method: 'POST',
        //     url: '/qa-feedback',
        //     data: $("#user-search-form").serialize(),
        //     success: function(response) {
        //         closeLoader();
        //         $('.qaFeedback').DataTable().ajax.reload();
        //         loadInputs();
        //     }
        // });
    }

    function cb(start, end) {
        $('#daterange').val(start.format('MMM DD, YYYY') + ' - ' + end.format('MMM DD, YYYY'));
    }

    $(document).on('click', '#qa-report-table img', function() {
        var src = $(this).attr("src");
        $("#qa-report-img img").attr("src", src);
        $('#qa-report-img').modal('show');
    });
    

$(document).ready(function (e) {
    var columns = [
        { data: 'reportedDate', name: 'reportedDate' },
        { data: 'user', name: 'user' },
        { data: 'task', name: 'task' },
        { data: 'severity', name: 'severity' },
        { data: 'reason', name: 'reason' },
        { data: 'exceedReason', name: 'exceedReason' },
        { data: 'comments', name: 'comments' }
    ];
    
    var actionColumn = $('.qaFeedback th:contains("Action")');
    if (actionColumn.length) {
        columns.push({ data: 'action', name: 'action' });
    }
    
    var table = $('.qaFeedback').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 10,
        ordering: false,
        ajax: {
            url: "/qa-feedback",
            data: function (d) {
                d.by_user = $('#by_user').val(),
                d.daterange = $('#daterange').val()
            }
        },
        columns: columns
    });

        $(document).on('click', '.search', function(e) {
            table.draw();
    });

    $(document).on('click', '#delete_feedback .continue-btn', function(e) {
        toasterOption();
        var deleteFeedbackId = $('#delete_feedback #delete_feedback_id').val();
        var deleteUrl = '/qa-feedback/' + deleteFeedbackId;
        $("body").append(
            '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
        );
        $('#delete_feedback').modal('hide');
        $.ajax({
            type: 'DELETE',
            url: deleteUrl,
            data: {},
            success: function(response) {
                $(".overlay").remove();
                toastr.success(response.message, 'Deleted');
                table.draw();  
                         
            },
            error: function(error) {}
        });
    });
});
