

    $(document).ready(function() {

        $(document).on('change','#clientId',function() {
          if($('#clientId').val()==""){
            $('.alert-start').removeClass('hidden');
            return;
          }
          loadData($('#daterange').val(),$('#clientId').val());
        });

        $(document).on('click','.search',function() {
          if($('#clientId').val()==""){
            $('.alert-start').removeClass('hidden');
            return;
          }
          loadData($('#daterange').val(),$('#clientId').val());
        });
    });
    function inputsLoader() {
        $('.chosen-select').chosen({
          width: "100%"
        });
        $('input[name="daterange"]').daterangepicker({
          opens: 'left',
          locale: {
            format: 'DD/MM/YYYY'
          }
        }); 

         $('.dataClient').DataTable({
                paging:false,

                searching:false,

                info:false,

                ordering:false,

                responsive: true,

                dom: '<"html5buttons"B>lTfgitp',

                buttons: [

                    { extend: 'copy'},

                    {extend: 'csv'},

                    {extend: 'excel', title: 'Hours Entered for '+$('#clientId option:selected').text()},

                    {extend: 'pdf', title: 'Hours Entered for '+$('#clientId option:selected').text()},



                    {extend: 'print',

                     customize: function (win){
                          $(win.document.body)
                            .css( 'font-size', '10pt' )
                            .prepend(
                                '<div><h2>Hours Entered for '+$('#clientId option:selected').text()+'</h2></div>'
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
    }
    inputsLoader();

    function loadData(daterange,clientId) {
        $('.alert-start').addClass('hidden');
        $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
        $.ajax({
          type: 'POST',
          url: '/client-daterange-search',
          data: {
            'daterange':daterange,
            'clientId':clientId
          },
          success: function(response) {
            $('.overlay').remove();
            $('.list').html(response.data);
            inputsLoader();
          }
           
        });
    }


