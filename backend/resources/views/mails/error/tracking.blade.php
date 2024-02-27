<!DOCTYPE html>
<html lang="en" dir="ltr">

    <head>
        <meta charset="utf-8">
        <title></title>
    </head>

    <body style="background: #eaebf0 ;color: #004d77 ; font-weight:500;">
        <div style="max-width:1140px; margin: 0 auto; ">

            <div style="background: #fff ;  border-radius: 8px ;margin:20px 10px; padding: 16px">
                <div style="padding: 5px;font-weight: 450; font-size: 1.10rem">
                    Tracking Request Error Occurred While trying to Track the Shipment.
                    <br>
                    Here are the Error Details :
                </div>

                @foreach ($errors as $error)
                <div style="background: #FFCCC9;  border-radius: 8px ;margin:20px 10px; color: #000;font-weight: 450; font-size: 1.05rem ; padding: 16px">
                    Title &nbsp;&nbsp;&nbsp;&nbsp;:&nbsp; {{ $error['title'] ?? ''}}.
                    <br>
                    Code &nbsp;&nbsp;&nbsp;:&nbsp; {{ $error['code'] ?? '' }}.
                    <br>
                    Detail &nbsp;&nbsp;:&nbsp; {{ $error['detail'] ?? '' }}.

                    @if (isset($error['retry_count']) && $error['retry_count'])
                    <br>
                    Retried :&nbsp; {{ $error['retry_count'] ?? '' }} times.

                    @endif
                </div>
                @endforeach



            </div>



        </div>
    </body>

</html>