

    $(document).ready(function() {

        $(document).on('change','#userId',function() {
          if($('#userId').val()==""){
            $('.alert-start').removeClass('hidden');
            return;
          }
          loadData($('#daterange').val(),$('#userId').val());
        });

        $(document).on('click','.search',function() {
          if($('#userId').val()==""){
            $('.alert-start').removeClass('hidden');
            return;
          }
          loadData($('#daterange').val(),$('#userId').val());
        });
    });
    function inputsLoader() {
        $('.chosen-select').chosen({
          width: "100%"
        });
        $('input[name="daterange"]').daterangepicker({
          opens: 'left',
          locale: {
              format: 'DD/MM/YYYY',
            }
        }); 

         $('.dataUser').DataTable({
                paging:false,

                searching:false,

                info:false,

                ordering:false,

                responsive: true,

                dom: '<"html5buttons"B>lTfgitp',

                buttons: [

                    { extend: 'copy'},

                    {extend: 'csv'},

                    {extend: 'excel', title: 'Hours Entered by '+$('#userId option:selected').text()},

                    {extend: 'pdf', title: 'Hours Entered by '+$('#userId option:selected').text()},



                    {extend: 'print',

                     customize: function (win){
                      $(win.document.body)
                            .css( 'font-size', '10pt' )
                            .prepend(
                                '<div><h2>Hours Entered by '+$('#userId option:selected').text()+'</h2></div>'
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

    function loadData(daterange,userId) {
        $('.alert-start').addClass('hidden');
        $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
        $.ajax({
          type: 'POST',
          url: '/user-daterange-search',
          data: {
            'daterange':daterange,
            'userId':userId
          },
          success: function(response) {
            $('.overlay').remove();
            $('.list').html(response.data);
            inputsLoader();
          }
           
        });
    }

