<!-- Tracking Shipment -->
<div class="col-sm-12" id="tracking-shipment">
    <h3 class="page-header">{{ EndPointHelper::endPoint('Tracking Shipment') }}</h3>
    <div class="col-sm-12" id="tracking-shipment-create"><!-- Tracking Shipment create resource -->
        <h3>{{ EndPointHelper::create('Tracking Shipment') }}</h3>
        <p>
            {{ EndPointHelper::createDescription('Tracking Shipment', array('mbl_num', 'po_num')) }}
        </p>
        <br>
        <div id="carrier-type-specified" class="" style="position:relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-tracking-shipments-new" aria-hidden="true">
                <span
                    data-toggle="collapse"
                    data-target="#route-tracking-shipments-new"
                    aria-expanded="false"
                    aria-controls="route-tracking-shipments-new"
                    style="cursor: pointer"
                    class="i-tracking-shipments-new"
                > <code>api/tracking-shipments/new</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                </span>
            </span>
            <div class="collapse api-route" id="route-tracking-shipments-new">
                <div class="badge-success tracking-shipments-new hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="tracking-shipments-new">Copy</div>
                </div>

                <div class="api_ noselect" id="tracking-shipments-new">
                    {{ config('app.url')}}/api/tracking-shipments/new
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b>
            <code>mbl_num</code>,
            <code>po_num</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Tracking Shipment (Create Tracking Shipment) </code> <small> </small><br>
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
     <span class="slctrl-attr">"mbl_num"</span>:  <span class="slctrl-string">"{mbl_num}"</span>,
     <span class="slctrl-attr">"po_num"</span>:  <span class="slctrl-string">"{po_num}"</span>
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
                    <td><i>String</i> The mbl_num for the Tracking Shipment to create</td>
                </tr>
                <tr>
                    <td>po_num<span class="badge-danger">required</span></td>
                    <td><i>String</i> The po_num for the Tracking Shipment to create</td>
                </tr>
                </tbody>
            </table>
        </div>
        <h4>Response
            <span
                data-toggle="collapse"
                data-target="#tracking-shipments-new-response"
                aria-expanded="false"
                aria-controls="tracking-shipments-new-response"
                style="cursor: pointer; top: 2px !important;"
                class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre class="collapse" id="tracking-shipments-new-response">
{
    <span class="slctrl-attr">"data"</span>: {
        <span class="slctrl-attr">"mbl_num"</span>: <span class="slctrl-string">"{mbl_num}"</span>,
        <span class="slctrl-attr">"po_num"</span>: <span class="slctrl-string">"{mbl_num}"</span>,
        <span class="slctrl-attr">"is_tracking_shipment"</span>: <span class="slctrl-literal">true</span>,
        <span class="slctrl-attr">"customer_id"</span>: <span class="slctrl-number">1</span>
    },
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
                            data-target="#create-tracking-shipment-data"
                            aria-expanded="false"
                            aria-controls="create-tracking-shipment-data"
                            style="cursor: pointer;  "
                            class="glyphicon1 tracking-shipment-data"
                            rel="tracking-shipment-data"
                        >
                            <span class="tracking-shipment-data">
                               data <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                            </span>
                        </td>
                        <td>object</td>
                    </tr>
                    <tr  class="collapse" id="create-tracking-shipment-data">
                        <td colspan="2">
                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                <table class="table-bordered">
                                    <thead></thead>
                                    <tbody>
                                    <tr>
                                        <td>mbl_num</td>
                                        <td><i>String</i> Default: <code>NULL</code></td>
                                    </tr>
                                    <tr>
                                        <td>po_num</td>
                                        <td><i>String</i> Default: <code>NULL</code></td>
                                    </tr>
                                    <tr>
                                        <td>is_tracking_shipment</td>
                                        <td><i>Boolean</i> Default: <code>NULL</code></td>
                                    </tr>
                                    <tr>
                                        <td>customer_id</td>
                                        <td><i>Integer</i> Default: <code>NULL</code></td>
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
    </div><!-- End Tracking Shipment Create resource -->

    <!-- Tracking Shipment Delete -->
<div class="col-sm-12"  id="tracking-shipment-destroy" >
    <h3>
        {{ EndPointHelper::delete('Tracking Shipment') }}
    </h3>
    <p>
        {{ EndPointHelper::deleteDescription('Tracking Shipment', array('shipment_id')) }}
    </p>
    <br>
    <div class="" style="position:relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
        <b>Route: </b> &nbsp;
        <span class="glyphicon1" rel="i-tracking-shipments-id-delete" aria-hidden="true">
                <span
                    data-toggle="collapse"
                    data-target="#route-tracking-shipments-id-delete"
                    aria-expanded="false"
                    aria-controls="route-tracking-shipments-id-delete"
                    style="cursor: pointer"
                    class="i-tracking-shipments-id-delete"
                > <code>api/tracking-shipments/{shipment_id}/delete</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                </span>
            </span>
        <div class="collapse api-route" id="route-tracking-shipments-id-delete">
            <div class="badge-success tracking-shipments-id-delete hidden" style="border-radius: 6px !important; ">
                <span >Copied</span>
            </div>
            <div class="flex-port">
                <p>SHIFL CENTRAL API</p>
                <div  class="copy-api" rel="tracking-shipments-id-delete">Copy</div>
            </div>
            <div class="api_ noselect" id="tracking-shipments-id-delete">
                {{ config('app.url')}}/api/tracking-shipments/{shipment_id}/delete
            </div>
        </div>
        <br>
        <b>Request Type: &nbsp;</b> <code>DELETE</code> <br>
        <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
        <b>Body Form Data required: &nbsp;</b> <code>shipment_id</code><br>
        <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
        <b>Will Return: &nbsp;</b> <code>Tracking Shipment (Delete Tracking Shipment) </code> <small> </small><br>
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
    <span class="slctrl-attr">"shipment_id"</span>: <span class="slctrl-string">"{shipment_id}"</span>,
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
                    <td><i>Integer</i> The shipment_id for the Tracking Shipment to delete</td>
                </tr>
            </tbody>
        </table>
    </div>
    <h4>Response
        &nbsp
        <span
            data-toggle="collapse"
            data-target="#tracking-shipments-id-delete-response"
            aria-expanded="false"
            aria-controls="tracking-shipments-id-delete-response"
            style="cursor: pointer; top: 2px !important;"
            class="glyphicon glyphicon-eye-open">
        </span>
    </h4>
    <pre class="collapse" id="tracking-shipments-id-delete-response">
{
    <span class="slctrl-attr">"message"</span>: <span class="slctrl-string">"Shipment Deleted Successfully"</span>
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
                        Message
                    </td>
                    <td> Delete message to indicate that the action is fail or success</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <p>
        {{ EndPointHelper::userGuide() }}
    </p>
</div><!-- end Tracking Shipment Delete -->
</div> <!-- end Tracking Shipment -->
