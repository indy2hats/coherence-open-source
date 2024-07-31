
$(document).ready(function() {
  loadInputs();

	$(document).on('ifChanged','.i-checks', function(e) {
		 toasterOption();
            e.preventDefault();
            $('.field-error').html('');
            openLoader();
            var data = $(this).closest("form").serialize();
            $.ajax({
                url: $('#checklist_update').attr('action'),
                data: data,
                type: 'POST',
                success: function(response) {
                     closeLoader();
                     $('.list').html(response.data);
                    toastr.success(response.message, 'Updated');
                    loadInputs();
                },
                 error: function(error) {
                    closeLoader();
                    if (error.status == 422) {
                        if (error.responseJSON.errors) {
                            $.each(error.responseJSON.errors, function(field, error) {
                                $('#label_list_' + field).html(error);
                                toastr.warning('No items selected', 'Warning');
                            });
                        }
                    }
                }
            });
	});

   $('#save_list').on('hidden.bs.modal', function() {
            $(this).find('.text-danger').html('');
            $('#save_form').trigger('reset');
        });

  $(document).on('click', '.add-to-report', function(e) {
            toasterOption();
            e.preventDefault();
            $('.field-error').html('');
            openLoader();
            var data = $('#save_form').serialize();
            $.ajax({
                url: $('#save_form').attr('action'),
                data: data,
                type: 'POST',
                success: function(response) {
                    $('#save_list').modal('hide');
                     closeLoader();
                     $('.list').html(response.data);
                    toastr.success(response.message, 'Saved');
                    loadInputs();
                },
                error: function(error) {
                    closeLoader();
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

  function loadInputs() {
        var date = new Date();
        date.setDate(date.getDate() - 1);
        $('#daterange').datepicker({
          todayBtn: "linked",
          keyboardNavigation: false,
          forceParse: false,
          calendarWeeks: true,
          autoclose: true,
          endDate: '+0d',
          format: 'dd/mm/yyyy',
        }).on('changeDate', function (selected) {
            var minDate = selected.format();
            $("#daterange").val(minDate);
            loadPerformance();
        });
          $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green'
        });
        $('#daterangechecklist').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true,
            endDate: '+0d',
            format: 'dd/mm/yyyy',
          }).on('changeDate', function (selected) {
              var minDate = selected.format();
              $("#daterange").val(minDate);
          });
    }

     function loadPerformance()
    {
        openLoader();
        $.ajax({
            method: 'POST',
            url: '/search-checklist-report',
            data: $("#task-search-form").serialize(),
            success: function(response) {
                closeLoader();
                $("#formatted_date").html(response.formatted_date);
                $("#performance_content").html(response.data);
            }
        });
    }

  $(document).on('click', '.save', function() {
    $('#save_list #save_id').val($(this).data('id'));
  });

	$(document).on('click', '.collapse-link-user', function() {
            var ibox = $(this).closest('div.ibox');
              var button = $(this).find('i');
              var content = ibox.children('.ibox-content');
              content.slideToggle(200);
              button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
              ibox.toggleClass('').toggleClass('border-bottom');
              setTimeout(function() {
                ibox.resize();
                ibox.find('[id^=map-]').resize();
              }, 50);
        });
});
