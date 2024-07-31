<script type="text/javascript">
$('.projectTable').DataTable({
                paging:false,

                info:false,

                ordering:false,

                responsive: true,

                dom: '<"html5buttons"B>lTfgitp',

                buttons: [

                    { extend: 'copy'},

                    {extend: 'csv'},

                    {extend: 'excel', title: 'Hours Spent on Projects'},

                    {extend: 'pdf', title: 'Hours Spent on Projects'},



                    {extend: 'print',

                     customize: function (win){
                      $(win.document.body)
                            .css( 'font-size', '10pt' )
                            .prepend(
                                '<div><h2>Hours Spent on Projects</h2></div>'
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

$('.taskTable').DataTable({
                paging:false,

                info:false,

                ordering:false,

                responsive: true,

                dom: '<"html5buttons"B>lTfgitp',

                buttons: [

                    { extend: 'copy'},

                    {extend: 'csv'},

                    {extend: 'excel', title: 'Task List of {{$project->project_name}}' },

                    {extend: 'pdf', title: 'Task List of {{$project->project_name}}'},



                    {extend: 'print',

                     customize: function (win){
                      $(win.document.body)
                            .css( 'font-size', '10pt' )
                            .prepend(
                                '<div><h2>Task List of {{$project->project_name}}</h2></div>'
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
