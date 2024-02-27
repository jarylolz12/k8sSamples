<table>
    <thead>
        @php
        $loginEmail = \Auth::user()->email ?? '';
        @endphp
        <tr>
            <th>Shifl Ref# {{$loginEmail== 'mary@shifl.com' ? ' DB' : ''}}</th>
            <th>MBL#</th>
            <th>PO#</th>
            <th>Consignee</th>
            <th>Shipper</th>
            <th>Telex Release</th>
            <th>Booked Date</th>
            <th>ETD</th>
            <th>ETA</th>
            <th>POL</th>
            <th>POD</th>
            <th>Terminal</th>
            <th>Number of Containers</th>
            <th>Container#</th>
            <th>Container Size</th>
            <th>Discharge Date</th>
            <th>Freight Release</th>
            <th>Customs Release</th>
            <th>Customs Submitted/Release Date</th>
            <th>LFD</th>
            <th>Status</th>
            <th>Full Gated Out</th>
            <th>Empty Gated In</th>
            <th>Tracking Status</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($shipments as $shipment)

        <tr>
            <?php
            $supplier_count = count(json_decode($shipment->suppliers_group,true) ?? []);
            $schedule = (new \App\Custom\ProcessSchedulesGroupBookings($shipment->schedules_group_bookings))->getSchedule();
            
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

            $pol = '';
            $pod = '';
            $etd = '';
            $eta_latest = '';
            if($schedule ?? false){

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
            }    

            ?>
            <!-- Shifl Ref# -->
            <td> {{ $shipment->shifl_ref ?? '' }}</td>
                
            <!-- MBL# -->
            <td>
                {{ $shipment->mbl_num ?? '' }}
            </td>
            
            <!-- PO# -->
            <td style="text-align: right;">
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

            <!-- Consignee -->
            <td> 
                {{ $shipment->customer->company_name ?? '' }}
            </td>

            <!-- Shipper -->
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

            <!-- Telex Release -->
            <td>
                @php
                echo implode("<br> ", $suppliers['status']);
                @endphp
            </td>

            <!-- Booked Date -->
            <td>
            {{ empty($shipment->booking_confirmed_at) ? " " : \Carbon\Carbon::parse($shipment->booking_confirmed_at)->format("m-d-Y")  }}
            </td>

            <!-- ETD -->
            <td>
               {{ $etd ?? ''}}
            </td>

             <!-- ETA -->
             <td>
             {{ $eta_latest ?? ''}}

            </td>

            <!-- POL -->
            <td>
             {{ $pol ?? ''}}
            </td>
                
            
            <!-- POD -->
            <td>
            {{ $pod ?? ''}}
            </td>

            <!-- Terminal -->
            <td>
                @if ($shipment->terminal_id ?? false)
                {{ \App\Terminal::find($shipment->terminal_id)->name ?? '' }}
                @endif
            </td>


            <!-- Number of Containers  -->
            <td>
                @php
                $all_containers_count = count($all_containers);
                @endphp
                {{ ($all_containers_count > 0) ?  $all_containers_count : ''}}
            </td>


            <!-- Container# -->
            <td style="text-align: right;">
               @php
                   echo implode("<br> ", array_column($all_containers, 'container_num'));
                @endphp
            </td>

            <!-- Container Size -->
            <td>
                @php
                   echo implode("<br>", array_column($all_containers, 'size'));
                @endphp
            </td>

            <!-- Discharge Date -->
            <td>
               @php
                echo implode("<br>",array_column($all_containers, 'vessel_discharged'));
                @endphp
            </td>

            <!-- Freight Release -->
            <td>
                {{ $shipment->freight_released_processed == 1 ? 'Yes' : 'No' }}
            </td>

            <!-- Customs Release -->
            <td>
                {{ $shipment->customs_released == 1 ? 'Yes' : 'No' }}
            </td>

            
            <!-- Customs Submitted/Release Date -->
            <td>
             {{ empty($shipment->entry_netchb_date) ? " " : \Carbon\Carbon::parse($shipment->entry_netchb_date)->format("m-d-Y")  }}
            </td>

            <!-- LFD -->
            <td>
                @php
                    echo implode("<br>", array_column($all_containers, 'pickup_lfd'));
                @endphp
            </td>


            <!-- Status -->
            <td>
             {{ $shipment->getGeneralStatus() ?? '' }}
            </td>


            <!-- Full Gate Out -->
            <td>
                @php
                    echo implode("<br>", array_column($all_containers, 'full_out'));
                @endphp
            </td>

            <!-- Empty Gated In -->
            <td>
                @php
                    echo implode("<br>", array_column($all_containers, 'empty_in'));
                @endphp
            </td>

            <!-- Tracking Status -->
            <td>
            {{ $shipment->getTrackingStatus() ?? '' }}

            </td>

        </tr>


        @endforeach


    </tbody>
</table>