<script type="text/javascript">
$('.userTable').DataTable({
                paging:false,

                info:false,

                ordering:false,

                responsive: true,

                dom: '<"html5buttons"B>lTfgitp',

                buttons: [

                    { extend: 'copy'},

                    {extend: 'csv'},

                    {extend: 'excel', title: 'Hours Spent by Employees'},

                    {extend: 'pdf', title: 'Hours Spent by Employees'},



                    {extend: 'print',

                     customize: function (win){
                      $(win.document.body)
                            .css( 'font-size', '10pt' )
                            .prepend(
                                '<div><h2>Hours Spent by Employees</h2></div>'
                            );

                            $(win.document.body).addClass('white-bg');

                            $(win.document.body).css('font-size', '10px');



                            $(win.document.body).find('table')

                                    .addClass('compact')

                                    .css('font-size', 'inherit');

                    }

                    }

                ]

            });
</script>
