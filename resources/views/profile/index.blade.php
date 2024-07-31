@extends('layout.main')
@section('content')
@include('profile.details') 
@endsection
@section('after_scripts')
<script src="{{ asset('js/plugins/sparkline/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('js/plugins/d3/d3.min.js') }}"></script>
<script src="{{ asset('js/plugins/c3/c3.min.js') }}"></script>
<script src="{{ asset('js/resources/profile/script-min.js') }}"></script>
<script>

$(document).ready(function() {

@hasrole('project-manager|employee')
    Morris.Donut({
        element: 'donut',
        data: [{ label: "LOP", value: {{$leave['lop']}} },
            { label: "Casual", value: {{$leave['casual']}} },
            { label: "Medical", value: {{$leave['medical']}} } ],
        resize: true,
        colors: ['#ea3f59', '#403dce','#23af59'],
    });
@endhasrole

    c3.generate({
        bindto: '#pie',
        data: {
            columns: [
                ['Low', {{$rejections['low']}} ],
                ['Medium', {{$rejections['medium']}} ],
                ['High', {{$rejections['high']}} ],
                ['Critical', {{$rejections['critical']}} ]
            ],
            colors:{

                    Low: '#23af59',

                    Medium: '#403dce',

                    High: '#f224c2',

                    Critical: '#ea3f59'

                },
            type: 'pie'
        }
    });


var sparklineCharts = function(){

     $("#sparkline1").sparkline([{{$total['Mon']/60}},{{$total['Tue']/60}}, {{ $total['Wed']/60 }}, {{$total['Thu']/60}},{{$total['Fri']/60}},{{$total['Sat']/60}},{{$total['Sun']/60}} ], {

         type: 'line',

         width: '100%',

         height: '60',

         lineColor: '#1ab394',

         fillColor: "#ffffff"

     });


};



var sparkResize;



$(window).resize(function(e) {

    clearTimeout(sparkResize);

    sparkResize = setTimeout(sparklineCharts, 500);

});



sparklineCharts();

});</script>
@endsection