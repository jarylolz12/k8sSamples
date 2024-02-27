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
            <div class="ewrapper" style="width:1024px;margin-left:auto;margin-right:auto;padding-top:44px;padding-bottom:44px;">
                <table style="width:100%;border:none;" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="width:auto;border-bottom:none!important;vertical-align:top!important;padding-right:15%">
                            <h1 style="text-align:left;margin-bottom:8px;">Statement of Account</h1>
                            <p style="text-align:left;margin-top:0px;margin-bottom:0px;">Statement Date: {{ $statement_date }}</p>
                        </td>
                        <td style="width:auto;border-bottom:none!important;vertical-align:top!important;width:30%">
                           <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJYAAAAlCAYAAACgXxA5AAAABmJLR0QA/wD/AP+gvaeTAAAId0lEQVR42u2cCWwUVRiA38623sFbFBWNRuXw4NB4IahIFATFRFSCoCRSa7HdmVlaNBKpEYiaQAS0pnR3Z960BdNIxZCgUrReFDDcKNLulqsidyBWRBQY/zc7hT3e25nZ2dldurzkT4/dnffvvG/++a83CDk0XAJuA1GjRMRfoWwfpdLVSMQi6LsIZCXIKpAGl6B8xgk4gHhcjMql87T3CvgZeG11rCBB7u24nuXlHOgzD/FSH5RLw8XjXXFg8fLSrFZaxK+Cnu1xekfLCVRQeUHE++PegzzKvY7r6q3pFZ5PXkMgyx2wBPz7GQWWgEsNgOqQHTEgZgYsAb90aj4ev5JLYLVlDKyOW5VpSyXdB/odNwlWQzaAxYnyRxFzHkAT8eW5AtaOtIMlKC9EzLsZrurHTOr6dWKY5D3wc4UGFfhf2QCW7vudmpMTlNm5Atb2tILFyw8S/ydmziOoVL454eeKA1dSPqeL8iXxZQx8svSDVVCZD/McjZn3OPLgO3MBrG3pBAsipI+piwy+k4GVG8SwVOtQeWOeCWc//WB55f5UnXn8I0Kqq5ODpbSmFSxeqaAuMi+XJbZ0eCgdSHmKySgy/WAJSiHrtg16P9fZ0w2htN4Kw5bnZMycR2Hxb00KLFGZkK1gcYLsS+ATtqFJyoWd+VYYTLvzLiovwzz79Pm2w9/DjH2zMw8smGNDomADHPl3OzNYzRlLNwi+y8w7/WcYWJCchTn+M0iJ/INKpFuyjwpe6Ul8E72ssRlkP8he/fdvOR7PhBM/ChXXdEkA1m+GYE2uvBjmGgtX2Cw45qfggNcRJxz8hAJU5u9mSWdyLHBq40SoOz/qfSRyinode+hXPZ5OPV7kd84EWOHo1zjfxuMvsgeoEuV2svgUX4V5ZYDTjBlgbWaC5VG6c6JSQwmZo8JnjpclWPhrzVmp05noaMuDH4nRq93kdzN2jjMBlqjwpnU14wo4PkT8IgHF6onmRFxFB0v+lQpWOIn5l/k55D3Iqww4C9aptEqtBX2DqHjOuZmDKhxR/ZvciaZnt+G1X2gWzoI1jJQ/DZN/OQIWHL/Fkr5G6RaHHe0mRub5MFwhHxIrowEEji78LCLtI/D6H8TvQqPq3Iw81kY7C0iRLSTjnNNggR+ZxIXZbtlfTcnwVN/GVChROYMscoJeIKOQmNwONR+K9DeFwfWQnif4/zH2oiqFdsFCojxOCw50gYtkLiNkn6UFKLHCV1+TMbDg4k7mYoDvWJ2BCFB+kqHQMptWcH0Cv+kH5lUETXLU5GpYltsGK+5z0hP0Wwh+NuvSDYLyRpKW9iTyyAMd5Ujadkmer2Ug5w9O5HzBIu0KZihzEHnmd7UB1lrGcZsTpSk60h0My3UCFdVdlKtgwbEX2riNr2W5LZYGqZ9WtfR0B0LP5/laZ7j9wcVuf2g7iNohnD+0CZGIK2F5wGhhmCdBXkNfsOqRJmt/mPp5r9Qjh8Haac8/TOBKsKyQv3UAQFSQ5w/NBmh+AjkSCRFF2uBzN2q90yYijWVg2QZbPAmrKcc5ZNgxYAQKj+/PSbDg7pGCAOgg8s6/gmaFzpkX7O32BUfl+YPluhVqNQCIJvuINYs4QdUPwKR/m1BsA8mUmzGpLlH5mfL5JgvZ/0fpi8bwFTo7WCIenoromvNIEje6XOQqmwUAoVK3QkeTgChWDucHgv1ojuHd1DIMK/QX8NMGFmuVrTIDLz1MB4VhOTs5WNDR8E5q0jaKigaPV7myBWoKYOqQI3nS1ocSnGSoq0Efkl4bNBHGKpVxtbjTYK10BCyP8nguggXHXZKqnCBX9ImKBo1R3b6WVEB1DGSouW9B+nl4ZSK13kdr36VsP9J7xB2wWHh4joK1N6UJ55EiWK1au1AdB38smcZCaHMNn/jlBsXOCaay+akACzaJ5gRY/i3dwJkewflaJ7srNi1McRVDdZX4VdeQ8Xas1kl3IJiCbWfhJjpWPbGJAtZyZ8BitN5mE1iwncz096wJdsmvCvWHhRoXEdK3R+WFZq1SUw4WkbHTVa40OasFuSpvCrP0eCozjI0FCxr7HQGLl8dkPVhwTFMOObFGZhZxxjJnwOJl1TWixLLV4gKtb6f2Ru+tHsJsbYlLN+DvHbJY47MeLJKSMTF0S3XIcCGnLHIGLCKFc8FqVZuGChKmM63UoArNlG9ILzXDgW+k3Aq/swWWR7qH3v8lv589YGnuAb2AbXLk+4J9YcEOMBfTF1Jdk2qdA4vI6KnmrFYgJCPV7Pay4prr9FYM0iu1BBbodS27DRs5tUQoqc1Bjou0z7C2oUNuqYTSNtNoCywoUjNabndRe9ozA9Ywpo5G9VCzcFVsdBYqIsVVECHONwKr3nTVJJE5t9SlSMllwf+/sQUWQE18N2YFIBvAmqRcxdxFDZUHK0+ByZda+tDg4maucB4sIhNmqe55zQxLFVyK5gStdaLCQRcn7/wR6xG4iXFce2ChuAdfRFfqswEszZeUP2dWJywOHa79UWBNa0gPWDzcwqfU08BqQsoG6/sU2d2jBtlb2FWjPaSMfdwG2ztHwrfD3fE+jLwgW8AiG0PI014oOvqSiY/y/VvvioSLe6s+PWARnUt8UVYLeqvWo4odl9qI9mpv0DPt9dTHD+m7ZkgrjOY8k+1hRsCGd/vY35IkSHfEtjkDLOVZAxYZsIcvtjbKjF7NDNLv5A/tJovr8lanDSwNrmlL9egv1IyqtnZFKR3EOSaPO4SQn+xjI+3Llnd7QMsxqSVGCjj5ryXXXAa+CgFElN+EIOI96OmKr6KL8lPh50VECwk8El9UNb1i9QzrivtaP28wF2zP0vYJiIHrba0BwMXNXXcgnVBpYEEEChn/naiytTs6Ozrp+GBFP7AgZekWNKOxh13V/wc+tdw5YOrPsQAAAABJRU5ErkJggg==">
                           <p style="text-align:left;margin-top:0px;margin-bottom:8px;">
                               {{ $company_data['address']}}
                           </p>
                           <p style="text-align:left;margin-top:0px;margin-bottom:0px;">
                               Email <b>{{ $company_data['email']}}</b>
                           </p>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="ewrapper" style="width:1024px;margin-left:auto;margin-right:auto;padding-bottom:24px;">
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
    </body>

</html>
