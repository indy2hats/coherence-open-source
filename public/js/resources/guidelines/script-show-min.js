
	$(document).ready(function() {


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
                inputsLoader();
                reload();
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
        		window.location.href='/guidelines';
            },
            error: function(error) {
            	closeLoader();
                toastr.error('Something went Wrong', 'Error');
            }
        });
    });

    function inputsLoader() {
        $('.summernote').summernote({
        	height:200
        });
    }

    function reload() {
        $.ajax({
            type: 'POST',
            url: '/load-guideline',
            data: {
                'id':$('.edit-guideline').data('id')
            },
            success: function(response) {
                $(".list").html(response.data);
                closeLoader();
            },
            error: function(error) {
               
            }
        });
    }

});
