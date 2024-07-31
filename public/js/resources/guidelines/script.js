
	$(document).ready(function() {
    
    inputsLoader();

    $('.create-guideline').click(function(e) {
        toasterOption();
        openLoader();
        $('.field-error').html('');
        e.preventDefault();

        $.ajax({
            method: 'POST',
            url: $('#add_form').attr('action'),
            data: $('#add_form').serialize(),
            success: function(response) {
                console.log(response);
                $("#create_guideline").modal('hide');
                toastr.success(response.message, 'Saved');
                $("#list").html(response.data);
                $(".search").html(response.search);
                inputsLoader();
                closeLoader();
            },
            error: function(error) {
                closeLoader();
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
    $('#create_guideline').on('hidden.bs.modal', function() {
        $(this).find('.text-danger').html('');
        $('#add_form').trigger('reset');
        $('.summernote').summernote('reset');
    });

    /**
     * guideline edit form loading when clicking edit
     */
     $(document).on('click', '.edit-guideline', function(e) {
        e.preventDefault();
       openLoader();
        var guidelineId = $(this).data('id');
        var editUrl = '/guidelines/' + guidelineId + '/edit';

        $.ajax({
            type: 'GET',
            url: editUrl,
            data: {},
            success: function(response) {
                $('#edit_guideline').html(response);
                closeLoader();
                $("#edit_guideline").modal('show');
                $('.summernote').summernote({
                    height:200
                });
                $('.chosen-select').chosen({
                    width:'100%'
                });
                typeAhead();
            }
        });
    });

    /**
     * Edit guideline form - submit button action
     */
    $(document).on('click', '.update-guideline', function(e) {
        toasterOption();
        $('.field-error').html('');
        e.preventDefault();
       openLoader();
        var data = new FormData($('#edit_form')[0]);
        console.log(data);
        $.ajax({
            type: 'POST',
            url: $('#edit_form').attr('action'),
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                $('#edit_guideline').modal('hide');
                closeLoader();
                 $("#list").html(response.data);
                 $(".search").html(response.search);
                inputsLoader();
                toastr.success(response.message, 'Updated')
            },
            error: function(error) {
                closeLoader();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function(field, error) {
                        $('#label_edit_' + field).html(error);
                    });
                }
            }
        });
    });

    $(document).on('click', '.delete-guideline', function() {
        var deleteGuidelineId = $(this).data('id');
        $('#delete_guideline #delete_guideline_id').val(deleteGuidelineId);
    });

    /**
     * Delete guideline form - continue button action
     */
     $(document).on('click', '#delete_guideline .continue-btn', function(e) {
        toasterOption();
        var deleteGuidelineId = $('#delete_guideline #delete_guideline_id').val();
        var deleteUrl = '/guidelines/' + deleteGuidelineId;
        openLoader();
        $.ajax({
            type: 'DELETE',
            url: deleteUrl,
            data: {},
            success: function(response) {
                closeLoader();
        		$('#delete_guideline').modal('hide');
                toastr.success(response.message, 'Deleted');
                $("#list").html(response.data);
                inputsLoader();
            },
            error: function(error) {
            	closeLoader();
                toastr.error('Something went Wrong', 'Error');
            }
        });
    });

    function inputsLoader() {

        $('.listTable').dataTable();
        $('.summernote').summernote({
        	height:200
        });
        typeAhead();
        $('.chosen-select').chosen({
            width:'100%'
        });

        typeAhead();
    }

    function typeAhead() {
        $.get('get-typhead-categories',
            function(response) {
                var name = [];
                for (var i = response.data.length - 1; i >= 0; i--) {
                    name.push(response.data[i]['title'])
                }
                $(".category").typeahead({
                    source: name
                });
            }, 'json');
    }

    $(document).on('change', '.category_type', function(e) {
        toasterOption();
        e.preventDefault();
       openLoader();
        $.ajax({
            type: 'POST',
            url: '/get-category-list',
            data: {
                'type':$(this).val()
            },
            success: function(response) {
                $("#list").html(response.data);
                closeLoader();
                inputsLoader();
            }
        });
    });

    $('.create-tag').click(function(e) {
        toasterOption();
        openLoader();
        $('.field-error').html('');
        e.preventDefault();

        $.ajax({
            method: 'POST',
            url: $('#add_tag_form').attr('action'),
            data: $('#add_tag_form').serialize(),
            success: function(response) {
                $('#create_tag').modal('hide');
                $(".search").html(response.data);
                closeLoader();
                inputsLoader();
            },
            error: function(error) {
                closeLoader();
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function(field, error) {
                        $('#label_add_' + field).html(error);
                    });
                }
            }
        });
    });

});