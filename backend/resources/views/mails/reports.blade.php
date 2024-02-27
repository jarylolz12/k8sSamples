<!DOCTYPE html>
<html lang="en" dir="ltr">

    <head>
        <meta charset="utf-8">
        <title></title>
    </head>

    <body style="color: #004d77;">
        <div style="max-width:1140px; margin: 0 auto;">

            <div style="background: #fff ;  border-radius: 8px ;margin:20px 10px; padding: 16px ; line-height: 1.6;">
             <?php
                if(isset($content['report']) && $content['report'] == 1){
                    $greetings = 'Please see shipment report of company '.$content['company'] ?? '';    
                }else{
                    $greetings = "Dear ". $content['name'] .", <br> Please find attached report of your shipments";    
                }
             ?>
              {!! $greetings !!}, there are two tabs for active and completed shipments in the bottom. Feel free to reach out to us with any comments or feature requests. <br>
              <br>
              Thanks <br>
              The shifl team <br>
            </div>
        </div>
    </body>

</html>
