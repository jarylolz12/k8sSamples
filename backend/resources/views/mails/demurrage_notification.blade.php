<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

        <title>Demurrage Notification</title>
    </head>

    <body style="background: #eaebf0 ;color: #004d77 ;">
        <div style="max-width:1140px; margin: 0 auto;">
            <div>

                <div style="background: #fff ;  border-radius: 8px ;margin:20px 10px; padding: 16px">
                    Demurrage Alert!<br><br>
                    Please note, <b>{{ substr(strtolower($content['customer']), -1) == 's' ? ($content['customer']."'"):($content['customer']."'s") }}</b> Shipment <b>{{$content['shifl_ref']}}</b> is in <b style="color: #FF0000">Demurrage.</b> <br><br>

                    Respond to this email with the following Information:
                    <ul>
                        <li>Amount of Demurrage</li>
                        <li>The reason for Demurrage</li>
                        <li>When you pressed Paid</li>
                        <li>When you approved Customs</li>
                    </ul>
                    We will need to deal with this <i>urgently</i> - but it is not simply a matter of paying right away - rather, acting immediately upon the situation with actions that will depend on the reason why the demurrage happened in the first place.
                    <ul>
                        <li>For a Late Telex - If the reason for the late release is due to Telex - reach out to Shia and he will guide you.</li>
                        <li>For No available appointments/Closed location - we will probably get the demurrage waived by the port - if you pay, though- we will not be able to get a refund - sometimes we have to clear though. That is why it is important to ask someone who has experience in the matter.</li>
                    </ul>

                    Please ensure that this shipment is taken care of ASAP.
                </div>
            </div>
        </div>
    </body>

</html>
