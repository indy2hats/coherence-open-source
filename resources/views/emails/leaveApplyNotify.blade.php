@extends('emails.email-layout')
@section('content')

<table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;margin-top:0 !important;margin-bottom:0 !important;margin-right:auto !important;margin-left:auto !important;mso-table-lspace:0pt !important;mso-table-rspace:0pt !important;border-spacing:0 !important;border-collapse:collapse !important;table-layout:fixed !important;" >
<tbody style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;" >
    <tr style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;" >
    <td class="bg_white email-section" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;mso-table-lspace:0pt !important;mso-table-rspace:0pt !important;background-color:#ffffff;background-image:none;background-repeat:repeat;background-position:top left;background-attachment:scroll;padding-top:2.5em;padding-bottom:2.5em;padding-right:2.5em;padding-left:2.5em;" >
    <div class="heading-section" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;text-align:left;padding-top:0px;padding-bottom:0px;padding-right:0px;padding-left:0px;" >
        <h3 style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;font-family:'Work Sans', sans-serif;color:#000000;margin-top:0;font-weight:400;" >Hi {{$user->full_name}},</h3>
        <p style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;margin-top:0;" >{{$leave->users->full_name}} has applied for a leave with the following details.</p>
    </div>
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;mso-table-lspace:0pt !important;mso-table-rspace:0pt !important;border-spacing:0 !important;border-collapse:collapse !important;table-layout:fixed !important;margin-top:0 !important;margin-bottom:0 !important;margin-right:auto !important;margin-left:auto !important;" >
        <tbody style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;" ><tr style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;" >
            <td class="text-services" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;text-align:left;padding-right:25px;mso-table-lspace:0pt !important;mso-table-rspace:0pt !important;padding-top:10px;padding-bottom:0;padding-left:10px;" >
                        <div class="services-list" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;margin-top:0;margin-bottom:20px;margin-right:0;margin-left:0;width:100%;" >
                            <div class="text" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;" >
                                <h3 style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;font-family:'Work Sans', sans-serif;color:#000000;font-size:18px;font-weight:400;margin-top:0;margin-bottom:0;" >From Date</h3>
                                <p style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;" >{{$leave->from_date_format}}</p>
                            </div>
                        </div>
                        <div class="services-list" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;margin-top:0;margin-bottom:20px;margin-right:0;margin-left:0;width:100%;" >
                            <div class="text" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;" >
                                <h3 style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;font-family:'Work Sans', sans-serif;color:#000000;font-size:18px;font-weight:400;margin-top:0;margin-bottom:0;" >To Date</h3>
                                <p style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;" >{{$leave->to_date_format}}</p>
                            </div>
                        </div>
                        <div class="services-list" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;margin-top:0;margin-bottom:20px;margin-right:0;margin-left:0;width:100%;" >
                            <div class="text" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;" >
                                <h3 style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;font-family:'Work Sans', sans-serif;color:#000000;font-size:18px;font-weight:400;margin-top:0;margin-bottom:0;" >No: of days</h3>
                                <p style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;" >{{$leave->total_leave_days}}</p>
                            </div>
                        </div>
                        <div class="services-list" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;margin-top:0;margin-bottom:20px;margin-right:0;margin-left:0;width:100%;" >
                            <div class="text" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;" >
                                <h3 style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;font-family:'Work Sans', sans-serif;color:#000000;font-size:18px;font-weight:400;margin-top:0;margin-bottom:0;" >Leave Type</h3>
                                <p style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;" >{{$leave->type}}</p>
                            </div>
                        </div>
                        <div class="services-list" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;margin-top:0;margin-bottom:20px;margin-right:0;margin-left:0;width:100%;" >
                            <div class="text" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;" >
                                <h3 style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;font-family:'Work Sans', sans-serif;color:#000000;font-size:18px;font-weight:400;margin-top:0;margin-bottom:0;" >Leave Session</h3>
                                <p style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;" >{{$leave->session}}</p>
                            </div>
                        </div>
                        <div class="services-list" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;margin-top:0;margin-bottom:20px;margin-right:0;margin-left:0;width:100%;" >
                            <div class="text" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;" >
                                <h3 style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;font-family:'Work Sans', sans-serif;color:#000000;font-size:18px;font-weight:400;margin-top:0;margin-bottom:0;" >Reason</h3>
                                <p style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;" >{!! $leave->reason!!}</p>
                            </div>
                        </div>
                        @if($user->can('manage-leaves'))
                            <p style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;margin-top:0;text-align: center;min-width: 300px;"><a href="{{url('/get-leave/'.$leave->email_code.'.'.$user->id)}}" class="btn btn-primary" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;text-decoration:none;color:#fff;border-radius: 2rem;
                            background: #1c84c6; padding: 5px 20px;
                        display: inline-block;">Accept Leave</a><a href="{{url('/get-leave/'.$leave->email_code.'.'.$user->id)}}" class="btn btn-primary" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;text-decoration:none;color:#fff;border-radius: 2rem;
                            background: #ed5565; padding: 5px 20px;
                        display: inline-block; margin-left:10px;">Reject Leave</a></p>
                        @endif
            </td>
        </tr>
        </tbody></table>
    </td>
    </tr>
    </tbody></table>
    </td>
</tr>
    
    <!-- end: tr -->
<!-- end tr -->
    <!-- end: tr -->
    <!-- end: tr -->
        <!-- end: tr -->
<!-- 1 Column Text + Button : END -->
</tbody>
</table>
@endsection