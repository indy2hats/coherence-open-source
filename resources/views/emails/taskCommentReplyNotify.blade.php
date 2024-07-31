@extends('emails.email-layout')
@section('content')
<table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
    style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;margin-top:0 !important;margin-bottom:0 !important;margin-right:auto !important;margin-left:auto !important;mso-table-lspace:0pt !important;mso-table-rspace:0pt !important;border-spacing:0 !important;border-collapse:collapse !important;table-layout:fixed !important;">
    <tbody style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;">
        <tr style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;">
            <td class="bg_white email-section"
                style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;mso-table-lspace:0pt !important;mso-table-rspace:0pt !important;background-color:#ffffff;background-image:none;background-repeat:repeat;background-position:top left;background-attachment:scroll;padding-top:2.5em;padding-bottom:2.5em;padding-right:2.5em;padding-left:2.5em;">
                <div class="heading-section"
                    style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;text-align:left;padding-top:0px;padding-bottom:0px;padding-right:0px;padding-left:0px;">
                    <h3
                        style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;font-family:'Work Sans', sans-serif;color:#000000;margin-top:0;font-weight:400;">
                        Hi {{$user->full_name}},</h3>
                    <p style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;margin-top:0;">
                        {{$comment->commenter->full_name}} has replied to a comment.</p>
                </div>
                <div class="services-list"
                style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;margin-top:0;margin-bottom:20px;margin-right:0;margin-left:0;width:100%;">
                <div class="text"
                    style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;">
                    <h3
                        style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;font-family:'Work Sans', sans-serif;color:#000000;font-size:18px;font-weight:400;margin-top:0;margin-bottom:0;">
                        Task</h3>
                    <p
                        style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;">
                        {{$comment->commentable->title ?? ''}}</p>
                </div>
            </div>
            <div class="services-list"
            style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;margin-top:0;margin-bottom:20px;margin-right:0;margin-left:0;width:100%;">
            <div class="text"
                style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;">
                <h3
                    style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;font-family:'Work Sans', sans-serif;color:#000000;font-size:18px;font-weight:400;margin-top:0;margin-bottom:0;">
                    Comment</h3>
                    <div style="text-align: left;max-width:100%;overflow: hidden;">
                    {!! $comment->parent->comment !!}
                    </div>
            </div>
        </div>

        <div class="services-list"
            style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;margin-top:0;margin-bottom:20px;margin-right:0;margin-left:0;width:100%;">
            <div class="text"
                style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;">
                <h3
                    style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;font-family:'Work Sans', sans-serif;color:#000000;font-size:18px;font-weight:400;margin-top:0;margin-bottom:0;">
                    Reply</h3>
                    <div style="text-align: left;max-width:100%;overflow: hidden;">
                        {!! $comment->comment !!}
                    </div>
            </div>
        </div>
        <p style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;margin-top:0;text-align: center;min-width: 300px;"><a href="{{url('/tasks/'.$comment->commentable->id)}}" class="btn btn-primary" style="-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;text-decoration:none;color:#fff;border-radius: 2rem;
            background: #1c84c6; padding: 5px 20px;
            display: inline-block;">View Task</a></p>
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