<!DOCTYPE html>
<html>
	<head>
		<title>Invoice {{ $data['invoice_number'] }}</title>
		<style type="text/css">
			@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');
			html {
                width: 100%;
                height: 100%;
                padding-top: 100px;
                margin: 0;
                margin-top: 20px;


                /* zoom: 120%; */
            }

			body {
				margin: 0;
				padding: 0;
			}

		</style>
	</head>
	<body>
		<div style="min-height: 100vh; padding: 1rem;">
			<div style="width: 100%;text-align: left;font-size: 20px;font-weight:  600;color: #4a4a4a;border-bottom: 2px solid #d2e3ed;height: 20px;padding-bottom:  12px;margin-bottom:  12px; font-family: 'Inter', sans-serif;">Invoice #{{ $data['invoice_number'] }}</div>
			<div style="width: 100%;">
				<div style="width: 66.66%; float: left">
					<div style="width: 100%;font-family: 'Inter', sans-serif; color: #819fb2; font-size: 10px; text-transform: uppercase; margin-bottom: 5px; font-weight: 600;">Bill to</div>
					<div style="width: 100%;font-family: 'Inter', sans-serif; font-size: 12px;color: #4a4a4a!important;">{{ $data['bill_to'] }}</div>
					<div style="width: 100%;font-family: 'Inter', sans-serif; font-size: 12px; color: #4a4a4a!important; line-height: 1.25rem;">{{ $data['bill_to_address'] }}</div>
					<div style="clear:both;"></div>
				</div>
				<div style="width: 33.3333333333%; float: left;">
					<div style="width: 100%;">
						<div style="color: #819fb2; font-size: 10px; text-transform: uppercase; font-family: 'Inter', sans-serif; width: 35%; display: inline-block; font-weight: 600;">Date</div>
						<div style="width: 65%;font-family: 'Inter', sans-serif; font-size: 12px;color: #4a4a4a!important; display: inline-block;">
							{{ $data['date'] }}
						</div>
					</div>
					<div style="width: 100%; margin-top: -10px;">
						<div style="color: #819fb2; font-size: 10px; text-transform: uppercase; font-family: 'Inter', sans-serif; width: 35%; display: inline-block; font-weight: 600;">Due Date</div>
						<div style="width: 65%;font-family: 'Inter', sans-serif; font-size: 12px;color: #4a4a4a!important; display: inline-block;">
							{{ $data['due_date'] }}
						</div>
					</div>
					<div style="width: 100%; margin-top: -10px;">
						<div style="color: #819fb2; font-size: 10px; text-transform: uppercase; font-family: 'Inter', sans-serif; width: 35%; display: inline-block; font-weight: 600;">Reference</div>
						<div style="width: 65%;font-family: 'Inter', sans-serif; font-size: 12px;color: #4a4a4a!important; display: inline-block;">
							{{
								$data['shipment_reference_number']
							}}
						</div>
					</div>
				</div>
				<div style="clear:both;"></div>
			</div>
			<div style="width: 100%;">
				<div style="width: 33.33%; float: left; border: 1px solid #ebf2f5; padding: 12px; border-radius: 4px;">
					<div style="width: 100%;font-family: 'Inter', sans-serif; color: #819fb2; font-size: 10px; text-transform: uppercase; margin-bottom: 5px;font-weight: 600;">BL #</div>
					<div style="width: 100%;font-family: 'Inter', sans-serif; font-size: 12px;color: #4a4a4a!important; margin-bottom: 12px;">{{ $data['mbl_number'] }}</div>
					<div style="width: 100%;font-family: 'Inter', sans-serif; color: #819fb2; font-size: 10px; text-transform: uppercase; margin-bottom: 5px;font-weight: 600;">From</div>
					<div style="width: 100%;font-family: 'Inter', sans-serif; font-size: 12px;color: #4a4a4a!important; margin-bottom: 5px;">{{ $data['from'] }}</div>
					<div style="width: 100%;font-family: 'Inter', sans-serif; font-size: 12px;color: #4a4a4a!important; margin-bottom: 12px;">{{ $data['etd'] }}</div>
					<div style="width: 100%;font-family: 'Inter', sans-serif; color: #819fb2; font-size: 10px; text-transform: uppercase; margin-bottom: 5px;font-weight: 600;">To</div>
					<div style="width: 100%;font-family: 'Inter', sans-serif; font-size: 12px;color: #4a4a4a!important; margin-bottom: 5px;">{{ $data['to']}}</div>
					<div style="width: 100%;font-family: 'Inter', sans-serif; font-size: 12px;color: #4a4a4a!important;">{{ $data['eta'] }}</div>
				</div>
				<div style="width: 5% ;float: left;"></div>
				<div style="width: 54%; float: left; border: 1px solid #ebf2f5; padding: 12px; border-radius: 4px;">
					<div style="width: 100%;font-family: 'Inter', sans-serif; color: #819fb2; font-size: 10px; text-transform: uppercase; margin-bottom: 5px;font-weight: 600;">Suppliers</div>
					<div style="width: 100%;font-family: 'Inter', sans-serif; font-size: 12px;color: #4a4a4a!important; margin-bottom: 12px;">
						{{
							$data['suppliers_name_string']
						}}
					</div>
					<div style="width: 100%;font-family: 'Inter', sans-serif; color: #819fb2; font-size: 10px; text-transform: uppercase; margin-bottom: 12px;font-weight: 600;">Purchase Orders</div>
					<div style="width: 100%;font-family: 'Inter', sans-serif; font-size: 12px;color: #4a4a4a!important; margin-bottom: 12px;">
						{{ $data['purchase_numbers_string'] }}
					</div>
					<div style="width: 100%;font-family: 'Inter', sans-serif; color: #819fb2; font-size: 10px; text-transform: uppercase; margin-bottom: 5px;font-weight: 600;">Containers</div>
					<div style="width: 100%;font-family: 'Inter', sans-serif; font-size: 12px;color: #4a4a4a!important; margin-bottom: 12px;">
						{{ $data['containers_name_string'] }}
					</div>
				</div>
				<div style="clear:both;"></div>
			</div>
			<div style="width: 100%; margin-top: 16px; border-bottom: 2px solid #819fb2; margin-bottom: 16px;">
				<table style="margin: 0px;padding: 0px; width: 100%; border-collapse: collapse;">
					<thead>
						<tr style="background-color: #f5f9fc;">
							<th style="width: 45%;text-align: left;padding: 8px 16px;font-family: 'Inter', sans-serif; font-size: 12px;color: #819fb2;border-bottom: none; text-transform: uppercase;">Item & Description</th>
							<th style="width: 15%;text-align: left;padding: 8px 16px;font-family: 'Inter', sans-serif; font-size: 12px;color: #819fb2;border-bottom: none; text-transform: uppercase;">Qty</th>
							<th style="width: 20%;text-align: left;padding: 8px 16px;font-family: 'Inter', sans-serif; font-size: 12px;color: #819fb2;border-bottom: none; text-transform: uppercase;">Rate</th>
							<th style="width: 20%;text-align: left;padding: 8px 16px;font-family: 'Inter', sans-serif; font-size: 12px;color: #819fb2;border-bottom: none; text-transform: uppercase;">Amount</th>
						</tr>
					</thead>
					<tbody>
						<?php if (count($data['invoice_items']) > 0) : ?>
						<?php foreach( $data['invoice_items'] as $item) : ?>
						<tr>
							<td style="width: 55%;text-align: left;padding: 8px 16px;font-family: 'Inter', sans-serif; font-size: 14px;color: #4a4a4a;">
								{{
									$item->qbo_service_name
								}}
							</td>
							<td style="width: 15%;text-align: left;padding: 8px 16px;font-family: 'Inter', sans-serif; font-size: 14px;color: #4a4a4a;">
								{{
									$item->quantity
								}}
							</td>
							<td style="width: 15%;text-align: left;padding: 8px 16px;font-family: 'Inter', sans-serif; font-size: 14px;color: #4a4a4a;">${{$item->rate}}</td>
							<td style="width: 15%;text-align: left;padding: 8px 16px;font-family: 'Inter', sans-serif; font-size: 14px;color: #4a4a4a;">${{number_format(floatval(floatval($item->rate) * floatval($item->quantity)),2)}}</td>
						</tr>
						<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
			<div style="width: 100%;">
				<div style="font-weight: 600;width: 10%; float: right; font-family: 'Inter', sans-serif; font-size: 14px;color: #4a4a4a;">${{$data['total_amount']}}</div>
				<div style="font-weight: 600;width: 20%; float: right;font-family: 'Inter', sans-serif; font-size: 14px;color: #4a4a4a;">
					Total Amount
				</div>
				<div style="clear:both;"></div>
			</div>
            @if($data['paid_on'] || $data['payment_method'])
			<div style="width: 100%; margin-top: 24px;">
                @if($data['paid_on'])
				<div style="width: 20%;font-family: 'Inter', sans-serif; color: #819fb2; font-size: 10px; text-transform: uppercase; margin-bottom: 5px; float: left; font-weight: 600;">Paid On</div>
				<div style="width: 20%;font-family: 'Inter', sans-serif; font-size: 12px;color: #4a4a4a!important; float: left;">{{ $data['paid_on'] }}</div>
				<div style="clear:both;"></div>
                @endif
                @if($data['payment_method'])
				<div style="width: 20%;font-family: 'Inter', sans-serif; color: #819fb2; font-size: 10px; text-transform: uppercase; margin-bottom: 5px; float: left; font-weight: 600; float: left;">Payment Method</div>
				<div style="width: 60%;font-family: 'Inter', sans-serif; font-size: 12px;color: #4a4a4a!important; float: left; font-weight: 600;">{{ $data['payment_method'] }}</div>
				<div style="clear:both;"></div>
                @endif
			</div>
            @endif
		</div>
	</body>
</html>
