


$('body').on('click', "#filter-section", function() {
    $(".filter-area").toggle();
});


$(document).keydown(function(e) {
    // ESCAPE key pressed
    if (e.keyCode == 27) {
        $('#create_task').modal('hide');
        $('#edit_task').modal('hide');
        $('#delete_task').modal('hide');

    }
});

jQuery(document).ready(function() {

    inputsLoader();
    loadIchecks();

    $(document).on("show.bs.modal", '.modal', function(event) {
        var zIndex = 100000 + (10 * $(".modal:visible").length);
        $(this).css("z-index", zIndex);
        setTimeout(function() {
            $(".modal-backdrop").not(".modal-stack").first().css("z-index", zIndex - 1)
                .addClass("modal-stack");
        }, 0);
    }).on("hidden.bs.modal", '.modal', function(event) {
        console.log("Global hidden.bs.modal fire");
        $(".modal:visible").length && $("body").addClass("modal-open");
    });
    $(document).on('inserted.bs.tooltip', function(event) {
        var zIndex = 100000 + (10 * $(".modal:visible").length);
        var tooltipId = $(event.target).attr("aria-describedby");
        $("#" + tooltipId).css("z-index", zIndex);
    });
    $(document).on('inserted.bs.popover', function(event) {
        var zIndex = 100000 + (10 * $(".modal:visible").length);
        var popoverId = $(event.target).attr("aria-describedby");
        $("#" + popoverId).css("z-index", zIndex);
    });

    $('.create-modal').click(function(e) {
        e.preventDefault();
        $('.datetimepicker').datepicker('setDate', new Date());
        var task_parent_id=$(this).attr('data-id');
        if(task_parent_id){
            $('#task_parent').val(task_parent_id).trigger('chosen:updated');
        }
        $('#create_task').modal('show');
    });
    $(document).on('click','.create-modal',function(e) {
        e.preventDefault();
        $('.datetimepicker').datepicker('setDate', new Date());
        var task_parent_id=$(this).attr('data-id');
        if(task_parent_id){
            $('#task_parent').val(task_parent_id).trigger('chosen:updated');
        }
        $('#create_task').modal('show');
    });

    $(document).on('click', '.create-task', function(e) {
        var page=$("#create_task_action").attr('data-action');
        toasterOption();
        e.preventDefault();
        $('.field-error').html('');
        $("body").append(
            '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
        );

        var data = new FormData($('#add_task_form')[0]);
        $.ajax({
            url: $('#add_task_form').attr('action'),
            data: data,
            type: 'POST',
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                $('#create_task').modal('hide');
                $(".overlay").remove();
                toastr.success(response.message, 'Created');
                if(page=="gantt"){
                    fetchTasks();
                }else{
                    unloadIchecks();
                    $('.nav-tabs a[href="#tab-1"]').tab('show');
                    searchTask(e);
                }
            },
            error: function(error) {
                $(".overlay").remove();
                $('.nav-tabs a[href="#tab-1"]').tab('show');
                if (error.status == 422) {
                    if (error.responseJSON.errors) {
                        $.each(error.responseJSON.errors, function(field, error) {
                            $('#label_' + field).html(error);
                        });
                    }
                  
                }
            }
        });
    });



    /**
     * Removing validation errors and reset form on model window close
     */
    $('#create_task').on('hidden.bs.modal', function() {
        $(this).find('.text-danger').html('');
        $('#add_task_form').trigger('reset');
        $('.summernote').summernote('reset');

        //}
    });

    $('#create_task').on('shown.bs.modal', function() {
        $('.summernote').summernote({
            tooltip: false,
            dialogsInBody: true,
            dialogsFade: false,
            callbacks: {
                onImageUpload: function(files, editor) {
                    for (let i = 0; i < files.length; i++) {
                        sendFile(files[i], $(this));
                    }
                }
            }
        });
    });

    /** to update on enter key */
    $(document).on('keyup', '#edit_task_title', function(event) {
        if (event.keyCode === 13) {
            $('.update-task').click();
        }
    });

    $(document).on('keyup', '#edit_estimated_time', function(event) {
        if (event.keyCode === 13) {
            $('.update-task').click();
        }
    });

    $(document).on('keyup', '#edit_url', function(event) {
        if (event.keyCode === 13) {
            $('.update-task').click();
        }
    });

    $(document).on('keyup', '#percent_complete', function(event) {
        if (event.keyCode === 13) {
            $('.update-task').click();
        }
    });


    /**
     * Task edit form loading when clicking edit
     */
    $(document).on('click', '.edit-task', function(e) {
        e.preventDefault();
        $("body").append(
            '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
        );
        var taskId = $(this).data('id');
        var editUrl = '/tasks/' + taskId + '/edit';

        $.ajax({
            type: 'GET',
            url: editUrl,
            data: {},
            success: function(data) {
                $('#edit_task').html(data);
                inputsLoader();
                loadIchecks();
                $(".overlay").remove();
                $("#edit_task").modal('show');
            }
        });
    });

    /**
     * Edit task form - submit button action
     */
    $(document).on('click', '.update-task', function(e) {
        var page=$("#edit_task").attr('data-action');
        toasterOption();
        $('.field-error').html('');
        e.preventDefault();
        $("body").append(
            '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
        );
        var data = new FormData($('#edit_task_form')[0]);
        $.ajax({
            type: 'POST',
            url: $('#edit_task_form').attr('action'),
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                $(".overlay").remove();
                
                toastr.success(response.message, 'Updated')
                if(page=="gantt"){
                    fetchTasks(1,true);
                }else{
                    $('#edit_task').modal('hide');
                    searchTask(e);
                }
            },
            error: function(error) {
                $(".overlay").remove();
                $('.nav-tabs a[href="#edit-tab-1"]').tab('show');
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function(field, error) {
                        $('#label_' + field).html(error);
                    });
                }
            }
        });
    });

    $('#edit_task').on('shown.bs.modal', function() {
        $('.summernote').summernote({
            tooltip: false,
            dialogsInBody: true,
            dialogsFade: false,
            callbacks: {
                onImageUpload: function(files, editor) {
                    for (let i = 0; i < files.length; i++) {
                        sendFile(files[i], $(this));
                    }
                }
            }
        });
    });

    $(document).on('click', '.delete_task_onclick', function() {
        var deleteTaskId = $(this).data('id');
        $('#delete_task #delete_task_id').val(deleteTaskId);
    });


    /**
     * Delete task form - continue button action
     */
    $(document).on('click', '#delete_task .continue-btn', function(e) {
        var page=$("#delete_task_action").attr('data-action');
        toasterOption();
        var deleteTaskId = $('#delete_task #delete_task_id').val();
        var deleteUrl = '/tasks/' + deleteTaskId;
        $("body").append(
            '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
        );
        $('#delete_task').modal('hide');
        $.ajax({
            type: 'DELETE',
            url: deleteUrl,
            data: {},
            success: function(response) {
                toastr.success(response.message, 'Archived');
                if(page=="gantt"){
                    fetchTasks();
                }else{
                    $(".overlay").remove();
                    searchTask(e);
                }
                
            },
            error: function(error) {}
        });
    });

    $(document).on('click', '.delete-doc', function(e) {
        e.preventDefault();
        var $this = $(this);
        var deleteUrl = $(this).attr('href');
        $("body").append(
            '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
        );
        $.ajax({
            type: 'GET',
            url: deleteUrl,
            data: {},
            success: function(response) {
                closeLoader();
                $this.parents('.doc-repeat').remove();
            },
            error: function(error) {}
        });
    });

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
        select: function(event, ui) {
            var keyCode = $.ui.keyCode;
            var proceed = true;
            switch (event.keyCode) {
              case keyCode.PAGE_UP:
              case keyCode.PAGE_DOWN:
              case keyCode.UP:
              case keyCode.DOWN:
                event.preventDefault();
                break;
                
            }
            return false;
        },
        source: function(request, response) {
            $.ajax({
                url: '/get-autocomplete-data-task',
                data: {
                    term: request.term,
                    project_ids : $("#search_project_name").val()
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
    $('#search_project_name_chosen .search-field input').autocomplete({
        minLength: 0,
        search: function(event, ui) {
            /*keyCode will "undefined" if user presses any function keys*/
            if (event.keyCode) {
                event.preventDefault();
            }
        },
        select: function(event, ui) {
            var keyCode = $.ui.keyCode;
            var proceed = true;
            switch (event.keyCode) {
              case keyCode.PAGE_UP:
              case keyCode.PAGE_DOWN:
              case keyCode.UP:
              case keyCode.DOWN:
                event.preventDefault();
                break;
                
            }
            return false;
        },
        
        source: function(request, response) {
            $.ajax({
                url: '/get-autocomplete-data-project',
                data: {
                    term: request.term
                },
                dataType: "json",
                success: function(data) {
                    $('#search_project_name').empty();
                    if (data.length > 0) {
                        response($.map(data, function(item) {
                            $('#search_project_name').append(
                                '<option value="' + item.id + '">' +
                                item.project_name + '</option>');
                        }));
                    }
                    $("#search_project_name").trigger("chosen:updated");
                    $('#search_project_name_chosen .search-field input').val(request
                        .term);
                }
            });
        }
        
    })
});

function searchTask(e) {
    e.preventDefault();
    $('#search-task').submit();

}


function typeAhead() {
    $.get('get-typhead-data-project',
        function(response) {
            var name = [],
                id = [];
            for (var i = response.data.length - 1; i >= 0; i--) {
                name.push(response.data[i]['project_name']);
                id.push(response.data[i]['project_id']);
            }
            $(".typeahead_name").typeahead({
                source: name
            });
            $(".typeahead_id").typeahead({
                source: id
            });
        }, 'json');
}

function inputsLoader() {

    //$('.summernote').summernote();

    $('.chosen-select').chosen({
        allow_single_deselect:true,
        width: "100%",
    });
    $('.datetimepicker').mask('00/00/0000');
    $('.datetimepicker').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        format: "dd/mm/yyyy",
        autoclose: true
    });

}

function sendFile(file, editor, welEditable) {
    var data = new FormData();
    data.append("file", file);
    var url = '/content-image-upload';
    $("body").append(
        '<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>'
    );
    $.ajax({
        data: data,
        type: "POST",
        url: url,
        cache: false,
        contentType: false,
        processData: false,
        success: function(url) {
            $(".overlay").remove();
            editor.summernote('editor.insertImage', url);
        }
    });
}

function loadIchecks() {
    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
    });

    $('.check-all').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
    });

    $('.check-all').on('ifChecked', function(event) {
        $(this).parents('tr').find('.i-checks').iCheck('check');
        triggeredByChild = false;
    });

    $('.check-all').on('ifUnchecked', function(event) {
        if (!triggeredByChild) {
            $(this).parents('tr').find('.i-checks').iCheck('uncheck');
        }
        triggeredByChild = false;
    });
    // Removed the checked state from "All" if any checkbox is unchecked
    $('.i-checks').on('ifUnchecked', function(event) {
        triggeredByChild = true;
        $(this).parents('tr').find('.check-all').iCheck('uncheck');
    });

    $('.i-checks').on('ifChecked', function(event) {
        if ($(this).parents('tr').find('.i-checks').filter(':checked').length == $(this).parents('tr').find(
                '.i-checks').length) {
            $(this).parents('tr').find('.check-all').iCheck('check');
        }
    });

    $(".check-all").each(function() {
        if ($(this).parents('tr').find('.i-checks').filter(':checked').length == $(this).parents('tr').find(
                '.i-checks').length) {
            $(this).iCheck('check');
        }
    });
}

function unloadIchecks() {
    $('.i-checks').iCheck('uncheck');
    $(".check-all").iCheck('uncheck');
}



    $(document).ready(function(){

        $(document).on('click', '.clear-single-filter', function(e) {
            $(this).parent().find('.chosen-select').val('').trigger('chosen:updated');
            searchTask(e)
        });
        $(document).on('click', '.create-tag', function(e) {
            toasterOption();
            e.preventDefault();
            $('.field-error').html('');
            openLoader();
            var data = $('#add_tag_form').serialize();
            $.ajax({
                url: $('#add_tag_form').attr('action'),
                data: data,
                type: 'POST',
                success: function(response) {
                    $('#add_tag').modal('hide');
                    closeLoader();
                    toastr.success(response.message, 'Created');
                    if(($("#edit_task").data('bs.modal') || {}).isShown) {
                            $('#edit_task_form #tag').append('<option selected value="'+response.tag.slug+'">'+response.tag.title+'</option>').trigger("chosen:updated");
                    }
                    if(($("#create_sub_task").data('bs.modal') || {}).isShown) {
                            $('#add_sub_task_form #tag').append('<option selected value="'+response.tag.slug+'">'+response.tag.title+'</option>').trigger("chosen:updated");
                    }
                    if ($('#add_task_form #tag').length){
                        if(($("#create_task").data('bs.modal') || {}).isShown) {
                           $('#add_task_form #tag').append('<option selected value="'+response.tag.slug+'">'+response.tag.title+'</option>').trigger("chosen:updated"); 
                        } else {
                            $('#add_task_form #tag').append('<option value="'+response.tag.slug+'">'+response.tag.title+'</option>').trigger("chosen:updated"); 
                        }
                    }
                },
                error: function(error) {
                    closeLoader();
                    if (error.status == 422) {
                        if (error.responseJSON.errors) {
                            $.each(error.responseJSON.errors, function(field, error) {
                                $('#add_tag_form #label_' + field).html(error);
                            });
                        }
                    }
                }
            });
        });

        $('#add_tag').on('hidden.bs.modal', function() {
            $(this).find('.text-danger').html('');
            $('#add_tag_form').trigger('reset');
        });
    });

$(document).on('change', '#edit_project_id, #select_project_name', function(e) {
    $('#task_parent').val('');
    var projectId= $(this).val();
    $.ajax({
        url: '/get-project-tasks/'+projectId,          
        type: 'GET',
        success: function(response) {
            $("#task_parent").empty();                
            $("#task_parent").append(
                '<option selected  value="">Select</option>'
            );    
            if (response && response?.status === "success") {           
                response?.data?.map((task_parent) => {
                    const frameworks = `<option value='${task_parent?.id}'> ${task_parent?.title} </option>`;
                    $("#task_parent").append(frameworks);
                });
            }
            $('#task_parent').val('').trigger('chosen:updated');
        },
    });
});

$(document).on('click', '.destroy_task', function() {
    var deleteTaskId = $(this).data('id');
    $('#destroy_task #destroy_task_id').val(deleteTaskId);
});

/**
         * Destroy task form - continue button action
         */
$(document).on('click', '#destroy_task .continue-btn', function(e) {
    toasterOption();
    var destroyTaskId = $('#destroy_task #destroy_task_id').val();
    $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
    $('#destroy_task').modal('hide');
    $.ajax({
        type: 'POST',
        url: '/destroy-task-ajax',
        data: {
            'taskId': destroyTaskId,
            'projectId': $('#project_id').attr('data-id')
        },
        success: function(response) {
            $(".overlay").remove();
            if (response.status === "success") {
                toastr.success(response.message, 'Deleted');
                setTimeout(function () {
                    searchTask(e);
                }, 1000);
            } else {
                toastr.error(response.message, "Failed");
                setTimeout(function () {
                    searchTask(e);
                }, 1000);
            }
        },
        error: function(error) {}
    });
});
    