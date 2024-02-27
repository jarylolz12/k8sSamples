<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title></title>
</head>

<body style="background: #eaebf0 ;color: #004d77 ; font-weight:500;">
<div style="max-width:1140px; margin: 0 auto; ">

    <div style="background: #fff ;  border-radius: 8px ;margin:20px 10px; padding: 16px">
        Information<br/><br/>
        #{{ $content['ref_no'] }} has been converted from <b style="color:red;">{{ $content['from'] }}</b> to <b style="color:red;">{{ $content['to'] }}</b> by <b>{{ $content['user'] }}</b> on <b style="color:red;">{{ date('M d, Y') }}</b>
    </div>
</div>
</body>

</html>
