jQuery(document).ready(function() {

    $('.payroll-datepicker').datepicker({
        startView: 1,
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true,
        format: "M yyyy",
        viewMode: "months",
        minViewMode: "months",
        endDate: "+1m", 
      }); 
  });
  
  $(document).on('submit', '#upload_form', function(e) {
      toasterOption();
      e.preventDefault();
      $('.field-error').html('');
      openLoader();
      $('#filter').val($('#payroll_year_filter').val());
      var formData = new FormData(this);
      $.ajax({
          url: $('#upload_form').attr('action'),
          data: formData,
          cache:false,
          contentType: false,
          processData: false,
          type: 'POST',
          success: function(response) {                
              closeLoader();           
              if(response.status==200){   
                  $('#upload-payroll-file').modal('hide');
                  $('.payroll-list').html(response.data);
                  toastr.success(response.message);  
                  $('.payroll-table').DataTable({
                      info:false,
                      ordering:true,
                      "aaSorting": [],
                      paging:false    
                  });
              }
              else{           
              toastr.error(response.message,'error');
              }
              
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
  
  $(document).on('submit', '#export_form', function(e) {
    toasterOption();
    e.preventDefault();
    openLoader();
    var formData = new FormData(this);
    $.ajax({
        url: $('#export_form').attr('action'),
        data: formData,
        cache:false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function(response) {                
            closeLoader();           
            $('#download-payroll-file').modal('hide');   
        },
        error: function(error) {
            closeLoader();
            $('#download-payroll-file').modal('hide');
            toastr.error('Something went wrong');
        }
    });
});
  
  $('.payroll-table').DataTable({
      info:false,
      ordering:true,
      "aaSorting": [],
      paging:false    
  });
  
  $(document).on('change', '#payroll_year_filter', function(e) {
      var year = $(this).val();
      window.location = "/payrolls/"+year;
  });
  
  $(document).on('click', '#update-payroll-status', function() {
      var updatePayrollId = $(this).data('id');
      var updatePayrollLabel=$(this).data('label');
      $('#change_status #payroll-status-id').val(updatePayrollId);
      $('#change_status #payroll-status-label').val(updatePayrollLabel);
  });
  
  $(document).on('click', '#change_status .continue-btn', function() {
      var updatePayrollId = $('#change_status #payroll-status-id').val();
      var updatePayrollLabel=$('#change_status #payroll-status-label').val();
      var year = $('#payroll_year_filter').val();
      updateUrl = '/payroll/' + updatePayrollId+'/update';
      $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
      $('#change_status').modal('hide');
      
      $.ajax({
          type: 'PATCH',
          url: updateUrl,
          data: {
              "_token": $('meta[name="csrf-token"]').attr('content'),
              'status':updatePayrollLabel,
              'year':year
          },
          success: function(response) {
              $('#change_status').modal('hide');      
              closeLoader();           
              if(response.status==200){   
                  $('.payroll-list').html(response.data);
                  toastr.success(response.message);  
                  $('.payroll-table').DataTable({
                      info:false,
                      ordering:true,
                      "aaSorting": [],
                      paging:false    
                  });
              }
              else{           
              toastr.error(response.message,'error');
              }
  
          },
          error: function(error) {
              closeLoader();
              toastr.error('Something went wrong');
          }
      });
  });
  
  $('.chosen-select').chosen({
      width: "100%"
  });