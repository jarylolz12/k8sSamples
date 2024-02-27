<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

        <title>Document</title>
        <style media="screen">
            html {
                width: 100%;
                height: 100%;
                padding-top: 100px;
                margin: 0;
                margin-top: 20px;


                /* zoom: 120%; */
            }

            * {
                line-height: 1.2;
            }

            header {
                position: fixed;
                left: 0px;
                top: -20px;
                right: 0px;
                height: 20px;
                text-align: center;

                background: #eaebf0;
            }


            div {
                font-size: 13px
            }
        </style>
    </head>

    <body style="background: #eaebf0 ;color: #004d77 ;">
        <header>
        </header>

        <div style="max-width:1140px; margin: 0 auto;">


            <div>

                <div style="align-items: center;margin:0px 0px;text-align:center; display: flex;">
                    <span style="font-weight: 450; font-size: 1.25rem;">
                        Arrival Notice #{{ $content['shifl_ref'] }}
                    </span>
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

                        {{ \App\TerminalRegion::findOrFail($content['schedule'][0]->location_from) ? \App\TerminalRegion::findOrFail($content['schedule'][0]->location_from)->name : '' }}

                        @endif
                        @endif --}}

                        @if (isset($content['selected_schedule']) && !empty($content['selected_schedule']) && isset($content['selected_schedule']->location_from) && !empty($content['selected_schedule']->location_from) )

                        {{ \App\TerminalRegion::findOrFail($content['selected_schedule']->location_from) ? \App\TerminalRegion::findOrFail($content['selected_schedule']->location_from)->name : '' }}

                        @endif



                    </div>


                    <div style="padding: 0 8px; margin: 5px 0">
                        <span style="font-weight:500; "> Estimated Time of Departure :</span>
                        {{-- @if (isset($content['schedule']) && isset($content['schedule'][0]))
                        {{ \Carbon\Carbon::parse($content['schedule'][0]->etd)->format('D M d , Y') }}
                        @endif --}}

                        @if (isset($content['selected_schedule']) && !empty($content['selected_schedule']->etd) )
                        {{ \Carbon\Carbon::parse($content['selected_schedule']->etd)->format('D M d , Y') }}
                        @endif

                    </div>

                    {{-- @if (isset($content['schedule']) && isset($content['schedule'][0]))
                    @foreach ($content['schedule'] as $schedule)
                    @if ($schedule->location_to)

                    <div style="padding: 0 8px; margin: 5px 0">
                        <span style="font-weight:500; "> Location To :</span> {{ \App\TerminalRegion::find($schedule->location_to) ? \App\TerminalRegion::find($schedule->location_to)->name : '' }}
                </div>
                @endif
                @if ($schedule->eta)

                <div style="padding: 0 8px; margin: 5px 0">
                    <span style="font-weight:500; "> Estimated Time of Arrival :</span> {{ \Carbon\Carbon::parse($schedule->eta)->format('D M d , Y') }}
                </div>
                @endif

                @endforeach
                @endif --}}


                @if (isset($content['selected_schedule']) && !empty($content['selected_schedule']))


                @if ($content['selected_schedule']->location_to ?? false)

                <div style="padding: 0 8px; margin: 5px 0">
                    <span style="font-weight:500; "> Location To :</span> {{ \App\TerminalRegion::find($content['selected_schedule']->location_to) ? \App\TerminalRegion::find($content['selected_schedule']->location_to)->name : '' }}
                </div>

                @endif

                @if ($content['selected_schedule']->eta ?? false)

                <div style="padding: 0 8px; margin: 5px 0">
                    <span style="font-weight:500; "> Estimated Time of Arrival :</span> {{ \Carbon\Carbon::parse($content['selected_schedule']->eta)->format('D M d , Y') }}
                </div>

                @endif



                @endif


                @if (!empty($content['terminal']))

                @if (isset($content['terminal']->name))

                <div style="padding: 0 8px; margin: 5px 0">
                    <span style="font-weight:500; "> Arrival Terminal :</span> {{ $content['terminal']->name }}
                </div>

                @endif

                @if ($content['terminal']->firms_code )

                <div style="padding: 0 8px; margin: 5px 0">
                    <span style="font-weight:500; "> Firm Code :</span> {{ $content['terminal']->firms_code }}
                </div>

                @endif
                @if ($content['terminal']->address)

                <div style="padding: 0 8px; margin: 5px 0">
                    <span style="font-weight:500; "> Terminal Address :</span> {{ $content['terminal']->address }}
                </div>
                @endif

                @endif

                @if (!empty($content['trucker']))
                <div style="padding: 0 8px; margin: 5px 0">
                    <span style="font-weight:500; "> Trucker :</span> {{ $content['trucker'] }}
                </div>

                <div style="padding: 0 8px; margin: 5px 0">
                    <span style="font-weight:500; ">
                        {{ __('Delivery Address and Contact') }}:</span> {{ $content['notes'] }}
                </div>


                @endif


                <div style="padding: 0 8px; margin: 5px 0">
                    <span style="font-weight:500; "> Carrier :</span>
                    @if (isset($content['selected_schedule']) && !empty($content['selected_schedule']->carrier_name) )

                    {{ \App\Carrier::find( intval( $content['selected_schedule']->carrier_name->id ) ) ? \App\Carrier::find( intval( $content['selected_schedule']->carrier_name->id ) )->name : '' }}

                    @endif
                </div>
                <div style="padding: 0 8px; margin: 5px 0">
                    <span style="font-weight:500; "> Vessel :</span> {{ $content['vessel'] }}
                </div>
                <div style="padding: 0 8px; margin: 5px 0">
                    <span style="font-weight:500; "> MBL# :</span> {{ $content['mbl_num'] }}
                </div>



            </div>




            <div class="" style="background: #fff ;  border-radius: 8px ;margin:20px 10px; padding: 16px">
                <div style="padding: 0 8px; margin-bottom: 20px;margin: 5px 0; font-weight: 500">
                    Suppliers
                </div>
                <div style="border-top:1.35px solid #004d77; "></div>
                <div class="">
                    @foreach ($content['suppliers_group'] as $supplier)
                    <div class="" style=" margin-left: 10px; margin-bottom: 15px;margin-top:15px">
                        <h4 style="font-size:17px">#{{ $loop->index + 1 }}</h4>
                        <div style="margin-left:10px">


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
                                <span style="font-weight:500; "> Terms :</span> {{ \App\Incoterm::findOrFail($supplier->incoterm_id) ? \App\Incoterm::findOrFail($supplier->incoterm_id)->name : '' }}
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





            <div class="" style="background: #fff ;  border-radius: 8px ;margin:20px 10px; padding: 16px">




                <div style="padding: 0 8px; margin-bottom: 20px;margin: 5px 0; font-weight: 500">
                    Containers
                </div>
                <div style="border-top:1.35px solid #004d77; "></div>
                <div class="">
                    @foreach ($content['containers_group'] as $container)

                    <div class="" style=" margin-left: 10px; margin-bottom: 15px;margin-top:15px">
                        <h4 style="font-size:17px">#{{ $loop->index + 1 }}</h4>
                        <div style="margin-left:10px">

                            @if ($container->container_num)
                            <div style="padding: 0 8px; margin: 5px 0">
                                <span style="font-weight:500; "> Container # :</span> {{ $container->container_num }}
                            </div>
                            @endif
                            @if ($container->size)
                            <div style="padding: 0 8px; margin: 5px 0">
                                <span style="font-weight:500; "> Sizes :</span> {{ \App\ContainerSize::findOrFail($container->size) ? \App\ContainerSize::findOrFail($container->size)->name : '' }}
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

            {{-- @if ($content['suppliers_group'])

                <div style="background: #fff ;  border-radius: 8px ;margin:20px 10px; padding: 16px">
                    <div style="padding: 0 8px; margin-bottom: 5px;margin: 5px 0; font-weight: 500">
                        Suppliers
                    </div>
                    <div style="border-top:1.35px solid #004d77; margin-bottom: 1rem"></div>

                    <div class="card border-white">
                        @foreach ($content['suppliers_group'] as $supplier)


                        <div style="background: #fff ;  border-radius: 8px ;margin:auto 10px; padding: 16px auto ; margin-bottom: 25px">

                            <h4 class="ml-1">#{{ $loop->index + 1 }}</h4>
            <div class="card border-0 mx-2">

                @if ($supplier->supplier_id)
                <div style="padding: 0 8px; margin: 5px 0">
                    <span style="font-weight:500; "> Supplier Name :</span> {{ \App\Supplier::findOrFail($supplier->supplier_id)->company_name }}
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
                    <span style="font-weight:500; "> Terms :</span> {{ \App\Incoterm::findOrFail($supplier->incoterm_id) ? \App\Incoterm::findOrFail($supplier->incoterm_id)->name : '' }}
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

        --}}





        {{--


                <div style="background: #fff ;  border-radius: 8px ;margin:20px 10px; padding: 16px">
                    <div style="padding: 0 8px; margin-bottom: 5px;margin: 5px 0; font-weight: 500">
                        Containers
                    </div>
                    <div style="border-top:1.35px solid #004d77; margin-bottom: 1rem"></div>

                    <div class="card border-white">
                        @foreach ($content['containers_group'] as $container)


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
                <span style="font-weight:500; "> Sizes :</span> {{ \App\ContainerSize::findOrFail($container->size) ? \App\ContainerSize::findOrFail($container->size)->name : '' }}
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


        --}}





        <div style="background: #fff ;  border-radius: 8px ; margin:20px 10px; padding: 16px">
            Check out the Attachments for essential documents.
            <hr>
            Thanks <br>
            Shifl <br>


        </div>
        </div>
        </div>


    </body>

</html>