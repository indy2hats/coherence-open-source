
    $('.summernote').summernote();
    $('.listTable1').dataTable({
        paging:false,
        info:false,
        aaSorting: [[0, 'desc']]
    });
    $('.listTable2').dataTable({
        paging:false,
        info:false,
        aaSorting: [[0, 'desc']]

    });

    function reload() {
        openLoader();
        $.ajax({
            type: 'POST',
            url: '/get-all-pending-leave-applications',
            data: {},
            success: function(response) {
                closeLoader();
                $('#leave_list').html(response.data);
                $('.listTable1').dataTable();
                $('.listTable2').dataTable();
            }
        });

    }

    /** View Balance Leave Modal */
    $(document).on('click', '.view-remaining-leave', function(){
        $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
        $.ajax({
            type: 'POST',
            url: '/view-remainig-leaves',
            data: {
                'user_id': $(this).attr('data-id')
            },
            success: function(response) {
                $(".overlay").fadeOut(300, function() { $(this).remove(); });
                $('#view_remaining_leave').html(response);
                $('#view_remaining_leave').modal('show');
            }
        });
    });

    /* Adding leave id to hidden text field in accept model 
         */
        $(document).on('click', '.accept-leave', function() {
            var  acceptLeaveId = $(this).data('id');
            $('#accept_leave #accept_leave_id').val( acceptLeaveId);
        });

        /**
         * accept model continue button action
         */
        $(document).on('click', '#accept_leave .continue-btn', function() {
            var acceptLeaveId = $('#accept_leave #accept_leave_id').val();
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            $('#accept_leave').modal('hide');
            $.ajax({
                method: 'post',
                url: '/accept-leave-admin',
                data: {'leaveId':acceptLeaveId},
                success: function(res) {
                    $(".overlay").remove();
                    $('#accept_leave').trigger('reset');
                    toastr.success(res.message,'Approved');
                    reload();
                }
            });
        });

        /* Adding leave id to hidden text field in reject model 
         */
        $(document).on('click', '.reject-leave', function() {
            var  rejectLeaveId = $(this).data('id');
            $('#reject_reason #reject_leave_id').val( rejectLeaveId);
        });

        /**
         * reject model continue button action
         */
        $(document).on('click', '#reject_reason .continue-btn', function() {
            var rejectLeaveId = $('#reject_reason #reject_leave_id').val();
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            $.ajax({
                method: 'post',
                url: '/reject-leave-admin',
                data: $('#leave_rejection_form').serialize(),
                success: function(res) {
                    $(".overlay").remove();
                    $('#reject_reason').modal('hide');
                    $('#reject_reason').trigger('reset');
                    toastr.success(res.message,'Approved');
                    reload();
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

        // LOP onChange
        $(document).on('change', '.mark-lop', function(){
            $.ajax({
                type: 'POST',
                url: '/mark-as-lop',
                data: {
                    'id':$(this).attr('data-id'),
                    'lop':$(this).prop("checked") ? 'Yes' : 'No'
                },
                success: function(response) {
                    toastr.success(response.message,'Marked');
                }
            });
        });

        $(document).ready(function() {
            reload();
        });
