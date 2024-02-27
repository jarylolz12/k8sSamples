<!DOCTYPE html>
<html lang="en" dir="ltr">

    <head>
        <meta charset="utf-8">
        <title></title>
    </head>

    <body style="background: #eaebf0 ;color: #004d77 ; font-weight:500;">
        <div style="max-width:1140px; margin: 0 auto; ">

            <div style="background: #fff ;  border-radius: 8px ;margin:20px 10px; padding: 16px">
                Discrepancy!<br><br>

                Please note,<br>
                @foreach ($content['containers'] as $key => $container)
                The Status Tab shows that container, <b> {{ $container['number']}} </b> has been discharged on <b style="color: red;">{{ $container['discharged_at'] }}</b>. <br>
                @endforeach
                But the Estimated day of arrival we are pulling is showing <b style="color: red"> {{ $content['system_eta'] }}</b>, which is later. <br><br>

                Please ensure that this shipment is taken care of. Add your manager if you need assistance.<br>
                Write this Shipment down on a list of special cases to keep track of it.
            </div>
        </div>
    </body>

</html>