
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

   

    toastr.options = toasterOption();
    function toasterOption() {
    return {
      "closeButton": true,
      "debug": false,
      "progressBar": true,
      "preventDuplicates": false,
      "positionClass": "toast-top-right",
      "onclick": null,
      "showDuration": "2000",
      "hideDuration": "2000",
      "timeOut": "2000",
      "extendedTimeOut": "2000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    };
  }

  function openLoader()
{
    $("body").append('<div class="overlay"><div class="overlay__inner"><div class="overlay__content"><span class="spinner"></span></div></div></div>');
}
function closeLoader()
{
    $(".overlay").remove();
}






    $(function () {
        $('body').tooltip({selector: '[data-tooltip="tooltip"]'});
        $(document).on('click', '.create-eod-report', function(e) {
            toasterOption();
            e.preventDefault();
            $('.field-error').html('');
            openLoader();
            var data = $('#add_eod_form').serialize();
            $.ajax({
                url: $('#add_eod_form').attr('action'),
                data: data,
                type: 'POST',
                success: function(response) {
                    $('#add_eod_report').modal('hide');
                    closeLoader();
                    toastr.success(response.message, 'Created');
                    $("#eod_report_li").remove();
                },
                error: function(error) {
                    closeLoader();
                    if (error.status == 422) {
                        if (error.responseJSON.errors) {
                            $.each(error.responseJSON.errors, function(field, error) {
                                $('#add_eod_form #label_' + field).html(error);
                            });
                        }
                    }
                }
            });
        });

        $('#add_eod_report').on('hidden.bs.modal', function() {
            $(this).find('.text-danger').html('');
            $('#add_eod_form').trigger('reset');
        });

        function globalSearch() {

            var searchInputElem = $('#gSearch');
            var term = searchInputElem.val();
            

            if ( searchInputElem.data('prevTerm') == term) {
                return;
            }
            
            if (term.length > 2) {
                searchSpinnerToggle(true);
            }else{
                searchSpinnerToggle(false);
            }

            searchInputElem.data('prevTerm', term);

            $.ajax({
                type:'POST',
                url:"/search",
                data:{
                    'q':term
                },
                success: function( response ) {
                    searchSpinnerToggle(false);
                    $('#globalSearchResultWrapper').html(response.data);
                },
                complete: function() {
                    //searchInputElem.data('prevTerm', term);
                }
            });

        }       

        //global search on top nav
        $(document).on("keyup",'#gSearch', function () {
            var strLen = $(this).val().length; 
            if(strLen > 2){
                setTimeout(globalSearch, 1000);
            }          
        });
    });


    function searchSpinnerToggle(active) {
        if (active) {
            $('.g-search-li .fa-spinner').show();
            $('.g-search-li .fa-search').hide();
        }else{
            $('.g-search-li .fa-spinner').hide();
            $('.g-search-li .fa-search').show();
        }
    }

    $(document).mouseup(function(e) 
    {
        var container = $(".search-result");

        // if the target of the click isn't the container nor a descendant of the container
        if (!container.is(e.target) && container.has(e.target).length === 0) 
        {
            container.hide();
        }
    });


    function eodmodal(argument) {
        if($('#eodmodal').length){
            $("#eodmodal").modal('show');

            $('#eodmodal').on('hidden.bs.modal', function (e) {
                e.preventDefault();
                var editUrl = '/eod-report-notified';
                $.ajax({
                    type: 'GET',
                    url: editUrl,
                    data: {},
                    success: function(data) {
                    }
                });
            });
        }
    }
    $(document).ready(function(){
        $('.floatingButton').on('click',
            function(e){
                e.preventDefault();
                $(this).toggleClass('open');
                if($(this).children('.epms-icon--1x').hasClass('ri-add-line'))
                {
                    $(this).children('.epms-icon--1x').removeClass('ri-add-line');
                    $(this).children('.epms-icon--1x').addClass('ri-close-fill');
                } 
                else if ($(this).children('.epms-icon--1x').hasClass('ri-close-fill')) 
                {
                    $(this).children('.epms-icon--1x').removeClass('ri-close-fill');
                    $(this).children('.epms-icon--1x').addClass('ri-add-line');
                }
                $('.floatingMenu').stop().slideToggle();
            }
        );
        $(this).on('click', function(e) {
            var container = $(".floatingButton");

            // if the target of the click isn't the container nor a descendant of the container
            if (!container.is(e.target) && $('.floatingButtonWrap').has(e.target).length === 0) 
            {
                if(container.hasClass('open'))
                {
                    container.removeClass('open');
                }
                if (container.children('.epms-icon--1x').hasClass('ri-close-fill')) 
                {
                    container.children('.epms-icon--1x').removeClass('ri-close-fill');
                    container.children('.epms-icon--1x').addClass('ri-add-line');
                }
                $('.floatingMenu').hide();
            }
        });
    });
