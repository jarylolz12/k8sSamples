<div id="sidebar-wrapper" >

    <ul class="sidebar-nav endpoint_" style="width: 100%">
        <li class="hidden">
            <div style="padding-top: 20px;">
                <form action="#" method="GET">
                    <div class="row my-5" style="display: flex; justify-content: center;">
                        <div class="col-md-11">
                            <div class="input-group" style="display: flex;flex-direction: row;justify-content: space-evenly;align-items: center;margin: 0px 10px;">
                                <input type="text" class="form-control" placeholder="Search" id="txtSearch"/>

                                <button class="btn btn-primary" type="submit">
                                    <span class="glyphicon glyphicon-search"></span>
                                </button>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </li>
        <li>
            <a href="#intro">Introduction</a>
        </li>
        <li>
            <a href="#structure">Structure</a>
        </li>

        <li>
            <a href="#purchase-order"
               data-toggle="collapse"
               data-target="#purchase-order-endpoint"
               aria-expanded="false"
               aria-controls="purchase-order-endpoint"
               class="glyphicon1 icn-purchase-order-data"
               rel="icn-purchase-order-data"
            >
                <span class="icn-purchase-order-data">
                    {{ EndPointHelper::endPoint('Purchase Order') }}
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true">
                    </span>
                </span>
            </a>

            <ul class="endpoint_ collapse" id="purchase-order-endpoint">
                <li>
                    <a href="#get-purchase-order"> <span class="badge-success">GET</span> {{ EndPointHelper::get('Purchase Order') }} </a>
                </li>

                <li>
                    <a href="#create-purchase-order"
                       {{--data-toggle="collapse"--}}
                       {{--data-target="#create-purchase-order-endpoint"--}}
                       {{--aria-expanded="false"--}}
                       {{--aria-controls="create-purchase-order-endpoint"--}}

                       {{--class="glyphicon1 icn-create-purchase-order-data"--}}
                       {{--rel="icn-create-purchase-order-data"--}}
                    >
                        {{--<span class="icn-create-purchase-order-data">--}}
                        <span class="badge-primary">POST</span> {{ EndPointHelper::create('Purchase Order') }}
                            {{--<span class="glyphicon glyphicon-menu-right" aria-hidden="true">--}}
                            {{--</span>--}}
                        {{--</span>--}}
                    </a>
                    {{--<ul class="endpoint_ collapse" id="create-purchase-order-endpoint">--}}
                        {{--<li>--}}
                            {{--<a href="#shipment-by-container-number">--}}
                                {{--<span class="badge-primary">POST</span>--}}
                                {{--{{ EndPointHelper::get('Order Type By PO') }}--}}
                            {{--</a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<a href="#shipment-by-shifl-reference-number">--}}
                                {{--<span class="badge-primary">POST</span>--}}
                                {{--{{ EndPointHelper::get('Order Type By SO') }}--}}
                            {{--</a>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                </li>
                <li>
                    <a href="#update-purchase-order"> <span class="badge-info">PUT</span> {{ EndPointHelper::update('Purchase Order') }} </a>
                </li>
                <li>
                    <a href="#delete-purchase-order"> <span class="badge-danger">DELETE</span> {{ EndPointHelper::delete('Purchase Order') }} </a>
                </li>
            </ul>
        </li>

        <li>
            <a href="#how_to_create_client_credentials">How to create Client Credentials?</a>
        </li>
        <li>
            <a href="#support-shifl ">Support</a>
        </li>
        {{--<li>--}}
            {{--<a href="{{ route('api.services.dashboard') }}">{{ __('Dahsboard') }}</a>--}}
        {{--</li>--}}
        <li>
            <a href="?version=v1" style="font-weight: bolder;;color:{{ $version == 'v1' ? 'blue' : '' }};">v1</a>
        </li>

        <!-- <li>
            <a href="#plugins">Plugin's Usage</a>
        </li>
        <li>
            <a href="#credits">Credits</a>
        </li>
        <li>
            <a href="#support">Support</a>
        </li> -->
        <!-- <li><a href="#changelog">Change Log</a></li> -->
    </ul>


    <div id="resizer" style="cursor: ew-resize; float: right;  height: 100%;  width: 8px; background-color: transparent; position:relative"></div>



</div>
<!-- /#sidebar-wrapper -->


