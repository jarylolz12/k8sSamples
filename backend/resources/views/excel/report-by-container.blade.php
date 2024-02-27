<table>
    <thead>
        @php
        $loginEmail = \Auth::user()->email ?? '';
        @endphp
        <tr>
            <th>Shifl Ref# {{$loginEmail == 'mary@shifl.com' ? 'DB' : ''}} </th>
            <th>PO#</th>
            <th>Shipper</th>
            <th>Factory Cargo Ready Date</th>
            <th>Supplier Cartons</th>
            <th>Booked Date</th>
            <th>POL</th>
            <th>POD</th>
            <th>Delivery Loc (WRHS)</th>
            <th>Consignee</th>
            <th>Type</th>
            <th>Mode</th>
            <th>Carrier</th>
            <th>MBL#</th>
            <th>Vessel Name</th>
            <th>Voyage#</th>
            <th>Total Cartons</th>
            <th>Container#</th>
            <th>Container Size</th>
            <th>Container Weight (kg)</th>
            <th>Container Cartons</th>
            <th>Container Volume</th>
            <th>Container Seal#</th>
            <th>Freight Rate</th>
            <th>Status</th>
            <th>HBL#</th>
            <th>Telex Release</th>
            <th>Current ETD</th>
            <th>Current ETA</th>
            <th>Original ETA</th>
            <th>Empty Out To FTY</th>
            <th>Gated In POL</th>
            <th>Arrival Notice Sent</th>
            <th>DO Sent</th>
            <th>Freight Release</th>
            <th>Customs Released</th>
            <th>Discharge</th>
            <th>Available for Pickup</th>
            <th>Latest LFD</th>
            <th>Container Picked Up</th>
            <th>Empty container returned</th>
            <th>Dmrg Rate Per Day</th>
            <th>Demurrage Days</th>
            <th>Demurrage Total</th>
            <th>Per Diem Rate Day</th>
            <th>Per Diem Days</th>
            <th>Total Per Diem</th>
            <th>Pier Pass</th>
            <th>Customs Entry Cost</th>
            <th>Other Charges</th>
            <th>Other Services Total</th>
            <th>Tracking Status</th>
            <th>Booking#</th>
            <!-- new columns from shipment trucking -->
            <th>Location At</th>
            <th>Deliver To</th>
            <th>Pre Gate Fees</th>
            <th>Pickup Scheduled/Port Appointment</th>
            <th>Pickup Date</th>
            <th>Prepull</th>
            <th>Port Wait Time</th>
            <th>Scheduled Delivery</th>
            <th>Trucking Mode</th>
            <th>Delivered</th>
            <th>Container Empty</th>
            <th>Wait Time</th>
            <th>Empty Pickup Date</th>
            <th>Return Empty</th>
            <th>Per Diem Date</th>
            <th>Chassis Days</th>
            <th>Storage Days</th>
            <!-- end new columns from shipment trucking -->
        </tr>
    </thead>
    <tbody>

        @foreach ($shipments as $shipment)
            @php
            
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

            $all_containers_count = count($all_containers);

            $cons = \Arr::pluck($all_containers, 'container_num') ?? [];
            $trucking = [];
            if($shipment->mbl_num && !empty(implode("", $cons))){
                $trucking =  $processShipment->getTruckingShipmentByMBLAndCon($shipment->mbl_num, $cons);
            }
           

            $suppliers = $processShipment->getShipmentSupplier($shipment); 
            @endphp
            @if($all_containers_count > 0)
                @foreach ($all_containers as $container)
                @php
                $container_num = $container['container_num'] ?? '';
                @endphp
                  @include('excel.report-body', [ 'shipment'=> $shipment, 'container' => $container, 
                    'container_count' => $all_containers_count, 'container_num' => $container['container_num'] ?? '', 'suppliers' => $suppliers,
                    't49_attr' => $t49_attr, 'trucking' => $trucking])  
                @endforeach
            @else
                @include('excel.report-body', ['shipment'=> $shipment, 'container' => [],
                    'container_count' => $all_containers_count, 'suppliers' => $suppliers, 't49_attr' => $t49_attr, 'container_num' => '', 'trucking' => $trucking ])  
            @endif
        @endforeach
    </tbody>
</table>