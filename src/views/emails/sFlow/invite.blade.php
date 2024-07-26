<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation - Setaro</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .largefont{
            font-size: 18px;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
        }
        .header {
            background-color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .logo {
            max-width: 200px;
        }
        .blue-banner {
            background-color: #6d92c4;
            padding: 8px 0;
            text-align: center;
        }
        .envelope-icon {
            background-color: #ffffff;
            border-radius: 50%;
            display: inline-block;
            padding: 15px;
            height: 45px;
        }
        .content {
            padding: 30px;
            color: #000000;
        }
        .message-box {
            background-color: #e6f3ff;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        .button-container {
            text-align: center;
            margin-top: 30px;
        }
        .button {
            display: inline-block;
            padding: 5px 40px;
            margin: 0 10px;
            text-decoration: none;
            color: #ffffff;
            border-radius: 5px;
            font-size: larger;
            font-weight: 500;
        }
        .accept {
            background-color: #4CAF50;
        }
        .reject {
            background-color: #bc1823;
        }
        .footer {
            background-color: #bacadf;
            color: #666666;
            text-align: center;
            padding: 20px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('/vendor/orgmgmt/img/cropped-logo_sbs_headline_small.png') }}" alt="Setaro Logo" class="logo">
        </div>
        <div class="blue-banner">
            <div class="envelope-icon">
                <img src="{{ asset('/vendor/orgmgmt/img/t.png') }}" alt="Envelope Icon" width="45" height="auto" style="margin-top: 7.5px;">
            </div>
        </div>
        <div class="content">
            <p class="largefont">{{ str_replace('<<Firstname>>', $data['toName'], getTranslation('orgmgmt.mails.sflow_invite_salutation'))}}</p>
            <p class="largefont">{{$data['sflowInviteMsg']}}</p>
            @if($data['invite_message'])
                <div class="message-box">
                    <p>{{ $data['invite_message'] }}</p>
                </div>
            @endif
            <p class="largefont">{{ getTranslation('orgmgmt.mails.sflow_invite_text1')}}</p>
            <p class="largefont">{{ getTranslation('orgmgmt.mails.sflow_invite_text2')}}</p>
            <div class="button-container">
                <a href="{{ $data['urlApprove'] }}" class="button accept">{{__('orgmgmt')['mails']['btn']['accept']}}</a>
                <a href="{{ $data['urlReject'] }}" class="button reject">{{__('orgmgmt')['mails']['btn']['reject']}}</a>
            </div>
            <p style="text-align: center; margin-top: 30px;" class="largefont">{{ getTranslation('orgmgmt.mails.thank_you')}},<br>{{ getTranslation('orgmgmt.mails.the')}} <span style="color: #083776;">{{getTranslation('orgmgmt.mails.sflow_team')}}</span></p>
        </div>
        <div class="footer">
            {{__('orgmgmt')['orgjoin']['copyright'] }} © 2024 <a href="{{ route('login')}}" style="color: #666666;">{{ getTranslation('orgmgmt.mails.click_to_start_sflow_session')}}</a>
        </div>
    </div>
</body>
</html>