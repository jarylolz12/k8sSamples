<!doctype html>
<html>
<head>
    <title>Payment Receipt</title>
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }

        .container {
            width: 560px;
            height: auto;
            margin: 0 auto;
            background: #FFFFFF;
            box-shadow: 0px 0px 1px rgba(41, 41, 41, 0.16), 0px 4px 12px rgba(41, 41, 41, 0.2);
            border-radius: 4px;
        }

        header {
            padding: 24px;
            background: #F7F7F7;
        }

        header img {
            height: 28px;
        }

        .container-body {
            padding: 24px;
        }

        .receipt-detail {
            padding-bottom: 24px;
        }

        .receipt-detail .row {
            margin-bottom: 16px;
        }

        .receipt-detail .row:last-child{
            margin-bottom: 0;
        }

        .col-4 {
            font-weight: 500;
            font-size: 14px;
            line-height: 20px;
            color: #6D858F;
            width: 23.5%;
            margin-right: 16px;
            display: inline-block;
            text-align: left;
            vertical-align: top;
        }

        .col-8 {
            font-weight: 400;
            font-size: 14px;
            line-height: 20px;
            color: #4A4A4A;
            text-align: left;
            display: inline-block;
            vertical-align: top;
        }

        .col-6 {
            font-weight: 400;
            font-size: 14px;
            line-height: 20px;
            color: #4A4A4A;
            text-align: left;
            display: inline-block;
            vertical-align: top
        }

        thead tr td {
            padding: 8px 16px;
            font-weight: 600;
            font-size: 12px;
            line-height: 18px;
            color: #6D858F;
            text-transform: uppercase;
        }

        thead tr td:last-child {
            text-align: right
        }

        tbody tr th,
        tbody tr td {
            border-bottom: 1px solid #EBF2F5 !important;
            padding: 8px 16px;
            font-weight: 400;
            font-size: 12px;
            line-height: 18px;
        }

        tbody tr th {
            text-align: left;
            color: #0171A1;
        }

        tbody tr td {
            text-align: right;
            color: #4A4A4A;
        }

        tbody tr:last-child td,
        tbody tr:last-child th {
            border-bottom: 1px solid #B4CFE0 !important;
        }

        tfoot tr td,
        tfoot tr td span {
            font-weight: 600;
            font-size: 14px;
            line-height: 20px;
            color: #4A4A4A;
            text-align: right;
            padding: 8px 16px;
        }

        tfoot tr td span {
            padding: 8px 0 8px 16px;
        }

        footer p,
        footer p a {
            font-weight: 400;
            font-size: 10px;
            line-height: 14px;
            color: #6D858F;
            text-decoration: none;
        }

        footer p a {
            margin-right: 34px;
            display: inline;
        }

        footer p a img {
            margin-right: 5px;
            vertical-align: middle;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <img src="https://beta.shifl.com/images/payment-history/logo-blue.png" alt="logo">
        </header>
        <div class="container-body">
            <section class="receipt-detail">
                <div class="row">
                    <div class="col-4">Receipt No:</div>
                    <div class="col-8">#{{($paymentData)? $paymentData->Id : '-'}}</div>
                </div>
                <div class="row">
                    <div class="col-4">Date & Time:</div>
                    <div class="col-8">{{$transactionTime}}, {{$transactionDate}}</div>
                </div>
                <div class="row">
                    <div class="col-4">Amount:</div>
                    <div class="col-8" style="font-weight: 600">${{($paymentData)? number_format($paymentData->TotalAmt, 2) : '0.00'}}</div>
                </div>
                <div class="row">
                    <div class="col-4">Payment Method:</div>
                    <div class="col-8">
                        <div class="col-6" style="margin-right: 8px">
                            @if($cardIcon)
                            <img src="https://beta.shifl.com/images/payment-history/{{$cardIcon}}" alt="card-icon" /></div>
                        @else
                            -
                        @endif
                            <div class="col-6">
                                {{$cardNumber}}
                                <span style="color: #6D858F; display:block">{{$cardHolderName}} {{$cardExp}}</span>
                            </div>
                    </div>
                </div>
            </section>
            <section class="receipt-items" style="margin-bottom: 50px;">
                <table style="width: 100%; border-spacing: 0;">
                    <thead style="background: #F5F9FC;">
                        <tr>
                            <td>INVOICE #</td>
                            <td>Amount</td>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($invoices AS $key => $value)
                        <tr>
                            <th>{{$value->invoice_num}}</th>
                            <td>${{number_format($value->total_amount, 2)}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">Total Payment <span> ${{($paymentData)? number_format($paymentData->TotalAmt, 2) : '0.00'}} </span></td>
                        </tr>
                    </tfoot>
                </table>
            </section>
            <footer>
                <p style="margin:0 0 8px 0">17 S. Post Lane, Airmount, NY 10952</p>
                <p style="margin:0">
                    <a href="tel:+1 8004474435"><img src="https://beta.shifl.com/images/payment-history/call-icon.png" alt="icon">+1 8004474435</a>
                    <a href="mailto:ap@shifl.com"><img src="https://beta.shifl.com/images/payment-history/mail-icon.png" alt="icon">ap@shifl.com</a>
                    <a href="https://www.shifl.com/" target="_blank"><img src="https://beta.shifl.com/images/payment-history/web-icon.png" alt="icon">www.shifl.com</a>
                </p>
            </footer>
        </div>
    </div>
</body>

</html>
