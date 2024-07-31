
  jQuery(document).ready(function() {
    loadInputs();

    jQuery(document).on("change", '#projectId', function() {
      $('.searchSheet').click();
    });

    jQuery(document).on("change", '.dateInput', function() {
      $('.searchSheet').click();
    });

    jQuery(document).on("click", '.searchSheet', function() {
      if($('#projectId').val() == ''){
         $('.alert-start').removeClass('hidden');
        return;
      }
      $('.alert-start').addClass('hidden');
      
      ajaxUserTimeSheetSearch($('#date').val(),$('#projectId').val());
    });

    jQuery(document).on("click", ".arrow-back", function() {
      if($('#projectId').val() == ''){
         $('.alert-start').removeClass('hidden');
        return;
      }
      $('.alert-start').addClass('hidden');
      
      var currentDate = $(this).attr('data-date');
      ajaxUserTimeSheetSearch(createDate(currentDate,7,0),$('#projectId').val());
    });
    jQuery(document).on("click", ".arrow-front", function() {
      if($('#projectId').val() == ''){
         $('.alert-start').removeClass('hidden');
        return;
      }
      $('.alert-start').addClass('hidden');
      
      var currentDate = $(this).attr('data-date');
      ajaxUserTimeSheetSearch(createDate(currentDate,7,1),$('#projectId').val());
    });
    jQuery(document).on("click", ".todayBtn", function() {
      if($('#projectId').val() == ''){
         $('.alert-start').removeClass('hidden');
        return;
      }
      $('.alert-start').addClass('hidden');
      
      var currentDate = $(this).attr('data-date');
      ajaxUserTimeSheetSearch(createDate(currentDate,0,1),$('#projectId').val());
    });

  });
  function loadInputs() {


    $('.chosen-select').chosen({
      width: "100%"
    });

    $('.datepicker').datepicker({
      todayBtn: "linked",
      keyboardNavigation: false,
      forceParse: false,
      calendarWeeks: true,
      autoclose: true,
      format: 'dd/mm/yyyy',
    });
  }
  function ajaxUserTimeSheetSearch(date,projectId) {
     $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
    $.ajax({
      type: 'POST',
      url: '/admin-timesheet-search-project',
      data: {
        'date': date,
        'projectId': projectId
      },
      success: function(res) {
        if (res.status == "OK") {
          $('.overlay').remove();
          $(".list").html(res.data);
          loadInputs();
        } else {
          //something went wrong!
        }
      }
    });
  }
  function createDate(currentDate,number_of_days,op) {

    var dateParts = currentDate.split("/");

    var newdate = new Date(+dateParts[2], dateParts[1] - 1, +dateParts[0]);
    
    op==1?newdate.setDate(newdate.getDate() + number_of_days):newdate.setDate(newdate.getDate() - number_of_days);
    var dd = ('0' + newdate.getDate()).slice(-2);
    var mm = ('0' + (newdate.getMonth() + 1)).slice(-2);
    var y = newdate.getFullYear();
    var date = dd + '/' + mm + '/' + y;
    return date;
  }