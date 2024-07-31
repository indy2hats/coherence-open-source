
    //data tables
    $(document).ready(function() {
        $('.dataTableProductive').DataTable({
          "lengthMenu": [[25, 50, -1], [25, 50, "All"]]
        });

        $('.dataTableUpskilling').DataTable({
          "lengthMenu": [[25, 50, -1], [25, 50, "All"]]
        });

        $('.dataTableIdle').DataTable({
          "lengthMenu": [[25, 50, -1], [25, 50, "All"]]
        });
        $('.overdueTaskTable').DataTable();

        $(".time-taken").each(function() {
            var count = $(this).data('count');
            var diff = $(this).data('total');
            var timer = $(this).children('span');
            if (count > 0) {

                $.each($(this).data('starts').split(','), function(index, value) { 
                    diff = diff + (( new Date() - new Date(value) ) / 1000 / 60) ;
                });

                var seconds_left = diff*60;
                var hours = 0;

                var countdownrefesh = setInterval(function () {

                    // Add one to seconds
                    seconds_left = seconds_left + count;

                    hours_left = hours*3600+seconds_left;

                    hours = parseInt(hours_left / 3600);
                    seconds_left = seconds_left % 3600;

                    minutes = parseInt(seconds_left / 60);

                    //minutes = parseInt(minutes/60*100);

                    t = hours + "h " + minutes + "m";
                    timer.html(t)

                }, 1000);
            }
            
        });

        $(".time").each(function() {
            var diff = ( new Date() - new Date($(this).data('start')) ) / 1000 / 60 ;

            var diff = diff+$(this).data('total');

            var seconds_left = diff*60;
            var hours = 0;


            var timer = $(this).children('.timer');

            var countdownrefesh = setInterval(function () {

                // Add one to seconds
                seconds_left = seconds_left + 1;

                hours_left = hours*3600+seconds_left;

                hours = parseInt(hours_left / 3600);
                seconds_left = seconds_left % 3600;

                minutes = parseInt(seconds_left / 60);

                t = hours + "h " + minutes + "m";
                timer.html(t)

            }, 1000);
        });
    });





    $(document).on('click','.send-alert',function() {
        $.ajax({
                type: 'POST',
                url: '/send-timer-notification',
                data:{
                    'user_id': $(this).attr('data-id')
                },
                success: function(response) {
                    toastr.success(response.message, 'Sent');
                }
            });
    });

    $("[data-toggle='tooltip']").tooltip({
    'selector': '',
    'container':'body'
  });

    $('#data_3 .input-group.date').datepicker({
                startView: 2,
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true,
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years",
                defaultDate: new Date()
            });

    load();

    $(document).on('change', '#date-chart', function() {
        load();
    });

    $(document).on('click', '.cache-clear', function() {
        $.ajax({
            method: 'POST',
            url: '/clear-chart-cache',
            data:{'date': $('#date-chart').val()},
            success: function(response) {
                toastr.success(response.success, 'Cleared!');
            }
        });
    });

    function load() {
        $('.income-chart').css('opacity',0.3);
        $.ajax({
            method: 'POST',
            url: '/load-chart-dashboard',
            data: {
                'date': $('#date-chart').val()
            },
            success: function(response) {
                $('.income-chart').css('opacity',1);
                pageLoad(response.expense,response.year,parseInt(response.year)+1);
            }
        });
    }

        function pageLoad(setData2, year,year2) {
            $('.income-chart').empty();
            $('.income-chart').append('<div><iframe class="chartjs-hidden-iframe" style="width: 100%; display: block; border: 0px none; height: 0px; margin: 0px; position: absolute; inset: 0px;"></iframe><canvas id="barChart" height="332" style="display: block; width: 570px; height: 266px;" width="700"></canvas></div>');
         var barData = {
            labels: ["Apr-"+year, "May-"+year, "Jun-"+year, "Jul-"+year,"Aug-"+year,"Sept-"+year,"Oct-"+year,"Nov-"+year,"Dec-"+year,"Jan-"+year2, "Feb-"+year2, "Mar-"+year2],
            datasets: [
                {
                    label: "Expense",
                    backgroundColor: '#dedede',
                    borderColor: "rgb(26,179,148)",
                    pointBackgroundColor: "rgb(26,179,148)",
                    pointBorderColor: "#fff",
                    data: setData2
                }
            ]
        };

        var barOptions = {
            responsive: true
        };


        var ctx2 = document.getElementById("barChart").getContext("2d");
        new Chart(ctx2, {type: 'bar', data: barData, options:barOptions});

    }

  


