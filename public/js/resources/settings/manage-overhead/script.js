
    $(document).keydown(function(e) {
        // ESCAPE key pressed
        if (e.keyCode == 27) {
            $('#add_overhead_type').modal('hide');
            $('#edit_overhead_type').modal('hide');
            $('#delete_overhead_type').modal('hide');

        }
    });

    var pie;
    var pie_employee;
    $(document).ready(function() {
      pie = Morris.Donut({
        element: 'morris-donut-chart',
        data: [10, 23, 78, 23],
        resize: true
    });
       pie_employee = Morris.Donut({
        element: 'employee_expense',
        data: [10, 23, 78, 23],
        resize: true
    });

    $(document).on('keyup', '#amount_id', function(event) {
            // enter key pressed
            if (event.keyCode === 13) {
                $('.create-overhead-type').click();

            }
        });

        $(document).on('keyup', '#type_id', function(event) {
            // enter key pressed
            if (event.keyCode === 13) {
                $('.create-overhead-type').click();

            }
        });

        /** 
         * Create overhead form - submit buttom action
         */
        $('.create-overhead-type').click(function(e) {
            toasterOption();
            e.preventDefault();
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            var data = $('#add_overhead_type_form').serialize();
            $.ajax({
                type: 'POST',
                url: $('#add_overhead_type_form').attr('action'),
                data: data,
                success: function(res) {
                    $(".overlay").remove();
                    $('#add_overhead_type').modal('hide');
                    $('#add_overhead_type_form').trigger('reset');
                    toastr.success(res.message,'Created');
                    loadPie();
                    $('.listTable').html(res.data);
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
        $('#add_overhead_type').on('hidden.bs.modal', function() {
            $(this).find('.text-danger').html('');
            $('#add_overhead_type_form').trigger('reset');
        });

        /** 
         * Loading edit overhead form with data to edit modal
         */
        $(document).on('click', '.edit-overhead-type', function() {
            var overheadId = $(this).data('id');
            editUrl = '/fixed-overhead/' + overheadId + '/edit';
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            $.ajax({
                method: 'GET',
                url: editUrl,
                data: {},
                success: function(response) {
                    $(".overlay").remove();
                    $('#edit_overhead_type').html(response);
                    $('#edit_overhead_type').modal('show');
                    $('.summernote').summernote();
                }
            });
        });

        $(document).on('keyup', '#edit_type_id', function(event) {
            // enter key pressed
            if (event.keyCode === 13) {
                $('.update-overhead-type').click();

            }
        });

        $(document).on('keyup', '#edit_amount_id', function(event) {
            // enter key pressed
            if (event.keyCode === 13) {
                $('.update-overhead-type').click();

            }
        });

        /**
         * Update overhead form - submit button action
         */
        $(document).on('click', '.update-overhead-type', function(e) {
            toasterOption();
            $('.field-error').html('');
            e.preventDefault();
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            $.ajax({
                type: 'POST',
                url: $('#edit_overhead_type_form').attr('action'),
                data: $('#edit_overhead_type_form').serialize(),
                success: function(res) {
                    $(".overlay").remove();
                    $('#edit_overhead_type').modal('hide');
                    toastr.success(res.message,'Updated');
                    loadPie();
                    $('.listTable').html(res.data);
                },
                error: function(error) {
                    $(".overlay").remove();
                    if (error.responseJSON.errors) {
                        $.each(error.responseJSON.errors, function(field, error) {
                            $('#edit_label_' + field).html(error);
                        });
                    }
                }
            });
        });

        /**
         * Adding overhead id to hidden text field in delete model 
         */
        $(document).on('click', '.delete-overhead-type', function() {
            var deleteOverheadId = $(this).data('id');
            $('#delete_overhead_type #delete_overhead_type_id').val(deleteOverheadId);
        });

        /**
         * Delete model continue button action
         */
        $(document).on('click', '#delete_overhead_type .continue-btn', function() {
            toasterOption();
            var deleteOverheadId = $('#delete_overhead_type #delete_overhead_type_id').val();
            var deleteUrl = '/fixed-overhead/' + deleteOverheadId;
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            $('#delete_overhead_type').modal('hide');
            $.ajax({
                method: 'DELETE',
                url: deleteUrl,
                data: {},
                success: function(res) {
                    $(".overlay").remove();
                    $('#delete_overhead_type').trigger('reset');
                    toastr.success(res.message,'Deleted');
                    loadPie();
                    $('.listTable').html(res.data);
                }
            });
        });

        /**
         * Adding overhead to current month 
         */
        $(document).on('click', '.add_to_current', function() {
            toasterOption();
            $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
            $.ajax({
                method: 'POST',
                url: '/add-to-month',
                data: {},
                success: function(res) {
                    $(".overlay").remove();
                    if(res.success){
                        toastr.success(res.message,'Added');
                    }
                    else{
                        toastr.error(res.message,'Error');
                    }
                }
            });
        });
    });

  function inputsLoader() {
      $('.listTable').DataTable();
      $('.summernote').summernote();
      loadPie();
      loadPieExpense();
  }
  inputsLoader();
  function loadPie() {
    $.ajax({
            method: 'POST',
            url: '/load-pie',
            data: {},
            success: function(response) {
              var data=[];
              var total=0;
                for (var i = 0; i < response.data.length; i++) {
                    data.push({
                        label: "" + response.data[i]['type'],
                        value: response.data[i]['amount']
                    });
                    total += parseInt(response.data[i]['amount']);
                }
                $('#total_id').text('Total: Rs.' + total);
                pie.setData(data);
            }
        });
  }

  function loadPieExpense(){
     $.ajax({
            method: 'POST',
            url: '/load-pie-expense',
            data: {},
            success: function(response) {
                 var data=[];
                  var total=0;
                    for (var i = 0; i < response.data.length; i++) {
                        data.push({
                            label: "" + response.data[i]['type'],
                            value: response.data[i]['amount']
                        });
                        total += parseInt(response.data[i]['amount']);
                    }
                $('#employee_expense_id').text('Total: Rs.' + total);
                pie_employee.setData(data);
            }
        });
  }
