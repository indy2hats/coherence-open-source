<script>
    $(document).ready(function() {
        loadApplications();
        $('.chosen-select').chosen({
            width: "100%"
        }).on('change', function(e) {
            loadApplications();
        });

        $(".arrow-back").on('click', function(e){
            e.preventDefault();
            var month = $("#month").val()-1;
            var year = $("#year").val();
            if(month <= 0) {
                $("#year").val(year-1).trigger('chosen:updated');
                $("#month").val(12).trigger('chosen:updated').trigger('change');
            } else {
                $("#month").val(month).trigger('chosen:updated').trigger('change');
            }
        });

        $(".arrow-front").on('click', function(e){
            e.preventDefault();
            var month = parseInt($("#month").val())+1;
            var year = $("#year").val();
            var current_year  = new Date().getFullYear();
            if(month >= 13) {
                if(year == current_year) {
                    $("#year").val(current_year).trigger('chosen:updated');
                    $("#month").val(12).trigger('chosen:updated').trigger('change');
                } else {
                    $("#year").val(parseInt(year)+1).trigger('chosen:updated');
                     $("#month").val(1).trigger('chosen:updated').trigger('change');
                }
            } else {
                $("#month").val(month).trigger('chosen:updated').trigger('change');
            }
        });

        $(".todayBtn").on('click', function(e){
            e.preventDefault();
            var current_year  = new Date().getFullYear();
            var current_month  = new Date().getMonth();
            $("#year").val(current_year).trigger('chosen:updated');
            $("#month").val(current_month+1).trigger('chosen:updated').trigger('change');
        });

        $('body').on('click', '.edit-data', function(e){
            e.preventDefault();
            openLoader();
            var leaveId = $(this).data('id');
            var editUrl = '../apply-leave/' + leaveId + '/edit';

            $.ajax({
                type: 'GET',
                url: editUrl,
                data: {},
                success: function(response) {
                    $('#edit_leave').html(response.data);
                    closeLoader();
                    inputsLoader();
                    $("#edit_leave").modal('show');
                }
            });
        });

         /**
         * Edit task form - submit button action
         */
        $(document).on('click','.update-leave', function(e) {
            $('.field-error').html('');
            e.preventDefault();
            openLoader();
            $.ajax({
                type:'POST',
                url:$('#edit_leave_form').attr('action'),
                data:$('#edit_leave_form').serialize(),
                success: function( response ) {
                    closeLoader();
                    $('#edit_leave').modal('hide');
                    toastr.success(response.message, 'Updated');
                    loadApplications();
                },
                error: function(error) {
                  closeLoader();
                    if(error.responseJSON.errors) {
                        $.each(error.responseJSON.errors, function (field, error) {
                            $('#label_'+field).html(error);
                        });
                    }
                }
            });       
        });

        $(document).on('click', '.delete_leave', function() {
            var  cancelLeaveId = $(this).data('id');
            $('#delete_leave #delete_leave_id').val( cancelLeaveId);
        });

        /**
         * Delete model continue button action
         */
        $(document).on('click', '#delete_leave .continue-btn', function() {
            var cancelLeaveId = $('#delete_leave #delete_leave_id').val();
            var deleteUrl = 'apply-leave/' +  cancelLeaveId;
            openLoader();
            $('#delete_leave').modal('hide');
            $.ajax({
                method: 'DELETE',
                url: deleteUrl,
                data: {},
                success: function(res) {
                    closeLoader();
                    $('#delete_leave').trigger('reset');
                    toastr.success(res.message,'Deleted');
                    loadApplications();
                }
            });
        });
    });

    function loadApplications() {
        openLoader();
        $.ajax({
            method: 'POST',
            url: '{{route('listPreviousApplications')}}',
            data: {
              'year':$('#year').val(),
              'month':$('#month').val(),
              'user':$('#user').val(),
              'userType':$('#userType').val(),
              'leaveType':$('#leaveType').val()
            },
            success: function(response) {
                closeLoader();
                $("#list_leave").html(response.data);
            }
        });
    }

    function inputsLoader(){
    //select box 
        $('.chosen-select').chosen({
            width: "100%"
        });
            //date picker
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
        $('.summernote').summernote();
    }
</script>