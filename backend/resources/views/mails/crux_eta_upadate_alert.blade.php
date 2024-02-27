<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

        <title>Eta update alert</title>
    </head>

    <body style="background: #eaebf0 ;color: #004d77 ;">

        <div style="max-width:1140px; margin: 0 auto;">


            <div>



                <div style="background: #fff ;  border-radius: 8px ;margin:20px 10px; padding: 16px">
                    Please Note : The ETA of the Shipment has been updated from - {{ $content["old_eta"] ? \Carbon\Carbon::parse($content["old_eta"])->format('D M d , Y') : "--" }} to -
                    {{-- @if (isset($content['schedule']) && isset($content['schedule'][0]))
                        {{ \Carbon\Carbon::parse($content["schedule"][0]->eta)->format('D M d , Y') }}
                    @endif --}}
                    @if (isset($content['schedule']))
                    {{ \Carbon\Carbon::parse($content["schedule"]->eta)->format('D M d , Y') }}
                    @endif

                </div>




                <div style="align-items: center;margin:20px 10px; display: flex">
                    <div style="margin-right: 8px">
                        <img src="{!! asset('logo_icon_round.png') !!}" alt="">
                    </div>
                    <div style="font-weight: 450; font-size: 1.25rem">
                        Shipment Details
                    </div>
                </div>



                <div style="background: #fff ;  border-radius: 8px ;margin:20px 10px; padding: 16px">
                    <div style="padding: 0 8px; margin-bottom: 5px;margin: 5px 0; font-weight: 500">
                        Reference Number #{{ $content['shifl_ref'] }}
                    </div>
                    <div style="border-top:1.35px solid #004d77; margin-bottom: 1rem"></div>
                    <div style="padding: 0 8px; margin: 5px 0">
                        <span style="font-weight:500; "> Customer Name :</span> {{ $content['customer'] }}
                    </div>
                    <div style="padding: 0 8px; margin: 5px 0">
                        <span style="font-weight:500; "> Location From :</span>

                        {{-- @if (isset($content['schedule']) && isset($content['schedule'][0]))
                            @if ($content['schedule'][0] && $content['schedule'][0]->location_from)

                            {{ \App\TerminalRegion::findOrFail($content['schedule'][0]->location_from)->name }}

                        @endif
                        @endif --}}
                        @if (isset($content['schedule']) )
                        @if ($content['schedule']&& $content['schedule']->location_from)

                        {{ \App\TerminalRegion::findOrFail($content['schedule']->location_from)->name }}

                        @endif
                        @endif

                    </div>
                    <div style="padding: 0 8px; margin: 5px 0">
                        <span style="font-weight:500; "> Estimated Time of Departure :</span>
                        {{-- @if (isset($content['schedule']) && isset($content['schedule'][0]))
                            @if ($content['schedule'][0] && $content['schedule'][0]->location_from)

                            {{ \Carbon\Carbon::parse($content['schedule'][0]->etd)->format('D M d , Y') }}

                        @endif
                        @endif --}}
                        @if (isset($content['schedule']))
                        @if ($content['schedule'] && $content['schedule']->etd)

                        {{ \Carbon\Carbon::parse($content['schedule']->etd)->format('D M d , Y') }}

                        @endif
                        @endif


                    </div>

                    @if (isset($content['schedule']) )

                    @if ($content['schedule']->location_to)

                    <div style="padding: 0 8px; margin: 5px 0">
                        <span style="font-weight:500; "> Location To :</span> {{ \App\TerminalRegion::find($content['schedule']->location_to)->name  }}
                    </div>
                    @endif
                    @if ($content['schedule']->eta)

                    <div style="padding: 0 8px; margin: 5px 0">
                        <span style="font-weight:500; "> Estimated Time of Arrival :</span> {{ \Carbon\Carbon::parse($content['schedule']->eta)->format('D M d , Y') }}
                    </div>
                    @endif

                    @endif

                    @if ($content['ais_link'])

                    <div style="padding: 0 8px; margin: 5px 0">
                        <span style="font-weight:500; "> Live Vessel Location : </span>
                        <a href="{{ $content['ais_link'] }}" target="_blank" style="font-weight: 500; color: rgb(76, 131, 238);"> map </a>
                    </div>

                    @endif

                    <div style="padding: 0 8px; margin: 5px 0">
                        <span style="font-weight:500; "> Carrier :</span>

                        @if (isset($content['schedule']) && !empty($content['schedule']->carrier_name) && isset($content['schedule']->carrier_name->id) )

                        {{ \App\Carrier::find( intval( $content['schedule']->carrier_name->id ) )->name ?? '' }}

                        @endif

                    </div>
                    <div style="padding: 0 8px; margin: 5px 0">
                        <span style="font-weight:500; "> Vessel :</span> {{ $content['vessel'] }}
                    </div>
                    <div style="padding: 0 8px; margin: 5px 0">
                        <span style="font-weight:500; "> MBL# :</span> {{ $content['mbl_num'] }}
                    </div>



                </div>



                @if ($content['suppliers_group'] && !$content['is_for_trucker'])

                <div style="background: #fff ;  border-radius: 8px ;margin:20px 10px; padding: 16px">
                    <div style="padding: 0 8px; margin-bottom: 5px;margin: 5px 0; font-weight: 500">
                        Suppliers
                    </div>
                    <div style="border-top:1.35px solid #004d77; margin-bottom: 1rem"></div>

                    <div class="card border-white">
                        @foreach ($content['suppliers_group'] ?? [] as $supplier)


                        <div style="background: #fff ;  border-radius: 8px ;margin:auto 10px; padding: 16px auto ; margin-bottom: 25px">

                            <h4 class="ml-1">#{{ $loop->index + 1 }}</h4>
                            <div class="card border-0 mx-2">

                                @if ($supplier->supplier_id)
                                <div style="padding: 0 8px; margin: 5px 0">
                                    <span style="font-weight:500; "> Supplier Name :</span> {{ ($s = \App\Supplier::find($supplier->supplier_id)) ? $s->company_name : '__' }}
                                </div>
                                @endif

                                @if ($supplier->po_num)
                                <div style="padding: 0 8px; margin: 5px 0">
                                    <span style="font-weight:500; "> PO # :</span> {{ $supplier->po_num }}
                                </div>
                                @endif

                                @if ($supplier->cbm)
                                <div style="padding: 0 8px; margin: 5px 0">
                                    <span style="font-weight:500; "> Volume :</span> {{ $supplier->cbm }}
                                </div>
                                @endif

                                @if ($supplier->kg)
                                <div style="padding: 0 8px; margin: 5px 0">
                                    <span style="font-weight:500; "> Weight :</span> {{ $supplier->kg }}
                                </div>
                                @endif
                                @if ($supplier->total_cartons)

                                <div style="padding: 0 8px; margin: 5px 0">
                                    <span style="font-weight:500; "> Total Cartons :</span> {{ $supplier->total_cartons }}
                                </div>
                                @endif
                                @if ($supplier->ams_num)

                                <div style="padding: 0 8px; margin: 5px 0">
                                    <span style="font-weight:500; "> AMS :</span> {{ $supplier->ams_num }}
                                </div>
                                @endif

                                @if ($supplier->hbl_num)

                                <div style="padding: 0 8px; margin: 5px 0">
                                    <span style="font-weight:500; "> HBL :</span> {{ $supplier->hbl_num }}
                                </div>
                                @endif

                                @if ($supplier->product_description)

                                <div style="padding: 0 8px; margin: 5px 0">
                                    <span style="font-weight:500; "> Product Description :</span> {{ $supplier->product_description }}
                                </div>
                                @endif

                                @if ($supplier->incoterm_id)

                                <div style="padding: 0 8px; margin: 5px 0">
                                    <span style="font-weight:500; "> Terms :</span> {{ \App\Incoterm::findOrFail($supplier->incoterm_id)->name }}
                                </div>
                                @endif

                                @if ($supplier->bl_status)

                                <div style="padding: 0 8px; margin: 5px 0">
                                    <span style="font-weight:500; "> BL Status :</span> {{ $supplier->bl_status }}
                                </div>
                                @endif

                            </div>

                        </div>

                        @endforeach

                    </div>

                </div>
                @endif




                <div style="background: #fff ;  border-radius: 8px ;margin:20px 10px; padding: 16px">
                    <div style="padding: 0 8px; margin-bottom: 5px;margin: 5px 0; font-weight: 500">
                        Containers
                    </div>
                    <div style="border-top:1.35px solid #004d77; margin-bottom: 1rem"></div>

                    <div class="card border-white">
                        @foreach ($content['containers_group'] ?? [] as $container)


                        <div style="background: #fff ;  border-radius: 8px ;margin:auto 10px; padding: 16px auto ; margin-bottom: 25px">

                            <h4 class="ml-1">#{{ $loop->index + 1 }}</h4>
                            <div class="card border-0 mx-2">


                                @if ($container->container_num)
                                <div style="padding: 0 8px; margin: 5px 0">
                                    <span style="font-weight:500; "> Container # :</span> {{ $container->container_num }}
                                </div>
                                @endif
                                @if ($container->size)
                                <div style="padding: 0 8px; margin: 5px 0">
                                    <span style="font-weight:500; "> Sizes :</span> {{ \App\ContainerSize::findOrFail($container->size)->name  }}
                                </div>
                                @endif

                                @if ($container->cbm)
                                <div style="padding: 0 8px; margin: 5px 0">
                                    <span style="font-weight:500; "> Volume(Number of CBM) :</span> {{ $container->cbm }}
                                </div>
                                @endif

                                @if ($container->kg)
                                <div style="padding: 0 8px; margin: 5px 0">
                                    <span style="font-weight:500; "> Weight(Number of KG) :</span> {{ $container->kg }}
                                </div>
                                @endif
                                @if ($container->cartons)

                                <div style="padding: 0 8px; margin: 5px 0">
                                    <span style="font-weight:500; "> Carton Count :</span> {{ $container->cartons }}
                                </div>
                                @endif


                            </div>

                        </div>

                        @endforeach

                    </div>

                </div>








                <div style="background: #fff ;  border-radius: 8px ; margin:20px 10px; padding: 16px">
                    Check out the Attachments for essential documents.
                    <hr>
                    Thanks <br>
                    Shifl <br>


                </div>
            </div>
        </div>

        <a href="http://shifl.com" target="_blank" style="text-decoration:none;">
            <div style="background:#fff; text-align:center; padding: 1rem; color:#004d77">
                © {{ \Carbon\Carbon::now()->format('Y') }} Shifl. All rights reserved.
            </div>
        </a>
    </body>

</html>
