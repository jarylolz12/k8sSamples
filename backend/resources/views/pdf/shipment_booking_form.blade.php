<!doctype html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style type="text/css">
        body {
            margin: 0;
            padding: 0;
        }
        .container {
            background: rgba(245, 243, 243, 0.08);
            margin: 20px;
            width: 7.27in;
            align-content: center;
        }
        .formTitle {
            position: relative;
            vertical-align: middle;
            /* top: 50%; */
            font-family: 'Inter';
            font-style: normal;
            font-weight: 700;
            font-size: 32px;
            line-height: 36px;
            color: #4A4A4A;
        }
        .floatLeft {
            float: left;
        }
        .floatRight {

            float: right;
        }
        .wrapper {
            display: table;
            table-layout: fixed;
            width:100%;
        }
        .wrapper .cell {
            display: table-cell;
        }
        .logo {
            text-align: right;
            padding-right: 50px;
        }
        .shipperTitle {
            color: #819FB2;
            font-family: 'Inter';
            font-style: normal;
            font-weight: 600;
            font-size: 10px;
            line-height: 14px;
        }
        .shipperSubTitle {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 600;
            font-size: 10px;
            line-height: 14px;
            text-transform: uppercase;
            color: #4A4A4A;
        }
        .shipperContent {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 400;
            font-size: 10px;
            line-height: 14px;
            color: #4A4A4A;
        }
        .mb10 {
            margin-bottom: 10px;
        }
        .mt10 {
            margin-top: 10px;
        }
        .mt15 {
            margin-top: 15px;
        }
        .mb15 {
            margin-bottom: 15px;
        }
        .mb5 {
            margin-bottom: 5px;
        }
        .mb20 {
            margin-bottom: 20px;
        }
        .orderTitle {
            color: #4A4A4A;
            font-family: 'Inter';
            font-style: normal;
            font-weight: 600;
            font-size: 14px;
            line-height: 20px;
        }
        .POTitle {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 600;
            font-size: 10px;
            line-height: 14px;
            color: #4A4A4A;
        }
        .manualPoTable {
            width: 100%;
            border: 1px solid #D8E7F0;
            /* border-collapse: collapse; */
            border-radius: 5px;
            -moz-border-radius: 5px;
            -webkit-border-radius: 5px;
        }
        .manualPoTable td {
            padding: 5px;
        }
        .tableBordered {
            width: 100%;
            border: 1px solid #D8E7F0;
            border-collapse: collapse;
        }
        .tableBordered td {
            border: 1px solid #D8E7F0;
            border-collapse: collapse;
            text-align: center;
            font-family: 'Inter';
            font-style: normal;
            font-weight: 400;
            font-size: 10px;
            line-height: 14px;
            color: #4A4A4A;
        }

        .first {
            text-align: left !important;
            padding-left: 5px !important;
            font-family: 'Inter';
            font-style: normal;
            font-weight: 400;
            font-size: 10px;
            line-height: 14px;
            color: #4A4A4A;
            padding: 2px;
        }

        .first p {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 400;
            font-size: 10px;
            line-height: 14px;
            color: #6D858F;
            margin-top: -1px;
        }
        .tableBordered th {
            border: 1px solid #D8E7F0;
            border-collapse: collapse;
            background: #F7F7F7;
            font-family: 'Inter';
            font-style: normal;
            font-weight: 600;
            font-size: 10px;
            line-height: 14px;
            text-align: center;
            color: #4A4A4A;
        }
        .total {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 500;
            font-size: 12px;
            line-height: 18px;
            color: #6D858F;
        }
        .totalAmount {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 400;
            font-size: 12px;
            line-height: 18px;
            color: #4A4A4A;
            margin-right: 10px;
        }
        .cargoDetails {
            width: 100%;
            text-align: center;
            border-collapse: separate;
        }
        .cargoDetails td {
            border: 1px solid #d8e7f0;
            border-collapse: collapse;
            font-family: 'Inter';
            font-style: normal;
            font-weight: 500;
            font-size: 12px;
            line-height: 18px;
            text-align: center;
            color: #4A4A4A;
            padding: 10px;
            border-radius: 5px;
            -moz-border-radius: 5px;
            -webkit-border-radius: 5px;
            padding-bottom: 0;
        }
        .cargoDetails:nth-child(2) {
            margin-left: 10px;
            margin-right: 10px;
        }
        .cargoDetails td p {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 400;
            font-size: 12px;
            line-height: 18px;
            text-align: center;
            color: #6D858F;
            margin-top: 1px;
        }
        .commodity {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 600;
            font-size: 10px;
            line-height: 20px;
            color: #6D858F;
            margin-top: 10px;
        }
        .commodityDetails {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 400;
            font-size: 10px;
            line-height: 14px;
            color: #4A4A4A;
        }

    </style>
</head>
<body>
<div class="container">
    <div class="wrapper mb20">
        <div class="cell formTitle">Booking Form</div>
        <div class="cell logo">
            @if(empty($content['company_name']))
                <img src="https://beta.shifl.com/images/forwarder/shifl-logo.png" alt="Shifl Logo" class="shifl-logo-image" border="0" height="28" width="116"/>
            @endif
            @if(!empty($content['company_name']))
                {{ $content['company_name'] }}
            @endif
        </div>
    </div>
    <div class="wrapper">
        <div class="cell">
            <table style="width: 100%">
                <tr>
                    <td class="shipperTitle">
                        <div class="shipperTitle">Shipper</div>
                        <div class="shipperSubTitle">{{ $content['shipper_name'] }}</div>
                        <div class="shipperContent">{{ $content['shipper_address']}}</div>
                        <div>&nbsp;</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="shipperTitle">Consignee</div>
                        <div class="shipperSubTitle">{{ $content['consignee_name'] }}</div>
                        <div class="shipperContent">{{ $content['consignee_address'] }}</div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="cell">
            <table>
                <tr>
                    <td class="shipperTitle">From</td>
                    <td class="shipperContent">{{ $content['location_from_name'] }}</td>
                </tr>
                <tr>
                    <td class="shipperTitle">To</td>
                    <td class="shipperContent">{{ $content['location_to_name'] }}</td>
                </tr>
                <tr>
                    <td class="shipperTitle">Mode</td>
                    <td class="shipperContent">{{ $content['mode'] }}</td>
                </tr>
                <tr>
                    <td class="shipperTitle">Type</td>
                    <td class="shipperContent">{{ $content['type'] }}</td>
                </tr>
                <tr>
                    <td class="shipperTitle">IncoTerm</td>
                    <td class="shipperContent">{{ $content['inco_term'] }}</td>
                </tr>
                <tr>
                    <td class="shipperTitle">Container Details</td>
                    <td class="shipperContent">
                        @foreach($content['containers'] as $container)
                            {{ $container->how_many . ' of '}}
                            {{ trim(\App\ContainerSize::find(intval($container->size))->name) }}
                            @if($loop->index !== (count($content['containers']) -1))
                                {{ ', ' }}
                            @endif
                        @endforeach
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div>
        @if(!empty($content['notify_info']))
            <div class="shipperTitle mt10">Notify</div>
            <div class="shipperContent">{{ $content['notify_info'] }}</div>
        @endif
    </div>
    @if(isset($content['final_purchase_orders']) && count($content['final_purchase_orders']) > 0 || isset($content['manual_orders']) && count($content['manual_orders']) > 0)
        <div class="orderTitle mb15 mt15">
            Orders
        </div>
    @endif
    @if(isset($content['final_purchase_orders']) && count($content['final_purchase_orders']) > 0)
        @foreach($content['final_purchase_orders'] as $key => $purchase_order)
            <div class="mt15 mb15">
                <div class="POTitle">
                    {{ ($content['role'] === 'shipper') ? 'SO' : 'PO'}} {{ $purchase_order['po_number']}}
                </div>
                <div>
                <table class="tableBordered">
                    <thead>
                    <tr>
                        <th class="first">SKU</th>
                        <th>Carton</th>
                        <th>Unit</th>
                        <th>Volume <br>
                            (CBM)</th>
                        <th>Weight <br>
                            (KG)</th>
                        <th>Cargo Ready <br> Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($purchase_order['products']) > 0)
                        @foreach($purchase_order['products'] as $product)
                            <tr>
                                <td class="first">{{ $product['product']['sku'] }} <br>
                                    <p> {{ $product['product']['name'] }} </p>
                                </td>
                                <td>{{ $product['carton'] }}</td>
                                <td>{{ $product['distribution_unit'] }}</td>
                                <td>{{ $product['volume'] }}</td>
                                <td>{{ $product['weight'] }}</td>
                                <td>{{ $purchase_order['cargo_ready_date'] ? \Carbon\Carbon::parse($purchase_order['cargo_ready_date'])->format('d/m/Y') : 'N/A'}}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                <div style="width: 100%;" class="mb15">
                    <div class="floatRight" style="margin-right: 20px;">
                        <span class="total">Total Carton:</span> <span class="totalAmount">{{ $purchase_order['total_cartons'] }}</span>
                        <span class="total">Total Volume:</span> <span class="totalAmount">{{ $purchase_order['total_volumes'] }}</span>
                        <span class="total">Total Weight:</span> <span class="totalAmount">{{ $purchase_order['total_weights'] }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @endif
    <div style="clear: both"></div>

    @if(isset($content['manual_orders']) && count($content['manual_orders']) > 0)
        @foreach($content['manual_orders'] as $manual_order)
            <div class="mt15 mb15">
                <div class="POTitle mt15">
                    {{ ($content['role'] === 'shipper') ? 'SO' : 'PO'}} {{ $manual_order['po_num'] }}
                </div>
                <div>
                    <table class="manualPoTable">
                        <tr>
                            <td> <span class="total">{{ ($content['role'] === 'shipper') ? 'Buyer' : 'Supplier' }}:</span> <span class="totalAmount">{{ ($content['role'] === 'shipper') ? $content['consignee_name'] : $content['shipper_name'] }}</span></td>
                            <td> <span class="total">Cargo Ready Date:</span> <span class="totalAmount">{{ \Carbon\Carbon::parse($manual_order['cargo_ready_date'])->format('F d, Y') }}</span></td>
                        </tr>
                        <tr>
                            <td>
                                <span class="total">Total Carton:</span> <span class="totalAmount">{{ $manual_order['total_cartons'] }}</span>
                            </td>
                            <td>
                                <span class="total">Total Volume:</span> <span class="totalAmount">{{ $manual_order['volume'] }} CBM</span>
                            </td>
                            <td>
                                <span class="total">Total Weight:</span> <span class="totalAmount">{{ $manual_order['kg'] }} KG</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        @endforeach
    @endif
    <div class="orderTitle mb15 mt15">
        Cargo Details
    </div>
    <div>
        <table class="cargoDetails">
            <tr>
                <td>
                    {{ $content['total_cartons'] }}
                    <p>Total Carton</p>
                </td>
                <td>
                    {{ $content['total_volumes'] }} CBM
                    <p>Total Volume</p>
                </td>
                <td>
                    {{ $content['total_weights'] }} KG
                    <p>Total Weight</p>
                </td>
            </tr>
        </table>
    </div>
    <div>
        <div class="commodity">
            Commodity, HS Code, Material, Usage
        </div>
        <div class="commodityDetails">
            {{
                !($content['commodities_info'] == 'undefined' || empty($content['commodities_info'])) ?  $content['commodities_info'] : 'N/A'
            }}
        </div>
    </div>

    <div>
        <table>
            <tr>
                <td>
                    <div class="commodity">
                        Special Instructions
                    </div>
                    <div class="commodityDetails">
                        {{ !(empty($content['special_instructions']) || $content['special_instructions'] === 'undefined') ? $content['special_instructions'] : 'N/A'}}
                    </div>
                </td>
                @if(!(empty($content['marks']) || $content['marks'] === 'undefined'))
                    <td>
                        <div class="commodity">
                            Marks
                        </div>
                        <div class="commodityDetails">
                            {{ !(empty($content['marks']) || $content['marks'] === 'undefined') ? $content['marks'] : 'N/A'}}
                        </div>
                    </td>
                @endif
            </tr>
        </table>
    </div>
</div>
</body>
</html>
