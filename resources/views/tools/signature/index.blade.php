<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Signature Customizer</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;600&display=swap');

        body {
            margin: 0;
        }

        .main-wrapper {
            display: flex;
            font-family: 'Poppins', sans-serif;
            height: 100vh;
        }

        .half-col {
            width: 50%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .inner-block {
            padding: 50px;
            width: 100%;
        }

        .form-col {
            background: #fff;
        }

        .preview-col {
            background: #efefef;
        }

        .header {
            margin-bottom: 30px;
            text-align: center;
        }

        .header .logo {
            max-width: 80px;
        }

        .header h1,
        .header h2 {
            font-size: 30px;
            line-height: 35px;
            font-weight: 300;
            text-transform: uppercase;
            color: #211529;
            margin-bottom: 30px;
            margin-top: 0;
        }

        .header h1 {
            margin-top: 30px;
        }

        .header span {
            font-size: 18px;
            line-height: 23px;
            font-weight: 300;
            color: #211529;
        }

        .header p,
        .footer p {
            text-align: center;
            font-size: 14px;
            line-height: 18px;
            font-weight: 600;
            color: #707070;
        }

        .header p {
            display: none;
        }

        .form-block {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }

        .form-block input {
            border: 0;
            border-bottom: 1px solid #bbb;
            padding: 5px 15px;
            color: #211529;
            font-size: 10px;
            line-height: 11px;
        }


        .form-block label {
            text-align: left;
            font-size: 10px;
            line-height: 11px;
            color: #707070;
            padding-left: 15px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-column-gap: 30px;
            grid-row-gap: 30px;
        }


        .preview-block {
            max-width: 450px;
            padding: 25px;
            background: #fff;
            width: 100%;
            margin: 0 auto;
            margin-bottom: 30px;
            -webkit-box-shadow: 0 0 11px 2px #D4D4D4;
            box-shadow: 0 0 11px 2px #D4D4D4;
            position: relative;
        }

        .copy-button {
            position: absolute;
            right: 25px;
            top: 25px;
            border: 0;
            background: #211529;
            padding: 5px 15px;
            text-align: center;
            color: #fff;
            cursor: pointer;
        }

        @media(max-width:1100px) {
            .inner-block {
                padding: 30px;
                width: 100%;
            }

            .header h1,
            .header h2 {
                font-size: 20px;
                line-height: 25px;
            }

            .header span {
                font-size: 15px;
                line-height: 25px;
            }

            .preview-block {
                max-width: 400px;
            }
        }

        @media(max-width:1024px) {
            .preview-col {
                display: none;
            }

            .form-col {
                width: 100%;
            }

            .header h1,
            .header span {
                display: none;
            }

            .header p {
                display: block;
            }

            .form-block {
                display: none;
            }
        }
    </style>

</head>

<body>

    <div class="main-wrapper">
        <div class="half-col form-col">
            <div class="inner-block">
                <div class="header">
                @if(Helper::getCompanyLogo())
                    <a href="https://www.2hatslogic.com/" target="_blank">
                        <img src="{{asset(Helper::getCompanyLogo())}}" alt="2hats Logic Solutions Logo"
                            class="logo">
                    </a>
                @endif
                    <h1>Welcome to the {{ $info['company_name']['value'] }} <br>
                        email signature customizer!</h1>
                    <span>Fill out the form with your {{ $info['company_name']['value'] }} contact information. </span>
                    <p>Please visit the {{ $info['company_name']['value'] }} email signature generator on a desktop computer or large
                        tablet.</p>
                </div>
                <div class="form-block">
                    <form action="">
                        <div class="form-row">
                            <div class="form-column">
                                <input type="text" maxlength="45" placeholder="Full Name" id="getname"
                                    onKeyUp="upname();">
                                <label for="">Name (45 characters)</label>
                            </div>
                            <div class="form-column">
                                <input type="text" maxlength="45" placeholder="Title" id="gettitle"
                                    onKeyUp="uptitle();">
                                <label for="">Title (45 characters)</label>
                            </div>
                            <div class="form-column">
                                <input type="text" placeholder="+91-" maxlength="14" value="+91-" id="getmobile"
                                    onKeyUp="upmobile();">
                                <label for="">Mobile Phone (10 characters)</label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="half-col preview-col">
            <div class="inner-block">
                <!-- <div class="header">
                    <h2>Gmail</h2>
                </div>     -->
                <div class="preview-block">
                    <button class="copy-button" id="copybtn" onclick="CopyToClipboard('preview-card')">Copy to
                        Clipboard</button>
                    <table style="font-family:'Poppins', sans-serif; width: 100%;" id="preview-card">
                        <tbody>
                            <tr>
                                <td>
                                    <p
                                        style="font-size:11px;line-height:16px;font-weight:300; color:#000; margin: 0; margin-bottom: 8px;">
                                        With kind
                                        regards,</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p style="font-size:14px;line-height:18px;font-weight:600; color:#202D42; margin: 0; text-transform: capitalize;"
                                        id="putname">
                                        Full Name</p>
                                    <p style="font-size:11px;line-height:16px;font-weight:300; color:#33475B; margin: 0; margin-bottom: 10px; text-transform: capitalize;"
                                        id="puttitle">
                                        Title</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p style="margin: 0; font-size:11px; line-height: 11px;">
                                        <a href=""
                                            style=" font-size:11px;line-height:16px;font-weight:400; color:#202D42; text-decoration: none;"
                                            id="putmobile">+91-</a>
                                    </p>
                                    <p style="margin: 0; margin-bottom: 5px; font-size:11px; line-height: 11px;">
                                        <a href="{{ $info['company_website_url']['value'] }}"
                                            style="font-size:11px;line-height:16px;font-weight:400; color:#202D42; text-decoration: none;"
                                            target="_blank">{{ $info['company_website_url']['value'] }}</a>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table>
                                        <tr>
                                        @if(Helper::getCompanyLogo())
                                            <td><img src="{{asset(Helper::getCompanyLogo())}}" alt="2hatslogic_logo"
                                                    style="width: 58px !important; margin-top: 5px;"></td>
                                        @endif
                                            <td style="padding-left:10px;">
                                                <p
                                                    style="font-size:12px;line-height:16px;font-weight:600; color:#202D42; margin: 0;text-transform: capitalize;">
                                                    {{ $info['company_name']['value'] }}</p>
                                                <p
                                                    style="font-size:11px;line-height:16px;font-weight:400; color:#202D42; margin: 0; font-style: normal;text-transform: capitalize;">
                                                    {{ $info['company_address_line1']['value'] }}<br>
                                                    @if($info['company_address_line2']['value'])
                                                    {{ $info['company_address_line2']['value'] }}<br>
                                                    @endif
                                                    {{ $info['company_zip']['value'] }}, {{ $info['company_state']['value'] }}, {{ $info['company_country']['value'] }} | Phone: {{ $info['company_phone']['value'] }}<br></a> </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table>
                                        <tr>
                                            @if($info['company_linkedin_link']['value'])
                                            <td><a href="{{ $info['company_linkedin_link']['value'] }}" target="_blank"><img
                                                src="{{asset('img/signature/linkedin-icon.png')}}"
                                                alt="linkedIn" style="max-width:20px !important;"></a></td>
                                            @endif
                                            @if($info['company_instagram_link']['value'])
                                            <td><a href="{{ $info['company_instagram_link']['value'] }}" target="_blank"><img
                                                src="{{asset('img/signature/instagram-icon.png')}}"
                                                alt="Instagram" style="max-width:20px !important;"></a></td>
                                            @endif
                                            @if($info['company_twitter_link']['value'])
                                            <td><a href="{{ $info['company_twitter_link']['value'] }}" target="_blank"><img
                                                src="{{asset('img/signature/twitter-icon.png')}}" alt="Twitter"
                                                style="max-width:20px !important;"></a></td>
                                            @endif
                                            @if($info['company_facebook_link']['value'])
                                            <td><a href="{{ $info['company_facebook_link']['value'] }}" target="_blank"><img
                                                src="{{asset('img/signature/facebook-icon.png')}}"
                                                alt="Facebook" style="max-width:20px !important;"></a></td>
                                            @endif
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="footer">
                    <p>Click the "Copy to Clipboard" button above and paste it in your email client's signature
                        settings.</p>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

    <script>
        // Copy clipboard
    function CopyToClipboard(containerid) {
        if (window.getSelection) {
            if (window.getSelection().empty) { // Chrome
                window.getSelection().empty();
            } else if (window.getSelection().removeAllRanges) { // Firefox
                window.getSelection().removeAllRanges();
            }
        } else if (document.selection) { // IE?
            document.selection.empty();
        }

        if (document.selection) {
            var range = document.body.createTextRange();
            range.moveToElementText(document.getElementById(containerid));
            range.select().createTextRange();
            document.execCommand("copy");
        } else if (window.getSelection) {
            var range = document.createRange();
            range.selectNode(document.getElementById(containerid));
            window.getSelection().addRange(range);
            document.execCommand("copy");
        }
    }

    // Update button text
    const btn = document.getElementById('copybtn');
        btn.addEventListener('click', function handleClick() {
        btn.textContent = 'Copied!';
    });

    //   Update text
    function upname(){
        document.getElementById("putname").innerHTML=document.getElementById("getname").value;
    }
    function uptitle(){
        document.getElementById("puttitle").innerHTML=document.getElementById("gettitle").value;
    }
    function upmobile(){
        document.getElementById("putmobile").innerHTML=document.getElementById("getmobile").value;
        $('#putmobile').attr('href', 'tel:'+$(getmobile).val());

        var l=$("a[href^='tel:']");
        l.attr("href",l.attr("href").replace(/\s+/g,'').replace("-", ""));
    }

    </script>

</body>

</html>