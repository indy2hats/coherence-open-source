
    jQuery(document).ready(function() {
        loadInputs();
        loadTaks();

        $(".search").on('click', function(e){
            e.preventDefault();
            loadTaks();
        });
    });
    function loadInputs() {


    $('.chosen-select').chosen({
      width: "100%"
    });

    $('input[name="daterange"]').daterangepicker({
          opens: 'left',
          locale: {
            format: 'DD/MM/YYYY'
          }
        }); 
    }

    function loadTaks() {
        openLoader();
        $.ajax({
            method: 'POST',
            url: '/task-bounce',
            data: $("#task-search-form").serialize(),
            success: function(response) {
                closeLoader();
                $("#task_content").html(response.data);
                if($('.listData tbody tr').length > 1){
                    $(".listData").dataTable({
                            "lengthMenu": [[25, 50, -1], [25, 50, "All"]]
                    });
                }
            }
        });
    }

    // Bounce Chart
    load();

    $(document).on('change', '#daterange, #userId', function() {
        load();
    });

    function load() {
        $('.bounce-chart').css('opacity',0.3);
        $.ajax({
            method: 'POST',
            url: '/load-bounce-chart',
            data: {
                'daterange': $('#daterange').val(),
                'userId': $('#userId').val()
            },
            success: function(response) {
                $('.bounce-chart').css('opacity',1);
                if(response.error) {
                    var userSelect = $('#userId'); 
                    userSelect.empty();
                    userSelect.append('<option value="">Select User</option>');
                    userSelect.trigger("chosen:updated");
                    
                    if(response.users){
                        var userSelect = $('#userId'); 
                        userSelect.empty();
                        userSelect.append('<option value="">Select User</option>');
                        
                        $.each(response.users, function(key, value) {
                            userSelect.append('<option value="' + key + '" selected>' + value + '</option>');
                        });
                        
                        userSelect.trigger("chosen:updated");
                    }

                    $('.bounce-chart').empty();
                    $('.bounce-chart').append('<div style="color:red;">'+response.error+'</div>');
                } else {
                    if(response.users){
                        var userSelect = $('#userId'); 
                        userSelect.empty();
                        userSelect.append('<option value="">Select User</option>');
                        
                        $.each(response.users, function(key, value) {
                            userSelect.append('<option value="' + key + '">' + value + '</option>');
                        });
                        
                        userSelect.trigger("chosen:updated");
                    }
                    pageLoad(response.bounce, response.months);
                }
            },
     
        });
    }

    function pageLoad(setData1, months) {
            var rejected = 'Rejection Count';
            $('.bounce-chart').empty();
            $('.bounce-chart').append('<div><iframe class="chartjs-hidden-iframe" style="width: 100%; display: block; border: 0px none; height: 0px; margin: 0px; position: absolute; inset: 0px;"></iframe><canvas id="barChart" height="332" style="display: block; width: 570px; height: 266px;" width="700"></canvas></div>');
            var barData = {
            labels: months,
            datasets: [
                {
                    label: "Rejection Count",
                    backgroundColor: '#b5b8cf',
                    borderColor: "rgb(26,179,148)",
                    pointBackgroundColor: "rgb(26,179,148)",
                    pointBorderColor: "#fff",
                    data: setData1,
                    maxBarThickness: 10
                }
            ]
        };

        var barOptions = {
            responsive: true,
            scales: {
                yAxes: [{
                    scaleLabel: {
                    display: true,
                    labelString: rejected
                    },
                    ticks: {
                        beginAtZero: true,
                        suggestedMax: Math.max(...setData1) + .1
                    }
                }]
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                    var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || '';
                    var value = tooltipItem.yLabel;
                    return datasetLabel + ': ' + value ;
                    }
                }
            }
        };

        var ctx2 = document.getElementById("barChart").getContext("2d");
        new Chart(ctx2, {type: 'bar', data: barData, options:barOptions});

    }

    
