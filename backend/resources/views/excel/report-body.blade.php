<tr>
            <?php
            $schedule = (new \App\Custom\ProcessSchedulesGroupBookings($shipment->schedules_group_bookings))->getSchedule();
            $customer = \App\Customer::find($shipment->customer_id)->qbo_customer ?? '';
            $qboCustomer =  !empty($customer) ? json_decode($customer) : [];

            // $trucking = [];
            // if(isset($shipment->mbl_num) && isset($container['container_num'])){
            //     $trucking =  (new \App\Custom\ProcessShipmentReport())->getTruckingShipmentByMBLAndCon($shipment->mbl_num, (array) $container['container_num']);
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
            ?>

            <td> 
                {{ $shipment->shifl_ref ?? '' }}

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
                 @php
                 echo implode("<br> ", $suppliers['cargo_date']);
                @endphp

            </td>

            <td>
              {{$suppliers['supplier_carton'] ?? ''}}
            </td>

            <td>
            {{ empty($shipment->booking_confirmed_at) ? " " : \Carbon\Carbon::parse($shipment->booking_confirmed_at)->format("m-d-Y")  }}
            </td>

            
            <td>
                @if($schedule ?? false)
                    @php
                    $pol = isset($schedule->location_from) ? \App\TerminalRegion::find($schedule->location_from)->name ?? ''  : '';
                    @endphp
                    {{ !empty($pol) ? $pol : $t49_attr['port_of_lading_name'] ?? '' }}
                @endif 
            </td>

            <td>
                 @if($schedule ?? false)
                    @php
                    $pod = isset($schedule->location_to) ? \App\TerminalRegion::find($schedule->location_to)->name ?? ''  : '';
                    @endphp
                    {{ !empty($pod) ? $pod : $t49_attr['port_of_discharge_name'] ?? '' }}
                @endif
            </td>

            <td>
                {{ $shipment->trucker_custom_note ?? '' }}
            </td>

            <td> 
                {{ $shipment->customer->company_name ?? '' }}
            </td>

             <td>
             {{ $schedule->type ?? ''}}  
            </td>

            <td>
            {{ $schedule->mode ?? ''}}  
            </td>

            <td>
              @if($schedule ?? false)
                    @php
                    $carrier = isset($schedule->carrier_name->id) ? \App\Carrier::find($schedule->carrier_name->id)->name ?? ''  : '';
                    @endphp
                    {{ !empty($carrier) ? $carrier : $t49_attr['shipping_line_name'] ?? '' }}
                @endif
            </td>

            <td>
                {{ $shipment->mbl_num ?? '' }}
            </td>
            

            <td>
                {{ $shipment->vessel ?? $t49_attr['pod_vessel_name'] ?? '' }}
            </td>

            <td>
                {{$shipment->voyage_number ??  $t49_attr['pod_voyage_number'] ?? '' }}
            </td>

            <td>
                @if($suppliers['supplier_carton'] ?? false)     
                    {{ ($suppliers['supplier_carton'] > 0) ? $suppliers['supplier_carton'] : ''}}
               @endif
            </td>

            <td>
                 @if($container_count > 0)
                 {{ $container['container_num'] ?? ''}}
                @else
                {{ 'Not yet Specified'}}
                @endif
            </td>

            <td>
             {{$container['size'] ?? ''}} 
            </td>

            <td style="text-align: right;">
                {{$container['kg'] ?? '' }}
             </td>

             <td style="text-align: right;">
                {{$container['cartons'] ?? '' }}
             </td>

             <td style="text-align: right;">
                
                @foreach (json_decode($shipment->containers_group) ?? [] as $con)
                @if($con->container_num == $container_num)
                {{ $con->cbm ?? '' }}
                @php
                break;
                @endphp
                @endif
                @endforeach
            </td>

            <td>
             {{ $container['seal_num'] ?? '' }}
            </td>

             <td>
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
            </td>

            <td>
            {{ $shipment->getGeneralStatus() ?? '' }}
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
            {{ $etd ?? '' }}
            </td>

            <td>
             {{ $eta_latest ?? '' }}
            </td>


            <td>
                 {{ $original_eta ?? '' }}
            </td>

            <td>
                 {{ $container['empty_out'] ?? '' }}
            </td>

            <td>
                {{ $container['full_in'] ?? '' }}
            </td>

            <td>
                {{ $shipment->an_sent_at ?? ''}}
            </td>

            <td>
                {{ $shipment->do_sent_at}}
            </td>

            <td>
                {{ $shipment->freight_released_processed == 1 ? 'Yes' : 'No' }}
            </td>

            <td>
             {{ empty($shipment->entry_netchb_date) ? " " : \Carbon\Carbon::parse($shipment->entry_netchb_date)->format("m-d-Y")  }}
            </td>

             <td>
                {{ $container['vessel_discharged'] ?? '' }}
            </td>

            <td>
                {{ $container['available_for_pickup'] ?? '' }}
            </td>
             
            <td>
                {{ $container['pickup_lfd'] ?? '' }}
            </td>


            <td>
            {{ $container['full_out'] ?? '' }}
            </td>

            <td>
                {{ $container['empty_in'] ?? '' }}
            </td>

            <?php
                $demurrages = [];
                $per_diems = [];
                $others = [];
                if(!empty($qboCustomer)){
                    $demurrages = (new \App\Invoice())->getInvoiceServiceRateAndDays($shipment->id, $qboCustomer, 'Demurrage')->toArray() ?? [];
                    $per_diems = (new \App\Invoice())->getInvoiceServiceRateAndDays($shipment->id, $qboCustomer, 'Per Diem')->toArray() ?? [];
                    $others = (new \App\Invoice())->getOtherServiceTotal($shipment->id, $qboCustomer, ['Per Diem', 'Demurrage', 'Pier Pass', 'Pier Pass / CTF'], $schedule) ?? [];
                    $dmgTotal = (new \App\Invoice())->getTotalInvoiceServiceByShipmentId($shipment->id, ['Demurrage'], $qboCustomer) ?? '';
                    $pierDiemTotal = (new \App\Invoice())->getTotalInvoiceServiceByShipmentId($shipment->id, ['Per Diem'], $qboCustomer) ?? '';
                    $perPassTotal = (new \App\Invoice())->getTotalInvoiceServiceByShipmentId($shipment->id, ['Pier Pass', 'Pier Pass / CTF'], $qboCustomer) ?? '';
                }

            ?>

            <td>
                 @php
                    echo implode("<br>", array_column($demurrages, 'rate'));
                @endphp
            </td>

            <td>
                @php
                    echo implode("<br>", array_column($demurrages, 'quantity'));
                @endphp
            </td>

            <td>
            {{ $dmgTotal ?? '' }}
            </td>

            <td>
               
                @php
                    echo implode("<br>", array_column($per_diems, 'rate'));
                @endphp
            </td>

            <td>
                 @php
                    echo implode("<br>", array_column($per_diems, 'quantity'));
                @endphp
            </td>

            <td>
            {{ $pierDiemTotal ?? ''}}    
            </td>

            <td>
            {{ $perPassTotal ?? ''}} 
            </td>

            <td>
                
                <?php
                    if($schedule->sell_rates ?? false){
                        $count = 0;
                        foreach($schedule->sell_rates as $sell_rate){
                            if($sell_rate->service_id == 2){
                                if($count > 0) echo "<br>";
                                echo $sell_rate->amount ?? '';
                                $count++;
                            }
                        }
                    }
                ?>
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

            
            <!-- new columns from shipment trucking -->
            <?php
            $mbl = $shipment->mbl_num ?? '';
            $num = $container['container_num'] ?? '';
            ?>

            <!-- Location At -->
            <td>
                {{ $trucking[$mbl][$num]['location_at'] ?? '' }}
                
            </td>

            <!-- Deliver To -->
            <td>
                {{ $trucking[$mbl][$num]['deliver_to'] ?? '' }}
            </td>

            <!-- Pre Gate Fees -->
            <td>
                {{ $trucking[$mbl][$num]['pre_gate_fees'] ?? '' }}
            </td>

            <!-- Pickup Scheduled/Port Appointment -->
            <td>
             {{ $trucking[$mbl][$num]['pickup_scheduled'] ?? '' }}
            </td>

            <!-- Pickup Date -->
            <td>
              
                {{ $trucking[$mbl][$num]['pickup_date'] ?? '' }}
            </td>

            <!-- Prepull -->
            <td>
                {{ $trucking[$mbl][$num]['prepull'] ?? '' }}
            </td>

            <!-- Port Wait Time -->
            <td>
                {{ $trucking[$mbl][$num]['port_wait_time'] ?? '' }}
            </td>

            <!-- Scheduled Delivery -->
            <td>
                {{ $trucking[$mbl][$num]['scheduled_delivery'] ?? '' }}
            </td>

            <!-- Trucking Mode -->
            <td>
            {{ $trucking[$mbl][$num]['mode'] ?? '' }}
            </td>

            <!-- Delivered -->
            <td>
              {{ $trucking[$mbl][$num]['delivered'] ?? '' }}
            </td>

            <!-- Container Empty -->
            <td>
                {{ $trucking[$mbl][$num]['container_empty'] ?? '' }}
            
            </td>

            <!-- Wait Time -->
            <td>
                 {{ $trucking[$mbl][$num]['wait_time'] ?? '' }}
            </td>

            <!-- Empty Pickup Date -->
            <td>
                {{ $trucking[$mbl][$num]['empty_pickup_date'] ?? '' }}
            </td>

            <!-- Return Empty -->
            <td>
                {{ $trucking[$mbl][$num]['return_empty'] ?? '' }}
            </td>

            <!-- Per Diem Date -->
            <td>
             {{ $trucking[$mbl][$num]['per_diem_date'] ?? '' }}
            </td>

            <!-- Chassis Days -->
            <td>
            {{ $trucking[$mbl][$num]['chassis_days'] ?? '' }}
            </td>

            <!-- Storage Days -->
            <td>
            {{ $trucking[$mbl][$num]['storage_days'] ?? '' }}
            </td>

            <!-- end new columns from shipment trucking -->


        </tr>