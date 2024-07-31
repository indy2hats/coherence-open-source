<script type="text/javascript">
    $(document).ready(function() {
        loadInputs();
        loadTaskReport();
        $(".search").on('click', function(e){
            e.preventDefault();
            loadTaskReport();
        });
    });
    function initTable()
    {
        $('.listTable2').DataTable({
                pageLength: 25,
                responsive: true,
                "lengthMenu": [[25, 50, -1], [25, 50, "All"]],
                dom: '<"html5buttons"B>lTfgitp',
                "bInfo" : false,
                columnDefs: [
                    { targets: [2, 3, 4], orderable: false } 
                ],
               
                buttons: [
                    {extend: 'copy'},
                    {extend: 'csv'},
                    {extend: 'excel', title: 'Performance Report'},
                    {extend: 'pdfHtml5',
                        title: 'Performance Report',
                        customize: function(doc) {
                            doc.styles.tableHeader.alignment = 'left';
                        }
                    },
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

    function loadTaskReport()
    {
        openLoader();
        $.ajax({
            method: 'POST',
            url: '{{route('bounceReportSearch')}}',
            data: $("#task-search-form").serialize(),
            success: function(response) {
                closeLoader();
                $("#report_content").html(response.data);
                initTable();
            }
        });
    }

    function loadInputs() {
        var date = new Date();
        $('input[name="daterange"]').daterangepicker({
          opens: 'left',
          locale: {
            format: 'DD/MM/YYYY'
          },
          maxDate: date
        }); 
    }
</script>