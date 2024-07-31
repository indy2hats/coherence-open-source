
    $(document).ready(function() {


        typeAhead();
        inputsLoader();

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
                $('#create_project').modal('hide');
                $('#edit_project').modal('hide');
                $('#delete_project').modal('hide');
            }
        });

        /** to  save on enter key */
        $(document).on('keyup','#project_name_id', function (event) {
        if (event.keyCode === 13) {
              $('.create-project').click();
        }
        });
        
        $(document).on('keyup','#rate_id', function (event) {
        if (event.keyCode === 13) {
              $('.create-project').click();
        }
        });
        
        $(document).on('keyup','#estimated_hours_id', function (event) {
        if (event.keyCode === 13) {
              $('.create-project').click();
        }
        });
        
        $(document).on('keyup','#url_id', function (event) {
        if (event.keyCode === 13) {
              $('.create-project').click();
        }
        });



         $('.create-modal').click(function(e) {
            $('.datetimepicker').datepicker('setDate', new Date());
         });

        /**
         * Create project - submit button action
         */
        $('.create-project').click(function(e) {
            var page=$("#create_project_action").attr('data-action');
            toasterOption();
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            $('.field-error').html('');
            e.preventDefault();

            $.ajax({
                method: 'POST',
                url: $('#add_project_form').attr('action'),
                data: $('#add_project_form').serialize(),
                success: function(response) {
                    $(".overlay").remove();
                    $("#create_project").modal('hide');
                    toastr.success(response.message, 'Saved');
                    if(page=='gantt'){
                        filterProjects();
                    }else{
                        window.location.href='/projects/'+response.id;
                    }
                },
                error: function(error) {
                    $(".overlay").remove();
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
        $('#create_project').on('hidden.bs.modal', function() {
            $(this).find('.text-danger').html('');
            $('#add_project_form').trigger('reset');
            $('.chosen-select').val('').trigger('chosen:updated');
            $('.summernote').summernote('reset');
        });

        /**
         * Project edit form loading when clicking edit
         */
        $(document).on('click', '.edit-project', function(e) {
            e.preventDefault();
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            var projectId = $(this).data('id');
            var editUrl = '/projects/' + projectId + '/edit';

            $.ajax({
                type: 'GET',
                url: editUrl,
                data: {},
                success: function(data) {
                    $('#edit_project').html(data);
                    inputsLoader();
                    loadIchecks();
                    typeAhead();
                    $(".overlay").remove();
                    $("#edit_project").modal('show');
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
            var page=$("#edit_project").attr('data-action');
            toasterOption();
            $('.field-error').html('');
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: $('#edit_project_form').attr('action'),
                data: $('#edit_project_form').serialize(),
                success: function(response) {
                    $(".overlay").remove();
                    toastr.success(response.message, 'Updated');
                    if(page=='gantt'){
                        fetchProjects();
                    }else{
                        $('#edit_project').modal('hide');
                        $(".main").html(response.data);
                        $('.chosen-select').val('').trigger('chosen:updated'); 
                    }
                },
                error: function(error) {
                    $(".overlay").remove();
                    if (error.responseJSON.errors) {
                        $.each(error.responseJSON.errors, function(field, error) {
                            $('#label_' + field).html(error);
                        });
                    }
                }
            });
        });

        $(document).on('click', '.delete-project', function() {
            var deleteProjectId = $(this).data('id');
            $('#delete_project #delete_project_id').val(deleteProjectId);
        });

        /**
         * Delete project - button click action
         */
        $(document).on('click', '#delete_project .continue-btn', function() {
            var deleteUserId = $('#delete_project #delete_project_id').val();
            deleteUrl = '/projects/' + deleteUserId;
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            $('#delete_project').modal('hide');
            $.ajax({
                type: 'DELETE',
                url: deleteUrl,
                data: {
                    "_token": $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('.overlay').remove();
                    if (response.status === "success") {
                        toastr.success(response.message, "Deleted");
                        setTimeout(loadArchivedProjects, 1000);
                    } else {
                        toastr.error(response.message, "Failed");
                    }
                },
                error: function(error) {
                }
            });
        });
        function loadArchivedProjects() {  
            window.location="/archived-projects/"; 
        }   

        $(document).on('change', '#search_project_name', function(e) {
            var id = $(this).val();
            window.location = "/projects/"+id;
        });

        $(document).on('change', '#search_project_company', function(e) {
            searchList(e);
        });

        $('#search_project_priority').change(function(e) {
            searchList(e);
        });

        $(document).on('change', '#search_technology', function(e) {
            searchList(e);
        });

        $(document).on('change', '#projectCategory', function(e) {
            searchList(e);
        });

        $(document).on('change', '#category', function(e) {
            if($('#category').val() != 'External')
            {
                $('.requiredstar').hide();
            } 
            else
            {
                 $('.requiredstar').show();
            }
        });

        $(document).on('change', '#edit_category', function(e) {
            if($('#edit_category').val() != 'External')
            {
                $('.requiredstar').hide();
            } 
            else
            {
                 $('.requiredstar').show();
            }
        });
        $(document).on('click','.archive-project',function(){
            var page=$(this).attr('data-action');
            $.ajax({
                type: 'POST',
                url: '/change-archive-project',
                data: {
                    'is_archived': true,
                    'id':$(this).data('id'),
                },
                success: function(response) {
                    toastr.success('Project Archived Successfully!', 'Archived');   
                    if(page=="gantt"){
                        fetchProjects();
                    }else{
                        $(".main").html(response.data);
                    }
                },error: function(error) {
                    toastr.error('Something Went Wrong!', 'Error');
                }
            });
        });
    });

    function searchList(e) {
        e.preventDefault();
        $('#search-project').submit();
    }


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

    function loadIchecks() {
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
    }

    function inputsLoader() {
        
        // $('.summernote').summernote();
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
        $('.files').dataTable();
        
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

        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
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

    $(document).on('click', '.create-modal' , function (e) {
        e.preventDefault();
        $('.datetimepicker').datepicker('setDate', new Date());
        $('#add_task').modal('show');
    });
    
    $(document).on('click', '.add-task', function(e) {
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
                $('#add_task').modal('hide');
                $(".overlay").remove();
                toastr.success(response.message, 'Created');
            },
            error: function(error) {
                $(".overlay").remove();
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
 $('#add_task').on('hidden.bs.modal', function() {
    $(this).find('.text-danger').html('');
    $('#add_task_form').trigger('reset');
    $('.summernote').summernote('reset');

    //}
});

$('#add_task').on('shown.bs.modal', function() {
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
