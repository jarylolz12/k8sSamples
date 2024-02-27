<table>
    <thead>
        <tr>
            @foreach($columns as $field)
            <th style="font-weight: bold;">{{ $field }}</th>
            @endforeach
            @php
            $loginEmail = \Auth::user()->email ?? '';
            @endphp
            @if($loginEmail == 'mary@shifl.com')
            <th>DB</th>
            @endif
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

            
            $suppliers = (new \App\Custom\ProcessShipmentReport())->getShipmentSupplier($shipment); 
        @endphp
        @if($all_containers_count > 0)
            @foreach ($all_containers as $container)
            @php
            $container_num = $container['container_num'] ?? '';
            @endphp
                @include('excel.customize-body', ['shipment'=> $shipment, 'container' => $container, 
                'container_count' => $all_containers_count, 'container_num' => $container['container_num'] ?? '', 'suppliers' => $suppliers,
                't49_attr' => $t49_attr, 'columns' => $columns])  
            @endforeach
        @else
            @include('excel.customize-body', ['shipment'=> $shipment, 'container' => [],
                'container_count' => $all_containers_count, 'suppliers' => $suppliers, 't49_attr' => $t49_attr, 'container_num' => '', 'columns' => $columns])  
        @endif

        @endforeach
        </tbody>
</table>
