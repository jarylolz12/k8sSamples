<table>
    <thead>
        @php
        $loginEmail = \Auth::user()->email ?? '';
        @endphp
        <tr>
            <th>Shifl Ref {{$loginEmail == 'mary@shifl.com' ? ' DB' : ''}}</th>
            <th>MBL#</th>
            <th>Consignee</th>
            <th>Status</th>
            <th>Booked Date</th>
            <th>PO#</th>
            <th>Shipper</th>
            <th>Supplier Cartons</th>
            <th>HBL#</th>
            <th>Telex Release</th>
            <th>Type</th>
            <th>Mode</th>
            <th>Carrier</th>
            <th>Vessel Name</th>
            <th>Voyage#</th>
            <th>Total Cartons</th>
            <th>No. of Containers</th>
            <th>Container#</th>
            <th>Container Seal#</th>
            <th>Container Sizes</th>
            <th>Container Weight (kg)</th>
            <th>Container Cartons</th>
            <th>Container Volume</th>
            <th>Freight Cost</th>
            <th>Current ETD</th>
            <th>Current ETA</th>
            <th>Original ETA</th>
            <th>POL</th>
            <th>POD</th>
            <th>Cargo Ready Date</th>
            <th>Empty Out</th>
            <th>Gated In</th>
            <th>Terminal</th>
            <th>Shifl AN Sent</th>
            <th>Shifl DO Sent</th>
            <th>Delivery Loc (WRHS)</th>
            <th>Freight Release</th>
            <th>Customs Release</th>
            <th>Discharge</th>
            <th>LFD(latest)</th>
            <th>Available for Pickup</th>
            <th>Full Out</th>
            <th>Empty In</th>
            <th>Demurrage Days</th>
            <th>Dmrg Rate Per Day</th>
            <th>Demurrage Total</th>
            <th>Per Diem Total</th>
            <th>Pier Pass Total</th>
            <th>Other Totals</th>
            <th>Customs Total</th>
            <th>Other Services Total</th>
            <th>Tracking Status</th>
            <th>Booking#</th>
            
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
            
            $suppliers = $processShipment->getShipmentSupplier($shipment); 

            $carrier = '';
            $pol = '';
            $pod = '';
            $etd = '';
            $eta_latest = '';
            $original_eta = '';
            
            if($schedule ?? false){
                 $carrier = isset($schedule->carrier_name->id) ? \App\Carrier::find($schedule->carrier_name->id)->name ?? ''  : '';
                 $carrier =  !empty($carrier) ? $carrier : $t49_attr['shipping_line_name'] ?? '';

                 $pol = isset($schedule->location_from) ? \App\TerminalRegion::find($schedule->location_from)->name ?? ''  : '';
                 $pol = !empty($pol) ? $pol : $t49_attr['port_of_lading_name'] ?? '';

                 $pod = isset($schedule->location_to) ? \App\TerminalRegion::find($schedule->location_to)->name ?? ''  : '';
                 $pod = !empty($pod) ? $pod : $t49_attr['port_of_discharge_name'] ?? '';

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

        ?>    

            <td> 
                {{ $shipment->shifl_ref ?? '' }}
            </td>

            
            <td>
                {{ $shipment->mbl_num ?? '' }}
            </td>


            <td> 
                {{ $shipment->customer->company_name ?? '' }}
            </td>


            <td>
                {{ $shipment->getGeneralStatus() ?? '' }}
            </td>
                

            <td>
             {{ empty($shipment->booking_confirmed_at) ? " " : \Carbon\Carbon::parse($shipment->booking_confirmed_at)->format("m-d-Y")  }}
            </td>


            <td>
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
            </td>


            <td>
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

            </td>

            <td>
            {{$suppliers['supplier_carton'] ?? ''}}
            </td>


            <td>
               @php
                echo implode("<br> ", $suppliers['hbl_num']);
                @endphp
              </td> 

              <td>
               @php
                echo implode("<br> ", $suppliers['status']);
                @endphp
              </td> 

              <td>
              {{ $schedule->type ?? ''}}  
              </td>

              <td>
              {{ $schedule->mode ?? ''}}  
              </td>

              <td>
                  {{ $carrier ?? ''}}
              </td>

              <td>
                  {{ $shipment->vessel ?? $t49_attr['pod_vessel_name'] ?? '' }}
              </td>

              <td>
                  {{ $shipment->voyage_number ??  $t49_attr['pod_voyage_number'] ?? '' }}
              </td>

              <td>
              {{$suppliers['supplier_carton'] ?? ''}}
              </td>
          
              <td>
                @php
                $all_containers_count = count($all_containers);
                @endphp
                {{ ($all_containers_count > 0) ?  $all_containers_count : ''}}
            </td>

            <td>
                @php
                   echo implode("<br> ", array_column($all_containers, 'container_num'));
                @endphp
            </td>


            <td>
                @php
                   echo implode("<br>", array_column($all_containers, 'seal_num'));
                @endphp
            </td>


             <td>
                @php
                   echo implode("<br>", array_column($all_containers, 'size'));
                @endphp
            </td>


            <td style="text-align: right;">
                @php
                   echo implode("<br>", array_column($all_containers, 'kg'));
                @endphp
            </td>

            <td style="text-align: right;">
                @php
                   echo implode("<br>", array_column($all_containers, 'cartons'));
                @endphp
            </td>


            <td style="text-align: right;">
                @php
                   $container_group = json_decode($shipment->containers_group) ?? [];
                   echo implode("<br>", array_column($container_group, 'cbm'));
                @endphp
            </td>

            <td>
                @if($schedule->sell_rates ?? false)
                @php
                $sellRateCount = 0;
                @endphp
                @foreach($schedule->sell_rates as $sell_rate)
                @if($sell_rate->service_id == 1)
                    @if($sellRateCount > count($schedule->sell_rates))
                    <br>
                    @endif
                    {{ $sell_rate->amount ?? '' }}
                    @php
                    $sellRateCount++
                    @endphp

                    
                @endif
                @endforeach
                @endif
            </td>

            <td>
                {{ $etd ?? '' }}
               
            </td>

            <td>
                {{ $eta_latest ?? '' }}
            </td>

            <td>
                {{ $original_eta ?? '' }}
            </td>

            <td>
                {{ $pol ?? ''}}
            </td>

            <td>
                {{ $pod ?? ''}}
            </td>

            <td>
                @php
                echo implode("<br> ", $suppliers['cargo_date']);
                @endphp
            </td>

            <td>
                @php
                echo implode("<br> ", array_column($all_containers, 'empty_out'));
                @endphp
            </td>

            <td>
                @php
                echo implode("<br> ", array_column($all_containers, 'full_in'));
                @endphp
            </td>

            <td>
                @if($shipment->terminal_id ?? false)
                {{ \App\Terminal::find($shipment->terminal_id)->name ?? '' }}
                @endif
            </td>

            <td>
                {{ $shipment->an_sent_at ?? ''}}
            </td>

            <td>
                {{ $shipment->do_sent_at}}
            </td>

            <td>
                {{ $shipment->trucker_custom_note ?? '' }}
            </td>

            <td>
                {{ $shipment->freight_released_processed == 1 ? 'Yes' : 'No' }}
            </td>

            <td>
                {{ empty($shipment->entry_netchb_date) ? " " : \Carbon\Carbon::parse($shipment->entry_netchb_date)->format("m-d-Y")  }}
            </td>

            <td>
                @php
                echo implode("<br>",array_column($all_containers, 'vessel_discharged'));
                @endphp
            </td>

            <td>
                @php
                    echo implode("<br>", array_column($all_containers, 'pickup_lfd'));
                @endphp
               
            </td>

            <td>
                @php
                    echo implode("<br>", array_column($all_containers, 'available_for_pickup'));
                @endphp
            </td>

            <td>
                @php
                    echo implode("<br>", array_column($all_containers, 'full_out'));
                @endphp
            </td>

            <td>
                @php
                    echo implode("<br>", array_column($all_containers, 'empty_in'));
                @endphp
            </td>






            <?php
                $demurrages = [];
                $others = [];
                if(!empty($qboCustomer)){
                    $demurrages = (new \App\Invoice())->getInvoiceServiceRateAndDays($shipment->id, $qboCustomer, 'Demurrage')->toArray() ?? [];
                    $others = (new \App\Invoice())->getOtherServiceTotal($shipment->id, $qboCustomer, ['Per Diem', 'Demurrage', 'Pier Pass', 'Pier Pass / CTF'], $schedule) ?? [];
                    $dmgTotal = (new \App\Invoice())->getTotalInvoiceServiceByShipmentId($shipment->id, ['Demurrage'], $qboCustomer) ?? '';
                    $pierDiemTotal = (new \App\Invoice())->getTotalInvoiceServiceByShipmentId($shipment->id, ['Per Diem'], $qboCustomer) ?? '';
                    $perPassTotal = (new \App\Invoice())->getTotalInvoiceServiceByShipmentId($shipment->id, ['Pier Pass', 'Pier Pass / CTF'], $qboCustomer) ?? '';
                }

            ?>


            <td>
                @php
                    echo implode("<br>", array_column($demurrages, 'quantity'));
                @endphp
            </td>

            <td>
                @php
                    echo implode("<br>", array_column($demurrages, 'rate'));
                @endphp
            </td>
            
             <td>
             {{ $dmgTotal ?? '' }}
            </td>

            <td>
            {{ $pierDiemTotal ?? ''}}    
            </td>

            <td>
            {{ $perPassTotal ?? ''}}
            </td>

            <td>
                <?php
                    if(is_countable($others) && count($others) > 0){
                        $count = 0;
                        foreach($others as $key => $val){
                            $count++;
                            echo $key . ' '. $val;
                            if($count != count($others)){
                                echo ", ";
                            }
                        }   
                    }    
                ?>
            </td>

            <td>
                @if($schedule->sell_rates ?? false)
                @foreach($schedule->sell_rates as $sell_rate)
                @if($sell_rate->service_id == 2)
                    {{ $sell_rate->amount ?? '' }}
                @endif
                @endforeach
                @endif
            </td>

            <td>
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
            </td>

            <td>
                {{ $shipment->getTrackingStatus() ?? ''}}
            </td>

            <td>
                {{ $shipment->booking_number ?? '' }}

            </td>

        </tr>
        @endforeach
        </tbody>
</table>
