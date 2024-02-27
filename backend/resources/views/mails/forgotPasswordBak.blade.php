<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

        <title>Forgot Password</title>
    </head>

    <body style="background: #eaebf0 ;color: #004d77 ;">
        <nav style="background-color: #004d77; align-items: center; padding: 20px 16px; display:flex;justify-content: space-between; color: #fff;">
            <!-- <div>
                <img src="{!! asset('logo_master.png') !!}" alt="" width="125px">
            </div> -->
        </nav>
        <div style="max-width:1140px; margin: 0 auto;">
        <?php if(isset($content)) : ?>
        <p style="padding-left: 1rem;margin-top: 1rem;">
            Hello {{ $content['name'] }},
        </p>
        <p style="padding-left: 1rem;">
            Please click this <a style="text-decoration: none;" href="{{ $content['link'] }}">link</a> to change your password. If you did not made this request. Just ignore this message.
        </p>
        <p style="text-align: right; padding-right: 1rem;">
            Regards, Shifl
        </p>
        <?php endif; ?>
        </div>
        <a href="http://shifl.com" target="_blank" style="text-decoration:none;">
            <div style="background:#fff; text-align:center; padding: 1rem; color:#004d77">
                © {{ \Carbon\Carbon::now()->format('Y') }} Shifl. All rights reserved.
            </div>
        </a>
    </body>

</html>
