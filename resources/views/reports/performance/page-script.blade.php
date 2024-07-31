<script type="text/javascript">
    $(document).ready(function() {
        loadInputs();
        loadPerformance();
        $(".search").on('click', function(e){
            e.preventDefault();
            loadPerformance();
        });
    });
    function loadInputs() {
        var date = new Date();
        date.setDate(date.getDate() - 1);
    $('input[name="daterange"]').daterangepicker({
          opens: 'left',
          locale: {
            format: 'DD/MM/YYYY'
          },
          maxDate: date
        }); 
    }

    function loadPerformance()
    {
        openLoader();
        $.ajax({
            method: 'POST',
            url: '{{route('employeePerformanceSearch')}}',
            data: $("#task-search-form").serialize(),
            success: function(response) {
                closeLoader();
                $("#performance_content").html(response.data);
                initTable();
            }
        });
    }


    function initTable()
    {
        $('.listData').DataTable({
                pageLength: 25,
                responsive: true,
                "lengthMenu": [[25, 50, -1], [25, 50, "All"]],
                dom: '<"html5buttons"B>lTfgitp',
                "bInfo" : false,
                buttons: [
                    { extend: 'copy'},
                    {extend: 'csv'},
                    {extend: 'excel', title: 'Performance Report'},
                    {extend: 'pdf', title: 'Performance Report'},

                    {extend: 'print',
                     customize: function (win){
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');

                            $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('font-size', 'inherit');
                    }
                    }
                ]

            });
    }

</script>
