
    $(document).ready(function() {

        inputsLoader();
        loadIchecks();

    $(document).on("show.bs.modal", '.modal', function (event) {
        var zIndex = 100000 + (10 * $(".modal:visible").length);
        $(this).css("z-index", zIndex);
        setTimeout(function () {
            $(".modal-backdrop").not(".modal-stack").first().css("z-index", zIndex - 1).addClass("modal-stack");
        }, 0);
    }).on("hidden.bs.modal", '.modal', function (event) {
        console.log("Global hidden.bs.modal fire");
        $(".modal:visible").length && $("body").addClass("modal-open");
    });
    $(document).on('inserted.bs.tooltip', function (event) {
        var zIndex = 100000 + (10 * $(".modal:visible").length);
        var tooltipId = $(event.target).attr("aria-describedby");
        $("#" + tooltipId).css("z-index", zIndex);
    });
    $(document).on('inserted.bs.popover', function (event) {
        var zIndex = 100000 + (10 * $(".modal:visible").length);
        var popoverId = $(event.target).attr("aria-describedby");
        $("#" + popoverId).css("z-index", zIndex);
    });
        $(document).keydown(function(e) {
            // ESCAPE key pressed
            if (e.keyCode == 27) {
                $('#create_task').modal('hide');
                $('#edit_task').modal('hide');
                $('#delete_tasks').modal('hide');
            }
        });

       
        
        $(document).on('click', '.add-project-manager', function() {
            toasterOption();
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            var projectManagers = $('#select_project_managers').val();
            var projectId = $('#current_project_id').val();

            var ajaxUrl = '/add-project-managers';
            $.ajax({
                method: 'POST',
                data: {
                    'project_managers': projectManagers,
                    'project_id': projectId
                },
                url: ajaxUrl,
                success: function(response) {
                    $(".overlay").remove();
                    $('#assign_leader').modal('hide');
                    $('.managers').html(response.data);
                    toastr.success(response.message, 'Updated');
                },
                error: function(error) {
                }
            });
        });

        function getProjectManagers(project_id) {
            $.ajax({
                type: 'POST',
                url: '/get-project-managers',
                data: {
                    'project_id': project_id,
                },
                success: function(data) {
                    $('.project-managers-list').html(data);
                }
            });
        }

        /**
         * Delete task modal view
         */
        $(document).on('click', '.delete_task_from_project_onclick', function() {
            var deleteTaskId = $(this).data('id');
            $('#delete_tasks #delete_task_id').val(deleteTaskId);
        });

            /**
         * Project edit form loading when clicking edit
         */
        $(document).on('click', '.edit-project', function() {
            // $('.project-success').addClass('hidden');
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            var projectId = $(this).data('id');
            var editUrl = '../projects/' + projectId + '/edit';
            $.ajax({
                type: 'GET',
                url: editUrl,
                data: {},
                success: function(data) {
                    $('#edit_project').html(data);
                    $('#edit_project').modal('show');
                    $(".overlay").remove();
                    inputsLoader();
                    loadIchecks();
                    typeAhead();
                }
            });

        });
             /** to update on enter key */
       
    $(document).on('keyup','#edit_project_name', function (event) {
        if (event.keyCode === 13) {
          $('.update-project').click();
      }
    });

    $(document).on('keyup','#edit_rate', function (event) {
        if (event.keyCode === 13) {
          $('.update-project').click();
      }
    });
    
    
    $(document).on('keyup','#edit_estimated_hours', function (event) {
        if (event.keyCode === 13) {
          $('.update-project').click();
      }
    });
    
    
    $(document).on('keyup','#edit_url', function (event) {
        if (event.keyCode === 13) {
          $('.update-project').click();
      }
	});
         /**
         * Edit project form - submit button action
         */
        $(document).on('click', '.update-project', function(e) {
            toasterOption();
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            $('.field-error').html('');
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: $('#edit_project_form').attr('action'),
                data: $('#edit_project_form').serialize(),
                success: function(response) {
                    $(".overlay").remove();
                    $('#edit_project').modal('hide');
                    reloadPage();
                    toastr.success(response.message, 'Updated');
                },
                error: function(error) {
                    if (error.responseJSON.errors) {
                        $.each(error.responseJSON.errors, function(field, error) {
                            $('#label_' + field).html(error);
                        });
                    }
                }
            });
        });
        /**
         * Delete task form - continue button action
         */
        $(document).on('click', '#delete_tasks .continue-btn', function() {
            toasterOption();
            var deleteTaskId = $('#delete_tasks #delete_task_id').val();
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            $('#delete_tasks').modal('hide');
            $.ajax({
                type: 'POST',
                url: '/delete-task-ajax',
                data: {
                    'taskId': deleteTaskId,
                    'projectId': $('#project_id').attr('data-id')
                },
                success: function(response) {
                    $(".overlay").remove();
                    $('.task').html(response.data);
                    toastr.success(response.message, 'Archived');
                    loadStatus();
                },
            });
        });

        $('.create-modal').click(function(e) {
            $('.datetimepicker').datepicker('setDate', new Date());
         });
        /**
         * create task
         */
        $(document).on('click', '.create-task', function(e) {
            console.log('ee');
            toasterOption();
            e.preventDefault();
            var id = $('#p_id').val();
            $('.field-error').html('');
            openLoader();
            var data = new FormData($('#add_task_form')[0]);
            $.ajax({
                url: '/create-task-ajax',
                data: data,
                contentType: false,
                cache: false,
                processData:false,
                type: 'POST',
                success: function(response) {
                    $(".overlay").remove();
                    $('#create_task').modal('hide');
                    $('.task').html(response.data);
                    toastr.success(response.message, 'Created');
                    loadStatus();
                    unloadIchecks();
                    $('.nav-tabs a[href="#tab-1"]').tab('show');
                },
                error: function(error) {
                    $(".overlay").remove();
                    $('.nav-tabs a[href="#tab-1"]').tab('show');
                    if (error.responseJSON.errors) {
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
        $('#create_task').on('hidden.bs.modal', function() {
            $(this).find('.text-danger').html('');
            $('#add_task_form').trigger('reset');
            $('.chosen-select').val('').trigger('chosen:updated');
            $('.summernote').summernote('reset');

        });
        /**
         * Task edit form loading when clicking edit
         */
        $(document).on('click', '.edit-task', function(e) {
            e.preventDefault();
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            var taskId = $(this).data('id');
            var editUrl = '../tasks/' + taskId + '/edit';

            $.ajax({
                type: 'GET',
                url: editUrl,
                data: {},
                success: function(data) {
                    $('#edit_task').html(data);
                    inputsLoader();
                    $(".overlay").remove();
                    $("#edit_task").modal('show');
                    loadIchecks();
                }
            });
        });

        /** to update on enter key */
        $(document).on('keyup','#edit_project_form', function (event) {
            if (event.keyCode === 13) {
                $('.update-task').click();
            }
        });



        /**
         * Edit task form - submit button action
         */
        $(document).on('click', '.update-task', function(e) {
            toasterOption();
            $('.field-error').html('');
            e.preventDefault();
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            $.ajax({
                type: 'PATCH',
                url: '/update-task-ajax',
                data: $('#edit_task_form').serialize(),
                success: function(response) {
                    $(".overlay").remove();
                    $('#edit_task').modal('hide');
                    $('.task').html(response.data);
                    toastr.success(response.message, 'Updated');
                    loadStatus();
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

        $('#search_project_name').keyup(function(e) {
            if (this.value.length < 4) return;
            /* code to run below */
            searchList(e);
        });
        $('#search_project_company').keyup(function(e) {
            if (this.value.length < 4) return;
            /* code to run below */
            searchList(e);
        });
        $('#search_project_priority').change(function(e) {
            searchList(e);
        });

        $(document).on('click', '.search-project', function(e) {
            searchList(e);
        });


    });


    function typeAhead() {
        $.get('get-typhead-data-project',
            function(response) {
                var name = [],company = [],project_type=[];
                for (var i = response.data.length - 1; i >= 0; i--) {
                    name.push(response.data[i]['project_name']);
                    company.push(response.data[i]['client']['company_name']);
                }
                for (var i = response.type.length-1 ; i >= 0; i--) {
                    project_type.push(response.type[i]['project_type']);
                }
                $(".typeahead_name").typeahead({
                    source: name
                });
                $(".typeahead_company").typeahead({
                    source: company
                });
                $(".typeahead_type").typeahead({
                    source: project_type
                });
            }, 'json');
    }


    function reloadPage() 
    {
        $.ajax({
                type:'POST',
                url:'/update-project-detail',
                data:{
                    'project_id':$('#project_id').attr('data-id')
                },
                success: function( response ) {
                    $('#main').html(response.data);
                    loadStatus();
                    inputsLoader();
                }
            });
    }

    function inputsLoader() {
        $('.datetimepicker').mask('00/00/0000');
        $('.datetimepicker').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            format: "dd/mm/yyyy",
            autoclose: true
        });
        $('.chosen-select').chosen({
            width: "100%"
        });
        loadStatus();
        $('.summernote').summernote({
            dialogsInBody: true,
            dialogsFade: false,
            callbacks:{
                onImageUpload: function(files, editor) {
                    for(let i=0; i < files.length; i++) {
                        sendFile(files[i], $(this));
                    }
                }
            }
        });

        $('.dataTable').dataTable();
    }

    function loadStatus() {
        $.ajax({
            type: 'POST',
            url: '/load-project-status',
            data: {
                'id':$('#project_id').attr('data-id')
            },
            success: function(data) {
                $('#overdue').text(data.overdue);
                $('#pending').text(data.pending);
                 c3.generate({
                    bindto: '#gauge',
                    data: {
                        columns: [
                            ['Project Completed', data.status]
                        ],
                        type: 'gauge'
                    },
                    color: {
                        pattern: ['#1A7BB9']
                    }
                });
            }
        });
    }

    function sendFile(file, editor, welEditable) {
        var  data = new FormData();
        data.append("file", file);
        var url = '/content-image-upload';
        $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
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

    function loadIchecks()
    {
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });

        $('.check-all').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });

        $('.check-all').on('ifChecked', function (event) {
            $(this).parents('tr').find('.i-checks').iCheck('check');
            triggeredByChild = false;
        });

        $('.check-all').on('ifUnchecked', function (event) {
            if (!triggeredByChild) {
                $(this).parents('tr').find('.i-checks').iCheck('uncheck');
            }
            triggeredByChild = false;
        });
        // Removed the checked state from "All" if any checkbox is unchecked
        $('.i-checks').on('ifUnchecked', function (event) {
            triggeredByChild = true;
            $(this).parents('tr').find('.check-all').iCheck('uncheck');
        });

        $('.i-checks').on('ifChecked', function (event) {
            if ($(this).parents('tr').find('.i-checks').filter(':checked').length == $(this).parents('tr').find('.i-checks').length) {
                $(this).parents('tr').find('.check-all').iCheck('check');
            }
        });

        $(".check-all").each(function() {
            if ($(this).parents('tr').find('.i-checks').filter(':checked').length == $(this).parents('tr').find('.i-checks').length) {
                $(this).iCheck('check');
            }
        });
    }

    function unloadIchecks()
    {
        $('.i-checks').iCheck('uncheck');
        $(".check-all").iCheck('uncheck');
    }

        