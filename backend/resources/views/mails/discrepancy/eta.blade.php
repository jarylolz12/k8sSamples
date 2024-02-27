<!DOCTYPE html>
<html lang="en" dir="ltr">

    <head>
        <meta charset="utf-8">
        <title></title>
    </head>

    <body style="background: #eaebf0 ;color: #004d77 ; font-weight:500;">
        <div style="max-width:1140px; margin: 0 auto; ">

            <div style="background: #fff ;  border-radius: 8px ;margin:20px 10px; padding: 16px">
                Discrepancy! <br> <br>

                Please note that the carrier's ETA is off from the ETA in our system by <b style="red"> {{ $content['diff_days'] }} </b> days. <br>
                The carrier ETA is <b style="color:red;"> {{ $content['t49_eta'] }} </b> and the system eta is <b style="color:red;"> {{ $content['system_eta'] }} </b>. <br><br>

                Please ensure that this shipment is taken care of. Add your manager if you need assistance. <br>
                Don't change the shipment eta - It won’t help - as it could change back. <br>
                Write this Shipment down on a list of special cases to keep track of it.
            </div>
        </div>
    </body>

</html>