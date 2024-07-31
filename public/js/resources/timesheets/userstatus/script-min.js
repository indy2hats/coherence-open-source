
  jQuery(document).ready(function() {
    loadInputs();
    

    $('body').on("keydown", "input[id*='add-session-box-time'], input[id*='edit-session-box-time']", function (event) {
            if (event.shiftKey == true) {
                event.preventDefault();
            }

            if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105) || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 190 || event.keyCode == 110) {

            } else {
                event.preventDefault();
            }
            
            if($(this).val().indexOf('.') !== -1 && event.keyCode == 190)
                event.preventDefault();
        });

    jQuery(document).on("click", '.searchSheet', function() {
      $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
      ajaxUserTimeSheetSearch($('#date').val(),$('#projectId').val());
    });

    jQuery(document).on("click", ".arrow-back", function() {
      $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
      var currentDate = $(this).attr('data-date');
      ajaxUserTimeSheetSearch(createDate(currentDate,7,0),'');
    });
    jQuery(document).on("click", ".arrow-front", function() {
      $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
      var currentDate = $(this).attr('data-date');
      ajaxUserTimeSheetSearch(createDate(currentDate,7,1),'');
    });
    jQuery(document).on("click", ".todayBtn", function() {
      $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
      var today= new Date;
      let month = today.getMonth() + 1;
      var currentDate = today.getDate() + '/' + month + '/' + today.getFullYear();
      ajaxUserTimeSheetSearch(createDate(currentDate,0,1),'');
    });

    jQuery(document).on("change", ".new-project", function() {
      var id = $("option:selected",this).val();
      inputObject = $(this).closest('tr');
      $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
      $.ajax({
        type: 'POST',
        url: '/get-tasks-project-user',
        data: {
          'projectId': id
        },
        success: function(res) {
          $(".overlay").remove();
          if (res.status == "OK") {
           var items=[];
            $('.listData tbody tr').find('#select_task').each( function(){
               items.push( $(this).val());       
            });
            $('.listData tr:last td:eq(1)').remove();
            var task_option = '<option value="">Select Task</option>';
            for(var i=res.data.length-1;i>=0;i--){
              if(checkTaskPresence(res.data[i]['id'],items))
                task_option +='<option data-start="'+res.data[i]['start_date']+'" value="'+res.data[i]['id']+'">'+res.data[i]['title']+'</option>'
            }
            inputObject.append('<td width="200" id="td"><select class="chosen-select new-task" id="select_task">'+task_option+'</select></td>');
            if(task_option == '<option value="">Select Task</option>'){
              $('.listData tr:last td:eq(1)').remove();
            }
            loadInputs();
          } else {
            console.log("error");
          }
        }
      });
    });



    $(document).on('click', '.inputBox', function(e){
        e.preventDefault();

        var date = $(this).data('date');
        var task_id = $(this).data('task_id');

        var currrent_date = process(date);
        var start_date = process($(this).data('start'));

        var days = Math.round((currrent_date - start_date ) / 1000 / 60 / 60 / 24);

        if (days < 0) {
            toastr.error('You are trying enter time on a previous date for this task !!','Error');
            return false;
        }

        var fullDate = new Date()
        console.log(fullDate);
        //Thu May 19 2011 17:25:38 GMT+1000 {}
         
        //convert month to 2 digits
        var twoDigitMonth = ((fullDate.getMonth().length+1) === 1)? (fullDate.getMonth()+1) : '0' + (fullDate.getMonth()+1);
         
        var start_date = fullDate.getDate() + "/" + twoDigitMonth + "/" + fullDate.getFullYear();
        start_date = process(start_date);

        var days = Math.round((currrent_date - start_date ) / 1000 / 60 / 60 / 24);

        if (days > 0) {
            toastr.error('You are trying enter time on a future date for this task !!','Error');
            return false;
        }


        if (typeof $(this).data('session_id') !== 'undefined' && $(this).data('session_id') != '') {
            var sessionId = $(this).data('session_id');
            var editUrl = "/task-session/" + sessionId + '/edit';
            openLoader();
            $.ajax({
                type: 'GET',
                url: editUrl,
                data: {},
                success: function(data) {
                    $('#edit_task_session').html(data);
                    inputsLoader();
                    closeLoader();
                    $('#edit_task_session').modal('show');
                }
            });
        } else {
            $("#sheet_task_id").val(task_id);
            $("#sheet_date").val(date);
            inputsLoader();
            $("#add-session").modal('show');
        }
    });

     $(document).on('click','.create-session', function (e) {
        e.preventDefault();
        createSession();
    });

    $(document).on('click','.edit-session', function (e) {
            e.preventDefault();
            editSession();
        });
    });

  function checkValue(value){
    var arr=$('.listData').attr('data-id');
    for(var i=0; i<arr.length; i++){
      var name = arr[i];
      if(name == value){
        return 'class="alert alert-danger alert-dismissable";';
      }
    }
    return '';
  }
  

  function loadInputs() {
    
    var rowTotal=0;
    var colTotal=0;
    $('.loading').addClass('hidden');

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
    $.ajax({
      type: 'POST',
      url: '/user-timesheet-search',
      data: {
        'date': date,
        'projectId': projectId
      },
      success: function(res) {
        $(".overlay").remove();
        if (res.status == "OK") {
          $('.loading').addClass('hidden');
          $("#tableContent").html(res.data);
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

  function getMinutes(time) {
    var time = time.split(' ');
    var h = time[0].split('h');
    h=parseInt(h[0]);
    var m =time[1].split('m');
    m= parseInt(m[0]);
    return((h*60)+m);
  }
  function checkTaskPresence(id,items) {
    for (var i = items.length - 1; i >= 0; i--) {
      if(items[i] == id)
        return false;
    }
    return true;
  }

  function colSum(day,time) {
    colTotal = getMinutes($('#col_'+day).text());
    colTotal = colTotal + time;
    $('#col_'+day).text(Math.floor(colTotal / 60) + "h " + colTotal % 60 + "m");
    colTotal = getMinutes($('#col_total').text());
    colTotal = colTotal + time;
    $('#col_total').text(Math.floor(colTotal / 60) + "h " + colTotal % 60 + "m");
  }

    function inputsLoader() { 
        $('.chosen-select').chosen({
            width: "100%"
        });
        $("#data_1 .date input").attr('readonly', true);
    }

    function loadCurrentSession() {
      $.ajax({
          type: 'GET',
          url: '/check-task-session',
          data: {
              'task-id':$.cookie('running_task')
          },
          success: function (response) {
              if(response.flag== true){
                  if(response.status=='started'){
                      $('#pause-task').removeClass('hidden');
                  }
              }
          }
      });
  }

    function editSession() {
        $('.field-error').html('');
        openLoader();
        $.ajax({
            type: 'PATCH',
            url: $('#edit_session_form').attr('action'),
            data: $('#edit_session_form').serialize(),
            success: function(response) {
                closeLoader();
                $('#edit_task_session').modal('hide');
                ajaxUserTimeSheetSearch($('#date').val(),$('#projectId').val());
                $('#pause-task').addClass('hidden');
                loadCurrentSession();
                toastr.success(response.message,'Updated');
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
    }

    function createSession() {
        $('.field-error').html('');
        openLoader();
        $.ajax({
            type: 'POST',
            url: $('#create_session_form').attr('action'),
            data: $('#create_session_form').serialize(),
            success: function(response) {
                closeLoader();
                $('#add-session').modal('hide');
                ajaxUserTimeSheetSearch($('#date').val(),$('#projectId').val());
                toastr.success(response.message,'Added');
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
    }


    function process(date){
       var parts = date.split("/");
       return new Date(parts[2], parts[1] - 1, parts[0]);
    }

    function convertDate(date){
       return date.split("-").reverse().join("/");
    }