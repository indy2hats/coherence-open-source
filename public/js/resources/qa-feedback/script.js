
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
                    maxDate: moment().subtract(1, 'days'),
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
                $('.list').html(response.data);
                toastr.success(response.message, 'Deleted');
                $('.rejectionTable').dataTable();
                 loadInputs();
            },
            error: function(error) {}
        });
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
                $('.list').html(response.data);
                toastr.success(response.message, 'Created');
                $('.rejectionTable').dataTable();
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


    $(document).on('click', '.search', function(e) {
        loadFeedbacks();
    });


    function loadFeedbacks() {
        openLoader();
        $.ajax({
            method: 'POST',
            url: '/user-feedback-search',
            data: $("#user-search-form").serialize(),
            success: function(response) {
                closeLoader();
                $(".issues").html(response.data);
                $(".rejectionTable").dataTable();
                loadInputs();
            }
        });
    }

    function cb(start, end) {
        $('#daterange').val(start.format('MMM DD, YYYY') + ' - ' + end.format('MMM DD, YYYY'));
    }

