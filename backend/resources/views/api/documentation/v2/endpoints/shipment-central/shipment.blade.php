<div class="col-sm-12" id="shipment"> <!-- start Shipment -->
    <h3 class="page-header">{{ EndPointHelper::endPoint('Shipment') }}</h3>
    <div class="col-sm-12 hidden" id="shipment-snooze"><!-- Snooze Shipment -->

        <h3>Snooze Shipment</h3>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/v2/snooze-shipment</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST </code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>shipment_id</code>, <code>snooze_date</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Snooze Shipment </code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>


        <h4> Form Data </h4>
        <pre>
{
    "shipment_id": "{shipment_id}",
    "snooze_date_at": "{snooze_date  }"
}
</pre>

        <h4>Response</h4>
        <pre>
{
    "data": {
       "snooze_date_at": "{snooze_date_at}",
       "pass": "{snooze_date}"
    },
    "status": "ok",
}
                                                        </pre>
        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>
    </div><!-- End Snooze Shipment-->
    <div class="col-sm-12" id="get-shipment-po"><!-- Get Po Shipment -->
        <h3>{{ EndPointHelper::get('Po Shipment') }}</h3>
        <p>
            {{ EndPointHelper::getDescriptionArray(' Po Shipment', array('supplier_id', 'customer_id', 'po_number')) }}
        </p>
        <br>
        <div class="" style="position:relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-shipment-po-details-po-id" aria-hidden="true">
                <span
                    data-toggle="collapse"
                    data-target="#route-shipment-po-details-po-id"
                    aria-expanded="false"
                    aria-controls="route-shipment-po-details-po-id"
                    style="cursor: pointer"
                    class="i-shipment-po-details-po-id"
                > <code>api/shipment-po-details/{po_id}</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                </span>
            </span>
            <div class="collapse api-route" id="route-shipment-po-details-po-id">
                <div class="badge-success shipment-po-details-po-id hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="shipment-po-details-po-id">Copy</div>
                </div>
                <div class="api_ noselect" id="shipment-po-details-po-id">
                    {{ config('app.url')}}/api/shipment-po-details/{po_id}
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>supplier_id</code>, <code>customer_id</code>, <code>po_number</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Po Shipment</code> <small> </small><br>
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
    <span class="slctrl-attr">"supplier_id"</span>: <span class="slctrl-string">"{supplier_id}"</span>,
    <span class="slctrl-attr">"customer_id"</span>: <span class="slctrl-string">"{customer_id}"</span>,
    <span class="slctrl-attr">"po_number"</span>: <span class="slctrl-string">"{po_number}"</span>
}</pre>
        <div>
            <div>
                <h4 style="display: inline-block">REQUEST</h4>
            </div>
            <table class="table-bordered">
                <thead>
                <tr>
                    <th colspan="2">Body Parameters</th>
                </tr>
                <tr>
                    <th>Attribute</th>
                    <th>Description</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>supplier_id<span class="badge-danger">required</span></td>
                        <td><i>String</i> The supplier id for the Po Shipment to be retrieved</td>
                    </tr>
                    <tr>
                        <td>customer_id<span class="badge-danger">required</span></td>
                        <td><i>String</i> The customer id for the Po Shipment to be retrieved</td>
                    </tr>
                    <tr>
                        <td>po_number<span class="badge-danger">required</span></td>
                        <td><i>String</i> The po_number for the Po Shipment to be retrieved</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <h4>Response
            <span
                data-toggle="collapse"
                data-target="#shipment-po-details-po-id-response"
                aria-expanded="false"
                aria-controls="shipment-po-details-po-id-response"
                style="cursor: pointer; top: 2px !important;"
                class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre class="collapse" id="shipment-po-details-po-id-response">
{
    <span class="slctrl-attr">"data"</span>: [],
    <span class="slctrl-attr">"links"</span>: {
        <span class="slctrl-attr">"first"</span>: <span class="slctrl-string">"api/shipment-po-details/32?page=1"</span>,
        <span class="slctrl-attr">"last"</span>: <span class="slctrl-string">"api/shipment-po-details/32?page=1"</span>,
        <span class="slctrl-attr">"prev"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"next"</span>: <span class="slctrl-literal">null</span>
    },
    <span class="slctrl-attr">"meta"</span>: {
        <span class="slctrl-attr">"current_page"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"from"</span>: <span class="slctrl-attr">null</span>,
        <span class="slctrl-attr">"last_page"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"path"</span>: <span class="slctrl-string">"api/shipment-po-details/32"</span>,
        <span class="slctrl-attr">"per_page"</span>: <span class="slctrl-attr">30</span>,
        <span class="slctrl-attr">"to"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"total"</span>: <span class="slctrl-number">0</span>
    }
} </pre>
        <div style="margin-bottom: 10px">
            <div>
                <h4 style="display: inline-block">Responses</h4>
            </div>
            <div>
                <table class="table-bordered">
                    <thead>
                    <tr>
                        <th colspan="2"><span class="badge-success">200 collection of Carrier</span></th>
                    </tr>
                    <tr>
                        <th>RESPONSE SCHEMA: </th>
                        <th>application/json</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            data
                        </td>
                        <td>object</td>
                    </tr>

                    <tr>
                        <td
                            data-toggle="collapse"
                            data-target="#get-shipment-po-details-po-id-links"
                            aria-expanded="false"
                            aria-controls="get-shipment-po-details-po-id-links"
                            style="cursor: pointer;  "

                            class="glyphicon1 shipment-po-details-po-id-links"
                            rel="shipment-po-details-po-id-links"
                        >
                            <span class="shipment-po-details-po-id-links">
                               links <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                            </span>
                        </td>

                        <td>object</td>
                    </tr>

                    <tr  class="collapse" id="get-shipment-po-details-po-id-links">
                        <td colspan="2">
                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                <table class="table-bordered">
                                    <thead></thead>
                                    <tbody>
                                    <tr>
                                        <td>first</td>
                                        <td><i>String</i> Default: <code>NULL</code>, link to the first page</td>
                                    </tr>
                                    <tr>
                                        <td>last</td>
                                        <td><i>String</i> Default: <code>NULL</code>, link to the last page</td>
                                    </tr>
                                    <tr>
                                        <td>prev</td>
                                        <td><i>String</i> Default: <code>NULL</code>, link to the previous page</td>
                                    </tr>
                                    <tr>
                                        <td>next</td>
                                        <td><i>String</i> Default: <code>NULL</code>, link to the next page</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>







                    <tr>
                        <td

                                data-toggle="collapse"
                                data-target="#get-shipment-po-details-po-id-meta"
                                aria-expanded="false"
                                aria-controls="get-shipment-po-details-po-id-meta"
                                style="cursor: pointer;  "

                                class="glyphicon1 shipment-po-details-po-id-meta"
                                rel="shipment-po-details-po-id-meta">

                                        <span class="shipment-po-details-po-id-meta">
                                           meta <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                        </span>
                        </td>

                        <td>object</td>
                    </tr>



                    <tr  class="collapse" id="get-shipment-po-details-po-id-meta">
                        <td colspan="2">
                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                <table class="table-bordered">
                                    <thead></thead>
                                    <tbody>
                                    <tr>
                                        <td>current_page</td>
                                        <td><i>String</i> Default: <code>NULL</code></td>
                                    </tr>
                                    <tr>
                                        <td>from</td>
                                        <td><i>String</i> Default: <code>NULL</code></td>
                                    </tr>
                                    <tr>
                                        <td>last_page</td>
                                        <td><i>String</i> Default: <code>NULL</code></td>
                                    </tr>
                                    <tr>
                                        <td>path</td>
                                        <td><i>String</i> Default: <code>NULL</code></td>
                                    </tr>
                                    <tr>
                                        <td>per_page</td>
                                        <td><i>String</i> Default: <code>NULL</code></td>
                                    </tr>
                                    <tr>
                                        <td>to</td>
                                        <td><i>String</i> Default: <code>NULL</code></td>
                                    </tr>
                                    <tr>
                                        <td>total</td>
                                        <td><i>String</i> Default: <code>NULL</code></td>
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
        <p>
            {{ EndPointHelper::userGuide() }}
        </p>
    </div><!-- End Get Po Shipment -->

    <div class="col-sm-12 hidden" id="shipment-schedule-option"><!-- Get Schedule Option -->

        <h3>Get Schedule Option</h3>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code> api/schedule-options</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>NONE</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Schedule Option</code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>


        <h4> Form Data </h4>
        <pre>
NONE
</pre>

        <h4>Response</h4>
        <pre>
{
    "data": [
             {
        "id": 2,
        "po_num": null,
        "mbl_num": null,
        "type": null,
        "term": null,
        "shipment_status": "Cancelled",
        "shifl_ref": "60717",
        "etd": "2020-01-24T00:00:00.000000Z",
        "eta": "2020-02-08T00:00:00.000000Z",
        "custom_notes": "PENDING",
        "total_cbm": null,
        "total_kg": null,
        "total_cartons": null,
        "suppliers_group": "[]",
        "created_at": "2020-01-08T00:35:22.000000Z",
        "updated_at": "2020-03-09T23:23:22.000000Z"
    }, {...},...,
            ],
    "links": {
        "first": "api/shipment-po-details/32?page=1",
        "last": "api/shipment-po-details/32?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": null,
        "last_page": 1,
        "path": "api/shipment-po-details/32",
        "per_page": 30,
        "to": null,
        "total": 0
    }
}
                                                        </pre>
        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>
    </div><!-- End Get Schedule Option -->

    <div class="col-sm-12 hidden" id="shipment-unsnooze"><!-- unsnooze shipment-->

        <h3>Unsnooze Shipment</h3>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code> api/v2/unsnooze-shipment</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>shipment_id</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Unsnooze Shipment</code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>


        <h4> Form Data </h4>
        <pre>
{
    "shipment_id": "{shipment_id}"
}
</pre>

        <h4>Response</h4>
        <pre>
{
   'status' => 'ok'
}
                                                        </pre>
        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>
    </div><!-- End unsnooze shipment -->

    <div class="col-sm-12 hidden" id="shipment-select-schedule-app"><!-- Select Schedule App -->

        <h3>Select Schedule App</h3>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/select-schedule</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>id</code>, <code>schedule_id</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Select Schedule App</code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>


        <h4> Form Data </h4>
        <pre>
{
    "id": "{id}",
    "schedule_id": "{schedule_id}"
}
</pre>

        <h4>Response</h4>
        <pre>
{
   'status' => 'ok'
}
                                                        </pre>
        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>
    </div><!-- End Select Schedule App -->

    <div class="col-sm-12 hidden" id="shipment-req-new-sched"><!-- Request New Schedule -->

        <h3>Request New Schedule</h3>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/request-new-schedule/{shipment_id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b><code>schedule_id</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Request New Schedule</code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>


        <h4> Form Data </h4>
        <pre>
{
    "schedule_id": "{schedule_id}"
}
</pre>

        <h4>Response</h4>
        <pre>
{
    "status": "ok",
    "error_message": ""
}
                                                        </pre>
        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>
    </div><!-- End Request New Schedule -->

    <div class="col-sm-12" id="shipment-event"><!-- Events -->
        <h3>{{ EndPointHelper::get('Shipment Events') }}</h3>
        <p>
            {{ EndPointHelper::getDescription('Shipment Events', 'mbl_num') }}
        </p>
        <br>
        <div class="" style="position:relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-shipment-events-mbl-num" aria-hidden="true">
                <span
                    data-toggle="collapse"
                    data-target="#route-shipment-events-mbl-num"
                    aria-expanded="false"
                    aria-controls="route-shipment-events-mbl-num"
                    style="cursor: pointer"
                    class="i-shipment-events-mbl-num"
                > <code>api/events/{mbl_num}</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                </span>
            </span>
            <div class="collapse api-route" id="route-shipment-events-mbl-num">
                <div class="badge-success shipment-events-mbl-num hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="shipment-events-mbl-num">Copy</div>
                </div>
                <div class="api_ noselect" id="shipment-events-mbl-num">
                    {{ config('app.url')}}/api/events/{mbl_num}
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b><code>mbl_num</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Events</code> <small> </small><br>
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
    <span class="slctrl-attr">"mbl_num"</span>: <span class="slctrl-string">"{mbl_num}"</span>
}
</pre>
        <div>
            <div>
                <h4 style="display: inline-block">REQUEST</h4>
            </div>
            <table class="table-bordered">
                <thead>
                <tr>
                    <th colspan="2">Body Parameters</th>
                </tr>
                <tr>
                    <th>Attribute</th>
                    <th>Description</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>mbl_num<span class="badge-danger">required</span></td>
                    <td><i>String</i> The mbl_num for the Shipment Events to be retrieved</td>
                </tr>
                </tbody>
            </table>
        </div>
        <h4>Response
            <span
                data-toggle="collapse"
                data-target="#shipment-events-mbl-num-response"
                aria-expanded="false"
                aria-controls="shipment-events-mbl-num-response"
                style="cursor: pointer; top: 2px !important;"
                class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre class="collapse" id="shipment-events-mbl-num-response">
{
    <span class="slctrl-attr">"eta_logs"</span>: [],
    <span class="slctrl-attr">"containers"</span>: {
        <span class="slctrl-attr">"CSNU1945531"</span>: {
            <span class="slctrl-attr">"last_free_day"</span>: <span class="slctrl-string">"2021-04-21T07:00:00Z"</span>,
            <span class="slctrl-attr">"released_at_terminal"</span>: <span class="slctrl-literal">false</span>,
            <span class="slctrl-attr">"holds"</span>: [],
            <span class="slctrl-attr">"milestones"</span>: {
                <span class="slctrl-attr">"errors"</span>: [
                    {
                        <span class="slctrl-attr">"detail"</span>: <span class="slctrl-string">"invalid API token"</span>
                    }
                ]
            },
            <span class="slctrl-attr">"pickup_appointment_at"</span>: <span class="slctrl-string">""</span>,
            <span class="slctrl-attr">"container_details"</span>: {
                <span class="slctrl-attr">"number"</span>: <span class="slctrl-string">"CSNU1945531"</span>,
                <span class="slctrl-attr">"seal_number"</span>: <span class="slctrl-string">"17023647"</span>,
                <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2021-04-08T03:10:55Z"</span>,
                <span class="slctrl-attr">"equipment_type"</span>: <span class="slctrl-string">"dry"</span>,
                <span class="slctrl-attr">"equipment_length"</span>: <span class="slctrl-number">20</span>,
                <span class="slctrl-attr">"equipment_height"</span>: <span class="slctrl-string">"standard"</span>,
                <span class="slctrl-attr">"weight_in_lbs"</span>: <span class="slctrl-number">48546</span>,
                <span class="slctrl-attr">"fees_at_pod_terminal"</span>: [],
                <span class="slctrl-attr">"holds_at_pod_terminal"</span>: [],
                <span class="slctrl-attr">"pickup_lfd"</span>: <span class="slctrl-string">"2021-04-21T07:00:00Z"</span>,
                <span class="slctrl-attr">"pickup_appointment_at"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"pod_full_out_chassis_number"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"location_at_pod_terminal"</span>: <span class="slctrl-string">"T-XP88011-Y (OUT)"</span>,
                <span class="slctrl-attr">"availability_known"</span>: <span class="slctrl-literal">true</span>,
                <span class="slctrl-attr">"available_for_pickup"</span>: <span class="slctrl-literal">false</span>,
                <span class="slctrl-attr">"pod_arrived_at"</span>: <span class="slctrl-string">"2021-04-10T21:52:00Z"</span>,
                <span class="slctrl-attr">"pod_discharged_at"</span>: <span class="slctrl-string">"2021-04-15T22:00:00Z"</span>,
                <span class="slctrl-attr">"final_destination_full_out_at"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"pod_full_out_at"</span>: <span class="slctrl-string">"2021-04-20T16:44:00Z"</span>,
                <span class="slctrl-attr">"empty_terminated_at"</span>: <span class="slctrl-string">"2021-04-28T04:59:00Z"</span>
            },
            <span class="slctrl-attr">"fees"</span>: []
        },
        <span class="slctrl-attr">"attributes"</span>: {
            <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2021-04-06T16:05:27Z"</span>,
            <span class="slctrl-attr">"ref_numbers"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"tags"</span>: [],
            <span class="slctrl-attr">"bill_of_lading_number"</span>: <span class="slctrl-string">"COSU6290946880"</span>,
            <span class="slctrl-attr">"shipping_line_scac"</span>: <span class="slctrl-string">"COSU"</span>,
            <span class="slctrl-attr">"shipping_line_name"</span>: <span class="slctrl-string">"COSCO"</span>,
            <span class="slctrl-attr">"shipping_line_short_name"</span>: <span class="slctrl-string">"COSCO"</span>,
            <span class="slctrl-attr">"port_of_lading_locode"</span>: <span class="slctrl-string">"MYWSP"</span>,
            <span class="slctrl-attr">"port_of_lading_name"</span>: <span class="slctrl-string">"Port Klang - Westport"</span>,
            <span class="slctrl-attr">"port_of_discharge_locode"</span>: <span class="slctrl-string">"USLAX"</span>,
            <span class="slctrl-attr">"port_of_discharge_name"</span>: <span class="slctrl-string">"Los Angeles"</span>,
            <span class="slctrl-attr">"pod_vessel_name"</span>: <span class="slctrl-string">"APL SENTOSA"</span>,
            <span class="slctrl-attr">"pod_vessel_imo"</span>: <span class="slctrl-string">"9632040"</span>,
            <span class="slctrl-attr">"pod_voyage_number"</span>: <span class="slctrl-string">"0TUGFE1MA"</span>,
            <span class="slctrl-attr">"destination_locode"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"destination_name"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"destination_timezone"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"destination_ata_at"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"destination_eta_at"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"pol_etd_at"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"pol_atd_at"</span>: <span class="slctrl-string">"2021-03-04T04:48:00Z"</span>,
            <span class="slctrl-attr">"pol_timezone"</span>: <span class="slctrl-string">"Asia/Kuala_Lumpur"</span>,
            <span class="slctrl-attr">"pod_eta_at"</span>: <span class="slctrl-string">"2021-04-10T15:00:00Z"</span>,
            <span class="slctrl-attr">"pod_ata_at"</span>: <span class="slctrl-string">"2021-04-10T21:52:00Z"</span>,
            <span class="slctrl-attr">"pod_timezone"</span>: <span class="slctrl-string">"America/Los_Angeles"</span>,
            <span class="slctrl-attr">"line_tracking_last_attempted_at"</span>: <span class="slctrl-string">"2021-04-28T08:24:05Z"</span>,
            <span class="slctrl-attr">"line_tracking_last_succeeded_at"</span>: <span class="slctrl-string">"2021-04-28T08:25:41Z"</span>,
            <span class="slctrl-attr">"line_tracking_stopped_at"</span>: <span class="slctrl-string">"2021-04-28T08:25:48Z"</span>,
            <span class="slctrl-attr">"line_tracking_stopped_reason"</span>: <span class="slctrl-string">"all_containers_terminated"</span>
        }
} </pre>
        <div style="margin-bottom: 10px">
            <div>
                <h4 style="display: inline-block">Responses</h4>
            </div>
            <div>
                <table class="table-bordered">
                    <thead>
                    <tr>
                        <th colspan="2"><span class="badge-success">200 collection of Carrier</span></th>
                    </tr>
                    <tr>
                        <th>RESPONSE SCHEMA: </th>
                        <th>application/json</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                           eta_logs
                        </td>
                        <td>object</td>
                        <tr  class="collapse" id="get-shipment-events-containers">
                            <td colspan="2">
                                <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                    <table class="table-bordered">
                                        <thead></thead>
                                        <tbody>
                                        <tr>
                                            <td
                                                data-toggle="collapse"
                                                data-target="#get-shipment-events-CSNU1945531"
                                                aria-expanded="false"
                                                aria-controls="get-shipment-events-CSNU1945531"
                                                style="cursor: pointer;  "

                                                class="glyphicon1 icn-shipment-events-CSNU1945531"
                                                rel="icn-shipment-events-CSNU1945531"
                                            >
                                                <span class="icn-shipment-events-CSNU1945531">
                                                   CSNU1945531 <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                                </span>
                                            </td>
                                            <td>object</td>
                                        </tr>
                                        <tr  class="collapse" id="get-shipment-events-CSNU1945531">
                                            <td colspan="2">
                                                <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                    <table class="table-bordered">
                                                        <thead></thead>
                                                        <tbody>
                                                        <tr>
                                                            <td>last_free_day</td>
                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                        </tr>
                                                        <tr>
                                                            <td>released_at_terminal</td>
                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                        </tr>
                                                        <tr>
                                                            <td>holds</td>
                                                            <td><i>Object</i></td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                data-toggle="collapse"
                                                                data-target="#get-shipment-events-milestones"
                                                                aria-expanded="false"
                                                                aria-controls="get-shipment-events-milestones"
                                                                style="cursor: pointer;  "

                                                                class="glyphicon1 icn-shipment-events-milestones"
                                                                rel="icn-shipment-events-milestones"
                                                            >
                                                                <span class="icn-shipment-events-milestones">
                                                                   milestones <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                                                </span>
                                                            </td>
                                                            <td><i>Object</i></td>
                                                        </tr>

                                                        <tr  class="collapse" id="get-shipment-events-milestones">
                                                            <td colspan="2">
                                                                <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                                    <table class="table-bordered">
                                                                        <thead></thead>
                                                                        <tbody>
                                                                        <tr>
                                                                            <td
                                                                                data-toggle="collapse"
                                                                                data-target="#get-shipment-events-errors"
                                                                                aria-expanded="false"
                                                                                aria-controls="get-shipment-events-errors"
                                                                                style="cursor: pointer;  "

                                                                                class="glyphicon1 icn-shipment-events-errors"
                                                                                rel="icn-shipment-events-errors"
                                                                            >
                                                                                <span class="icn-shipment-events-errors">
                                                                                   errors <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                                                                </span>
                                                                            </td>
                                                                            <td><i>Object</i></td>
                                                                        </tr>

                                                                        <tr  class="collapse" id="get-shipment-events-errors">
                                                                            <td colspan="2">
                                                                                <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                                                    <table class="table-bordered">
                                                                                        <thead></thead>
                                                                                        <tbody>
                                                                                        <tr>
                                                                                            <td>detail</td>
                                                                                            <td><i>String</i> Default: <code>NULL</code></td>
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
                                                        <tr>
                                                            <td>pickup_appointment_at</td>
                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                data-toggle="collapse"
                                                                data-target="#get-shipment-events-container_details"
                                                                aria-expanded="false"
                                                                aria-controls="get-shipment-events-container_details"
                                                                style="cursor: pointer;  "

                                                                class="glyphicon1 icn-shipment-events-container_details"
                                                                rel="icn-shipment-events-container_details"
                                                            >
                                                                <span class="icn-shipment-events-container_details">
                                                                   container_details <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                                                </span>
                                                            </td>
                                                            <td><i>Object</i> Default: <code>NULL</code>,</td>
                                                        </tr>
                                                        <tr  class="collapse" id="get-shipment-events-container_details">
                                                            <td colspan="2">
                                                                <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                                    <table class="table-bordered">
                                                                        <thead></thead>
                                                                        <tbody>
                                                                        <tr>
                                                                            <td>number</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>seal_number</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>created_at</td>
                                                                            <td><i>Timestamp</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>equipment_type</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>equipment_length</td>
                                                                            <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>equipment_height</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>weight_in_lbs</td>
                                                                            <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>fees_at_pod_terminal</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>holds_at_pod_terminal</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>pickup_lfd</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>pickup_appointment_at</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>pod_full_out_chassis_number</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>location_at_pod_terminal</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>availability_known</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>available_for_pickup</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>final_destination_full_out_at</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>pod_full_out_at</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>empty_terminated_at</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>fees</td>
                                                            <td><i>Object</i> </td>
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
                    <tr>
                        <td
                            data-toggle="collapse"
                            data-target="#get-shipment-events-attributes"
                            aria-expanded="false"
                            aria-controls="get-shipment-events-attributes"
                            style="cursor: pointer;  "

                            class="glyphicon1 icn-shipment-events-attributes"
                            rel="icn-shipment-events-attributes"
                        >
                            <span class="icn-shipment-events-attributes">
                               attributes <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                            </span>
                        </td>
                        <td>object</td>
                    </tr>
                    <tr  class="collapse" id="get-shipment-events-attributes">
                        <td colspan="2">
                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                <table class="table-bordered">
                                    <thead></thead>
                                    <tbody>
                                    <tr>
                                        <td>created_at</td>
                                        <td><i>Timestamp</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>ref_numbers</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>tags</td>
                                        <td><i>Object</i></td>
                                    </tr>
                                    <tr>
                                        <td>bill_of_lading_number</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>shipping_line_scac</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>shipping_line_name</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>shipping_line_short_name</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>port_of_lading_locode</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>port_of_lading_name</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>port_of_discharge_locode</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>port_of_discharge_name</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>pod_vessel_name</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>pod_vessel_imo</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>pod_voyage_number</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>destination_locode</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>destination_name</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>destination_timezone</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>destination_ata_at</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>destination_eta_at</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>pol_etd_at</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>pol_atd_at</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>pol_timezone</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>pod_eta_at</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>pod_ata_at</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>pod_timezone</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>line_tracking_last_attempted_at</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>line_tracking_last_succeeded_at</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>line_tracking_stopped_at</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>line_tracking_stopped_reason</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
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
        <p>
            {{ EndPointHelper::userGuide() }}
        </p>
    </div><!-- End Events -->

    <div class="col-sm-12 hidden" id="shipment-specified"><!-- shipment specified -->

        <h3>Specified resource</h3>
        <p>
            Specified resource for Shipment endpoint. To access their Shipment specific detail,
            we need to have the id. And the endpoint will return the specific Shipment regarding the given id
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code> api/shipment/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b><code>id</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Shipment (Specific Shipment)</code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>


        <h4> Form Data </h4>
        <pre>
{
    "id": "{id}"
}
</pre>

        <h4>Response</h4>
        <pre>
{
    "data": {
        "id": 1,
        "po_num": null,
        "mbl_num": null,
        "type": null,
        "term": null,
        "customer_id": 1,
        "shipment_status": "Expired",
        "shifl_ref": "60589",
        "origin_country": null,
        "destination_country": null,
        "etd": null,
        "eta": null,
        "custom_notes": "DN in system\nHave SWB\nAn in system - Paid\nSent customs to samantha",
        "total_cbm": null,
        "total_kg": null,
        "total_cartons": null,
        "created_at": "2020-01-08T22:56:57.000000Z",
        "updated_at": "2020-01-23T04:45:29.000000Z",
        "carrier_id": 0,
        "suppliers_group": "",
        "schedules_group": "",
        "containers_group": "",
        "mbl_copy": null,
        "debit_note": null,
        "vessel": null,
        "booking_confirmed": 0,
        "arrival_notice_confirmed": 0,
        "freight_released_processed": 0,
        "customs_processed": 0,
        "DO_confirmed": 0,
        "freight_confirmed": 0,
        "customs_released": 0,
        "pending_refund": 0,
        "delivered": 0,
        "billed": 0,
        "cancelled": 0,
        "shipment_left": 0,
        "terminal_id": null,
        "arrival_notice": 0,
        "cloned_from": null,
        "trucker_id": null,
        "trucker_custom_note": null,
        "delivery_order_left": 0,
        "custom_content": "",
        "copy_customer": 1,
        "memo_customer": null,
        "suppliers_group_bookings": "[]",
        "containers_group_bookings": "[]",
        "schedules_group_bookings": "[]",
        "booking_confirmed_at": null,
        "mbl_shipper": null,
        "mbl_shipper_address": null,
        "mbl_shipper_phone_number": null,
        "mbl_consignee": null,
        "mbl_consignee_address": null,
        "mbl_consignee_phone_number": null,
        "mbl_notify": null,
        "mbl_notify_address": null,
        "mbl_notify_phone_number": null,
        "mbl_description": null,
        "mbl_marks": null,
        "entry_netchb_submitted": 0,
        "entry_netchb_no": null,
        "entry_netchb_date": null,
        "entry_data": null,
        "tracking_request_id": null,
        "do_sent_at": null,
        "an_sent_at": null,
        "retry_tracking_request": 0,
        "suppliers_commercial_docs": null,
        "services_section": null,
        "netchb_pdf": null,
        "netchb_xml": null,
        "shifl_office_origin_from_id": 0,
        "shifl_office_origin_to_id": 0,
        "rate_confirmed": 0,
        "so_released": 0,
        "released_at_terminal": 0,
        "isf_done": 0,
        "ams_done": 0,
        "is_tracking_shipment": 0,
        "containers_group_untracked": "",
        "untracked_tool_last_updated_at": null,
        "carrier_arrival_notice_eta": null,
        "carrier_arrival_notice_firms": null,
        "tracked_up_to": null,
        "customs_sent": null,
        "customs_sent_at": null,
        "mbl_released_confirmed": 0,
        "mbl_copy_surrendered": null,
        "is_schedule_requested": 0,
        "customs_broker_id": null,
        "status_v2": null,
        "ais_link": "",
        "carrier": null,
        "obl_filled": false,
        "commercial_documents_filled": false,
        "suppliers_name": [],
        "shipment_suppliers": [],
        "projected_profit": "No selected schedule",
        "profitt": 0,
        "customer": {
            "id": 1,
            "company_name": "Yoki shoes",
            "address": null,
            "phone": null,
            "admin_user_id": null,
            "created_at": "2020-01-07T22:54:10.000000Z",
            "updated_at": "2021-09-08T22:24:34.000000Z",
            "managers": "119",
            "sale_persons": "0",
            "emails": [
                {
                    "email": "elie@yokigroup.com"
                },
                {
                    "email": "devi@yokigroup.com"
                },
                {
                    "email": "samantha@cachb.com"
                }
            ],
            "requirements": "NO Trucking NO Customs   ",
            "credit_term_freight": 0,
            "credit_term_duty": 0,
            "ein": null,
            "booking_email_recipients": "[]",
            "loi": "customers/81179f359120099142e62e20adbd0f48.pdf",
            "offices_managers": "",
            "qbo_customer": "",
            "poa": null,
            "default_duty_layout": 0,
            "created_by": null,
            "last_updated_by": null,
            "last_updated": null,
            "handling_freight": null,
            "handling_trucking": null,
            "handling_customs": null,
            "default_requirements_section": null,
            "confirmed_default_requirements": 0
        },
        "containers": [],
        "suppliers": [],
        "terminal_regions": [],
        "shipment_schedules": [],
        "terminal_fortynine": null,
        "invoice": [],
        "bill": []
    }
}
</pre>
        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>
    </div><!-- End shipment specified  -->

    <div class="col-sm-12 hidden" id="shipment-transit"><!-- Transit -->

        <h3>Transit</h3>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/v2/shipments</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b><code>NONE</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Transit</code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>


        <h4> Form Data </h4>
        <pre>
NONE
</pre>

        <h4>Response</h4>
        <pre>

{
    "data": [
        {
            "id": 2,
            "po_num": null,
            "mbl_num": null,
            "type": "pending",
            "term": null,
            "shipment_status": "Cancelled",
            "shifl_ref": "60717",
            "etd": "2020-01-24T00:00:00.000000Z",
            "eta": "2020-02-08T00:00:00.000000Z",
            "custom_notes": "SHIPMENT CANCELLED",
            "total_cbm": null,
            "total_kg": null,
            "total_cartons": null,
            "suppliers_group": "[]",
            "created_at": "2020-01-08T00:35:22.000000Z",
            "updated_at": "2020-03-09T23:23:22.000000Z"
        },
        {...},...

    ],
    "links": {
        "first": "/?page=1",
        "last": "/?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "path": "/",
        "per_page": "30",
        "to": 2,
        "total": 2
    }
}

</pre>
        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>
    </div><!-- End Transit  -->

    <div class="col-sm-12 hidden" id="shipment-expired"><!-- Expired -->
        <h3>Expired</h3>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;<code>api/v2/expired-shipments</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b><code>NONE</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Expired</code> <small> </small><br>
        </div>
        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>


        <h4> Form Data </h4>
        <pre>
NONE
</pre>

        <h4>Response</h4>
        <pre>

{
    "data": [
        {
            "id": 2,
            "po_num": null,
            "mbl_num": null,
            "type": "pending",
            "term": null,
            "shipment_status": "Cancelled",
            "shifl_ref": "60717",
            "etd": "2020-01-24T00:00:00.000000Z",
            "eta": "2020-02-08T00:00:00.000000Z",
            "custom_notes": "SHIPMENT CANCELLED",
            "total_cbm": null,
            "total_kg": null,
            "total_cartons": null,
            "suppliers_group": "[]",
            "created_at": "2020-01-08T00:35:22.000000Z",
            "updated_at": "2020-03-09T23:23:22.000000Z"
        },
        {...},...

    ],
    "links": {
        "first": "/?page=1",
        "last": "/?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "path": "/",
        "per_page": "30",
        "to": 2,
        "total": 2
    }
}

</pre>
        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>
    </div><!-- End Expired  -->

    <div class="col-sm-12" id="shipment-get-shipment"><!-- Get shipment -->
        <h3>{{ EndPointHelper::get('Shipment') }}</h3>
        <p>
            {{ EndPointHelper::getDescription('Shipment', 'shipment_id') }}
        </p>
        <br>
        <div class="" style="position:relative;padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-main-shipments-shipment_id" aria-hidden="true">
                <span
                    data-toggle="collapse"
                    data-target="#route-main-shipments-shipment_id"
                    aria-expanded="false"
                    aria-controls="route-main-shipments-shipment_id"
                    style="cursor: pointer"
                    class="i-main-shipments-shipment_id"
                > <code>api/main/shipments/{shipment_id}</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                </span>
            </span>
            <div class="collapse api-route" id="route-main-shipments-shipment_id">
                <div class="badge-success main-shipments-shipment_id hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="main-shipments-shipment_id">Copy</div>
                </div>
                <div class="api_ noselect" id="main-shipments-shipment_id">
                    {{ config('app.url')}}/api/main/shipments/{shipment_id}
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b><code>shipment_id</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get shipment</code> <small> </small><br>
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
    <span class="slctrl-attr">"shipment_id"</span>: <span class="slctrl-string">"{shipment_id}"</span>
} </pre>
        <div>
            <div>
                <h4 style="display: inline-block">REQUEST</h4>
            </div>
            <table class="table-bordered">
                <thead>
                <tr>
                    <th colspan="2">Body Parameters</th>
                </tr>
                <tr>
                    <th>Attribute</th>
                    <th>Description</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>shipment_id<span class="badge-danger">required</span></td>
                    <td><i>String</i> The shipment_id for the Shipment to be retrieved</td>
                </tr>
                </tbody>
            </table>
        </div>
        <h4>Response
            <span
                data-toggle="collapse"
                data-target="#main-shipments-shipment_id-response"
                aria-expanded="false"
                aria-controls="main-shipments-shipment_id-response"
                style="cursor: pointer; top: 2px !important;"
                class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre class="collapse" id="main-shipments-shipment_id-response">
{
    <span class="slctrl-attr">"data"</span>: [
        {
            <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">2</span>,
            <span class="slctrl-attr">"po_num"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"mbl_num"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"type"</span>: <span class="slctrl-string">"pending"</span>,
            <span class="slctrl-attr">"term"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"shipment_status"</span>: <span class="slctrl-string">"Cancelled"</span>,
            <span class="slctrl-attr">"shifl_ref"</span>: <span class="slctrl-string">"60717"</span>,
            <span class="slctrl-attr">"etd"</span>: <span class="slctrl-string">"2020-01-24T00:00:00.000000Z"</span>,
            <span class="slctrl-attr">"eta"</span>: <span class="slctrl-string">"2020-02-08T00:00:00.000000Z",</span>
            <span class="slctrl-attr">"custom_notes"</span>: <span class="slctrl-string">"SHIPMENT CANCELLED"</span>,
            <span class="slctrl-attr">"total_cbm"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"total_kg"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"total_cartons"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"suppliers_group"</span>: <span class="slctrl-string">"[]"</span>,
            <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2020-01-08T00:35:22.000000Z"</span>,
            <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2020-03-09T23:23:22.000000Z"</span>
        },
        {...},...

    ],
    <span class="slctrl-attr">"links"</span>: {
        <span class="slctrl-attr">"first"</span>: <span class="slctrl-string">"/?page=1"</span>,
        <span class="slctrl-attr">"last"</span>: <span class="slctrl-string">"/?page=1"</span>,
        <span class="slctrl-attr">"prev"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"next"</span>: <span class="slctrl-literal">null</span>
    },
    <span class="slctrl-attr">"meta"</span>: {
        <span class="slctrl-attr">"current_page"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"from"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"last_page"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"path"</span>: <span class="slctrl-string">"/"</span>,
        <span class="slctrl-attr">"per_page"</span>: <span class="slctrl-string">"30"</span>,
        <span class="slctrl-attr">"to"</span>: <span class="slctrl-number">2</span>,
        <span class="slctrl-attr">"total"</span>: <span class="slctrl-number">2</span>
    }
} </pre>
        <div style="margin-bottom: 10px">
            <div>
                <h4 style="display: inline-block">Responses</h4>
            </div>
            <div>
                <table class="table-bordered">
                    <thead>
                    <tr>
                        <th colspan="2"><span class="badge-success">200 collection of Carrier</span></th>
                    </tr>
                    <tr>
                        <th>RESPONSE SCHEMA: </th>
                        <th>application/json</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td
                            data-toggle="collapse"
                            data-target="#get-shipment-data"
                            aria-expanded="false"
                            aria-controls="get-shipment-data"
                            style="cursor: pointer;  "
                            class="glyphicon1 shipment-data"
                            rel="shipment-data"
                        >
                            <span class="shipment-data">
                               data <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                            </span>
                        </td>
                        <td>object</td>
                    </tr>
                    <tr  class="collapse" id="get-shipment-data">
                        <td colspan="2">
                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                <table class="table-bordered">
                                    <thead></thead>
                                    <tbody>
                                        <tr>
                                            <td>id</td>
                                            <td><i>Integer</i> Default: <code>NULL</code></td>
                                        </tr>
                                        <tr>
                                            <td>po_num</td>
                                            <td><i>String</i> Default: <code>NULL</code></td>
                                        </tr>
                                        <tr>
                                            <td>mbl_num</td>
                                            <td><i>String</i> Default: <code>NULL</code></td>
                                        </tr>
                                        <tr>
                                            <td>type</td>
                                            <td><i>String</i> Default: <code>NULL</code></td>
                                        </tr>
                                        <tr>
                                            <td>term</td>
                                            <td><i>String</i> Default: <code>NULL</code></td>
                                        </tr>
                                        <tr>
                                            <td>shipment_status</td>
                                            <td><i>String</i> Default: <code>NULL</code></td>
                                        </tr>
                                        <tr>
                                            <td>shifl_ref</td>
                                            <td><i>String</i> Default: <code>NULL</code></td>
                                        </tr>
                                        <tr>
                                            <td>etd</td>
                                            <td><i>String</i> Default: <code>NULL</code></td>
                                        </tr>
                                        <tr>
                                            <td>eta</td>
                                            <td><i>String</i> Default: <code>NULL</code></td>
                                        </tr>
                                        <tr>
                                            <td>custom_notes</td>
                                            <td><i>String</i> Default: <code>NULL</code></td>
                                        </tr>
                                        <tr>
                                            <td>total_cbm</td>
                                            <td><i>String</i> Default: <code>NULL</code></td>
                                        </tr>
                                        <tr>
                                            <td>total_kg</td>
                                            <td><i>String</i> Default: <code>NULL</code></td>
                                        </tr>
                                        <tr>
                                            <td>total_cartons</td>
                                            <td><i>String</i> Default: <code>NULL</code></td>
                                        </tr>
                                        <tr>
                                            <td>suppliers_group</td>
                                            <td><i>String</i> Default: <code>NULL</code></td>
                                        </tr>
                                        <tr>
                                            <td>created_at</td>
                                            <td><i>Timestamp</i> Default: <code>NULL</code></td>
                                        </tr>
                                        <tr>
                                            <td>updated_at</td>
                                            <td><i>Timestamp</i> Default: <code>NULL</code></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td
                            data-toggle="collapse"
                            data-target="#get-shipment-links"
                            aria-expanded="false"
                            aria-controls="get-shipment-links"
                            style="cursor: pointer;  "

                            class="glyphicon1 shipment-links"
                            rel="shipment-links"
                        >
                            <span class="shipment-links">
                               links <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                            </span>
                        </td>
                        <td>object</td>
                    </tr>

                    <tr  class="collapse" id="get-shipment-links">
                        <td colspan="2">
                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                <table class="table-bordered">
                                    <thead></thead>
                                    <tbody>
                                    <tr>
                                        <td>first</td>
                                        <td><i>String</i> Default: <code>NULL</code>, link to the first page</td>
                                    </tr>
                                    <tr>
                                        <td>last</td>
                                        <td><i>String</i> Default: <code>NULL</code>, link to the last page</td>
                                    </tr>
                                    <tr>
                                        <td>prev</td>
                                        <td><i>String</i> Default: <code>NULL</code>, link to the previous page</td>
                                    </tr>
                                    <tr>
                                        <td>next</td>
                                        <td><i>String</i> Default: <code>NULL</code>, link to the next page</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td
                            data-toggle="collapse"
                            data-target="#get-shipment-meta"
                            aria-expanded="false"
                            aria-controls="get-shipment-meta"
                            style="cursor: pointer;  "

                            class="glyphicon1 shipment-meta"
                            rel="shipment-meta"
                        >
                            <span class="shipment-meta">
                               meta <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                            </span>
                        </td>

                        <td>object</td>
                    </tr>
                    <tr  class="collapse" id="get-shipment-meta">
                        <td colspan="2">
                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                <table class="table-bordered">
                                    <thead></thead>
                                    <tbody>
                                    <tr>
                                        <td>current_page</td>
                                        <td><i>String</i> Default: <code>NULL</code></td>
                                    </tr>
                                    <tr>
                                        <td>from</td>
                                        <td><i>String</i> Default: <code>NULL</code></td>
                                    </tr>
                                    <tr>
                                        <td>last_page</td>
                                        <td><i>String</i> Default: <code>NULL</code></td>
                                    </tr>
                                    <tr>
                                        <td>path</td>
                                        <td><i>String</i> Default: <code>NULL</code></td>
                                    </tr>
                                    <tr>
                                        <td>per_page</td>
                                        <td><i>String</i> Default: <code>NULL</code></td>
                                    </tr>
                                    <tr>
                                        <td>to</td>
                                        <td><i>String</i> Default: <code>NULL</code></td>
                                    </tr>
                                    <tr>
                                        <td>total</td>
                                        <td><i>String</i> Default: <code>NULL</code></td>
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
        <p>
            {{ EndPointHelper::userGuide() }}
        </p>
    </div><!-- End Get shipment -->

    <div class="col-sm-12 hidden" id="shipment-pending"><!-- Pending -->

        <h3>Pending</h3>
        <br>
        <div class="hidden" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/v2/pending-shipments</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b><code>NONE</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Pending</code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>
        <h4> Form Data </h4>
        <pre>
NONE
</pre>

        <h4>Response</h4>
        <pre>

{
    "data": [
        {
            "id": 2,
            "po_num": null,
            "mbl_num": null,
            "type": "pending",
            "term": null,
            "shipment_status": "Cancelled",
            "shifl_ref": "60717",
            "etd": "2020-01-24T00:00:00.000000Z",
            "eta": "2020-02-08T00:00:00.000000Z",
            "custom_notes": "SHIPMENT CANCELLED",
            "total_cbm": null,
            "total_kg": null,
            "total_cartons": null,
            "suppliers_group": "[]",
            "created_at": "2020-01-08T00:35:22.000000Z",
            "updated_at": "2020-03-09T23:23:22.000000Z"
        },
        {...},...

    ],
    "links": {
        "first": "/?page=1",
        "last": "/?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "path": "/",
        "per_page": "30",
        "to": 2,
        "total": 2
    }
}

</pre>
        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>
    </div><!-- End Pending -->

    <div class="col-sm-12 hidden" id="shipment-pending"><!-- Pending -->

        <h3>Pending</h3>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/v2/pending-shipments</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b><code>NONE</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Pending</code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>
        <h4> Form Data </h4>
        <pre>
NONE
</pre>

        <h4>Response</h4>
        <pre>

{
    "data": [
        {
            "id": 2,
            "po_num": null,
            "mbl_num": null,
            "type": "pending",
            "term": null,
            "shipment_status": "Cancelled",
            "shifl_ref": "60717",
            "etd": "2020-01-24T00:00:00.000000Z",
            "eta": "2020-02-08T00:00:00.000000Z",
            "custom_notes": "SHIPMENT CANCELLED",
            "total_cbm": null,
            "total_kg": null,
            "total_cartons": null,
            "suppliers_group": "[]",
            "created_at": "2020-01-08T00:35:22.000000Z",
            "updated_at": "2020-03-09T23:23:22.000000Z"
        },
        {...},...

    ],
    "links": {
        "first": "/?page=1",
        "last": "/?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "path": "/",
        "per_page": "30",
        "to": 2,
        "total": 2
    }
}

</pre>
        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>
    </div><!-- End Pending -->

    <div class="col-sm-12 hidden" id="shipment-completed"><!-- Completed -->

        <h3>Completed</h3>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/v2/completed-shipments</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b><code>NONE</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Completed</code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>
        <h4> Form Data </h4>
        <pre>
NONE
</pre>

        <h4>Response</h4>
        <pre>

{
    "data": [
        {
            "id": 2,
            "po_num": null,
            "mbl_num": null,
            "type": "pending",
            "term": null,
            "shipment_status": "Cancelled",
            "shifl_ref": "60717",
            "etd": "2020-01-24T00:00:00.000000Z",
            "eta": "2020-02-08T00:00:00.000000Z",
            "custom_notes": "SHIPMENT CANCELLED",
            "total_cbm": null,
            "total_kg": null,
            "total_cartons": null,
            "suppliers_group": "[]",
            "created_at": "2020-01-08T00:35:22.000000Z",
            "updated_at": "2020-03-09T23:23:22.000000Z"
        },
        {...},...

    ],
    "links": {
        "first": "/?page=1",
        "last": "/?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "path": "/",
        "per_page": "30",
        "to": 2,
        "total": 2
    }
}

</pre>
        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>
    </div><!-- End Completed -->

    <div class="col-sm-12 hidden" id="shipment-search-customer-order"><!-- Shipment Search Customer Order -->

        <h3>Shipment Search Customer Order</h3>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/v2/shipments/search</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b><code>q</code>,
            <code>tab</code>,
            <code>order</code>,
            <code>sort</code>,
            <code>per_page</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Shipment Search Customer Order</code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>
        <h4> Form Data </h4>
        <pre>
{
    "q": "{q}",
    "tab": "{tab}",
    "order": "{order}",
    "sort": "{sort}",
    "per_page": "per_page",
}
</pre>

        <h4>Response</h4>
        <pre>


{
    "data": [
        {
            "name": "John Doe",
            "email": "johndoe@example.org",
            "email_verified_at": "2022-08-04T10:53:50.000000Z"
        },
        {
            "name": "Mike",
            "email": "mike@example.org",
            "email_verified_at": "2022-08-04T10:53:50.000000Z"
        }
    ]
}


</pre>
        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>
    </div><!-- End Shipment Search Customer Order -->

    <div class="col-sm-12 hidden" id="shipment-specified-v2"><!-- shipment-specified-v2 -->

        <h3>{{ EndPointHelper::get('Shipment V2') }}</h3>
        <p>
            {{ EndPointHelper::getDescription('Shipment V2', 'id') }}
        </p>



        <div class="" style="position:relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;


            <span class="glyphicon1" rel="i-v2-shipment-id" aria-hidden="true">
                <span
                        data-toggle="collapse"
                        data-target="#route-v2-shipment-id"
                        aria-expanded="false"
                        aria-controls="route-v2-shipment-id"
                        style="cursor: pointer"
                        class="i-v2-shipment-id"
                > <code>api/v2/shipment/{id}</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                </span>
            </span>

            <div class="collapse api-route" id="route-v2-shipment-id">
                <div class="badge-success v2-shipment-id hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="v2-shipment-id">Copy</div>
                </div>

                <div class="api_ noselect" id="v2-shipment-id">
                    {{ config('app.url')}}/api/v2/shipment/{id}
                </div>
            </div>



            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b><code>id</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Specified resource v2</code> <small> </small><br>

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
    <span class="slctrl-attr">"id"</span>: <span class="slctrl-string">"{id}"</span>,
} </pre>






        <div>
            <div>
                <h4 style="display: inline-block">REQUEST</h4>
            </div>
            <table class="table-bordered">
                <thead>
                <tr>
                    <th colspan="2">Body Parameters</th>
                </tr>
                <tr>
                    <th>Attribute</th>
                    <th>Description</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>id<span class="badge-danger">required</span></td>
                    <td><i>String</i> The id for the Shipment V2 to be retrieved</td>
                </tr>
                </tbody>
            </table>
        </div>



        <h4>Response</h4>
        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">data</span>: {
        <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"po_num"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"mbl_num"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"type"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"term"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"customer_id"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"shipment_status"</span>: <span class="slctrl-string">"Expired"</span>,
        <span class="slctrl-attr">"shifl_ref"</span>: <span class="slctrl-string">"60660"</span>,
        <span class="slctrl-attr">"origin_country"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"destination_country"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"etd"</span>: <span class="slctrl-string">"2020-01-13T00:00:00.000000Z"</span>,
        <span class="slctrl-attr">"eta"</span>: <span class="slctrl-string">"2020-01-26T00:00:00.000000Z"</span>,
        <span class="slctrl-attr">"custom_notes"</span>: <span class="slctrl-string">"SHIPMENT CANCELLED"</span>,
        <span class="slctrl-attr">"total_cbm"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"total_kg"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"total_cartons"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2020-01-09T01:21:43.000000Z"</span>,
        <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2020-03-09T23:23:52.000000Z"</span>,
        <span class="slctrl-attr">"carrier_id"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"suppliers_group"</span>: <span class="slctrl-string">"[]"</span>,
        <span class="slctrl-attr">"schedules_group"</span>: <span class="slctrl-string">"[{\"id\":1583767339886,\"eta\":\"2020-01-26\",\"etd\":\"2020-01-13\",\"location_from\":\"\",\"location_to\":\"\",\"mode\":\"\",\"etaError\":{},\"etdError\":{}}]"</span>,
        <span class="slctrl-attr">containers_group"</span>": <span class="slctrl-string">"[]"</span>,
        <span class="slctrl-attr">"mbl_copy"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"debit_note"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"vessel"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"booking_confirmed"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"arrival_notice_confirmed"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"freight_released_processed"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"customs_processed"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"DO_confirmed"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"freight_confirmed"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"customs_released"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"pending_refund"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"delivered"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"billed"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"cancelled"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"shipment_left"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"terminal_id"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"arrival_notice"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"cloned_from"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"trucker_id"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"trucker_custom_note"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"delivery_order_left"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"custom_content"</span>: <span class="slctrl-string">""</span>,
        <span class="slctrl-attr">"copy_customer"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"memo_customer"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"suppliers_group_bookings"</span>: <span class="slctrl-string">"[]"</span>,
        <span class="slctrl-attr">"containers_group_bookings"</span>: <span class="slctrl-string">"[]"</span>,
        <span class="slctrl-attr">"schedules_group_bookings"</span>: <span class="slctrl-string">"[]"</span>,
        <span class="slctrl-attr">"booking_confirmed_at"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"mbl_shipper"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"mbl_shipper_address"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"mbl_shipper_phone_number"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"mbl_consignee"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"mbl_consignee_address"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"mbl_consignee_phone_number"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"mbl_notify"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"mbl_notify_address"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"mbl_notify_phone_number"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"mbl_description"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"mbl_marks"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"entry_netchb_submitted"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"entry_netchb_no"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"entry_netchb_date"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"entry_data"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"tracking_request_id"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"do_sent_at"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"an_sent_at"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"retry_tracking_request"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"suppliers_commercial_docs"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"services_section"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"netchb_pdf"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"netchb_xml"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"shifl_office_origin_from_id"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"shifl_office_origin_to_id"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"rate_confirmed"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"so_released"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"released_at_terminal"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"isf_done"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"ams_done"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"is_tracking_shipment"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"containers_group_untracked"</span>: <span class="slctrl-string">""</span>,
        <span class="slctrl-attr">"untracked_tool_last_updated_at"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"carrier_arrival_notice_eta"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"carrier_arrival_notice_firms"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"tracked_up_to"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"customs_sent"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"customs_sent_at"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"mbl_released_confirmed"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"mbl_copy_surrendered"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"is_schedule_requested"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"customs_broker_id"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"status_v2"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"ais_link"</span>: <span class="slctrl-string">""</span>,
        <span class="slctrl-attr">"carrier"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"location_to_name"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"location_from_name"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"obl_filled"</span>: <span class="slctrl-literal">false</span>,
        <span class="slctrl-attr">"commercial_documents_filled"</span>: <span class="slctrl-literal">false</span>,
        <span class="slctrl-attr">"suppliers_name"</span>: [],
        <span class="slctrl-attr">"shipment_suppliers"</span>: [],
        <span class="slctrl-attr">"projected_profit"</span>: <span class="slctrl-string">"No selected schedule"</span>,
        <span class="slctrl-attr">"profitt"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"customer"</span>: {
            <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">1</span>,
            <span class="slctrl-attr">"company_name"</span>: <span class="slctrl-string">"Yoki shoes"</span>,
            <span class="slctrl-attr">"address"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"phone"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"admin_user_id"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2020-01-07T22:54:10.000000Z"</span>,
            <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2021-09-08T22:24:34.000000Z"</span>,
            <span class="slctrl-attr">"managers"</span>: <span class="slctrl-string">"119"</span>,
            <span class="slctrl-attr">"sale_persons"</span>: <span class="slctrl-string">"0"</span>,
            <span class="slctrl-attr">"emails"</span>: [
                {
                    <span class="slctrl-attr">"email"</span>: <span class="slctrl-string">"elie@yokigroup.com"</span>
                },
                {
                    <span class="slctrl-attr">"email"</span>: <span class="slctrl-string">"devi@yokigroup.com"</span>
                },
                {
                    <span class="slctrl-attr">"email"</span>: <span class="slctrl-string">"samantha@cachb.com"</span>
                }
            ],
            <span class="slctrl-attr">"requirements"</span>: <span class="slctrl-string">"NO Trucking NO Customs               [!!!!!!!!!!!!!!!!!!!!!!]       \n---------------------------\nAN: Need telex, notify:  Telex Pending (Sundy&Lydia) 1651359290@qq.com, lydia001205@vip.163.com\nDO: don't even send it out - Mark DO confirmed. \nRandom: @Ryan, Chassis Usage?  (Ryan) ryan.nguyen.lax@unique-logistics.com,\n                 If trucking:  WAREHOUSE: #'s to confirm before; Javier Salazar Tel: (714) (323) 742-3870 Dulce Olivarez Tel:(626) 608-5490 Ext 2413"</span>,
            <span class="slctrl-attr">"credit_term_freight"</span>: <span class="slctrl-number">0</span>,
            <span class="slctrl-attr">"credit_term_duty"</span>: <span class="slctrl-number">0</span>,
            <span class="slctrl-attr">"ein"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"booking_email_recipients"</span>: <span class="slctrl-string">"[]"</span>,
            <span class="slctrl-attr">"loi"</span>: <span class="slctrl-string">"customers/81179f359120099142e62e20adbd0f48.pdf"</span>,
            <span class="slctrl-attr">"offices_managers"</span>: <span class="slctrl-string">"[{\"office_id\": 1, \"manager_id\": 119}, {\"office_id\": 2, \"manager_id\": 41}, {\"office_id\": 3, \"manager_id\": 120}, {\"office_id\": 4, \"manager_id\": 150}, {\"office_id\": 5, \"manager_id\": 40}]"</span>,
            <span class="slctrl-attr">"qbo_customer"</span>: <span class="slctrl-string">"{\"customer\":{\"Id\":\"25\",\"DisplayName\":\"Yoki Shoes\",\"FullyQualifiedName\":\"Yoki Shoes\",\"Balance\":\"64980.00\",\"PrimaryEmailAddr\":{\"Id\":null,\"Address\":\"devi@yokigroup.com\",\"Default\":null,\"Tag\":null},\"GivenName\":null,\"FamilyName\":null,\"BillAddr\":null},\"company\":\"shifl Inc\",\"realm_id\":123146157027444}"</span>,
            <span class="slctrl-attr">"poa"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"default_duty_layout"</span>: <span class="slctrl-number">0</span>,
            <span class="slctrl-attr">"created_by"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"last_updated_by"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"last_updated"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"handling_freight"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"handling_trucking"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"handling_customs"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"default_requirements_section"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"confirmed_default_requirements"</span>: <span class="slctrl-number">0</span>
        },
        <span class="slctrl-attr">"containers"</span>: [],
        <span class="slctrl-attr">"suppliers"</span>: [],
        <span class="slctrl-attr">"terminal_regions"</span>: [],
        <span class="slctrl-attr">"shipment_schedules"</span>: [
            {
                <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">160</span>,
                <span class="slctrl-attr">"carrier_name"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"mode"</span>: <span class="slctrl-string">""</span>,
                <span class="slctrl-attr">"location_from"</span>: <span class="slctrl-string">""</span>,
                <span class="slctrl-attr">"location_to"</span>: <span class="slctrl-string">""</span>,
                <span class="slctrl-attr">"etd_date"</span>: <span class="slctrl-string">"2020-01-13"</span>,
                <span class="slctrl-attr">"eta_date"</span>: <span class="slctrl-string">"2020-01-26"</span>,
                <span class="slctrl-attr">"shipment_id"</span>: <span class="slctrl-number">14</span>,
                <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"unique_id"</span>: <span class="slctrl-string">"1583767339886"</span>,
                <span class="slctrl-attr">"is_new"</span>: <span class="slctrl-number">1</span>,
                <span class="slctrl-attr">"size_id"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"price"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"selling_size_id"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"selling_price"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"is_confirmed"</span>: <span class="slctrl-number">0</span>
            }
        ],
        <span class="slctrl-attr">"terminal_fortynine"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"invoice"</span>: [],
        <span class="slctrl-attr">"bill"</span>: []
    }
} </pre>








        <div style="margin-bottom: 10px">
            <div>
                <h4 style="display: inline-block">Responses</h4>
            </div>
            <div>
                <table class="table-bordered">
                    <thead>
                    <tr>
                        <th colspan="2"><span class="badge-success">200 collection of Carrier</span></th>
                    </tr>
                    <tr>
                        <th>RESPONSE SCHEMA: </th>
                        <th>application/json</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>

                            <td

                                    data-toggle="collapse"
                                    data-target="#get-shipment-v2-data"
                                    aria-expanded="false"
                                    aria-controls="get-shipment-v2-data"
                                    style="cursor: pointer;"
                                    class="glyphicon1 shipment-v2-data"
                                    rel="shipment-data-v2"

                            >
                                        <span class="shipment-data-v2">
                                           data <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                        </span>
                            </td>

                            <td>object</td>
                        </tr>



                        <tr  class="collapse" id="get-shipment-data">
                            <td colspan="2">
                                <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                    <table class="table-bordered">
                                        <thead></thead>
                                        <tbody>
                                            <tr>
                                                <td>id</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>po_num</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>mbl_num</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>type</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>term</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>customer_id</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>shipment_status</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>shifl_ref</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>origin_country</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>destination_country</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>etd</td>
                                                <td><i>Timestamp</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>eta</td>
                                                <td><i>Timestamp</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>custom_notes</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>total_cbm</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>total_kg</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>total_cartons</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>created_at</td>
                                                <td><i>Timestamp</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>updated_at</td>
                                                <td><i>Timestamp</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>carrier_id</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>suppliers_group</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>schedules_group</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>containers_group</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>mbl_copy</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>debit_note</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>vessel</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>booking_confirmed</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>arrival_notice_confirmed</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>freight_released_processed</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>customs_processed</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>DO_confirmed</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>freight_confirmed</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>customs_released</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>pending_refund</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>delivered</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>billed</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>cancelled</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>shipment_left</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>terminal_id</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>arrival_notice</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>cloned_from</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>trucker_id</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>trucker_custom_note</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>delivery_order_left</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>custom_content</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>copy_customer</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>memo_customer</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>suppliers_group_bookings</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>containers_group_bookings</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>schedules_group_bookings</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>booking_confirmed_at</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>mbl_shipper</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>mbl_shipper_address</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>mbl_shipper_phone_number</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>mbl_consignee</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>mbl_consignee_address</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>mbl_consignee_phone_number</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>mbl_notify</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>mbl_notify_address</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>mbl_notify_phone_number</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>mbl_description</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>mbl_marks</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>entry_netchb_submitted</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>entry_netchb_no</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>entry_netchb_date</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>entry_data</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>tracking_request_id</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>do_sent_at</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>an_sent_at</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>retry_tracking_request</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>suppliers_commercial_docs</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>services_section</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>netchb_pdf</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>netchb_xml</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>shifl_office_origin_from_id</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>shifl_office_origin_to_id</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>rate_confirmed</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>so_released</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>released_at_terminal</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>isf_done</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>ams_done"</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>is_tracking_shipment</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>containers_group_untracked</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>untracked_tool_last_updated_at</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>carrier_arrival_notice_eta</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>carrier_arrival_notice_firms</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>tracked_up_to</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>customs_sent</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>customs_sent_at</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>mbl_released_confirmed</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>mbl_copy_surrendered</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>is_schedule_requested</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>customs_broker_id</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>status_v2</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>


                                            <tr>
                                                <td>ais_link</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>carrier</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>location_to_name</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>location_from_name</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>obl_filled</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>commercial_documents_filled</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>suppliers_name</td>
                                                <td><i>Object</i></td>
                                            </tr>
                                            <tr>
                                                <td>shipment_suppliers</td>
                                                <td><i>Object</i></td>
                                            </tr>
                                            <tr>
                                                <td>projected_profit</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>profitt</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td

                                                        data-toggle="collapse"
                                                        data-target="#get-shipment-v2-customer"
                                                        aria-expanded="false"
                                                        aria-controls="get-shipment-v2-customer"
                                                        style="cursor: pointer;"
                                                        class="glyphicon1 shipment-v2-customer"
                                                        rel="shipment-v2-customer"

                                                >


                                                        <span class="shipment-v2-customer">
                                                           customer <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                                        </span>


                                                    </td>
                                                <td><i>Object</i></td>
                                            </tr>

                                            <tr  class="collapse" id="get-shipment-v2-customer">
                                                <td colspan="2">
                                                    <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                        <table class="table-bordered">
                                                            <thead></thead>
                                                            <tbody>
                                                            <tr>
                                                                <td>id</td>
                                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                                            </tr>
                                                            <tr>
                                                                <td>company_name</td>
                                                                <td><i>String</i> Default: <code>NULL</code></td>
                                                            </tr>
                                                            <tr>
                                                                <td>address</td>
                                                                <td><i>String</i> Default: <code>NULL</code></td>
                                                            </tr>
                                                            <tr>
                                                                <td>phone</td>
                                                                <td><i>String</i> Default: <code>NULL</code></td>
                                                            </tr>
                                                            <tr>
                                                                <td>admin_user_id</td>
                                                                <td><i>String</i> Default: <code>NULL</code></td>
                                                            </tr>
                                                            <tr>
                                                                <td>created_at</td>
                                                                <td><i>Timestamp</i> Default: <code>NULL</code></td>
                                                            </tr>
                                                            <tr>
                                                                <td>updated_at</td>
                                                                <td><i>Timestamp</i> Default: <code>NULL</code></td>
                                                            </tr>
                                                            <tr>
                                                                <td>managers</td>
                                                                <td><i>String</i> Default: <code>NULL</code></td>
                                                            </tr>
                                                            <tr>
                                                                <td>sale_persons</td>
                                                                <td><i>String</i> Default: <code>NULL</code></td>
                                                            </tr>
                                                            <tr>
                                                                <td>emails</td>
                                                                <td><i>Array</i></td>
                                                            </tr>
                                                            <tr>
                                                                <td>requirements</td>
                                                                <td><i>String</i> Default: <code>NULL</code></td>
                                                            </tr>
                                                            <tr>
                                                                <td>credit_term_freight</td>
                                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                                            </tr>
                                                            <tr>
                                                                <td>credit_term_duty</td>
                                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                                            </tr>
                                                            <tr>
                                                                <td>ein</td>
                                                                <td><i>String</i> Default: <code>NULL</code></td>
                                                            </tr>
                                                            <tr>
                                                                <td>booking_email_recipients</td>
                                                                <td><i>String</i> Default: <code>NULL</code></td>
                                                            </tr>
                                                            <tr>
                                                                <td>loi</td>
                                                                <td><i>String</i> Default: <code>NULL</code></td>
                                                            </tr>
                                                            <tr>
                                                                <td>offices_managers</td>
                                                                <td><i>String</i> Default: <code>NULL</code></td>
                                                            </tr>
                                                            <tr>
                                                                <td>qbo_customer</td>
                                                                <td><i>String</i> Default: <code>NULL</code></td>
                                                            </tr>
                                                            <tr>
                                                                <td>poa</td>
                                                                <td><i>String</i> Default: <code>NULL</code></td>
                                                            </tr>
                                                            <tr>
                                                                <td>default_duty_layout</td>
                                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                                            </tr>
                                                            <tr>
                                                                <td>created_by</td>
                                                                <td><i>String</i> Default: <code>NULL</code></td>
                                                            </tr>
                                                            <tr>
                                                                <td>last_updated_by</td>
                                                                <td><i>String</i> Default: <code>NULL</code></td>
                                                            </tr>
                                                            <tr>
                                                                <td>last_updated</td>
                                                                <td><i>String</i> Default: <code>NULL</code></td>
                                                            </tr>
                                                            <tr>
                                                                <td>handling_freight</td>
                                                                <td><i>String</i> Default: <code>NULL</code></td>
                                                            </tr>
                                                            <tr>
                                                                <td>handling_trucking</td>
                                                                <td><i>String</i> Default: <code>NULL</code></td>
                                                            </tr>
                                                            <tr>
                                                                <td>handling_customs</td>
                                                                <td><i>String</i> Default: <code>NULL</code></td>
                                                            </tr>
                                                            <tr>
                                                                <td>default_requirements_section</td>
                                                                <td><i>String</i> Default: <code>NULL</code></td>
                                                            </tr>
                                                            <tr>
                                                                <td>confirmed_default_requirements</td>
                                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>


                                            <tr>
                                                <td>containers</td>
                                                <td><i>Object</i></td>
                                            </tr>

                                            <tr>
                                                <td>suppliers</td>
                                                <td><i>Object</i></td>
                                            </tr>

                                            <tr>
                                                <td>terminal_regions</td>
                                                <td><i>Object</i></td>
                                            </tr>

                                            <tr



                                                    data-toggle="collapse"
                                                    data-target="#get-shipment-v2-shipment_schedules"
                                                    aria-expanded="false"
                                                    aria-controls="get-shipment-v2-shipment_schedules"
                                                    style="cursor: pointer;"
                                                    class="glyphicon1 shipment-v2-shipment_schedules"
                                                    rel="shipment-v2-shipment_schedules"

                                            >
                                                <td>



                                                        <span class="shipment-v2-shipment_schedules">
                                                           shipment_schedules <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                                        </span>

                                                    </td>
                                                <td><i>Object</i></td>
                                            </tr>

                                            <tr  class="collapse" id="get-shipment-v2-shipment_schedules">
                                                <td colspan="2">
                                                    <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                        <table class="table-bordered">
                                                            <thead></thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>id</td>
                                                                    <td><i>Integer</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>carrier_name</td>
                                                                    <td><i>String</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>mode</td>
                                                                    <td><i>String</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>location_from</td>
                                                                    <td><i>String</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>location_to</td>
                                                                    <td><i>String</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>etd_date</td>
                                                                    <td><i>String</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>shipment_id</td>
                                                                    <td><i>Integer</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>created_at</td>
                                                                    <td><i>Timestamp</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>updated_at</td>
                                                                    <td><i>Timestamp</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>unique_id</td>
                                                                    <td><i>String</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>is_new</td>
                                                                    <td><i>Integer</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>price</td>
                                                                    <td><i>String</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>selling_size_id</td>
                                                                    <td><i>String</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>selling_price</td>
                                                                    <td><i>String</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>is_confirmed</td>
                                                                    <td><i>String</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>terminal_fortynine</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>invoice</td>
                                                <td><i>Object</i></td>
                                            </tr>
                                            <tr>
                                                <td>bill</td>
                                                <td><i>Object</i></td>
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


        <p>
            {{ EndPointHelper::userGuide() }}
        </p>
    </div><!-- End Specified resource v2 -->

    <div class="col-sm-12 hidden" id="shipment-po-v2"><!-- Shipment by PO v2 -->
<h3>{{ EndPointHelper::get('Shipment BY PO V2') }}</h3>
<p>
    {{ EndPointHelper::getDescription('Shipment BY PO V2', 'id') }}
</p>
<br>
<div class="" style="position: relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
    <b>Route: </b> &nbsp;
    <span class="glyphicon1" rel="i-v2-shipment-po" aria-hidden="true">
                <span
                        data-toggle="collapse"
                        data-target="#route-v2-shipment-po"
                        aria-expanded="false"
                        aria-controls="route-v2-shipment-po"
                        style="cursor: pointer"
                        class="i-v2-shipment-po"
                > <code>api/v2/shipment-po</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                </span>
            </span>

    <div class="collapse api-route" id="route-v2-shipment-po">
        <div class="badge-success v2-shipment-po hidden" style="border-radius: 6px !important; ">
            <span >Copied</span>
        </div>
        <div class="flex-port">
            <p>SHIFL CENTRAL API</p>
            <div  class="copy-api" rel="v2-shipment-po">Copy</div>
        </div>

        <div class="api_ noselect" id="v2-shipment-po">
            {{ config('app.url')}}/api/v2/shipment-po
        </div>
    </div>

    <br>
    <b>Request Type: &nbsp;</b> <code>GET</code> <br>
    <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
    <b>Query Form Data required: &nbsp;</b><code>po_number</code>,
    <br>
    <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
    <b>Will Return: &nbsp;</b> <code>Shipment by PO v2</code> <small> </small><br>

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
    <span class="slctrl-attr">"po_number"</span>: <span class="slctrl-attr">"{po_number}"</span>
} </pre>



<div>
    <div>
        <h4 style="display: inline-block">REQUEST</h4>
    </div>
    <table class="table-bordered">
        <thead>
        <tr>
            <th colspan="2">Body Parameters</th>
        </tr>
        <tr>
            <th>Attribute</th>
            <th>Description</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>po_number<span class="badge-danger">required</span></td>
            <td><i>String</i> The po_number for the Shipment BY PO V2 to be retrieved</td>
        </tr>
        </tbody>
    </table>
</div>


<h4>Response</h4>
<pre>
{
    <span class="slctrl-attr">"status"</span>: <span class="slctrl-attr">"ok"</span>
}
</pre>






<div style="margin-bottom: 10px">
    <div>
        <h4 style="display: inline-block">Responses</h4>
    </div>
    <div>
        <table class="table-bordered">
            <thead>
            <tr>
                <th colspan="2"><span class="badge-success">200 collection of Carrier</span></th>
            </tr>
            <tr>
                <th>RESPONSE SCHEMA: </th>
                <th>application/json</th>
            </tr>
            </thead>
            <tbody>
            <tr>

                <td>
                    Status
                </td>

                <td> Status message to indicate that the action is fail or success</td>
            </tr>

            </tbody>
        </table>
    </div>
</div>
<p>
    {{ EndPointHelper::userGuide() }}
</p>
</div><!-- End Shipment by PO v2 -->

    <div class="col-sm-12 hidden" id="pending-quote-shipments"><!-- Pending Quote Shipments -->

        <h3>Pending Quote Shipments</h3>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/v2/pending-quote-shipments</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Query Form Data required: &nbsp;</b><code>per_page</code>,
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Pending Quote Shipments</code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>
        <h4> Form Data </h4>
        <pre>
{
    "per_page": "{per_page}"
}
</pre>

        <h4>Response</h4>
        <pre>
{
    "status": "Undefined offset: 0"
}
</pre>
        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>
    </div><!-- End Pending Quote Shipments -->

    <div class="col-sm-12 hidden" id="snooze-shipment-v2"><!-- Snooze Shipment v2 -->

        <h3>Snooze Shipment v2</h3>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/v2/snooze-shipments</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Query Form Data required: &nbsp;</b><code>per_page</code>,
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Pending Quote Shipments</code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>
        <h4> Form Data </h4>
        <pre>
{
    "per_page": "{per_page}"
}
</pre>

        <h4>Response</h4>
        <pre>
{
    "data": [],
    "links": {
        "first": "http://staging.shifl.test:81/api/v2/snooze-shipments?page=1",
        "last": "http://staging.shifl.test:81/api/v2/snooze-shipments?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": null,
        "last_page": 1,
        "path": "http://staging.shifl.test:81/api/v2/snooze-shipments",
        "per_page": "30",
        "to": null,
        "total": 0
    }
}
</pre>
        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>
    </div><!-- End Snooze Shipment v2 -->

    <div class="col-sm-12 hidden" id="shipment-transit-completed"><!-- Transit/Completed -->

        <h3>Transit/Completed</h3>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/v2/shipments-completed-merge</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Query Form Data required: &nbsp;</b><code>per_page</code>, <code>sort</code>, <code>order</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Transit Completed Merge</code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>
        <h4> Form Data </h4>
        <pre>
{
    per_page: "{per_page}",
    sort: "{sort}",
    order: "{order}"
}
</pre>

        <h4>Response</h4>
        <pre>
{
    "data": [],
    "links": {
        "first": "http://staging.shifl.test:81/api/v2/snooze-shipments?page=1",
        "last": "http://staging.shifl.test:81/api/v2/snooze-shipments?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": null,
        "last_page": 1,
        "path": "http://staging.shifl.test:81/api/v2/snooze-shipments",
        "per_page": "30",
        "to": null,
        "total": 0
    }
}
</pre>
        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>
    </div><!-- End Transit/Completed -->

</div> <!-- end of Shipment -->