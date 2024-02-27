<table>
    <thead>
        @php
        $loginEmail = \Auth::user()->email ?? '';
        @endphp
        <tr>
            @foreach($columns as $field)
            <th style="font-weight: bold;font-size:10px;">{{ $field }}</th>
            @endforeach
            @if($loginEmail== 'mary@shifl.com')
            <th>DB</th>
            @endif
            
        </tr>
    </thead>
    <tbody>

        @foreach ($shipments as $shipment)

        <tr>
            <?php
            $schedule = (new \App\Custom\ProcessSchedulesGroupBookings($shipment->schedules_group_bookings))->getSchedule();
            $customer = \App\Customer::find($shipment->customer_id)->qbo_customer ?? '';
            $qboCustomer =  !empty($customer) ? json_decode($customer) : [];
            

            $processShipment = new \App\Custom\ProcessShipmentReport();
            $t49_attr = [];
            if($loginEmail !== 'mary@shifl.com'){
                $t49 = $processShipment->getShipmentContainerAndT49($shipment);
                $all_containers = $t49['all_containers'] ?? [];
                $t49_attr = $t49['attributes'] ?? [];
            }else{
                $all_containers = $shipment->containers_data ?? [];
                $shipment_t49 = $shipment->terminal_fortynine;
                if($shipment_t49){
                    $t49_attr = json_decode($shipment_t49->attributes,true) ?? [];
                }
                
            }


            $numbers = \Arr::pluck($all_containers, 'container_num') ?? [];    
            if(empty(implode("", $numbers))){
                $all_containers = $processShipment->getContainersGroupBookings($shipment) ?? [];
            }
            
            // $all_containers =  $shipment->containers_data ?? [];
            // $t49_attr = [];
            // $shipment_t49 = \App\Terminal49Shipment::find($shipment->mbl_num);
            // if($shipment_t49){
            //     $t49_attr = json_decode($shipment_t49->attributes,true) ?? [];
            // }

            $etd = '';
            $eta_latest = '';
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
            
            $suppliers = $processShipment->getShipmentSupplier($shipment); 
            
            ?>

          
            @foreach($columns as $field)
           
            <td> 
                @if($field == 'Shifl Ref#')
                {{ $shipment->shifl_ref ?? '' }}
                @endif

                @if($field == 'MBL#')
                {{ $shipment->mbl_num ?? '' }}
                @endif

                @if($field == 'Consignee')
                {{ $shipment->customer->company_name ?? '' }}
                @endif

                @if($field == 'Status')
                {{ $shipment->getGeneralStatus() ?? '' }}
                @endif

                @if($field == 'Booked Date')
                {{ empty($shipment->booking_confirmed_at) ? " " : \Carbon\Carbon::parse($shipment->booking_confirmed_at)->format("m-d-Y")  }}
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
                
                @if($field == 'Supplier Cartons')
                 {{$suppliers['supplier_carton'] ?? ''}}
                @endif

                @if($field == 'HBL#')
                    @php
                    echo implode("<br> ", $suppliers['hbl_num']);
                    @endphp
                @endif

                @if($field == 'Telex Release')
                    @php
                    echo implode("<br> ", $suppliers['status']);
                    @endphp
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

                @if($field == 'Vessel Name')
                {{ $shipment->vessel ?? $t49_attr['pod_vessel_name'] ?? '' }}
                @endif

                @if($field == 'Voyage #')
                 {{ $shipment->voyage_number ??  $t49_attr['pod_voyage_number'] ?? '' }}
                @endif

                @if($field == 'Total Cartons')
                 {{$suppliers['supplier_carton'] ?? ''}}
                @endif

                @if($field == 'No. of Containers')
                    @php
                    $all_containers_count = count($all_containers);
                    @endphp
                    {{ ($all_containers_count > 0) ?  $all_containers_count : ''}}
                @endif


                @if($field == 'Container #' || $field == 'Container#')   
                    @php
                    echo implode("<br> ", array_column($all_containers, 'container_num'));
                    @endphp
                @endif

                
                @if($field == 'Container Seal#' || $field == 'Container Seal #')
                    @php
                    echo implode("<br>", array_column($all_containers, 'seal_num'));
                    @endphp
                @endif

                @if($field == 'Container Size')
                    @php
                    echo implode("<br>", array_column($all_containers, 'size'));
                    @endphp
                @endif

                @if($field == 'Container Weight (kg)')
                    @php
                      echo implode("<br>", array_column($all_containers, 'kg'));
                    @endphp
                @endif


                @if($field == 'Container Cartons')
                    @php
                     echo implode("<br>", array_column($all_containers, 'cartons'));
                    @endphp 
                @endif


                @if($field == 'Container Volume')
                    @php
                    $container_group = json_decode($shipment->containers_group) ?? [];
                    echo implode("<br>", array_column($container_group, 'cbm'));
                    @endphp
                @endif


                @if($field == 'Freight Cost' || $field == 'Freight Rate')
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
                        $sellRateCount++
                        @endphp
                    @endif
                    @endforeach
                    @endif
                @endif


                @if($field == 'Current ETD')
                    {{ $etd ?? '' }}
                @endif


                @if($field == 'Current ETA')
                    {{ $eta_latest ?? ''}}
                @endif


                @if($field == 'Original ETA')
                    {{ $original_eta ?? '' }}
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


                @if($field == 'Cargo Ready Date' || $field == 'Factory Cargo Ready Date')
                    @php
                    echo implode("<br> ", $suppliers['cargo_date']);
                    @endphp
                @endif


                @if($field == 'Empty Out' || $field == 'Empty Out To FTY')
                    @php
                     echo implode("<br> ", array_column($all_containers, 'empty_out'));
                    @endphp 
                @endif


                @if($field == 'Gated In' || $field == 'Gated In POL')
                    @php
                    echo implode("<br> ", array_column($all_containers, 'full_in'));
                    @endphp
                @endif


                @if($field == 'Terminal')
                    @if($shipment->terminal_id ?? false)
                    {{ \App\Terminal::find($shipment->terminal_id)->name ?? '' }}
                    @endif
                @endif


                @if($field == 'Shifl AN Sent' || $field == 'Arrival Notice Sent')
                {{ $shipment->an_sent_at ? \Carbon\Carbon::parse($shipment->an_sent_at)->format("m-d-Y") : ''}}
                @endif


                @if($field == 'Shifl DO Sent' || $field == 'DO Sent')
                {{ $shipment->do_sent_at ? \Carbon\Carbon::parse($shipment->do_sent_at)->format("m-d-Y") : ''}}
                @endif


                @if($field == 'Delivery Loc (WRHS)')
                {{ $shipment->trucker_custom_note ?? '' }}
                @endif


                @if($field == 'Freight Release')
                {{ $shipment->freight_released_processed == 1 ? 'Yes' : 'No' }}
                @endif


                @if($field == 'Customs Release')
                {{ empty($shipment->entry_netchb_date) ? " " : \Carbon\Carbon::parse($shipment->entry_netchb_date)->format("m-d-Y")  }}
                @endif

                
                @if($field == 'Discharge')
                    @php
                    echo implode("<br>",array_column($all_containers, 'vessel_discharged'));
                    @endphp
                @endif


                @if($field == 'LFD(latest)')
                    @php
                     echo implode("<br>", array_column($all_containers, 'pickup_lfd'));
                     @endphp
                @endif

                @if($field == 'Available for Pickup')
                    @php
                    echo implode("<br>", array_column($all_containers, 'available_for_pickup'));
                    @endphp
                @endif


                @if($field == 'Full Out')
                @php
                    echo implode("<br>", array_column($all_containers, 'full_out'));
                @endphp
                @endif


                @if($field == 'Empty In')
                    @php
                        echo implode("<br>", array_column($all_containers, 'empty_in'));
                    @endphp
                @endif

                @php
                $count = 0;
                $demurrages = [];
                @endphp
                @if(!empty($qboCustomer))
                @php
                $demurrages = (new \App\Invoice())->getInvoiceServiceRateAndDays($shipment->id, $qboCustomer, 'Demurrage')->toArray()  ?? [];
                @endphp
                @endif


                @if($field == 'Demurrage Days')
                    @php
                        echo implode("<br>", array_column($demurrages, 'quantity'));
                    @endphp
                @endif


                @if($field == 'Dmrg Rate Per Day')
                    @php
                        echo implode("<br>", array_column($demurrages, 'rate'));
                    @endphp
                @endif


                @if($field == 'Demurrage Total')
                    @if(!empty($qboCustomer))
                        {{ (new \App\Invoice())->getTotalInvoiceServiceByShipmentId($shipment->id, ['Demurrage'], $qboCustomer) ?? ''}}
                    @endif 
                @endif


                @if($field == 'Per Diem Total' || $field == 'Total Per Diem')
                    @if(!empty($qboCustomer))
                    {{ (new \App\Invoice())->getTotalInvoiceServiceByShipmentId($shipment->id, ['Per Diem'], $qboCustomer) ?? ''}}
                    @endif  
                @endif


                @if($field == 'Pier Pass Total')
                    @if(!empty($qboCustomer))
                        {{ (new \App\Invoice())->getTotalInvoiceServiceByShipmentId($shipment->id, ['Pier Pass', 'Pier Pass / CTF'], $qboCustomer) ?? ''}}
                    @endif  
                @endif


                @if($field == 'Other Totals' || $field == 'Other Charges')
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


                @if($field == 'Customs Total' || $field == 'Customs Entry Cost')
                    @if($schedule->sell_rates ?? false)
                    @foreach($schedule->sell_rates as $sell_rate)
                    @if($sell_rate->service_id == 2)
                        {{ $sell_rate->amount ?? '' }}
                    @endif
                    @endforeach
                    @endif
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
                {{ $shipment->getTrackingStatus() ?? ''}}
                @endif

                @if($field == 'Booking#' || $field == 'Booking #')
                {{ $shipment->booking_number ?? '' }}
                @endif
               

            </td>
            @endforeach

            

                 
        </tr>
        @endforeach
       
        </tbody>
</table>