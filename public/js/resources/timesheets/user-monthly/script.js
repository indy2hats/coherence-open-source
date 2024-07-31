
    
    $(document).ready(function() {

        var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      plugins: [ 'interaction', 'dayGrid', 'timeGrid' ],
      defaultView: 'dayGridMonth',
      header: {
        left: 'prev,next',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek'
      },
      events: [data]
    });

    alert(data)
;

        $("#userId").on('change',function () {
            if($('#projectId').val() == '' || $('#userId').val() == ''){
                $('.alert-start').removeClass('hidden');
                return;
            }
            loadEvents();
        });

        $("#projectId").on('change',function () {
            if($('#projectId').val() == '' || $('#userId').val() == ''){
                $('.alert-start').removeClass('hidden');
                return;
            }
            loadEvents(); 
        });

        function loadEvents(){ 

          $.ajax({
            url: "/user-month-search",
            type: "POST",
            data: {
              'userId':$('#userId').val(),
              'projectId':$('#projectId').val()
            },
            success: function (response) {
                data=JSON.stringify(response.data);
                calendar.fullCalendar({
                    events: [data]
                });
            }
          });
        }



    });
    function inputsLoader() {
        $('.chosen-select').chosen({
          width: "100%"
        });
        
    }
    inputsLoader();

 