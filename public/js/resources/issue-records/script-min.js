
    $(document).ready(function() {
        $('.summernote').summernote();
        loadInputs();
        if($("#task_content").length) {
            loadTaks();
        }
        $(".search").on('click', function(e){
            e.preventDefault();
            loadTaks();
        });

        $(document).on('click', '.create-issue', function(e) {
            toasterOption();
            e.preventDefault();
            $('.field-error').html('');
            openLoader();
            var data = $('#add_issue_form').serialize();
            $.ajax({
                url: $('#add_issue_form').attr('action'),
                data: data,
                type: 'POST',
                success: function(response) {
                    $('#add_issue').modal('hide');
                    loadTaks();
                    toastr.success(response.message, 'Created');
                },
                error: function(error) {
                    closeLoader();
                    if (error.status == 422) {
                        if (error.responseJSON.errors) {
                            $.each(error.responseJSON.errors, function(field, error) {
                                $('#add_issue_form #label_' + field).html(error);
                            });
                        }
                    }
                }
            });
        });

        $(document).on('click', '.create-category', function(e) {
            toasterOption();
            e.preventDefault();
            $('.field-error').html('');
            openLoader();
            var data = $('#add_category_form').serialize();
            $.ajax({
                url: $('#add_category_form').attr('action'),
                data: data,
                type: 'POST',
                success: function(response) {
                    $('#add_category').modal('hide');
                    closeLoader();
                    toastr.success(response.message, 'Created');
                    if(($("#edit_issue").data('bs.modal') || {}).isShown) {
                            $('#edit_issue_form #category').append('<option selected value="'+response.category.slug+'">'+response.category.title+'</option>').trigger("chosen:updated");
                    }
                    if ($('#add_issue_form #category').length){
                        if(($("#add_issue").data('bs.modal') || {}).isShown) {
                           $('#add_issue_form #category').append('<option selected value="'+response.category.slug+'">'+response.category.title+'</option>').trigger("chosen:updated"); 
                        } else {
                            $('#add_issue_form #category').append('<option value="'+response.category.slug+'">'+response.category.title+'</option>').trigger("chosen:updated"); 
                        }
                    }

                    if($('#issue-search-form #category').length){
                        $('#issue-search-form #category').append('<option value="'+response.category.slug+'">'+response.category.title+'</option>').trigger("chosen:updated");
                    }
                },
                error: function(error) {
                    closeLoader();
                    if (error.status == 422) {
                        if (error.responseJSON.errors) {
                            $.each(error.responseJSON.errors, function(field, error) {
                                $('#add_category_form #label_' + field).html(error);
                            });
                        }
                    }
                }
            });
        });

        $(document).on('click', '.edit-issue', function(e) {
            e.preventDefault();
            openLoader();
            var issueId = $(this).data('id');
            var editUrl = "/issue-records/" + issueId + '/edit';

            $.ajax({
                type: 'GET',
                url: editUrl,
                data: {},
                success: function(data) {
                    $('#edit_issue').html(data);
                    loadInputs();
                    closeLoader();
                    $('.summernote').summernote();
                    $("#edit_issue").modal('show');
                }
            });
        });

        $(document).on('click', '.update-issue', function(e) {
            toasterOption();
            $('.field-error').html('');
            e.preventDefault();
            openLoader();
            $.ajax({
                type: 'POST',
                url: $('#edit_issue_form').attr('action'),
                data: $('#edit_issue_form').serialize(),
                success: function(response) {
                    $('#edit_issue').modal('hide');
                    closeLoader();
                    toastr.success(response.message, 'Updated');
                    if($("#task_content").length) {
                        loadTaks();
                    } else {
                        window.location.reload();
                    }
                },
                error: function(error) {
                    $(".overlay").remove();
                    if (error.responseJSON.errors) {
                        $.each(error.responseJSON.errors, function(field, error) {
                            $('#edit_issue_form #label_' + field).html(error);
                        });
                    }
                }
            });
        });

        $(document).on('click', '.delete_issue_onclick', function() {
            var deleteId = $(this).data('id');
            $('#delete_issue #delete_issue_id').val(deleteId);
        });

        $(document).on('click', '#delete_issue .continue-btn', function() {
            toasterOption();
            var deleteId = $('#delete_issue #delete_issue_id').val();
            var deleteUrl = "/issue-records/" + deleteId;
            openLoader();
            $('#delete_issue').modal('hide');
            $.ajax({
                type: 'DELETE',
                url: deleteUrl,
                data: {},
                success: function(response) {
                    $(".overlay").remove();
                    toastr.success(response.message, 'Deleted');
                    if($("#task_content").length) {
                        loadTaks();
                    } else {
                        window.location = '/issue-records';
                    }
                },
                error: function(error) {
                }
            });
        });


         /**
         * Removing validation errors and reset form on model window close
         */
        $('#add_issue').on('hidden.bs.modal', function() {
            $(this).find('.text-danger').html('');
            $('#add_issue_form').trigger('reset');
            $('.chosen-select').val('').trigger('chosen:updated');
        });

        $('#add_category').on('hidden.bs.modal', function() {
            $(this).find('.text-danger').html('');
            $('#add_category_form').trigger('reset');
        });
    });
    function loadInputs() {


    $('.chosen-select').chosen({
      width: "100%"
    });

    $('input[name="daterange"]').daterangepicker({
          opens: 'left',
          locale: {
            format: 'DD/MM/YYYY'
          }
        }); 

     $('#fromdate').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            format: "dd/mm/yyyy",
            autoclose: true,
        }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#todate').datepicker('setStartDate', minDate);
        });
        $('#todate').datepicker({

            keyboardNavigation: false,
            forceParse: false,
            format: "dd/mm/yyyy",
            autoclose: true
        }).on('changeDate', function (selected) {
            var maxDate = new Date(selected.date.valueOf());
            $('#fromdate').datepicker('setEndDate', maxDate);
        });
    }

    function loadTaks() {
        openLoader();
        $.ajax({
            method: 'POST',
            url: '/issue-record-search',
            data: $("#issue-search-form").serialize(),
            success: function(response) {
                closeLoader();                
                $("#task_content").html(response.data);
                if($('.listData tbody tr').length > 1){
                    $(".listData").dataTable({
                            "lengthMenu": [[25, 50, -1], [25, 50, "All"]]
                    });
                }
            }
        });
    }

    $(document).on('click', '#issue-records-table img', function() {
        var src = $(this).attr("src");
        $("#issue-records-img img").attr("src", src);
        $('#issue-records-img').modal('show');
    });
