<div id="available-client">
    <div class="col-sm-12" >
        <h3 class="page-header">Available Client Api</h3>
    </div>
    <div class="col-sm-12 p-t-30 " id="get_access_token">
        <h3>Get Access Token</h3>
        <p>
            As mentioned above, Shifl Client Api is a token based api. For Every api request client has to provide validate Access token. Client will get the token by oauth token endpoint.
        </p>
        <br>
        <div class="" style="position:relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b> Route: </b> &nbsp;

            <span class="glyphicon1" rel="i-oauth-token" aria-hidden="true">
                <span
                        data-toggle="collapse"
                        data-target="#route-oauth-token"
                        aria-expanded="false"
                        aria-controls="route-oauth-token"
                        style="cursor: pointer"
                        class="i-oauth-token"
                > <code>/oauth/token</code>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true">
                        </span>
                </span>
              </span>
            <div class="collapse api-route" id="route-oauth-token">
                <div class="badge-success oauth-token hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="oauth-token">Copy</div>
                </div>

                <div class="api_ noselect" id="oauth-token">
                    {{ config('app.url')}}/oauth/token
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>NONE</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>grant_type</code> , <code>client_id</code> , <code>client_secret</code> ,<code>scope</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Authentication Token </code> <small> (Bearer) </small><br>
        </div>
        <h4>Header</h4> <br>
        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"Accept"</span> : <span class="slctrl-string"> "application/json" </span>
{ </pre>
        <h4 style="margin-top: 25px">Form Data </h4> <br>
        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"grant_type"</span> : <span class="slctrl-string"> "{client_credentials}" </span>
    <span class="slctrl-attr">"client_id"</span> : <span class="slctrl-string"> "{client-id}" </span>
    <span class="slctrl-attr">"client_secret"</span> : <span class="slctrl-string"> "{client-secret}" </span>
    <span class="slctrl-attr">"scope"</span> : <span class="slctrl-string"> "{scope}" </span>
} </pre>
        <small> Here scope is by default "*" </small>
        <div>
            <div>
                <h4 style="display: inline-block">REQUEST</h4>
            </div>
            <table class="table-bordered" style="width: 50%">
                <thead>
                    <tr>
                        <th colspan="2">URL Parameters</th>
                    </tr>
                    <tr>
                        <th>Attribute</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>grant_type<span class="badge-danger">required</span></td>
                        <td><i>String</i> Client Credentials</td>
                    </tr>
                    <tr>
                        <td>client_id<span class="badge-danger">required</span></td>
                        <td><i>String</i> Unique identifier for the Client</td>
                    </tr>
                    <tr>
                        <td>client_secret<span class="badge-danger">required</span></td>
                        <td><i>String</i> Client secret token</td>
                    </tr>
                    <tr>
                        <td>scope<span class="badge-danger">required</span></td>
                        <td><i>String</i> Scope</td>
                    </tr>
                </tbody>
            </table>
        </div>
    <h4>Response
        <span
            data-toggle="collapse"
            data-target="#oauth-token-response"
            aria-expanded="false"
            aria-controls="oauth-token-response"
            style="cursor: pointer; top: 2px !important;"
            class="glyphicon glyphicon-eye-open">
        </span>
    </h4>
   <pre class="collapse" id="oauth-token-response" >
{
    <span class="slctrl-attr">"token_type"</span> : <span class="slctrl-string"> "Bearer"</span>
    <span class="slctrl-attr">"expires_in"</span> : <span class="slctrl-number"> 300 </span>
    <span class="slctrl-attr">"access_token"</span> : <span class="slctrl-string"> "{access_token}" </span>
} </pre>
    </div>

<div class="col-sm-12 p-t-30 " id="get_shipment_by_po">
        <h3>Get Shipments By PO</h3>
        <p>
            By this endpoint client can access their shipment's details. To access their shipment's details , they have to provide po. And the endpoint will return all their shipments regarding the given po.
        </p>
        <br>
        <div class="" style="position: relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;


            <span class="glyphicon1" rel="i-client-v1-shipment-by-po-po_num" aria-hidden="true">
                <span
                        data-toggle="collapse"
                        data-target="#route-client-v1-shipment-by-po-po_num"
                        aria-expanded="false"
                        aria-controls="route-client-v1-shipment-by-po-po_num"
                        style="cursor: pointer"
                        class="i-client-v1-shipment-by-po-po_num"
                >
                    <code>api/client/v1/shipment-by-po/{po_num}</code>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true">
                        </span>
                </span>
            </span>
            <div class="collapse api-route" id="route-client-v1-shipment-by-po-po_num">
                <div class="badge-success client-v1-shipment-by-po-po_num hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>

                    <div  class="copy-api" rel="client-v1-shipment-by-po-po_num">Copy</div>
                </div>

                <div class="api_ noselect" id="client-v1-shipment-by-po-po_num">
                    {{ config('app.url')}}/api/client/v1/shipment-by-po/{po_num}
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>po_num</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Shipments Detail </code> <small> </small><br>
        </div>
        <h4>Header</h4> <br>
        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"Authorization"</span> : <span class="slctrl-string">"Bearer {access_token}"</span>
    <span class="slctrl-attr">"Content-Type"</span> : <span class="slctrl-string"> "application/json" </span>
    <span class="slctrl-attr">"Accept"</span> : <span class="slctrl-string"> "application/json" </span>
} </pre>
        <h4 style="margin-top: 25px">Form Data </h4> <br>
        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"po_num"</span> : <span class="slctrl-string">"{po_num}"</span>
} </pre>
        <div>
            <div>
                <h4 style="display: inline-block">REQUEST</h4>
            </div>
            <table class="table-bordered"  style="width: 50%">
                <thead>
                <th colspan="2">URL Parameters</th>
                </thead>
                <thead>
                <th>Attribute</th>
                <th>Description</th>
                </thead>
                <tbody>
                <tr>
                    <td>po_num<span class="badge-danger">required</span></td>
                    <td><i>String</i> The Purchase Order Number for the Shipment by to be retrieved data</td>
                </tr>
                </tbody>
            </table>
        </div>
        <h4>Response
            <span
                data-toggle="collapse"
                data-target="#client-v1-shipment-by-po-po_num-response"
                aria-expanded="false"
                aria-controls="client-v1-shipment-by-po-po_num-response"
                style="cursor: pointer; top: 2px !important;"
                class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre class="collapse" id="client-v1-shipment-by-po-po_num-response" >
{
    <span class="slctrl-attr">"data"</span>: [
        {
            <span class="slctrl-attr">"id"</span>: <span class="slctrl-string">"{id}"</span>,
            <span class="slctrl-attr">"shifl_ref"</span>: <span class="slctrl-string">"{shifl_ref}"</span>,
            <span class="slctrl-attr">"customer"</span>: <span class="slctrl-string">"{customer_name}"</span>,
            <span class="slctrl-attr">"mbl_num"</span>: <span class="slctrl-string">"{mbl_number}"</span>,
            <span class="slctrl-attr">"is_tracking_shipment"</span>: <span class="slctrl-string">"{true/false}"</span>,
            <span class="slctrl-attr">"vessel"</span>: <span class="slctrl-string">"{vessel_name}"</span>,
            <span class="slctrl-attr">"booking_confirmed"</span>: <span class="slctrl-string">"{true/false}"</span>,
            <span class="slctrl-attr">"booking_confirmed_at"</span>: <span class="slctrl-string">"{booking_confirmed_date}"</span>,
            <span class="slctrl-attr">"schedules"</span>: {
                <span class="slctrl-attr">"etd"</span>: <span class="slctrl-string">"{estimated_time_of_departure}"</span>,
                <span class="slctrl-attr">"eta"</span>: <span class="slctrl-string">"{estimated_time_of_arrival}"</span>,
                <span class="slctrl-attr">"location_from"</span>: <span class="slctrl-string">"{location_from_name}"</span>,
                <span class="slctrl-attr">"location_to"</span>: <span class="slctrl-string">"{location_to_name}"</span>,
                <span class="slctrl-attr">"mode"</span>: <span class="slctrl-string">"{Ocean/Air/Rail/Truck}"</span>,
                <span class="slctrl-attr">"carrier"</span>: <span class="slctrl-string">"{carrier_name}"</span>,
                <span class="slctrl-attr">"type"</span>: <span class="slctrl-string">"{FCL/LCL/Air}"</span>,
                <span class="slctrl-attr">"legs"</span>: [
                    {
                        <span class="slctrl-attr">"mode"</span>: <span class="slctrl-string">"{Ocean/Air/Rail/Truck}"</span>,
                        <span class="slctrl-attr">"eta"</span>: <span class="slctrl-string">"{estimated_time_of_arrival}"</span>,
                        <span class="slctrl-attr">"location_to"</span>: <span class="slctrl-string">"{location_to_name}"</span>
                    },
                    {...}, ...
                ]
            },
            <span class="slctrl-attr">"location_to"</span>: [
                {
                    <span class="slctrl-attr">"supplier_name"</span>: <span class="slctrl-string">"{supplier_name}"</span>,
                    <span class="slctrl-attr">"cargo_ready_date"</span>: <span class="slctrl-string">"{date}"</span>,
                    <span class="slctrl-attr">"po_num_old"</span>: <span class="slctrl-string">"{po_number}"</span>,
                    <span class="slctrl-attr">"selected_po"</span>: [
                        {
                            <span class="slctrl-attr">"po_number"</span>: <span class="slctrl-string">"{po_number}"</span>,
                            <span class="slctrl-attr">"products"</span>: [
                                {
                                    <span class="slctrl-attr">"sku"</span>: <span class="slctrl-string">"{product_sku}"</span>,
                                    <span class="slctrl-attr">"name"</span>: <span class="slctrl-string">"{product_name}"</span>,
                                    <span class="slctrl-attr">"total_cartons"</span>: <span class="slctrl-string">"{total_carton_in_the_PO}"</span>,
                                    <span class="slctrl-attr">"to_ship_cartons"</span>: <span class="slctrl-string">"{to_ship_cartons}"</span>,
                                    <span class="slctrl-attr">"units_per_carton"</span>: <span class="slctrl-string">"{units_per_carton}"</span>
                                },
                                {...}, ...
                            ]
                        },
                        {...}, ...
                    ],
                    <span class="slctrl-attr">"weight"</span>: <span class="slctrl-string">"{number_of_kg}"</span>,
                    <span class="slctrl-attr">"volume"</span>: <span class="slctrl-string">"{number_of_cbm}"</span>,
                    <span class="slctrl-attr">"total_cartons"</span>: <span class="slctrl-string">"{total_cartons}"</span>,
                    <span class="slctrl-attr">"ams"</span>: <span class="slctrl-string">"{ams}"</span>,
                    <span class="slctrl-attr">"hbl"</span>: <span class="slctrl-string">"{hbl}"</span>,
                    <span class="slctrl-attr">"marks"</span>: <span class="slctrl-string">"{marks}"</span>,
                    <span class="slctrl-attr">"product_description"</span>: <span class="slctrl-string">"{id}"</span>,
                    <span class="slctrl-attr">"terms"</span>: <span class="slctrl-string">"{id}"</span>,
                    <span class="slctrl-attr">"bl_status"</span>: <span class="slctrl-string">"{Pending/Telex Released/Original Received}"</span>,
                    <span class="slctrl-attr">"hbl_copy"</span>: <span class="slctrl-string">"{file_path}"</span>,
                    <span class="slctrl-attr">"packing_list"</span>: <span class="slctrl-string">"{file_path}"</span>,
                    <span class="slctrl-attr">"commercial_documents"</span>: <span class="slctrl-string">"{file_path}"</span>,
                    <span class="slctrl-attr">"commercial_invoice"</span>: <span class="slctrl-string">"{file_path}"</span>
                },
                {...}, ...
            ],
            <span class="slctrl-attr">"containers"</span>: [
                {
                    <span class="slctrl-attr">"container_num"</span>: <span class="slctrl-string">"{container_number}"</span>,
                    <span class="slctrl-attr">"size"</span>: <span class="slctrl-string">"{container_size}"</span>,
                    <span class="slctrl-attr">"seal_num"</span>: <span class="slctrl-string">"{seal_number}"</span>,
                    <span class="slctrl-attr">"weight"</span>: <span class="slctrl-string">"{number_of_kg}"</span>,
                    <span class="slctrl-attr">"volume"</span>: <span class="slctrl-string">"{number_of_cbm}"</span>,
                    <span class="slctrl-attr">"carton_count"</span>: <span class="slctrl-string">"{carton_count}"</span>
                },
                {...}, ...
            ],
            <span class="slctrl-attr">"terminal"</span>: {
                <span class="slctrl-attr">"name"</span>: <span class="slctrl-string">"{terminal_name}"</span>,
                <span class="slctrl-attr">"firms_code"</span>: <span class="slctrl-string">"{terminal_firms_code}"</span>,
            },
            <span class="slctrl-attr">"customs_documents"</span>: [
                 {
                     <span class="slctrl-attr">"supplier_name"</span>: <span class="slctrl-string">"{supplier_name}"</span>,
                     <span class="slctrl-attr">"files"</span>: [
                         {
                             <span class="slctrl-attr">"name"</span>: <span class="slctrl-string">"{file_name}"</span>,
                             <span class="slctrl-attr">"path"</span>: <span class="slctrl-string">"{file_path}"</span>,
                         },
                         {...}, ...
                     ]
                 },
                 {...}, ...
             ]
         },
         {...}, ...
    ]
} </pre>
        @include('api.documentation.v2.endpoints.shipment-central.client.response.shipment_by_po')
        <p>
            {{ EndPointHelper::userGuide() }}
        </p>
</div>

<div class="col-sm-12 p-t-30 " id="get_shipment_status">
    <h3>Get Shipment Status</h3>
    <p>
        As mentioned above, Shifl Client Api is a token based api.
        For Every api request client has to provide validate Access token.
        Client will get the token by oauth token endpoint.
    </p>
    <br>
    <div class="" style="position: relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
        <b>Route: </b> &nbsp;
        <span class="glyphicon1" rel="i-client-v1-shipment-status-shipment-id" aria-hidden="true">
            <span
                    data-toggle="collapse"
                    data-target="#route-client-v1-shipment-status-shipment-id"
                    aria-expanded="false"
                    aria-controls="route-client-v1-shipment-status-shipment-id"
                    style="cursor: pointer"
                    class="i-client-v1-shipment-status-shipment-id"
            >
                <code>api/client/v1/shipment/status/{shipment_id}</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true">
                    </span>
            </span>
        </span>
            <div class="collapse api-route" id="route-client-v1-shipment-status-shipment-id">
                <div class="badge-success client-v1-shipment-status-shipment-id hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="client-v1-shipment-status-shipment-id">Copy</div>
                </div>
                <div class="api_ noselect" id="client-v1-shipment-status-shipment-id">
                    {{ config('app.url')}}/api/client/v1/shipment/status/{shipment_id}
                </div>
            </div>
        <br>
        <b>Request Type: &nbsp;</b> <code>GET</code> <br>
        <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
        <b>Body Form Data required: &nbsp;</b> <code>NONE</code> <br>
        <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
        <b>Will Return: &nbsp;</b> <code>Shipments Status (per Container) </code> <small> </small><br>
    </div>
    <h4>Header</h4> <br>
    <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"Authorization"</span> : <span class="slctrl-string">"Bearer {access_token}"</span>
    <span class="slctrl-attr">"Content-Type"</span> : <span class="slctrl-string"> "application/json" </span>
    <span class="slctrl-attr">"Accept"</span> : <span class="slctrl-string"> "application/json" </span>
} </pre>
    <h4 style="margin-top: 25px">Form Data </h4> <br>
    <pre style="font-weight:bold">
<span class="slctrl-attr">NONE</span> </pre>
    <div>
        <div>
            <h4 style="display: inline-block">REQUEST</h4>
        </div>
        <table class="table-bordered"  style="width: 50%">
            <thead>
            <th colspan="2">URL Parameters</th>
            </thead>
            <thead>
            <th>Attribute</th>
            <th>Description</th>
            </thead>
            <tbody>
            <tr>
                <td>NONE</td>
                <td>NONE</td>
            </tr>
            </tbody>
        </table>
    </div>
        <h4>Response
            <span
                data-toggle="collapse"
                data-target="#client-v1-shipment-status-shipment-id-response"
                aria-expanded="false"
                aria-controls="client-v1-shipment-status-shipment-id-response"
                style="cursor: pointer; top: 2px !important;"
                class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
<pre class="collapse" id="client-v1-shipment-status-shipment-id-response" >
{
   <span class="slctrl-attr">"data"</span>: [
        {
            <span class="slctrl-attr">"container_num"</span>: <span class="slctrl-string">"{container_number}"</span>,
            <span class="slctrl-attr">"last_free_day"</span>: <span class="slctrl-string">"{last_free_day}"</span>",
            <span class="slctrl-attr">"released_at_terminal"</span>: <span class="slctrl-string">"{true/false}"</span>,
            <span class="slctrl-attr">"holds"</span>: [
                {
                   <span class="slctrl-attr">"name"</span>: <span class="slctrl-string">"{customs/freight/USDA/TMF/other}"</span>,
                   <span class="slctrl-attr">"status"</span>: <span class="slctrl-string">"{pending/hold}"</span>,
                  <span class="slctrl-attr">"description"</span>: <span class="slctrl-string">"{description}"</span>
                },
                {...}, ...
            ],
            <span class="slctrl-attr">"fees"</span>: [
                {
                   <span class="slctrl-attr">"type"</span>: <span class="slctrl-string">"{demurrage/exam/total/other}"</span>,
                   <span class="slctrl-attr">"amount"</span>: <span class="slctrl-string">"{amount_in_usd}"</span>
                },
                {...}, ...
            ],
            <span class="slctrl-attr">"transport_events"</span>: [
                {
                    <span class="slctrl-attr">"event"</span>: <span class="slctrl-string">"{transport_event_name}"</span>,
                    <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"{datetime}"</span>,
                    <span class="slctrl-attr">"voyage_number"</span>: <span class="slctrl-number">{voyage_number}</span>,
                    <span class="slctrl-attr">"timestamp</span>: <span class="slctrl-string">"{timestamp}"</span>,
                    <span class="slctrl-attr">"location_locode"</span>: <span class="slctrl-string">"{lcode}"</span>,
                    <span class="slctrl-attr">"timezone"</span>: <span class="slctrl-string">"{timezone}"</span>
                },
                {...}, ...
            ]
        },
        {...}, ...
    ]
} </pre>

    <div style="margin-bottom: 10px">
        <div>
            <h4 style="display: inline-block">Responses</h4>
        </div>
        <div>
            <table class="table-bordered" style="width: 50%">
                <thead>
                    <tr>
                        <th colspan="2">
                            <span class="badge-success">
                                200 collection of Card
                            </span>
                        </th>
                    </tr>
                    <tr>
                        <th>RESPONSE SCHEMA: </th>
                        <th>application/json</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-toggle="collapse"
                            data-target="#shipment_status_data"
                            aria-expanded="false"
                            aria-controls="shipment_status_data"
                            style="cursor: pointer;  "
                            class="glyphicon1 icn-shipment_status_data"
                            rel="icn-shipment_status_data"
                        >
                            <span class="icn-shipment_status_data">
                               data <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                            </span>
                        </td>
                        <td>object</td>
                    </tr>

                    <tr class="endpoint_ collapse" id="shipment_status_data">
                        <td colspan="2">
                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                <table class="table-bordered"  style="width: 50%">
                                    <thead></thead>
                                    <tbody>

                                            <tr>
                                                <td>id</td>
                                                <td><i>Integer</i> Unique identifier for the Shipment Status, autoincrement</td>
                                            </tr>
                                            <tr>
                                                <td>container_num</td>
                                                <td><i>String</i> Default: <code>NULL</code>, Container number</td>
                                            </tr>
                                            <tr>
                                                <td>last_free_day</td>
                                                <td><i>String</i> Default: <code>NULL</code>, Last free day</td>
                                            </tr>
                                            <tr>
                                                <td>released_at_terminal</td>
                                                <td><i>String</i> Default: <code>NULL</code>, Releasd at terminal</td>
                                            </tr>
                                            <tr>

                                                <td data-toggle="collapse"
                                                    data-target="#shipment_status_holds"
                                                    aria-expanded="false"
                                                    aria-controls="shipment_status_holds"
                                                    style="cursor: pointer;  "
                                                    class="glyphicon1 icn-shipment_status_holds"
                                                    rel="icn-shipment_status_holds"
                                                >
                                                    <span class="icn-shipment_status_holds">
                                                       holds <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                                    </span>
                                                </td>
                                                <td>Object</td>
                                            </tr>

                                            <tr  class="collapse" id="shipment_status_holds">
                                                <td colspan="2">
                                                    <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                        <table class="table-bordered">
                                                            <thead></thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>name</td>
                                                                    <td><i>String</i> Default: <code>NULL</code>, Name for holds. E.g: customs/freight/USDA/TMF/other</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>status</td>
                                                                    <td><i>String</i> Default: <code>NULL</code>, Status for hold. E.g: pending/hold </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>description</td>
                                                                    <td><i>String</i> Default: <code>NULL</code>, Description for hold</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>


                                            <tr>
                                                <td data-toggle="collapse"
                                                    data-target="#shipment_status_fees"
                                                    aria-expanded="false"
                                                    aria-controls="shipment_status_fees"
                                                    style="cursor: pointer;  "
                                                    class="glyphicon1 icn-shipment_status_fees"
                                                    rel="icn-shipment_status_fees"
                                                >
                                                    <span class="icn-shipment_status_fees">
                                                       fees <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                                    </span>
                                                </td>
                                                <td>Object</td>
                                            </tr>



                                            <tr  class="collapse" id="shipment_status_fees">
                                                <td colspan="2">
                                                    <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                        <table class="table-bordered">
                                                            <thead></thead>
                                                            <tbody>
                                                            <tr>
                                                                <td>type</td>
                                                                <td><i>String</i> Default: <code>NULL</code>, Type for fees. E.g: demurrage/exam/total/other </td>
                                                            </tr>
                                                            <tr>
                                                                <td>amount</td>
                                                                <td><i>String</i> Default: <code>NULL</code>, Amount in use for fees.</td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>


                                            <tr>

                                                <td data-toggle="collapse"
                                                    data-target="#shipment_status_transport_events"
                                                    aria-expanded="false"
                                                    aria-controls="shipment_status_transport_events"
                                                    style="cursor: pointer;  "
                                                    class="glyphicon1 icn-shipment_status_transport_events"
                                                    rel="icn-shipment_status_transport_events"
                                                >
                                                    <span class="icn-shipment_status_transport_events">
                                                       transport_events <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                                    </span>
                                                </td>
                                                <td>Object</td>
                                            </tr>


                                            <tr  class="collapse" id="shipment_status_transport_events">
                                                <td colspan="2">
                                                    <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                        <table class="table-bordered">
                                                            <thead></thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>event</td>
                                                                    <td><i>String</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>created_at</td>
                                                                    <td><i>Timestamp</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>voyage_number</td>
                                                                    <td><i>String</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>timestamp</td>
                                                                    <td><i>Timestamp</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>location_locode</td>
                                                                    <td><i>String</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>timezone</td>
                                                                    <td><i>string</i> Default: <code>NULL</code>, Tramsport event time zone.</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>



                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <p class="m-t-20">
        {{ EndPointHelper::userGuide() }}
    </p>
</div>















    <!-- Email Report Schedule downloadable file -->
    <div class="col-sm-12"  id="get-all-customer-shipment-info" >


        <h3>Get All Customer Shipment Info</h3>

        <p>
            By this endpoint they can download their Email Report Schedule.
        </p>
        <br>
        <div style="position: relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;


            <span class="glyphicon1" rel="i-get-all-download-report-by-id" aria-hidden="true">
                <span
                        data-toggle="collapse"
                        data-target="#route-get-all-download-report-by-id"
                        aria-expanded="false"
                        aria-controls="route-get-all-download-report-by-id"
                        style="cursor: pointer"
                        class="i-get-all-download-report-by-id"
                > <code>api/download-report/{id}</code>
                    <span class="glyphicon glyphicon-menu-right " aria-hidden="true">
                    </span>
                </span>
            </span>

            <div class="collapse api-route" id="route-get-all-download-report-by-id">
                <div class="badge-success get-all-download-report-by-id hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">


                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="get-all-download-report-by-id">Copy</div>
                </div>

                <div class="api_ noselect" id="get-all-download-report-by-id">
                    {{ config('app.url')}}/api/download-report/{id}
                </div>
            </div>




            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>id</code><br>
            <b>Return Type: &nbsp;</b> <code>FILE</code> <br>
            <b>Will Return: &nbsp;</b> <code>Email Report Schedule (Download Email Report Schedule) </code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"Authorization"</span>: <span class="slctrl-string">"Bearer {access_token}"</span>
    <span class="slctrl-attr">"Content-Type"</span>: <span class="slctrl-string"> "application/json" </span>
    <span class="slctrl-attr">"Accept"</span>: <span class="slctrl-string"> "application/json" </span>
} </pre>


        <h4> Form Data </h4>
        <pre>
{
    <span class="slctrl-attr">"id"</span> : <span class="slctrl-number">{id}</span>,
} </pre>
        <div>
            <div>
                <h4 style="display: inline-block">REQUEST</h4>
            </div>
            <table class="table-bordered">
                <thead>
                <th colspan="2">URL Parameters</th>
                </thead>
                <thead>
                <th>Attribute</th>
                <th>Description</th>
                </thead>
                <tbody>
                <tr>
                    <td>id <span class="badge-danger">required</span></td>
                    <td><i>Integer</i> The unique id for Email Report Schedule to Get Customer Shipment Info data. </td>
                </tr>
                </tbody>
            </table>
        </div>


        <h4>Response
            <span
                    data-toggle="collapse"
                    data-target="#get-all-download-report-by-id-response"
                    aria-expanded="false"
                    aria-controls="get-all-download-report-by-id-response"

                    style="cursor: pointer; top: 2px !important;"
                    class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
<pre class="collapse" id="get-all-download-report-by-id-response">
  <span class="slctrl-attr">Downloadable File</span>
</pre>



        <div style="margin-bottom: 10px">
            <div>
                <h4 style="display: inline-block">Responses</h4>
            </div>
            <div>
                <table class="table-bordered">
                    <thead>
                    <tr>
                        <th colspan="2"><span class="badge-success">200 collection of Card</span></th>
                    </tr>
                    <tr>
                        <th>RESPONSE SCHEMA: </th>
                        <th>application/json</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td colspan="2">Downloadable File</td>
                    </tr>

                    </tbody>
                </table>

            </div>
        </div>

        <p>
            {{ EndPointHelper::userGuide() }}
        </p>

    </div><!-- end Email Report Schedule downloadable file-->












    <div class="col-sm-12 " style="margin-bottom: 25px" id="download-shipment-documents">
    <h3>Download Shipment Documents</h3>
    <p>
       By this endpoint client can download their shipment's documents(hbl_copy,packing_list etc...).
    </p>
    <br>

    <div id="download-shipment-document-header" class="" style="position:relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
        <b>Route: </b> &nbsp;
        <span class="glyphicon1" rel="i-client-v1-shipment-documents-download" aria-hidden="true">
            <span
                    data-toggle="collapse"
                    data-target="#route-client-v1-shipment-documents-download"
                    aria-expanded="false"
                    aria-controls="route-client-v1-shipment-documents-download"
                    style="cursor: pointer"
                    class="i-client-v1-shipment-documents-download"
            >
                <code>api/client/v1/shipment/documents/download</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true">
                    </span>
            </span>
        </span>
        <div class="collapse api-route" id="route-client-v1-shipment-documents-download">
            <div class="badge-success client-v1-shipment-documents-download hidden" style="border-radius: 6px !important; ">
                <span >Copied</span>
            </div>
            <div class="flex-port">
                <p>SHIFL CENTRAL API</p>
                <div  class="copy-api" rel="client-v1-shipment-documents-download">Copy</div>
            </div>
            <div class="api_ noselect" id="client-v1-shipment-documents-download">
                {{ config('app.url')}}/api/client/v1/shipment/documents/download
            </div>
        </div>
        <br>
        <b>Request Type: &nbsp;</b> <code>GET</code> <br>
        <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
        <b>Body Form Data required: &nbsp;</b> <code>document_type</code>,
        <code>document_path</code>,
        <code>shipment_id</code>
        <br>
        <b>Return Type: &nbsp;</b> <code>FILE</code> <br>
        <b>Will Return: &nbsp;</b> <code>Shipment Document </code> <small> </small><br>
    </div>
            <h4 id="download-shipment-document-header">Header</h4> <br>
   <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"Authorization"</span> : <span class="slctrl-string">"Bearer {access_token}"</span>
    <span class="slctrl-attr">"Content-Type"</span> : <span class="slctrl-string"> "application/json" </span>
    <span class="slctrl-attr">"Accept"</span> : <span class="slctrl-string"> "application/json" </span>
} </pre>

            {{--<p class="">--}}
                {{--This Header will be same for all documents (hbl copy , packing list , commercial documents, commercial invoice or customs_documents) download api request only form data will change--}}
            {{--</p>--}}




            {{--<div class="col-sm-12" id="hbl-copy">--}}
                {{--<h3>Hbl Copy</h3>--}}
                {{--<p class="m-t-20">--}}
                    {{--For Hbl copy the header will be same <a href="#download-shipment-document-header">As mentioned avobe</a> , only form data will change.--}}
                {{--</p>--}}


                <h4 style="margin-top: 25px">Form Data </h4> <br>


    <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"document_type"</span>: <span class="slctrl-string">"hbl_copy/packing_list/commercial_documents/commercial_invoice/customs_documents"</span>,
    <span class="slctrl-attr">"document_path"</span>: <span class="slctrl-string">"{suppliers > hbl_copy}"</span>,
    <span class="slctrl-attr">"shipment_id"</span>: <span class="slctrl-string">"{shipment_id}"</span>
} </pre>

    <div>
        <div>
            <h4 style="display: inline-block">REQUEST</h4>
        </div>
        <table class="table-bordered">
            <thead>

            <tr>
                <th colspan="2">URL Parameters</th>
            </tr>
            <tr>
                <th>Attribute</th>
                <th>Description</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td>document_type<span class="badge-danger">required</span></td>

                    <td><i>Integer</i> Must be one of hbl_copy, packing_list, commercial_documents, commercial_invoice, or customs_documents.</td>
                </tr>
                <tr>
                    <td>document_path<span class="badge-danger">required</span></td>
                    <td><i>Integer</i>, Document path </td>
                </tr>
                <tr>
                    <td>shipment_id<span class="badge-danger">required</span></td>
                    <td><i>Integer</i> Unique identifier for the Shipment</td>
                </tr>
            </tbody>
        </table>
    </div>



    <h4>Response
        <span
            data-toggle="collapse"
            data-target="#client-v1-shipment-documents-download-response"
            aria-expanded="false"
            aria-controls="client-v1-shipment-documents-download-response"
            style="cursor: pointer; top: 2px !important;"
            class="glyphicon glyphicon-eye-open">
        </span>
    </h4>

<pre class="collapse" id="client-v1-shipment-documents-download-response" >
    <span class="slctrl-attr">Downloadable File</span> </pre>


            {{--</div>--}}

            {{--<div class="col-sm-12" id="packing-list">--}}
                {{--<h3>Packing List</h3>--}}
                {{--<p class="m-t-20">--}}
                    {{--For Packing list the header will be same <a href="#download-shipment-document-header">As mentioned avobe</a> , only form data will change.--}}
                {{--</p>--}}


                {{--<h4 style="margin-top: 25px">Form Data </h4> <br>--}}


                {{--<pre style="font-weight:bold">--}}
    {{--{--}}
        {{--"document_type" : "packing_list",--}}
        {{--"document_path" : "{suppliers > packing_list}",--}}
        {{--"shipment_id" : "{shipment_id}"--}}
    {{--}</pre>--}}

                {{--<h4 style="margin-top: 25px">Response</h4> <br>--}}


                {{--<pre style="font-weight:bold">--}}
    {{--Downloadable File</pre>--}}
            {{--</div>--}}
            {{--<div class="col-sm-12" id="commercial-documents">--}}
                {{--<h3>Commercial Documents</h3>--}}
                {{--<p class="m-t-20">--}}
                    {{--For Commercial Documents the header will be same <a href="#download-shipment-document-header">As mentioned avobe</a> , only form data will change.--}}
                {{--</p>--}}


                {{--<h4 style="margin-top: 25px">Form Data </h4> <br>--}}


                {{--<pre style="font-weight:bold">--}}
    {{--{--}}
        {{--"document_type" : "commercial_documents",--}}
        {{--"document_path" : "{suppliers > commercial_documents}",--}}
        {{--"shipment_id" : "{shipment_id}"--}}
    {{--}</pre>--}}

                {{--<h4 style="margin-top: 25px">Response</h4> <br>--}}


                {{--<pre style="font-weight:bold">--}}
    {{--Downloadable File</pre>--}}
            {{--</div>--}}
            {{--<div class="col-sm-12" id="commercial-invoice">--}}
                {{--<h3>Commercial Invoice</h3>--}}
                {{--<p class="m-t-20">--}}
                    {{--For Commercial Invoice the header will be same <a href="#download-shipment-document-header">As mentioned avobe</a> , only form data will change.--}}
                {{--</p>--}}


                {{--<h4 style="margin-top: 25px">Form Data </h4> <br>--}}


                {{--<pre style="font-weight:bold">--}}
    {{--{--}}
        {{--"document_type" : "commercial_invoice",--}}
        {{--"document_path" : "{suppliers > commercial_invoice}",--}}
        {{--"shipment_id" : "{shipment_id}"--}}
    {{--}</pre>--}}

                {{--<h4 style="margin-top: 25px">Response</h4> <br>--}}


                {{--<pre style="font-weight:bold">--}}
    {{--Downloadable File</pre>--}}
            {{--</div>--}}
            {{--<div class="col-sm-12" id="customs-documents">--}}
                {{--<h3>Customs Documents</h3>--}}
                {{--<p class="m-t-20">--}}
                    {{--For Customs Documents the header will be same <a href="#download-shipment-document-header">As mentioned avobe</a> , only form data will change.--}}
                {{--</p>--}}


                {{--<h4 style="margin-top: 25px">Form Data </h4> <br>--}}


                {{--<pre style="font-weight:bold">--}}
    {{--{--}}
        {{--"document_type" : "customs_documents",--}}
        {{--"document_path" : "{customs_documents > files > path}",--}}
        {{--"shipment_id" : "{shipment_id}"--}}
    {{--}</pre>--}}

                {{--<h4 style="margin-top: 25px">Response</h4> <br>--}}


    {{--<pre style="font-weight:bold">--}}
{{--Downloadable File--}}
    {{--</pre>--}}
            {{--</div>--}}
            <p>
                {{ EndPointHelper::userGuide() }}
            </p>
        </div>

</div>