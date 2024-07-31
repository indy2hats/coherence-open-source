
    $(document).ready(function() {
        loadInputs();
        loadPerformance();
        $(".search").on('click', function(e){
            e.preventDefault();
            loadPerformance();
        });

        $(".arrow-back").on('click', function(e){
            e.preventDefault();
            var m = moment(process($('.datepicker').val()));
            m.subtract('days', 1);
            $('.datepicker').datepicker('setDate', m.toDate());
        });

        $(".arrow-front").on('click', function(e){
            e.preventDefault();
            var m = moment(process($('.datepicker').val()));
            m.add('days', 1);
            $('.datepicker').datepicker('setDate', m.toDate());
        });

        $(".todayBtn").on('click', function(e){
            e.preventDefault();
            var m = moment(process($(this).attr('data-date')));
            $('.datepicker').datepicker('setDate', m.toDate());
        });
    });
    function loadInputs() {
        var date = new Date();
        date.setDate(date.getDate() - 1);
        $('.datepicker').datepicker({
          todayBtn: "linked",
          keyboardNavigation: false,
          forceParse: false,
          calendarWeeks: true,
          autoclose: true,
          format: 'dd/mm/yyyy',
        }).on('changeDate', function (selected) {
            var minDate = selected.format();
            if(minDate){
                $("#daterange").val(minDate);
                $("#daterange").attr('data-searchdate',minDate);
            }
            else{
                var dateD = $("#daterange").attr('data-searchdate');
                $("#daterange").val(dateD);
                $('.datepicker').datepicker('setDate', dateD);
            }
            loadPerformance();
        });
    }

    function loadPerformance()
    {
        openLoader();
        $.ajax({
            method: 'POST',
            url: '/daily-status-report',
            data: $("#task-search-form").serialize(),
            success: function(response) {
                closeLoader();
                $("#formatted_date").html(response.formatted_date);
                $("#performance_content").html(response.data);
                initTable();
            }
        });
    }


    function initTable()
    {
        $('.listData').DataTable({
            "paging": false,
            responsive: true,
         });
    }

    function process(date){
       var parts = date.split("/");
       return new Date(parts[2], parts[1] - 1, parts[0]);
    }

