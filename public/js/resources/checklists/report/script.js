
    $(document).ready(function() {
        loadInputs();
        loadPerformance();

        $(".searchSheet").on('click', function(e){
           loadPerformance();
        });

        $(".arrow-back").on('click', function(e){
            e.preventDefault();
            var m = moment(process($('.datepicker').val()));
            m.subtract('days', 1);
            $('.datepicker').datepicker('setDate', m.toDate());
            loadPerformance();
        });

        $(".arrow-front").on('click', function(e){
            e.preventDefault();
            var m = moment(process($('.datepicker').val()));
            m.add('days', 1);
            if(m.diff(moment(), 'days') < 0){
                $('.datepicker').datepicker('setDate', m.toDate());
                loadPerformance();
            } else {
                toastr.error('Future days not allowed', 'Error');
            }
        });

        $(".todayBtn").on('click', function(e){
            e.preventDefault();
            var m = moment(process($(this).attr('data-date')));
            $('.datepicker').datepicker('setDate', m.toDate());
            loadPerformance();
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
          endDate: '+0d',
          format: 'dd/mm/yyyy',
        }).on('changeDate', function (selected) {
            var minDate = selected.format();
            $("#daterange").val(minDate);
        });
        $('.chosen-select').chosen({width:"100%"});
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

    function process(date){
       var parts = date.split("/");
       return new Date(parts[2], parts[1] - 1, parts[0]);
    }


