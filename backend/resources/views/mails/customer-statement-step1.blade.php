<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Customer Statement</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">

        <style type="text/css">
            body{
                background: #fff;
                color:#004d77;
                padding: 0px;
                margin: 0px;
                font-family: 'Nunito', sans-serif;
                color: #4A4A4A;
                font-size: 12px;
            }
            *{
                box-sizing:border-box;
                color: #4A4A4A;
            }
            a{
                color: #0171A1;
            }
            .ewrapper{
                width: 1024px;
                margin-left:auto;
                margin-right:auto;
                position: relative;
            }
            table{
                width:100%;
                border:none;
            }
            table td{
                width:auto;
                text-align:left;
                vertical-align:middle;
            }
            @media (max-width: 767px){
                .ewrapper{
                    width: 95%!important;
                }
            }
            button{
                padding: 8px 12px;
                background: #0171A1;
                color: #FFF;
                border: 1px solid #0171A1;
                border-radius: 4px;
                cursor: pointer;
            }
        </style>
    </head>
    <body style="background: #fff;color:#004d77;font-family:Roboto,Arial,sans-serif;padding:0px;margin:0px;">
        <div>
            <div style="background:#0B6085;padding:16px 0px;">
                <div class="ewrapper" style="width:1024px;margin-left:auto;margin-right:auto;">
                    <table style="width:100%;border:none;" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="width:auto;text-align:left;vertical-align:middle!important;">
                                <a href="{{ url('/') }}">
                                    <img src="{{ asset('images/images/shifl-logo-white.png') }}" alt="{{ url('/') }}">
                                </a>
                            </td>
                            <td style="width:auto;text-align:left;vertical-align:middle">
                                
                            </td>
                            <td style="width:auto;text-align:right;vertical-align:middle!important;">
                                <a href="{{ $shifl_linkedin_url }}" style="display:inline-block;margin-right:12px;">
                                    <img src="{{ asset('images/images/linkedin-white.png') }}" alt="{{ $shifl_linkedin_url }}">
                                </a>
                                <a href="{{ $shifl_whatsapp_url }}" style="display:inline-block;">
                                    <img src="{{ asset('images/images/whatsapp-white.png') }}" alt="{{ $shifl_whatsapp_url }}">
                                </a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="ewrapper" style="width:1024px;margin-left:auto;margin-right:auto;padding:32px 0px;margin-bottom:24px;">
                <p style="margin-bottom:32px;">Good day!</p>
                <p style="margin-bottom:16px;">Thank you for being a valued customer of {{ $company_data['name'] }}. We appreciate your business and want to ensure that you are always up-to-date. Please see your latest statement as of {{ $statement_date_readable }}.</p>
                <p style="margin-bottom:16px;">If you have terms with us, this weekly sending of statements serves as our common reference in monitoring the invoices. If you’ve already sent payment, please disregard this notice.</p>
                <div style="margin-top:32px;">
                    <a href="{{ $payment_link }}" style="border:1px solid #0171A1;text-decoration:none;padding:12px 24px;background:#0171A1;color:#FFF!important;border-radius:4px;cursor:pointer;display:inline-block;margin-right:8px;">Make Payment</a>
                    <a href="{{ $download_statement_url }}" style="border:1px solid #0171A1;text-decoration:none;padding:12px 24px;color:#0171A1!important;border-radius:4px;cursor:pointer;display:inline-block;">Download Statement</a>
                </div>
                <div style="height:1px;background:#EBF2F5;width:100%;margin-top:32px;margin-bottom:32px;"></div>
                <p style="margin-bottom:24px;">If the above button doesn’t work, copy the below url and paste it on your browser to access the payment link:</p>
                <a href="{{ $payment_link }}" style="background:#F7F7F7;padding:12px;">
                    {{ $payment_link }}
                </a>
                <p style="margin-bottom:16px;margin-top:24px;">Do not hesitate to contact us if you have any questions or concerns.</p>
            </div>
            <div class="ewrapper" style="width:100%!important;background:#EBF2F5;padding-top:24px;padding-bottom:24px;">
                <div class="ewrapper" style="width:1024px;margin-left:auto;margin-right:auto;">
                    <div style="width:100%; background:#FFF;padding:24px;border-radius:4px;">
                        <div style="margin-bottom:24px">
                            <h1 style="text-align:center;margin-bottom:8px;">Statement of Account</h1>
                            <p style="text-align:center;margin-top:0px;margin-bottom:0px;">Statement Date: {{ $statement_date }}</p>
                        </div>
                        <div style="height:1px;background:#EBF2F5;width:100%;margin-top:32px;margin-bottom:32px;">
                        </div>
                        <div style="margin-bottom: 24px;position:relative;overflow:auto">
                            <table style="width:100%;border:none;" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="width:auto;border-bottom:none!important;vertical-align:top!important;padding-right:15%">
                                        <p style="margin-top:0px!important;margin-bottom:8px!important;"><b>To:</b></p>
                                        <p style="margin-top:0px!important;margin-bottom:8px!important;"><b>{{ isset($customer_data['name']) ? $customer_data['name'] : '' }}</b></p>
                                        <p style="margin-top:0px!important;margin-bottom:8px!important;">{{ isset($customer_data['email']) ? $customer_data['email']  : '' }}</p>
                                        <p style="margin-top:0px!important;margin-bottom:8px!important;">{{ isset($customer_data['address']) ? $customer_data['address'] : '' }}</p>
                                        <p style="margin-top:0px!important;margin-bottom:8px!important;">{{ isset($customer_data['phone']) ? $customer_data['phone'] : '' }}</p>
                                    </td>
                                    <td style="width:auto;border-bottom:none!important;vertical-align:top!important;width:30%">
                                       <table style="width:100%;" border="0" cellpadding="0" cellspacing="0">
                                             <tr>
                                                <td style="width:auto;border-bottom:none!important;vertical-align:top!important;padding-bottom:8px">
                                                    <b>Statement No</b>
                                                </td>
                                                <td style="width:auto;border-bottom:none!important;vertical-align:top!important;padding-bottom:8px">
                                                    {{ $statement_id }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width:auto;border-bottom:none!important;vertical-align:top!important;padding-bottom:8px">
                                                    <b>Open Balance</b>
                                                </td>
                                                <td style="width:auto;border-bottom:none!important;vertical-align:top!important;padding-bottom:8px">
                                                    {{ ( isset($currency) && strtolower($currency) === 'usd' ? '$' : '' ).number_format($opening_balance,2) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width:auto;border-bottom:none!important;vertical-align:top!important;padding-bottom:8px">
                                                    <b>Invoice Amount</b>
                                                </td>
                                                <td style="width:auto;border-bottom:none!important;vertical-align:top!important;padding-bottom:8px">
                                                    {{ ( isset($currency) && strtolower($currency) === 'usd' ? '$' : '' ).number_format($invoice_amount,2) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width:auto;border-bottom:none!important;vertical-align:top!important;padding-bottom:8px">
                                                    <b>Amount Paid</b>
                                                </td>
                                                <td style="width:auto;border-bottom:none!important;vertical-align:top!important;padding-bottom:8px">
                                                    {{ ( isset($currency) && strtolower($currency) === 'usd' ? '$' : '' ).number_format($total_paid_amount,2) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width:auto;border-bottom:none!important;vertical-align:top!important;padding-bottom:8px">
                                                    <b>Total Balance</b>
                                                </td>
                                                <td style="width:auto;border-bottom:none!important;vertical-align:top!important;padding-bottom:8px">
                                                    <b>{{ ( isset($currency) && strtolower($currency) === 'usd' ? '$' : '' ).number_format($opening_balance,2) }}
                                                    {{-- ( isset($currency) && strtolower($currency) === 'usd' ? '$' : '' ).number_format($closing_balance,2) --}}</b>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div style="margin-bottom: 24px;position:relative;overflow:auto">
                            <table style="width:100%;" border="0" cellpadding="0" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th style="text-align:left;width:13%;color:#6D858F;background:#F7F7F7;padding:2px;border:0.5px solid #EBF2F5;">
                                            Invoice No
                                        </th>
                                        <th style="text-align:left;width:13%;color:#6D858F;background:#F7F7F7;padding:2px;border:0.5px solid #EBF2F5;">
                                            Invoice Date
                                        </th>
                                        <th style="text-align:left;width:13%;color:#6D858F;background:#F7F7F7;padding:2px;border:0.5px solid #EBF2F5;">
                                            Due Date
                                        </th>
                                        <th style="text-align:left;width:auto;color:#6D858F;background:#F7F7F7;padding:2px;border:0.5px solid #EBF2F5;">
                                            MBL
                                        </th>
                                        <th style="text-align:left;width:auto;color:#6D858F;background:#F7F7F7;padding:2px;border:0.5px solid #EBF2F5;">
                                            Invoice Amount
                                        </th>
                                        <th style="text-align:left;width:auto;color:#6D858F;background:#F7F7F7;padding:2px;border:0.5px solid #EBF2F5;">
                                            Amount Paid
                                        </th>
                                        <th style="text-align:left;width:auto;color:#6D858F;background:#F7F7F7;padding:2px;border:0.5px solid #EBF2F5;">
                                            Open Balance
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if( isset($invoices ) )
                                    @foreach($invoices as $d )
                                    <tr>
                                        <td style="text-align:left;width:13%;vertical-align:top!important;padding:2px;border:0.5px solid #EBF2F5;">
                                            {{ $d->invoice_num }}
                                        </td>
                                        <td style="text-align:left;width:13%;vertical-align:top!important;padding:2px;border:0.5px solid #EBF2F5;">
                                            {{ date_format(date_create($d->invoice_date),'Y-m-d') }}
                                        </td>
                                        <td style="text-align:left;width:13%;vertical-align:top!important;padding:2px;border:0.5px solid #EBF2F5;">
                                            {{ date_format(date_create($d->due_date),'Y-m-d') }}
                                        </td>
                                        <td style="text-align:left;width:auto;vertical-align:top!important;padding:2px;border:0.5px solid #EBF2F5;">
                                            {{ $d->mbl_num }}
                                        </td>
                                        <td style="text-align:left;width:auto;vertical-align:top!important;padding:2px;border:0.5px solid #EBF2F5;">
                                            {{ ( strtolower($currency) === 'usd' ? '$' : '' ).number_format($d->total_amount,2) }}
                                        </td>
                                        <td style="text-align:left;width:auto;vertical-align:top!important;padding:2px;border:0.5px solid #EBF2F5;">
                                            {{ ( strtolower($currency) === 'usd' ? '$' : '' ).number_format($d->total_paid_amount,2) }}
                                        </td>
                                        <td style="text-align:left;width:auto;vertical-align:top!important;padding:2px;border:0.5px solid #EBF2F5;">
                                            {{ ( strtolower($currency) === 'usd' ? '$' : '' ).number_format($d->opening_balance,2) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td style="text-align:left;width:auto;padding:2px;border:0.5px solid #EBF2F5;" colspan="3"></td>
                                        <td style="text-align:left;width:auto;background:#F7F7F7;padding:5px;border:0.5px solid #EBF2F5;">
                                            <b>Total</b>
                                        </td>
                                        <td style="text-align:left;width:auto;background:#F7F7F7;padding:2px;border:0.5px solid #EBF2F5;">
                                            <b>{{ ( isset($currency) && strtolower($currency) === 'usd' ? '$' : '' ).number_format($invoice_amount,2) }}</b>
                                        </td>
                                        <td style="text-align:left;width:auto;background:#F7F7F7;padding:2px;border:0.5px solid #EBF2F5;">
                                            <b>{{ ( isset($currency) && strtolower($currency) === 'usd' ? '$' : '' ).number_format($total_paid_amount,2) }}</b>
                                        </td>
                                        <td style="text-align:left;width:auto;background:#F7F7F7;padding:2px;border:0.5px solid #EBF2F5;">
                                            <b>{{ ( isset($currency) && strtolower($currency) === 'usd' ? '$' : '' ).number_format($total_balance,2) }}</b>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div style="margin-bottom: 24px;position:relative;overflow:auto">
                            <table style="width:100%;" border="0" cellpadding="0" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th style="text-align:left;width:auto;color:#6D858F;background:#F7F7F7;padding:2px;border:0.5px solid #EBF2F5;">
                                            1 - 30 Days Past Due
                                        </th>
                                        <th style="text-align:left;width:auto;color:#6D858F;background:#F7F7F7;padding:2px;border:0.5px solid #EBF2F5;">
                                            30 - 60 Days Past Due
                                        </th>
                                        <th style="text-align:left;width:auto;color:#6D858F;background:#F7F7F7;padding:2px;border:0.5px solid #EBF2F5;">
                                            60 - 90 Days Past Due
                                        </th>
                                        <th style="text-align:left;width:auto;color:#6D858F;background:#F7F7F7;padding:2px;border:0.5px solid #EBF2F5;">
                                            90+ Days Past Due
                                        </th>
                                        <th style="text-align:left;width:auto;color:#6D858F;background:#F7F7F7;padding:2px;border:0.5px solid #EBF2F5;">
                                            Total Due
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="text-align:left;width:auto;padding:2px;border:0.5px solid #EBF2F5;">
                                            {{ ( isset($currency) && strtolower($currency) === 'usd' ? '$' : '' ).number_format($due1,2) }}
                                        </td>
                                        <td style="text-align:left;width:auto;padding:2px;border:0.5px solid #EBF2F5;">
                                            {{ ( isset($currency) && strtolower($currency) === 'usd' ? '$' : '' ).number_format($due2,2) }}
                                        </td>
                                        <td style="text-align:left;width:auto;padding:2px;border:0.5px solid #EBF2F5;">
                                            {{ ( isset($currency) && strtolower($currency) === 'usd' ? '$' : '' ).number_format($due3,2) }}
                                        </td>
                                        <td style="text-align:left;width:auto;padding:2px;border:0.5px solid #EBF2F5;">
                                            {{ ( isset($currency) && strtolower($currency) === 'usd' ? '$' : '' ).number_format($due4,2) }}
                                        </td>
                                        <td style="text-align:left;width:auto;padding:2px;border:0.5px solid #EBF2F5;">
                                            {{ ( isset($currency) && strtolower($currency) === 'usd' ? '$' : '' ).number_format($total_amount,2) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ewrapper" style="width:100%!important;padding-top:32px;padding-bottom:32px;background:#F7F7F7;">
                <div class="ewrapper" style="width:1024px;margin-left:auto;margin-right:auto;">
                    <h1 style="text-align:left;margin-bottom:24px;color:#0171A1;">Download Shifl App</h1>
                    <div style="margin-bottom:32px;">
                        <a href="{{ $google_app_url }}" style="display:inline-block;margin-right:12px;">
                            <img src="{{ asset('images/images/google-play-badge-small.png') }}" alt="{{ $google_app_url }}" height="43" style="max-width: 100%;max-height:100%;">
                        </a>
                        <a href="{{ $apple_app_url }}" style="display:inline-block;">
                            <img src="{{ asset('images/images/apple-store-badge-small.png') }}" alt="{{ $apple_app_url }}" height="43" style="max-width: 100%;max-height:100%;">
                        </a>
                    </div>
                    <p style="margin-top:0px;margin-bottom:8px;color:#6D858F;">
                        Email Us <b>hello@shifl.com</b>
                    </p>
                    <p style="margin-top:0px;margin-bottom:8px;color:#6D858F;">
                        Phone <b>888-HI-SHIFL (888-447-4435)</b>
                    </p>
                    <div style="margin-top:24px;margin-bottom:32px;">
                        <a href="{{ $shifl_linkedin_url }}" style="display:inline-block;margin-right:12px;">
                            <img src="{{ asset('images/images/linkedin-gray.png') }}" alt="{{ $shifl_linkedin_url }}">
                        </a>
                        <a href="{{ $shifl_whatsapp_url }}" style="display:inline-block;">
                            <img src="{{ asset('images/images/whatsapp-gray.png') }}" alt="{{ $shifl_whatsapp_url }}">
                        </a>
                    </div>
                    <p style="margin-top:0px;margin-bottom:8px;color:#6D858F;">
                       © {{ date('Y') }} Shifl. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </body>

</html>
