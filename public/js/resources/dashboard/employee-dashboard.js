
$(document).ready(function() {


$('.chosen-select').chosen({
    width:"100%"
});




    var sparkResize;



    $(window).resize(function(e) {

        clearTimeout(sparkResize);

        sparkResize = setTimeout(sparklineCharts, 500);

    });



    sparklineCharts();





});

