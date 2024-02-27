<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

        <title>Forgot Password</title>
    </head>

    <body style="background: #eaebf0 ;color: #004d77; padding: 10px 0px 15px 0px;">
        {{-- <nav style="background-color: #004d77; align-items: center; padding: 20px 16px; display:flex;justify-content: space-between; color: #fff;">
            <!-- <div>
                <img src="{!! asset('logo_master.png') !!}" alt="" width="125px">
            </div> -->

        </nav> --}}

        <div style="max-width:1140px; margin: 0 auto;">
            <div>
                <div style="background: #fff ;  border-radius: 8px ;margin:30px 10px; padding: 16px">
                    <div style="padding: 0 8px; margin-bottom: 5px;margin: 5px 0; font-weight: 500">
                        Hello {{ $content['name'] }},
                    </div>
                    <div style="border-top:1.35px solid #004d77; margin-bottom: 1rem"></div>
                    <div style="padding: 0 8px; margin: 5px 0; font-weight: 500;">
                        Please click <a style="text-decoration:  none;font-weight: 700; color: #004d77;" href="{{ $content['link'] }}">here</a> to change your password.
                        If you did not make this request ignore this message.
                        <div style="margin: 20px 0px 0px 0px;">
                            Thanks,
                        </div>
                        <div>
                            Shifl
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <a href="http://shifl.com" target="_blank" style="text-decoration:none;">
            <div style="background: transparent; text-align:center; color:#004d77;">
                © {{ \Carbon\Carbon::now()->format('Y') }} Shifl. All rights reserved.
            </div>
        </a>
    </body>

</html>