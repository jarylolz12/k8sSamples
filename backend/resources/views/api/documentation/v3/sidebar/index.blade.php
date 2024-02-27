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
            <a
                    href="#shipment"
                    data-toggle="collapse"
                    data-target="#shipment-endpoint"
                    aria-expanded="false"
                    aria-controls="shipment-endpoint"
            >
                {{ EndPointHelper::endPoint('Shipment') }}
            </a>
            <ul class="endpoint_ collapse" id="shipment-endpoint">
                <li>
                    <a href="#shipment-by-container-number"><span class="badge-success">GET</span> {{ EndPointHelper::get('Shipment by Container Number') }} </a>
                </li>
                <li>
                    <a href="#shipment-by-shifl-reference-number"><span class="badge-success">GET</span> {{ EndPointHelper::get('Shipment by Shifl Reference Number') }}</a>
                </li>
                <li>
                    <a href="#shipment-by-shifl-reference-number-advance"><span class="badge-success">GET</span>  {{ EndPointHelper::get('Shipment by Shifl Reference Number Advanced') }}</a>
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


