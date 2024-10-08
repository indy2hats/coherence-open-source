jQuery(document).ready(function() {

    $('.payroll-month-datepicker').datepicker({
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
  
      if($('#label_export_error_message').length!=0)
      {
          toastr.error($('#label_export_error_message').text());
      }
  });
  
  $(document).on('click', '.edit-employee-payroll-button', function(e) {
      e.preventDefault();
      openLoader();
      var typeId = $(this).data('id');  
      var editUrl = "/payroll-user/" + typeId + '/edit';
  
      $.ajax({
          type: 'GET',
          url: editUrl,
          data: {},
          success: function(response) {
              $('#edit-employee-payroll').html(response);
              closeLoader();
              $("#edit-employee-payroll").modal('show');            
          }
      });
  });
  
  
  $('.employee-payroll-table').DataTable({
      info:false,
      ordering:true,
      "aaSorting": [],
      paging:true ,
      pageLength: 20, 
      "lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "All"]] ,   
  });
  
      
  $(document).on('click', '.update-employee-payroll', function(e) {
      toasterOption();
      $('.field-error').html('');
      e.preventDefault();
      openLoader();
      $.ajax({
          type: 'PATCH',
          url: $('#edit_form').attr('action'),
          data: $('#edit_form').serialize(),
          success: function(response) {
              closeLoader(); 
              if(response.status==200){  
                  $('#edit-employee-payroll').modal('hide');                
                  $('.employee-payroll-list').html(response.data);
                  toastr.success(response.message);
                  $('.employee-payroll-table').DataTable({
                      info:false,
                      ordering:true,
                      "aaSorting": [],
                      paging:true ,
                      pageLength: 20, 
                      "lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "All"]] ,   
                  });
              }
              else{           
                  toastr.error(response.message);
              }
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
  
  $(document).on('change', '#payroll-month-datepicker', function() {
      var userId = $(this).data('id');  
      var monthYear =$(this).val().replace(' ','-');
      window.location =  "/payroll-user/" + userId + '/' + monthYear;
  });
  
  $('.chosen-select').chosen({
      width: "100%"
  });