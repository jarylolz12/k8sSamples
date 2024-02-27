<!DOCTYPE html>
<html lang="en" dir="ltr">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $statement_id }} | Customer Statement</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter-Regular:wght@400;700&display=swap" rel="stylesheet">

        <style>
           *{
                font-family: Roboto, Arial, sans-serif;
           } 
        </style>
    </head>
    <body style="background: #fff;color:#004d77;font-family:Roboto,Arial,sans-serif;">
        <div style="max-width:1140px; margin: 32px auto;">
            <div style="margin-bottom: 24px;">
                <table style="width:100%;" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="width:auto;text-align:left;border-bottom:none!important;vertical-align:top!important;">
                            <img src="{{ asset('images/images/shifl-logo.png') }}">
                        </td>
                        <td style="width:auto;text-align:right;border-bottom:none!important;vertical-align:top!important;width:35%">
                            <h2 style="margin-top:0px!important;margin-bottom:12px">{{ isset($company_data['name']) ? $company_data['name'] : '' }}</h2>
                            <p style="margin-top:0px!important;margin-bottom:8px;opacity:.8">{{ isset($company_data['address']) ? $company_data['address'] : '' }}</p>
                            <p style="margin-top:0px!important;margin-bottom:8px;opacity:.8;color:#242424">{{ isset($company_data['phone']) ? $company_data['phone'] : '' }}</p>
                            <p style="margin-top:0px!important;margin-bottom:24px;opacity:.8;color:#242424">{{ isset($company_data['email']) ? $company_data['email'] : '' }}</p>
                        </td>
                    </tr>
                </table>
            </div>
            <div style="background:#F5F5F5;padding:12px;margin-bottom: 24px;">
                <div style="margin-bottom:32px;">
                    <h2 style="margin-top:0px!important;margin-bottom:12px;text-align:center">Statement of Account</h2>
                    <p style="margin-top:0px!important;margin-bottom:4px;margin-top: 0px;opacity:.8;color:#242424;text-align:center;font-weight:bold">
                        {{ $statement_date }}
                    </p>
                </div>
                <div>
                    <table style="width:100%;" border="0" cellpadding="0">
                        <tr>
                            <td style="width:auto;border-bottom:none!important;vertical-align:top!important;padding-right:15%">
                                <p style="margin-top:0px!important;margin-bottom:8px;margin-top: 0px;opacity:.8"><b>To:</b></p>
                                <p style="margin-top:0px!important;margin-bottom:8px;margin-top: 0px;opacity:.8;color:#242424">{{ isset($customer_data['name']) ? $customer_data['name'] : '' }}</p>
                                <p style="margin-top:0px!important;margin-bottom:8px;margin-top: 0px;opacity:.8;color:#242424">{{ isset($customer_data['email']) ? $customer_data['email']  : '' }}</p>
                                <p style="margin-top:0px!important;margin-bottom:8px;margin-top: 0px;opacity:.8;color:#242424">{{ isset($customer_data['address']) ? $customer_data['address'] : '' }}</p>
                                <p style="margin-top:0px!important;margin-bottom:8px;margin-top: 0px;opacity:.8;color:#242424">{{ isset($customer_data['phone']) ? $customer_data['phone'] : '' }}</p>
                            </td>
                            <td style="width:auto;border-bottom:none!important;vertical-align:top!important;width:30%">
                               <table style="width:100%;" border="0" cellpadding="0">
                                     <tr>
                                        <td style="width:auto;border-bottom:none!important;vertical-align:top!important;padding-bottom:8px">
                                            Statement No
                                        </td>
                                        <td style="width:auto;vertical-align:top!important;text-align:right;padding-bottom:8px">
                                            {{ $statement_id }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:auto;border-bottom:none!important;vertical-align:top!important;padding-bottom:8px">
                                            Open Balance
                                        </td>
                                        <td style="width:auto;vertical-align:top!important;text-align:right;padding-bottom:8px">
                                            {{ ( isset($currency) && strtolower($currency) === 'usd' ? '$' : '' ).number_format($opening_balance,2) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:auto;border-bottom:none!important;vertical-align:top!important;padding-bottom:8px">
                                            Invoice Amount
                                        </td>
                                        <td style="width:auto;vertical-align:top!important;text-align:right;padding-bottom:8px">
                                            {{ ( isset($currency) && strtolower($currency) === 'usd' ? '$' : '' ).number_format($invoice_amount,2) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:auto;border-bottom:none!important;vertical-align:top!important;padding-bottom:8px">
                                            Amount Paid
                                        </td>
                                        <td style="width:auto;vertical-align:top!important;text-align:right;padding-bottom:8px">
                                            {{ ( isset($currency) && strtolower($currency) === 'usd' ? '$' : '' ).number_format($total_paid_amount,2) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:auto;border-bottom:none!important;vertical-align:top!important;padding-bottom:8px">
                                            <b>Total Balance</b>
                                        </td>
                                        <td style="width:auto;vertical-align:top!important;text-align:right;padding-bottom:8px">
                                            <b>{{ ( isset($currency) && strtolower($currency) === 'usd' ? '$' : '' ).number_format($opening_balance,2) }}
                                            {{-- ( isset($currency) && strtolower($currency) === 'usd' ? '$' : '' ).number_format($closing_balance,2) --}}</b>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div style="margin-bottom: 24px;">
                <table style="width:100%;" border="0" cellpadding="0" class="bordered">
                    <thead>
                        <tr>
                            <th style="text-align:left;width:auto;border-bottom:2px dashed #F5F5F5!important;font-weight:bold;vertical-align:middle!important;">
                                Invoice No
                            </th>
                            <th style="text-align:left;width:auto;border-bottom:2px dashed #F5F5F5!important;font-weight:bold;vertical-align:middle!important;">
                                Invoice Date
                            </th>
                            <th style="text-align:left;width:auto;border-bottom:2px dashed #F5F5F5!important;font-weight:bold;vertical-align:middle!important;">
                                Due Date
                            </th>
                            <th style="text-align:left;width:auto;border-bottom:2px dashed #F5F5F5!important;font-weight:bold;vertical-align:middle!important;">
                                MBL
                            </th>
                            <th style="text-align:left;width:auto;border-bottom:2px dashed #F5F5F5!important;font-weight:bold;vertical-align:middle!important;">
                                Invoice Amount
                            </th>
                            <th style="text-align:left;width:auto;border-bottom:2px dashed #F5F5F5!important;font-weight:bold;vertical-align:middle!important;">
                                Amount Paid
                            </th>
                            <th style="text-align:left;width:auto;border-bottom:2px dashed #F5F5F5!important;font-weight:bold;vertical-align:middle!important;">
                                Open Balance
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if( isset($invoices ) )
                        @foreach($invoices as $d )
                        <tr>
                            <td style="text-align:left;width:auto;border-bottom:1px dashed #F5F5F5!important;vertical-align:top!important;">
                                {{ $d->invoice_num }}
                            </td>
                            <td style="text-align:left;width:auto;border-bottom:1px dashed #F5F5F5!important;vertical-align:top!important;">
                                {{ date_format(date_create($d->invoice_date),'Y-m-d') }}
                            </td>
                            <td style="text-align:left;width:auto;border-bottom:1px dashed #F5F5F5!important;vertical-align:top!important;">
                                {{ date_format(date_create($d->due_date),'Y-m-d') }}
                            </td>
                            <td style="text-align:left;width:auto;border-bottom:1px dashed #F5F5F5!important;vertical-align:top!important;">
                                {{ $d->mbl_num }}
                            </td>
                            <td style="text-align:left;width:auto;border-bottom:1px dashed #F5F5F5!important;vertical-align:top!important;">
                                {{ ( strtolower($currency) === 'usd' ? '$' : '' ).number_format($d->total_amount,2) }}
                            </td>
                            <td style="text-align:left;width:auto;border-bottom:1px dashed #F5F5F5!important;vertical-align:top!important;">
                                {{ ( strtolower($currency) === 'usd' ? '$' : '' ).number_format($d->total_paid_amount,2) }}
                            </td>
                            <td style="text-align:left;width:auto;border-bottom:1px dashed #F5F5F5!important;vertical-align:top!important;">
                                {{ ( strtolower($currency) === 'usd' ? '$' : '' ).number_format($d->opening_balance,2) }}
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <td style="text-align:left;width:auto;border-bottom:1px dashed #F5F5F5!important;vertical-align:top!important;">
                               <b>Total</b>
                            </td>
                            <td style="text-align:left;width:auto;border-bottom:1px dashed #F5F5F5!important;vertical-align:top!important;"></td>
                            <td style="text-align:left;width:auto;border-bottom:1px dashed #F5F5F5!important;vertical-align:top!important;"></td>
                            <td style="text-align:left;width:auto;border-bottom:1px dashed #F5F5F5!important;vertical-align:top!important;"></td>
                            <td style="text-align:left;width:auto;border-bottom:1px dashed #F5F5F5!important;vertical-align:top!important;"><b>{{ ( isset($currency) && strtolower($currency) === 'usd' ? '$' : '' ).number_format($invoice_amount,2) }}</b></td>
                            <td style="text-align:left;width:auto;border-bottom:1px dashed #F5F5F5!important;vertical-align:top!important;"><b>{{ ( isset($currency) && strtolower($currency) === 'usd' ? '$' : '' ).number_format($total_paid_amount,2) }}</b></td>
                            <td style="text-align:left;width:auto;border-bottom:1px dashed #F5F5F5!important;vertical-align:top!important;"><b>{{ ( isset($currency) && strtolower($currency) === 'usd' ? '$' : '' ).number_format($total_balance,2) }}</b></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div style="margin-bottom: 24px;">
                <table style="width:100%;" border="0" cellpadding="0" class="bordered">
                    <thead>
                        <tr>
                            <th style="text-align:left;width:auto;border-bottom:2px dashed #F5F5F5!important;font-weight:bold;vertical-align:middle!important;">
                                1 - 30 Days Past Due
                            </th>
                            <th style="text-align:left;width:auto;border-bottom:2px dashed #F5F5F5!important;font-weight:bold;vertical-align:middle!important;">
                                30 - 60 Days Past Due
                            </th>
                            <th style="text-align:left;width:auto;border-bottom:2px dashed #F5F5F5!important;font-weight:bold;vertical-align:middle!important;">
                                60 - 90 Days Past Due
                            </th>
                            <th style="text-align:left;width:auto;border-bottom:2px dashed #F5F5F5!important;font-weight:bold;vertical-align:middle!important;">
                                90+ Days Past Due
                            </th>
                            <th style="text-align:left;width:auto;border-bottom:2px dashed #F5F5F5!important;font-weight:bold;vertical-align:middle!important;">
                                Total Due
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="text-align:left;width:auto;border-bottom:1px dashed #F5F5F5!important;vertical-align:top!important;">{{ ( isset($currency) && strtolower($currency) === 'usd' ? '$' : '' ).number_format($due1,2) }}</td>
                            <td style="text-align:left;width:auto;border-bottom:1px dashed #F5F5F5!important;vertical-align:top!important;">{{ ( isset($currency) && strtolower($currency) === 'usd' ? '$' : '' ).number_format($due2,2) }}</td>
                            <td style="text-align:left;width:auto;border-bottom:1px dashed #F5F5F5!important;vertical-align:top!important;">{{ ( isset($currency) && strtolower($currency) === 'usd' ? '$' : '' ).number_format($due3,2) }}</td>
                            <td style="text-align:left;width:auto;border-bottom:1px dashed #F5F5F5!important;vertical-align:top!important;">{{ ( isset($currency) && strtolower($currency) === 'usd' ? '$' : '' ).number_format($due4,2) }}</td>
                            <td style="text-align:left;width:auto;border-bottom:1px dashed #F5F5F5!important;vertical-align:top!important;">{{ ( isset($currency) && strtolower($currency) === 'usd' ? '$' : '' ).number_format($total_amount,2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </body>

</html>
