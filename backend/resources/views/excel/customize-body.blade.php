<tr>
    <?php
    $schedule = (new \App\Custom\ProcessSchedulesGroupBookings($shipment->schedules_group_bookings))->getSchedule();
    $customer = \App\Customer::find($shipment->customer_id)->qbo_customer ?? '';
    $qboCustomer =  !empty($customer) ? json_decode($customer) : [];
    $eta_latest = '';
    $etd = '';
    $original_eta = '';
    if($schedule ?? false){
        if($shipment->etd){
            $etd = \Carbon\Carbon::parse($shipment->etd)->format("m-d-Y");
        }elseif($shipment->is_tracking_shipment == 1){
            $etd = $t49_attr['pol_etd_at'] ?? '';
            $atd = $t49_attr['pol_atd_at'] ?? '';
            $etd = $etd ? \Carbon\Carbon::parse($etd)->format("m-d-Y") : \Carbon\Carbon::parse($atd)->format("m-d-Y");
        } 

        $eta_latest = $shipment->eta ? $shipment->eta : $shipment->getFinalETA();
        $eta_latest = $eta_latest ? \Carbon\Carbon::parse($eta_latest)->format("m-d-Y"): '' ;

        $etaLog = \App\EtaLog::where("mbl_num", $shipment->mbl_num)
                ->orderBy('id', 'asc')->pluck('old_eta')
                ->first();
        $original_eta = $etaLog ? \Carbon\Carbon::parse($etaLog)->format("m-d-Y") : $eta_latest;
    }    
    $mbl = $shipment->mbl_num ?? '';
    $num = $container['container_num'] ?? '';
    ?>
@foreach($columns as $field)
@php
$field = trim($field);
@endphp
<td>
    @if($field == 'Shifl Ref#')
        {{ $shipment->shifl_ref ?? '' }}
    @endif


    @if($field == 'PO#')
         @php
        $po_list = $shipment->getTrackingAndSupplierPo($shipment);
        $po = array_filter($po_list);
        $i = 0;
        @endphp

        @if(is_countable($po) && count($po) > 0)
            @foreach($po as $p)
            @php
            $i++;
            @endphp
            {{$p}}
            @if($i != count($po)) <br>
            @endif
            @endforeach
        @endif
    @endif


    @if($field == 'Shipper')
            @php
            $count = 0;
            @endphp
            @foreach($suppliers['supplier'] as $shipper)
            @php
            $count++;
            @endphp
            {{$shipper}}
            @if($count != count($suppliers['supplier']))
            <br>
            @endif
            @endforeach
    @endif


    @if($field == 'Factory Cargo Ready Date')
            @php
            echo implode("<br> ", $suppliers['cargo_date']);
            @endphp
    @endif


    @if($field == 'Supplier Cartons')
      {{ $suppliers['supplier_carton'] ?? ''}}
    @endif


    @if($field == 'Booked Date')
        {{ empty($shipment->booking_confirmed_at) ? " " : \Carbon\Carbon::parse($shipment->booking_confirmed_at)->format("m-d-Y")  }}
    @endif


    @if($field == 'POL')
      @if($schedule ?? false)
            @php
            $pol = isset($schedule->location_from) ? \App\TerminalRegion::find($schedule->location_from)->name ?? ''  : '';
            @endphp
            {{ !empty($pol) ? $pol : $t49_attr['port_of_lading_name'] ?? '' }}
        @endif 
    @endif


    @if($field == 'POD')
        @if($schedule ?? false)
            @php
            $pod = isset($schedule->location_to) ? \App\TerminalRegion::find($schedule->location_to)->name ?? ''  : '';
            @endphp
            {{ !empty($pod) ? $pod : $t49_attr['port_of_discharge_name'] ?? '' }}
        @endif
    @endif


    @if($field == 'Delivery Loc (WRHS)')
        {{ $shipment->trucker_custom_note ?? '' }}
    @endif


    @if($field == 'Consignee')
        {{ $shipment->customer->company_name ?? '' }}
    @endif


    @if($field == 'Type')
        {{ $schedule->type ?? ''}}  
    @endif


    @if($field == 'Mode')
        {{ $schedule->mode ?? ''}}  
    @endif


    @if($field == 'Carrier')
       @if($schedule ?? false)
            @php
            $carrier = isset($schedule->carrier_name->id) ? \App\Carrier::find($schedule->carrier_name->id)->name ?? ''  : '';
            @endphp
            {{ !empty($carrier) ? $carrier : $t49_attr['shipping_line_name'] ?? '' }}
        @endif
    @endif


    @if($field == 'MBL#')
      {{ $shipment->mbl_num ?? '' }}
    @endif


    @if($field == 'Vessel Name')
        {{ $shipment->vessel ?? $t49_attr['pod_vessel_name'] ?? '' }}
    @endif


    @if($field == 'Voyage#' || $field == 'Voyage #')
       {{$shipment->voyage_number ??  $t49_attr['pod_voyage_number'] ?? '' }}
    @endif


    @if($field == 'Total Cartons')
        {{$suppliers['supplier_carton'] ?? ''}}
    @endif


    @if($field == 'Container#')
        @if($container_count > 0)
            {{ $container['container_num'] ?? ''}}
        @else
        {{ 'Not yet Specified'}}
        @endif
    @endif


    @if($field == 'Container Sizes' || $field == 'Container Size')
         @if($container_count > 0)
             {{$container['size'] ?? ''}} 
            @endif
    @endif


    @if($field == 'Container Weight (kg)')
         @if($container_count > 0)
            {{$container['kg'] ?? '' }}
            @endif
    @endif


    @if($field == 'Container Cartons')
         @if($container_count > 0)
            {{$container['cartons'] ?? '' }}
            @endif
    @endif


    @if($field == 'Container Volume (cbm)')
            @foreach (json_decode($shipment->containers_group) ?? [] as $con)
            @if($con->container_num == $container_num)
            {{ $con->cbm ?? '' }}
            @php
            break;
            @endphp
            @endif
            @endforeach
    @endif


    @if($field == 'Container Seal#')
        @if($container_count > 0)
            {{ $container['seal_num'] ?? '' }}
        @endif
    @endif


    @if($field == 'Freight Rate')
     @if($schedule->sell_rates ?? false)
        @php
        $sellRateCount = 0;
        @endphp
        @foreach($schedule->sell_rates as $sell_rate)
        @if($sell_rate->service_id == 1)
            @if($sellRateCount > 0)
            <br>
            @endif
            {{ $sell_rate->amount ?? '' }}
            @php
            $sellRateCount++;
            @endphp
        @endif
        @endforeach
        @endif
    @endif


    @if($field == 'Status')
        {{ $shipment->getGeneralStatus() ?? '' }}
    @endif


    @if($field == 'HBL#')
         @php
        echo implode("<br> ", $suppliers['hbl_num']);
        @endphp
    @endif


    @if($field == 'Telex Release')
         @php
            if(count($suppliers['status']) > 0){
                echo implode("<br> ", $suppliers['status']);
            }
            @endphp
    @endif


    @if($field == 'Current ETD')
        {{  $etd ?? ''  }}
    @endif


    @if($field == 'Current ETA')
        {{ $eta_latest ?? ' '}}
    @endif


    @if($field == 'Original ETA')
        {{ $original_eta ?? '' }}
    @endif


    @if($field == 'Empty out to FTY')
    {{ $container['empty_out'] ?? '' }}
    @endif


    @if($field == 'Gated In POL')
    {{ $container['full_in'] ?? '' }}
    @endif


    @if($field == 'Arrival Notice Sent')
        {{ $shipment->an_sent_at ?? '' }}
    @endif


    @if($field == 'DO Sent')
        {{ $shipment->do_sent_at ?? '' }}
    @endif


    @if($field == 'Freight Release')
        {{ $shipment->freight_released_processed == 1 ? 'Yes' : 'No' }}
    @endif


    @if($field == 'Customs Release')
        {{ empty($shipment->entry_netchb_date) ? " " : \Carbon\Carbon::parse($shipment->entry_netchb_date)->format("m-d-Y") }}
    @endif


    @if($field == 'Discharge')
         @if($container_count > 0)
            {{ $container['vessel_discharged'] ?? '' }}
            @endif
    @endif

    @if($field == 'Available for Pickup')
         @if($container_count > 0)
            {{ $container['available_for_pickup'] ?? '' }}
            @endif
    @endif


    @if($field == 'Latest LFD')
        {{ $container['pickup_lfd'] ?? '' }}
    @endif


    @if($field == 'Container Picked Up')
        {{ $container['full_out'] ?? '' }}
    @endif


    @if($field == 'Empty Container Returned')
        {{ $container['empty_in'] ?? '' }}
    @endif

    @php
    $demurrages = [];
    $per_diems = [];
    @endphp
    @if(!empty($qboCustomer))
        @php
        $demurrages = (new \App\Invoice())->getInvoiceServiceRateAndDays($shipment->id, $qboCustomer, 'Demurrage')->toArray() ?? [];
        $per_diems = (new \App\Invoice())->getInvoiceServiceRateAndDays($shipment->id, $qboCustomer, 'Per Diem')->toArray() ?? [];
        $pier_pass = (new \App\Invoice())->getInvoiceServiceRateAndDays($shipment->id, $qboCustomer, 'Pier Pass')->toArray() ?? [];
        @endphp
    @endif


    @if($field == 'Dmrg Rate Per Day')
        @php
            echo implode("<br>", array_column($demurrages, 'rate'));
        @endphp  
    @endif


    @if($field == 'Demurrage Days')
            @php
               echo implode("<br>", array_column($demurrages, 'quantity'));
            @endphp
    @endif


    @if($field == 'Demurrage Total')
         @if(!empty($qboCustomer))
            {{ (new \App\Invoice())->getTotalInvoiceServiceByShipmentId($shipment->id, ['Demurrage'], $qboCustomer) ?? ''}}
            @endif 
    @endif


    @if($field == 'Per Diem Rate Day')
        @php
            echo implode("<br>", array_column($per_diems, 'rate'));
        @endphp  
    @endif


    @if($field == 'Per Diem Days')
        @php
            echo implode("<br>", array_column($per_diems, 'quantity'));
        @endphp  
    @endif


    @if($field == 'Total Per Diem')
        @if(!empty($qboCustomer))
            {{ (new \App\Invoice())->getTotalInvoiceServiceByShipmentId($shipment->id, ['Per Diem'], $qboCustomer) ?? ''}}
         @endif 
    @endif


    @if($field == 'Pier Pass')
       @if(!empty($qboCustomer))
        {{ (new \App\Invoice())->getTotalInvoiceServiceByShipmentId($shipment->id, ['Pier Pass', 'Pier Pass / CTF'], $qboCustomer) ?? ''}}
        @endif
    @endif


    @if($field == 'Customs Entry Cost')
         @if($schedule->sell_rates ?? false)
                
            @foreach($schedule->sell_rates as $sell_rate)
            @if($sell_rate->service_id == 2)
                {{ $sell_rate->amount ?? '' }}
            @endif
            @endforeach
            @endif
    @endif


    @if($field == 'Other Charges')
         @php
            $others = [];
            $count = 0;
            @endphp
            @if(!empty($qboCustomer))
            @php
            $others = (new \App\Invoice())->getOtherServiceTotal($shipment->id, $qboCustomer, ['Per Diem', 'Demurrage', 'Pier Pass', 'Pier Pass / CTF'], $schedule) ?? [];
            @endphp
            @endif

            @foreach($others as $key => $val)
            @php
            $count++;
            $display = $key . ' '. $val;
            @endphp
            {{ $display }}
            @if($count != count($others))
                {{', '}}
            @endif
            @endforeach
    @endif


    @if($field == 'Other Services Total')
            @php
            $count = 0;
            @endphp
            @if($schedule->sell_rates ?? false)
                @foreach($schedule->sell_rates as $sell_rate)
                @if($sell_rate->service_id != 1 && $sell_rate->service_id != 2)
                    @php
                    $count++;
                    $service = $sell_rate->service_name ?? '';
                    $amount = $sell_rate->amount ?? '';
                    @endphp
                    {{$service . ' '. $amount}}
                    @if($count != count($schedule->sell_rates))
                    {{', '}}
                    @endif
                @endif
                @endforeach
            @endif

    @endif


    @if($field == 'Tracking Status')
        {{ $shipment->getTrackingStatus() ?? '' }}
    @endif


    @if($field == 'Booking#')
        {{ $shipment->booking_number ?? '' }}
    @endif

    @if($field == 'Location At')
        {{ $trucking[$mbl][$num]['location_at'] ?? '' }}
    @endif


    @if($field == 'Deliver To')
        {{ $trucking[$mbl][$num]['deliver_to'] ?? '' }}
    @endif


    @if($field == 'Pre Gate Fees')
        {{ $trucking[$mbl][$num]['pre_gate_fees'] ?? '' }}
    @endif


    @if($field == 'Pickup Scheduled/Port Appointment')
        {{ $trucking[$mbl][$num]['pickup_scheduled'] ?? '' }}
    @endif


    @if($field == 'Pickup Date')
        {{  $trucking[$mbl][$num]['pickup_date'] ?? '' }}
    @endif


    @if($field == 'Prepull')
        {{  $trucking[$mbl][$num]['prepull'] ?? '' }}
    @endif


    @if($field == 'Port Wait Time')
        {{  $trucking[$mbl][$num]['port_wait_time'] ?? '' }}
    @endif


    @if($field == 'Scheduled Delivery')
        {{  $trucking[$mbl][$num]['scheduled_delivery'] ?? '' }}
    @endif


    @if($field == 'Trucking Mode')
        {{  $trucking[$mbl][$num]['mode'] ?? '' }}
    @endif


    @if($field == 'Delivered')
        {{  $trucking[$mbl][$num]['delivered'] ?? '' }}
    @endif


    @if($field == 'Container Empty')
        {{  $trucking[$mbl][$num]['container_empty'] ?? '' }}
    @endif

    @if($field == 'Wait Time')
     {{ $trucking[$mbl][$num]['wait_time'] ?? '' }}
    @endif


    @if($field == 'Empty Pickup Date')
        {{ $trucking[$mbl][$num]['empty_pickup_date'] ?? '' }}
    @endif


    @if($field == 'Return Empty')
    {{ $trucking[$mbl][$num]['return_empty'] ?? '' }}
    @endif


    @if($field == 'Per Diem Date')
        {{ $trucking[$mbl][$num]['per_diem_date'] ?? '' }}
    @endif


    @if($field == 'Chassis Days')
        {{ $trucking[$mbl][$num]['chassis_days'] ?? '' }}
    @endif


    @if($field == 'Storage Days')
    {{ $trucking[$mbl][$num]['storage_days'] ?? '' }}
    @endif
    
</td>    
@endforeach
    </tr>