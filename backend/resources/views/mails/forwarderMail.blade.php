<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 ">
    <meta name="format-detection" content="telephone=no">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style type="text/css">
        body {
            margin: 0 !important;
            padding: 0 !important;
            -webkit-text-size-adjust: 100% !important;
            -ms-text-size-adjust: 100% !important;
            -webkit-font-smoothing: antialiased !important;
        }

        .reference-box {
        tr {
        td {
            padding-left: 24px;
            padding-top: 24px;
        }
        }
        }
    </style>
</head>
<body style="margin:0;padding:0;">
<div style="background:#F7F7F7; padding-bottom: 100px; width: 100%">
    <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#F7F7F7;">
        <tr>
            <td align="center" style="padding:0;">
                <table role="presentation" style="width:600px;border-collapse:collapse;border-spacing:0;text-align:left;">
                    <tr>
                        <td style="padding:0;">
                            <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                                <tr>
                                    @if(empty($content['company_name']))
                                        <td style="padding:24px 0px 0px 0px;width:19.33%;" align="left">
                                            <img src="https://beta.shifl.com/images/forwarder/shifl-logo.png" alt="Shifl Logo" class="shifl-logo-image" border="0" height="28" width="116"/>
                                        </td>
                                        <td style="padding:24px 0px 0px 0px;width:80.67%;" align="right">
                                            <a style="text-decoration:none;" href="https://app.shifl.com/shipment/<?= $content['shipment_id']?>">
                                                <div style="display: inline-block;color: #0171A1; font-family: 'Inter', sans-serif;font-weight: 500; font-size: 12px;">
                                                    View on Dashboard
                                                </div>
                                                <div style="display: inline-block;">
                                                    <img src="https://beta.shifl.com/images/forwarder/right-caret.png" border="0" height="7" width="7"/>
                                                </div>
                                            </a>
                                        </td>
                                    @endif
                                    @if(!empty($content['company_name']))
                                        <td style="padding:24px 0px 0px 0px;font-family: 'Inter';font-style: normal;font-weight: 600;font-size: 16px;line-height: 24px;color: #4A4A4A;" align="left">
                                            {{ $content['company_name'] }}
                                        </td>
                                    @endif
                                </tr>
                                <tr>
                                    <td colspan=2 style="padding:0;">
                                        <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                                            <tr>
                                                <td align="left">
                                                    <div style="width: 100%; background-color: #819FB2; height: 1px; margin-top: 24px;"></div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan=2 style="padding:0;">
                            <div style="width: 100%;font-family: 'Inter', sans-serif;font-weight: 600; font-size: 20px;color: #0171A1;line-height: 30px; margin-top: 40px;">
                                {{ $content['email_subject'] }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan=2 style="padding:0;">
                            <div style="width: 100%;font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px;color: #4A4A4A; margin-top: 4px;">
                                {{ $content['email_description'] }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan=2 style="padding: 0;">
                            <table class="reference-box" role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0; margin-top: 40px; margin-bottom: 16px;" bgcolor="#FFFFFF">
                                <tr>
                                    <td colspan=2 align="left" style="padding-left: 24px; padding-top: 24px;">
                                        <div style="width: 100%;font-family: 'Inter', sans-serif;font-weight: 600; font-size: 16px;color: #4A4A4A;">
                                            Reference {{ $content['shipment']['shifl_ref'] }}
                                            @if (!empty($content['customer_reference_number']))
                                                <span style="color: #6D858F;" > | Custom Ref #{{ $content['customer_reference_number'] }}</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" style="width:50%; padding: 24px;">
                                        <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0; margin-top:0px; padding: 0;">
                                            <tr style="padding-bottom: 12px;">
                                                <td style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #6D858F;" align="left">
                                                    <div style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #6D858F;">
                                                        Shipper
                                                    </div>
                                                    <div style="font-family: 'Inter', sans-serif;font-weight: 500; font-size: 14px; color: #4A4A4A;">
                                                        {{ $content['shipper_name'] }},
                                                    </div>
                                                    <div style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #4A4A4A;" >
                                                        {{
                                                            $content['shipper_address']
                                                        }}
                                                    </div>
                                                    <div style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #6D858F; margin-top: 16px;">
                                                        Consignee
                                                    </div>
                                                    <div style="font-family: 'Inter', sans-serif;font-weight: 500; font-size: 14px; color: #4A4A4A;">
                                                        {{ $content['consignee_name'] }},
                                                    </div>
                                                    <div style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #6D858F;" >
                                                        {{
                                                        $content['consignee_address']
                                                        }}
                                                    </div>
                                                    @if(is_array($content['forwarders']) && count($content['forwarders']))
                                                    <div style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #6D858F; margin-top: 16px;">
                                                        Forwarder
                                                        @foreach($content['forwarders'] as $key => $forwarder)
                                                            <div>
                                                                <span style="font-family: 'Inter', sans-serif;font-weight: 500; font-size: 14px; color: #4A4A4A;">{{ $forwarder->name }}</span>
                                                                <span style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px;"> ({{ $forwarder->email }})</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    @endif
                                                    @if(!empty($content['notify_info']))
                                                        <div style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #6D858F; margin-top: 16px;"> Notify </div>
                                                        <div style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #4A4A4A;" > {{ $content['notify_info'] }}</div>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td align="left" style="width:50%; padding: 24px;">
                                        <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0; margin-top:-10px; padding: 0;">
                                            <tr style="padding-bottom: 12px;">
                                                <td style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #6D858F;" align="left">
                                                    From
                                                </td>
                                                <td align="left" style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #4A4A4A;">
                                                    {{ $content['location_from_name'] }}
                                                </td>
                                            </tr>
                                            <tr style="padding-bottom: 12px;">
                                                <td style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #6D858F;" align="left">
                                                    To
                                                </td>
                                                <td align="left" style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #4A4A4A;">
                                                    {{ $content['location_to_name'] }}
                                                </td>
                                            </tr>
                                            <tr style="padding-bottom: 12px;">
                                                <td style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #6D858F;" align="left">
                                                    Type
                                                </td>
                                                <td align="left" style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #4A4A4A;">
                                                    {{ $content['type'] }}
                                                </td>
                                            </tr>
                                            <tr style="padding-bottom: 12px;">
                                                <td style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #6D858F;" align="left">
                                                    Incoterms
                                                </td>
                                                <td align="left" style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #4A4A4A;">
                                                    {{ $content['inco_term'] }}
                                                </td>
                                            </tr>
                                            <tr style="padding-bottom: 12px;">
                                                <td style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #6D858F;" align="left">
                                                    Mode
                                                </td>
                                                <td align="left" style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #4A4A4A;">
                                                    {{ $content['mode'] }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        @if (isset($content['manual_orders']) && count($content['manual_orders']) > 0 || isset($content['final_purchase_orders']) && count($content['final_purchase_orders']) > 0)
                            <td colspan=2 style="padding: 0; ont-family: 'Inter', sans-serif;font-weight: 600; font-size: 20px; color: #4A4A4A; padding-left: 24px;">
                                Orders
                            </td>
                        @endif
                    </tr>
                    @if (isset($content['final_purchase_orders']) && count($content['final_purchase_orders']) > 0)
                    @foreach($content['final_purchase_orders'] as $key => $purchase_order)
                    <tr>
                        <td colspan=2 style="padding: 0;">
                            <table class="reference-box" role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0; margin-top: 16px; margin-bottom: 16px;" bgcolor="#FFFFFF">
                                <tr>
                                    <td colspan=1 align="left" style="padding-left:24px; padding-top: 24px;">
                                        <div style="width: 100%;font-family: 'Inter', sans-serif;font-weight: 600; font-size: 16px;color: #4A4A4A; width: 90%;">
                                            {{ ($content['role']=='shipper') ? 'SO' : 'PO'}} #{{ $purchase_order['po_number']}}
                                        </div>
                                    </td>

                                    <td colspan=3 align="left" style="padding: 0px 24px 0px 0px; width: 10%;">
                                        <div style="font-family: 'Inter', sans-serif;font-weight: 600; font-size: 12px; color: #6D858F; background-image: url('https://beta.shifl.com/images/booking-email/ribbon-light.png');background-repeat: no-repeat; width: 140%; height: 40px; color: white; background-size: 20px 40px; text-align: center; padding-top: 10px; position: relative; top: -3px; margin-top: -10px !important;">
                                            {{ ($key<9) ? '0' . ($key + 1) :  ($key + 1) }}
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" style="padding-left: 24px; width: 100%;">
                                        <div style="float: left; margin-right: 20px;">
                                            <span style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #6D858F;" >Total Cartons:</span>
                                            <span style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #4A4A4A;">{{ $purchase_order['total_cartons'] }}</span>
                                        </div>
                                        <div style="float: left; margin-right: 20px;">
                                            <span style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #6D858F;" >Total Volume:</span>
                                            <span style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #4A4A4A;">{{ $purchase_order['total_volumes'] }} CBM</span>
                                        </div>
                                        <div style="float: left;">
                                            <span style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #6D858F;" >Total Weight:</span>
                                            <span style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #4A4A4A;">{{ $purchase_order['total_weights'] }} KG</span>
                                        </div>
                                        <div style="clear:both;"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan=4 align="left" style="padding-left: 24px; padding-right: 24px;">
                                        <div style="width: 100%; background-color: #EBF2F5; height: 1px; margin-top: 12px;">
                                        </div>
                                    </td>
                                </tr>
                                @if ( count($purchase_order['products']) > 0)
                                @foreach ($purchase_order['products'] as $product )
                                <tr>
                                    <td style="font-family: 'Inter', sans-serif;font-weight: 500; font-size: 14px; color: #4A4A4A; padding-left: 24px; padding-right: 24px; padding-top: 12px;" colspan=4>
                                        {{
                                        "SKU# " . $product['product']['sku'] . " " . $product['product']['name'] . ", " . $product['product']['description']
                                        }}
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" style="padding-left: 24px;">
                                        <div style="float: left; margin-right: 20px;">
                                            <span style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #6D858F;" >Cartons:</span>
                                            <span style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #4A4A4A;">{{ $product['carton']}}</span>
                                        </div>
                                        <div style="float: left; margin-right: 20px;">
                                            <span style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #6D858F;" >Units:</span>
                                            <span style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #4A4A4A;">{{ $product['distribution_unit'] }}</span>
                                        </div>
                                        <div style="float: left; margin-right: 20px;">
                                            <span style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #6D858F;" >Volume:</span>
                                            <span style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #4A4A4A;">{{ $product['volume'] }} CBM</span>
                                        </div>
                                        <div style="float: left; margin-right: 20px;">
                                            <span style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #6D858F;" >Weight:</span>
                                            <span style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #4A4A4A;">{{ $product['weight'] }} KG</span>
                                        </div>
                                        <div style="clear:both;"></div>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                                <tr>
                                    <td align="left" style="padding-left: 24px; padding-bottom: 24px;">
                                        <div>
                                            <span style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #6D858F;" >Cargo Ready Date:</span>
                                            <span style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #4A4A4A;">{{ $purchase_order['cargo_ready_date'] ? \Carbon\Carbon::parse($purchase_order['cargo_ready_date'])->format('F d,Y') : 'N/A'}}</span>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                    @if ( isset($content['manual_orders']) && count($content['manual_orders']) > 0)
                    <?php
                    $x = count($content['final_purchase_orders']) + 1;
                    ?>
                    @foreach( $content['manual_orders'] as $manual_order )
                    <tr>
                        <td colspan=2 style="padding: 0;">
                            <table class="reference-box" role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0; margin-top: 16px; margin-bottom: 16px;" bgcolor="#FFFFFF">
                                <tr>
                                    <td colspan=1 align="left" style="padding-left:24px; padding-top: 24px;">
                                        <div style="width: 100%;font-family: 'Inter', sans-serif;font-weight: 600; font-size: 16px;color: #4A4A4A; width: 90%;">
                                            {{ ($content['role']=='shipper') ? 'SO' : 'PO'}} #{{ $manual_order['po_num'] }}
                                        </div>
                                    </td>

                                    <td colspan=3 align="left" style="padding: 0px 24px 0px 0px; width: 10%;">
                                        <div style="font-family: 'Inter', sans-serif;font-weight: 600; font-size: 12px; color: #6D858F; background-image: url('https://beta.shifl.com/images/booking-email/ribbon-light.png');background-repeat: no-repeat; width: 140%; height: 40px; color: white; background-size: 20px 40px; text-align: center; padding-top: 10px; position: relative; top: -3px; margin-top: -10px !important;">
                                            {{ ($x<9) ? '0' . $x :  $x }}
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" style="padding-left: 24px; width: 100%;">
                                        <div style="float: left; margin-right: 20px;">
                                            <span style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #6D858F;" >{{ ($content['role']=='shipper') ? 'Buyer' : 'Shipper' }}:</span>
                                            <span style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #4A4A4A;">{{ ($content['role']=='shipper') ? $content['consignee_name'] : $content['shipper_name'] }}</span>
                                        </div>
                                        <div style="float: left; margin-right: 20px;">
                                            <span style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #6D858F;" >Cargo Ready:</span>
                                            <span style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #4A4A4A;">{{ \Carbon\Carbon::parse($manual_order['cargo_ready_date'])->format('F d,Y') }}</span>
                                        </div>
                                        <div style="clear:both;"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" style="padding-left: 24px; width: 100%;">
                                        <div style="float: left; margin-right: 20px;">
                                            <span style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #6D858F;" >Total Cartons:</span>
                                            <span style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #4A4A4A;">{{  $manual_order['total_cartons'] }}</span>
                                        </div>
                                        <div style="float: left; margin-right: 20px;">
                                            <span style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #6D858F;" >Total Volume:</span>
                                            <span style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #4A4A4A;">{{ $manual_order['volume'] }}</span>
                                        </div>
                                        <div style="float: left; margin-right: 20px;">
                                            <span style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #6D858F;" >Total Weights:</span>
                                            <span style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #4A4A4A;">{{ $manual_order['kg'] }}</span>
                                        </div>
                                        <div style="clear:both;"></div>
                                    </td>
                                </tr>
                                @if ( isset($manual_order['documents']) && count($manual_order['documents'])> 0)
                                <tr>
                                    <td colspan=4 align="left" style="padding-left: 24px; padding-right: 24px;">
                                        <div style="width: 100%; background-color: #EBF2F5; height: 1px; margin-top: 12px;">
                                        </div>
                                    </td>
                                </tr>
                                @foreach($manual_order['documents'] as $document )
                                <tr>
                                    <td align="left" style="padding-left: 24px; width: 100%;">
                                        <div style="float: left; margin-right: 20px;">
                                            <span style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #6D858F;" >{{ $document['type'] }}:</span>
                                            <span style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #4A4A4A;">{{  $document['name'] }}</span>
                                        </div>
                                        <div style="clear:both;"></div>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </table>
                        </td>
                    </tr>
                    <?php
                    $x++;
                    ?>
                    @endforeach
                    @endif
                    <tr>
                        <td colspan=2 style="padding: 0; font-family: 'Inter', sans-serif;font-weight: 600; font-size: 20px; color: #4A4A4A; padding-left: 24px;">
                            Cargo Details
                        </td>
                    </tr>
                    <tr>
                        <td colspan=2 style="padding: 0;">
                            <table class="reference-box" role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0; margin-top: 16px; margin-bottom: 16px;" bgcolor="#FFFFFF">
                                <tr>
                                    <td colspan=2 align="left" style="padding: 24px;">
                                        <div style="width: 170.67px; float: left; border: 1px solid #D8E7F0; padding: 12px; margin-right: 20px; border-radius: 4px;">
                                            <div style="font-family: 'Inter', sans-serif;font-weight: 600; font-size: 14px; text-align: center; color: #4A4A4A;">
                                                {{ $content['total_cartons']}}
                                            </div>
                                            <div style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; text-align: center; color: #6D858F;">
                                                Total Carton
                                            </div>
                                        </div>
                                        <div style="width: 170.67px; float: left; border: 1px solid #D8E7F0; padding: 12px; margin-right: 20px; border-radius: 4px;">
                                            <div style="font-family: 'Inter', sans-serif;font-weight: 600; font-size: 14px; text-align: center; color: #4A4A4A;">
                                                {{ $content['total_volumes'] }} CBM
                                            </div>
                                            <div style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; text-align: center; color: #6D858F;">
                                                Total Volume
                                            </div>
                                        </div>
                                        <div style="width: 170.67px; float: left; border: 1px solid #D8E7F0; padding: 12px; border-radius: 4px;">
                                            <div style="font-family: 'Inter', sans-serif;font-weight: 600; font-size: 14px; text-align: center; color: #4A4A4A;">
                                                {{ $content['total_weights'] }} KG
                                            </div>
                                            <div style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; text-align: center; color: #6D858F;">
                                                Total Weight
                                            </div>
                                        </div>
                                        <div style="clear: both;"></div>
                                        <div style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #6D858F; margin-top: 16px; line-height: 20px;">
                                            {{ "Commodity, HS Code, Material, Usage" }}
                                        </div>
                                        <div style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px;color: #4A4A4A; margin-top: 4px; line-height: 20px;">
                                            {{
                                            !($content['commodities_info'] == 'undefined' || empty($content['commodities_info'])) ?  $content['commodities_info'] : 'N/A'
                                            }}
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    @if (count($content['containers']) > 0)
                    <tr>
                        <td colspan=2 style="padding: 0; font-family: 'Inter', sans-serif;font-weight: 600; font-size: 20px; color: #4A4A4A; padding-left: 24px;">
                            Container
                        </td>
                    </tr>
                    <tr>
                        <td colspan=2 style="padding: 0;">
                            <table class="reference-box" role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0; margin-top: 16px; margin-bottom: 16px;" bgcolor="#FFFFFF">
                                <tr>
                                    <td colspan=2 align="left" style="padding: 24px;">
                                        @foreach($content['containers'] as $container)
                                        <div style="min-width: 126px; float: left; border: 1px solid #D8E7F0; padding: 8px 12px; margin-right: 20px; border-radius: 4px;">
                                            <div style="float: left;font-family: 'Inter', sans-serif;font-weight: 600; font-size: 16px; color: #0171A1; margin-right: 8px;">
                                                {{ $container->how_many . 'x'}}
                                            </div>
                                            <div style="float: left; margin-right: 8px;">
                                                <img width=20 height=22 src="https://beta.shifl.com/images/forwarder/cfirsthere.png" />
                                            </div>
                                            <div style="float: left;font-family: 'Inter', sans-serif;font-weight: 400; font-size: 16px; color: #4A4A4A;">
                                                {{
                                                \App\ContainerSize::find(intval($container->size))->name
                                                }}
                                            </div>
                                            <div style="clear:both;"></div>
                                        </div>
                                        @endforeach
                                        <div style="min-width: 126px; float: left; border: 1px solid #D8E7F0; padding: 8px 12px; margin-right: 20px; border-radius: 4px; display: none;">
                                            <div style="float: left;font-family: 'Inter', sans-serif;font-weight: 600; font-size: 16px; color: #0171A1; margin-right: 8px;">
                                                1x
                                            </div>
                                            <div style="float: left; margin-right: 8px;">
                                                <img width=20 height=22 src="https://beta.shifl.com/images/forwarder/csecondhere.png" />
                                            </div>
                                            <div style="float: left;font-family: 'Inter', sans-serif;font-weight: 400; font-size: 16px; color: #4A4A4A;">
                                                40FT
                                            </div>
                                            <div style="clear:both;"></div>
                                        </div>
                                        <div style="min-width: 126px; float: left; border: 1px solid #D8E7F0; padding: 8px 12px; border-radius: 4px; display: none;">
                                            <div style="float: left;font-family: 'Inter', sans-serif;font-weight: 600; font-size: 16px; color: #0171A1; margin-right: 8px;">
                                                1x
                                            </div>
                                            <div style="float: left; margin-right: 8px;">
                                                <img width=20 height=22 src="https://beta.shifl.com/images/forwarder/csecondhere.png" />
                                            </div>
                                            <div style="float: left;font-family: 'Inter', sans-serif;font-weight: 400; font-size: 16px; color: #4A4A4A;">
                                                45 HQ
                                            </div>
                                            <div style="clear:both;"></div>
                                        </div>
                                        <div style="clear: both;"></div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan=2 style="padding: 0; font-family: 'Inter', sans-serif;font-weight: 600; font-size: 20px; color: #4A4A4A; padding-left: 24px;">
                            Others
                        </td>
                    </tr>
                    <tr>
                        <td colspan=2 style="padding: 0;">
                            <table class="reference-box" role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0; margin-top: 16px; margin-bottom: 16px;" bgcolor="#FFFFFF">
                                <tr>
                                    <td align="left" style="padding-left: 24px; padding-top: 24px;">
                                        <div style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #6D858F;">
                                            Marks
                                        </div>
                                        <div style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #4A4A4A;">
                                            {{ !(empty($content['marks']) || $content['marks'] == 'undefined') ? $content['marks'] : 'N/A'}}
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" style="padding-left: 24px; padding-bottom: 24px;">
                                        <div style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #6D858F; margin-top: 16px;">
                                            Special Instructions
                                        </div>
                                        <div style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 14px; color: #4A4A4A;">
                                            {{ !(empty($content['special_instructions']) || $content['special_instructions'] == 'undefined') ? $content['special_instructions'] : 'N/A'}}
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td align="center" style="padding:0;">
                <table role="presentation" style="width:100%;border-collapse:collapse;border-spacing:0;text-align:left;">
                    <tr>
                        <td align="center">
                            <table role="presentation" style="width:605px;border-collapse:collapse;border:0;border-spacing:0;background-color: #4A4A4A">
                                @if(empty($content['company_name']))
                                    <tr>
                                        <td style="padding-top: 24px; padding-bottom: 30px; padding-left: 10px;">
                                            <div style="font-family: 'Inter', sans-serif;font-weight: 600; font-size: 16px; color: #FFFFFF; line-height: 24px; margin-bottom: 8px;" >
                                                Download Shifl App
                                            </div>
                                            <div style="width: 100%;">
                                                <div style="float:left; background-image: url('https://beta.shifl.com/images/booking-email/new-resources/playstore-new.png');background-repeat: no-repeat; width: 144px; height: 48px; background-size: 144px 48px; margin-right: 8px;">
                                                </div>
                                                <div style="float:left; background-image: url('https://beta.shifl.com/images/booking-email/new-resources/apple-new.png');background-repeat: no-repeat; width: 144px; height: 48px; background-size: 144px 48px;">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-bottom: 30px; padding-left: 10px;">
                                            <div style="float: left; width: 50%;">
                                                <div>
                                                    <span style="font-family: 'Inter', sans-serif;font-weight: 500; font-size: 12px; color: #D8E7F0; margin-right: 8px;" >Email us</span>
                                                    <span style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 12px; color: #D8E7F0;" >hello@shifl.com</span>
                                                </div>
                                                <div>
                                                    <span style="font-family: 'Inter', sans-serif;font-weight: 500; font-size: 12px; color: #D8E7F0; margin-right: 8px;" >Phone</span>
                                                    <span style="font-family: 'Inter', sans-serif;font-weight: 400; font-size: 12px; color: #D8E7F0;" >888-HI-SHIFL (888-447-4435)</span>
                                                </div>

                                                <div style="margin-top: 24px;">
                                                    <div style="float: left; background-image: url('https://beta.shifl.com/images/forwarder/social-spritesheet.png'); background-repeat: no-repeat; background-position: 0px 0px; background-size: 62px 27px; margin-right: 16px; width: 23px; height: 23px;">
                                                    </div>
                                                    <div style="float: left; background-image: url('https://beta.shifl.com/images/forwarder/social-spritesheet.png'); background-repeat: no-repeat; background-position: -37px 0px; background-size: 62px 27px; margin-right: 16px; width: 23px; height: 23px;">
                                                    </div>
                                                    <div style="clear: both;"></div>
                                                </div>

                                            </div>
                                            <div style="float: left; width: 50%;">
                                                <div style="font-family: 'Inter', sans-serif;font-weight: 500; font-size: 12px; color: #D8E7F0;">
                                                    {{ "Disclaimers" }}
                                                </div>
                                                <div style="font-family: 'Inter', sans-serif;font-weight: 500; font-size: 12px; color: #D8E7F0;">
                                                    1) We don't offer insurance<br/>
                                                    2) Given prices are subject to rate increase
                                                </div>

                                                <div style="margin-top: 24px;font-family: 'Inter', sans-serif;font-weight: 400; font-size: 12px; color: #D8E7F0;">
                                                    © {{ date("Y") }} Shifl. All rights reserved.
                                                </div>
                                            </div>
                                            <div style="clear: both;"></div>
                                        </td>
                                    </tr>
                                @endif
                                @if(!empty($content['company_name']))
                                    <tr>
                                        <td style="padding-bottom: 30px; padding-left: 20px; padding-top: 20px">
                                            <div style="float: left; width: 100%;">
                                                <div>
                                                    <span style="font-family: 'Inter', sans-serif;font-weight: 500; font-size: 14px; color: #D8E7F0; margin-right: 8px; line-height: 20px;" >Email Us</span>
                                                    <span style="font-family: 'Inter', sans-serif;font-weight: 600; font-size: 14px; color: #D8E7F0; line-height: 20px;" >{{ $content['company_email'] }}</span>
                                                </div>
                                                @if(!empty($content['company_phone']))
                                                    <div>
                                                        <span style="font-family: 'Inter', sans-serif;font-weight: 500; font-size: 14px; color: #D8E7F0; margin-right: 8px; line-height: 20px;">Phone</span>
                                                        <span style="font-family: 'Inter', sans-serif;font-weight: 600; font-size: 14px; color: #D8E7F0; line-height: 20px;" >{{ $content['company_phone'] }}</span>
                                                    </div>
                                                @endif
                                                <div style="margin-top: 20px;font-family: 'Inter', sans-serif;font-weight: 400; font-size: 12px; color: #D8E7F0;line-height: 18px;">
                                                    © {{ date("Y") }} {{ $content['company_name'] }}. All rights reserved.
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
