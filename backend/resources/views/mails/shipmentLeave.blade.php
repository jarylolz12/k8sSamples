<!DOCTYPE html><html lang="en"> <head> <meta charset="UTF-8"/> <meta name="viewport" content="width=device-width, initial-scale=1.0"/> <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"/> <title>Document</title> </head> <body style="background: #eaebf0; color: #004d77;">{{-- <nav style="background-color: #004d77; align-items: center; padding: 20px 16px; display: flex; justify-content: space-between; color: #fff;"> </nav> --}}<div style="max-width: 1140px; margin: 0 auto;"> <div> @if ($content['type']=='do') <div style="background: #fff; border-radius: 8px; margin: 20px 10px; padding: 16px;">ATTN Trucker: Please Confirm DO Receipt</div>@endif <div style="align-items: center; margin: 20px 10px; display: flex;"> <div style="margin-right: 8px;"><img src="{!! asset('logo_icon_round.png') !!}" alt=""/></div><div style="font-weight: 450; font-size: 1.25rem;">Shipment Details</div></div><div style="background: #fff; border-radius: 8px; margin: 20px 10px; padding: 16px;"> <div style="padding: 0 8px; margin-bottom: 5px; margin: 5px 0; font-weight: 500;">Reference Number #{{$content['shifl_ref']}}</div><div style="border-top: 1.35px solid #004d77; margin-bottom: 1rem;"></div><div style="padding: 0 8px; margin: 5px 0;"><span style="font-weight: 500;"> Customer Name :</span>{{$content['customer']}}</div><div style="padding: 0 8px; margin: 5px 0;"> <span style="font-weight: 500;"> Location From :</span> @if (isset($content['selected_schedule']) && !empty($content['selected_schedule']) && isset($content['selected_schedule']->location_from) && !empty($content['selected_schedule']->location_from) ){{\App\TerminalRegion::findOrFail($content['selected_schedule']->location_from)->name}}@endif{{-- cause of commenting --}}{{-- was showing the location from empty every time --}}{{-- @php @if (isset($content['schedule']) && isset($content['schedule'][0]) && isset($content['schedule'][0]->location_from) && $content['schedule'][0]->location_from !==''){\App\TerminalRegion::findOrFail($content['schedule'][0]->location_from)->name;}@endphp --}}</div><div style="padding: 0 8px; margin: 5px 0;"> <span style="font-weight: 500;"> Estimated Time of Departure :</span> @if (isset($content['selected_schedule']) && !empty($content['selected_schedule']->etd) ){{\Carbon\Carbon::parse($content['selected_schedule']->etd)->format('D M d , Y')}}@endif</div>@if (isset($content['selected_schedule']) && !empty($content['selected_schedule'])) @if ($content['selected_schedule']->location_to ?? false) <div style="padding: 0 8px; margin: 5px 0;"><span style="font-weight: 500;"> Location To :</span>{{\App\TerminalRegion::find($content['selected_schedule']->location_to)->name}}</div>@endif @if ($content['selected_schedule']->eta ?? false) <div style="padding: 0 8px; margin: 5px 0;"><span style="font-weight: 500;"> Estimated Time of Arrival :</span>{{\Carbon\Carbon::parse($content['selected_schedule']->eta)->format('D M d , Y')}}</div>@endif @endif @if (!empty($content['terminal'])) @if ($content['terminal']->name) <div style="padding: 0 8px; margin: 5px 0;"><span style="font-weight: 500;"> Arrival Terminal :</span>{{$content['terminal']->name}}</div>@endif @if ($content['terminal']->firms_code ) <div style="padding: 0 8px; margin: 5px 0;"><span style="font-weight: 500;"> Firm Code :</span>{{$content['terminal']->firms_code}}</div>@endif @if ($content['terminal']->address) <div style="padding: 0 8px; margin: 5px 0;"><span style="font-weight: 500;"> Terminal Address :</span>{{$content['terminal']->address}}</div>@endif @endif @if (!empty($content['trucker'])) <div style="padding: 0 8px; margin: 5px 0;"><span style="font-weight: 500;"> Trucker :</span>{{$content['trucker']}}</div><div style="padding: 0 8px; margin: 5px 0;"><span style="font-weight: 500;">{{__('Delivery Address and Contact')}}:</span>{{$content['notes']}}</div>@endif @if ( isset($content['ais_link']) && $content['ais_link'] && !empty($content['ais_link']) ) <div style="padding: 0 8px; margin: 5px 0;"> <span style="font-weight: 500;"> Live Vessel Location : </span> <a href="{{$content['ais_link']}}" target="_blank" style="font-weight: 500; color: rgb(76, 131, 238);"> map </a> </div>@endif @if (isset($content['selected_schedule']) && !empty($content['selected_schedule']->carrier_name) ) <div style="padding: 0 8px; margin: 5px 0;"><span style="font-weight: 500;"> Carrier :</span>{{\App\Carrier::find( intval( $content['selected_schedule']->carrier_name->id ) )->name}}</div>@endif <div style="padding: 0 8px; margin: 5px 0;"><span style="font-weight: 500;"> Vessel :</span>{{$content['vessel']}}</div><div style="padding: 0 8px; margin: 5px 0;"><span style="font-weight: 500;"> MBL# :</span>{{$content['mbl_num']}}</div></div>@if ($content['suppliers_group']) <div style="background: #fff; border-radius: 8px; margin: 20px 10px; padding: 16px;"> <div style="padding: 0 8px; margin-bottom: 5px; margin: 5px 0; font-weight: 500;">Suppliers</div><div style="border-top: 1.35px solid #004d77; margin-bottom: 1rem;"></div><div class="card border-white"> @foreach ($content['suppliers_group'] as $supplier) <div style="background: #fff; border-radius: 8px; margin: auto 10px; padding: 16px auto; margin-bottom: 25px;"> <h4 class="ml-1">#{{$loop->index + 1}}</h4> <div class="card border-0 mx-2"> @if ($supplier->supplier_id) <div style="padding: 0 8px; margin: 5px 0;"><span style="font-weight: 500;"> Supplier Name :</span>{{($s=\App\Supplier::find($supplier->supplier_id)) ? $s->company_name : '__'}}</div>@endif @if ($supplier->po_num) <div style="padding: 0 8px; margin: 5px 0;"><span style="font-weight: 500;"> PO # :</span>{{$supplier->po_num}}</div>@endif @if ($supplier->cbm) <div style="padding: 0 8px; margin: 5px 0;"><span style="font-weight: 500;"> Volume :</span>{{$supplier->cbm}}</div>@endif @if ($supplier->kg) <div style="padding: 0 8px; margin: 5px 0;"><span style="font-weight: 500;"> Weight :</span>{{$supplier->kg}}</div>@endif @if ($supplier->total_cartons) <div style="padding: 0 8px; margin: 5px 0;"><span style="font-weight: 500;"> Total Cartons :</span>{{$supplier->total_cartons}}</div>@endif @if ($supplier->ams_num) <div style="padding: 0 8px; margin: 5px 0;"><span style="font-weight: 500;"> AMS :</span>{{$supplier->ams_num}}</div>@endif @if ($supplier->hbl_num) <div style="padding: 0 8px; margin: 5px 0;"><span style="font-weight: 500;"> HBL :</span>{{$supplier->hbl_num}}</div>@endif @if ($supplier->product_description) <div style="padding: 0 8px; margin: 5px 0;"><span style="font-weight: 500;"> Product Description :</span>{{$supplier->product_description}}</div>@endif @if ($supplier->incoterm_id) <div style="padding: 0 8px; margin: 5px 0;"><span style="font-weight: 500;"> Terms :</span>{{\App\Incoterm::findOrFail($supplier->incoterm_id)->name}}</div>@endif @if ($supplier->bl_status) <div style="padding: 0 8px; margin: 5px 0;"><span style="font-weight: 500;"> BL Status :</span>{{$supplier->bl_status}}</div>@endif </div></div>@endforeach </div></div>@endif <div style="background: #fff; border-radius: 8px; margin: 20px 10px; padding: 16px;"> <div style="padding: 0 8px; margin-bottom: 5px; margin: 5px 0; font-weight: 500;">Containers</div><div style="border-top: 1.35px solid #004d77; margin-bottom: 1rem;"></div><div class="card border-white"> @foreach ($content['containers_group'] as $container) <div style="background: #fff; border-radius: 8px; margin: auto 10px; padding: 16px auto; margin-bottom: 25px;"> <h4 class="ml-1">#{{$loop->index + 1}}</h4> <div class="card border-0 mx-2"> @if ($container->container_num) <div style="padding: 0 8px; margin: 5px 0;"><span style="font-weight: 500;"> Container # :</span>{{$container->container_num}}</div>@endif @if ($container->size) <div style="padding: 0 8px; margin: 5px 0;"><span style="font-weight: 500;"> Sizes :</span>{{\App\ContainerSize::findOrFail($container->size)->name}}</div>@endif @if ($container->cbm) <div style="padding: 0 8px; margin: 5px 0;"><span style="font-weight: 500;"> Volume(Number of CBM) :</span>{{$container->cbm}}</div>@endif @if ($container->kg) <div style="padding: 0 8px; margin: 5px 0;"><span style="font-weight: 500;"> Weight(Number of KG) :</span>{{$container->kg}}</div>@endif @if ($container->cartons) <div style="padding: 0 8px; margin: 5px 0;"><span style="font-weight: 500;"> Carton Count :</span>{{$container->cartons}}</div>@endif </div></div>@endforeach </div></div><div style="background: #fff; border-radius: 8px; margin: 20px 10px; padding: 16px;"> Check out the Attachments for essential documents. <hr/> Thanks <br/> Shifl <br/> </div></div></div><a href="http://shifl.com" target="_blank" style="text-decoration: none;"> <div style="background: #fff; text-align: center; padding: 1rem; color: #004d77;">©{{\Carbon\Carbon::now()->format('Y')}}Shifl. All rights reserved.</div></a> </body></html>
