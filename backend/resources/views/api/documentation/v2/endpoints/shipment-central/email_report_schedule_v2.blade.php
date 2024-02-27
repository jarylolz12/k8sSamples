<div class="col-sm-12" id="email-report-schedule-v2" > <!-- Start Email Report Schedule Version 2-->

    <div class="col-sm-12" id="display-shipment-default-column"><!-- Email Report Schedule specified resource -->
        <h3>{{ EndPointHelper::get('Display Shipment Default Column') }}</h3>
        <p>
            {{ EndPointHelper::getAllDescription('Display Shipment Default Column') }};
        </p>
        <br>
        <div class="" style="position:relative;padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b>
            <span class="glyphicon1" rel="i-display-shipment-default-column" aria-hidden="true">
                    <span
                            data-toggle="collapse"
                            data-target="#route-display-shipment-default-column"
                            aria-expanded="false"
                            aria-controls="route-display-shipment-default-column"
                            style="cursor: pointer"
                            class="i-display-shipment-default-column"
                    > <code>api/shipment-fields</code>
                        <span class="glyphicon glyphicon-menu-right " aria-hidden="true">
                        </span>
                    </span>
                </span>
            <div class="collapse api-route" id="route-display-shipment-default-column">
                <div class="badge-success display-shipment-default-column hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">


                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="display-shipment-default-column">Copy</div>
                </div>

                <div class="api_ noselect" id="display-shipment-default-column">
                    {{ config('app.url')}}/api/shipment-fields
                </div>
            </div>  <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>None</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Display Shipment Default Column </code> <small> </small><br>
        </div>

        <h4>Header</h4>

        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"Authorization"</span> : <span class="slctrl-string">"Bearer {access_token}"</span>
    <span class="slctrl-attr">"Content-Type"</span> : <span class="slctrl-string"> "application/json" </span>
    <span class="slctrl-attr">"Accept"</span> : <span class="slctrl-string"> "application/json" </span>
} </pre>


        <h4> Form Data </h4>
        <pre>
{
    <span class="slctrl-attr">None</span>
 }</pre>



        <div>
            <div>
                <h4 style="display: inline-block">REQUEST</h4>
            </div>
            <table class="table-bordered" style="width: 50%">
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
                    <td>None</td>
                 </tr>
                </tbody>
            </table>
        </div>

        <h4>Response

            <span
                    data-toggle="collapse"
                    data-target="#display-shipment-default-column-response"
                    aria-expanded="false"
                    aria-controls="display-shipment-default-column-response"
                    style="cursor: pointer; top: 2px !important;"
                    class="glyphicon glyphicon-eye-open">
            </span>

        </h4>
        <pre  style="font-weight:bold" class="collapse" id="display-shipment-default-column-response">
{
    <span class="slctrl-attr">"by_reference"</span>: {
        <span class="slctrl-attr">"1"</span>: <span class="slctrl-string">"Shifl Ref#"</span>,
        <span class="slctrl-attr">"2"</span>: <span class="slctrl-string">"MBL#"</span>,
        <span class="slctrl-attr">"3"</span>: <span class="slctrl-string">"Consignee"</span>,
        <span class="slctrl-attr">"4"</span>: <span class="slctrl-string">"Status"</span>,
        <span class="slctrl-attr">"5"</span>: <span class="slctrl-string">"Booked Date"</span>,
        <span class="slctrl-attr">"6"</span>: <span class="slctrl-string">"PO#"</span>,
        <span class="slctrl-attr">"7"</span>: <span class="slctrl-string">"Shipper"</span>,
        <span class="slctrl-attr">"8"</span>: <span class="slctrl-string">"Supplier Cartons"</span>,
        <span class="slctrl-attr">"9"</span>: <span class="slctrl-string">"HBL#"</span>,
        <span class="slctrl-attr">"10"</span>: <span class="slctrl-string">"Telex Release"</span>,
        <span class="slctrl-attr">"11"</span>: <span class="slctrl-string">"Type"</span>,
        <span class="slctrl-attr">"12"</span>: <span class="slctrl-string">"Mode"</span>,
        <span class="slctrl-attr">"13"</span>: <span class="slctrl-string">"Carrier"</span>,
        <span class="slctrl-attr">"14"</span>: <span class="slctrl-string">"Vessel Name"</span>,
        <span class="slctrl-attr">"15"</span>: <span class="slctrl-string">"Voyage #"</span>,
        <span class="slctrl-attr">"16"</span>: <span class="slctrl-string">"Total Cartons"</span>,
        <span class="slctrl-attr">"17"</span>: <span class="slctrl-string">"No. of Containers"</span>,
        <span class="slctrl-attr">"18"</span>: <span class="slctrl-string">"Container#"</span>,
        <span class="slctrl-attr">"19"</span>: <span class="slctrl-string">"Container Seal#"</span>,
        <span class="slctrl-attr">"20"</span>: <span class="slctrl-string">"Container Size"</span>,
        <span class="slctrl-attr">"21"</span>: <span class="slctrl-string">"Container Weight (kg)"</span>,
        <span class="slctrl-attr">"22"</span>: <span class="slctrl-string">"Container Cartons"</span>,
        <span class="slctrl-attr">"23"</span>: <span class="slctrl-string">"Container Volume"</span>,
        <span class="slctrl-attr">"24"</span>: <span class="slctrl-string">"Freight Cost"</span>,
        <span class="slctrl-attr">"25"</span>: <span class="slctrl-string">"Current ETD"</span>,
        <span class="slctrl-attr">"26"</span>: <span class="slctrl-string">"Current ETA"</span>,
        <span class="slctrl-attr">"27"</span>: <span class="slctrl-string">"Original ETA"</span>,
        <span class="slctrl-attr">"28"</span>: <span class="slctrl-string">"POL"</span>,
        <span class="slctrl-attr">"29"</span>: <span class="slctrl-string">"POD"</span>,
        <span class="slctrl-attr">"30"</span>: <span class="slctrl-string">"Cargo Ready Date"</span>,
        <span class="slctrl-attr">"31"</span>: <span class="slctrl-string">"Empty Out"</span>,
        <span class="slctrl-attr">"32"</span>: <span class="slctrl-string">"Gated In"</span>,
        <span class="slctrl-attr">"33"</span>: <span class="slctrl-string">"Terminal"</span>,
        <span class="slctrl-attr">"34"</span>: <span class="slctrl-string">"Shifl AN Sent"</span>,
        <span class="slctrl-attr">"35"</span>: <span class="slctrl-string">"Shifl DO Sent"</span>,
        <span class="slctrl-attr">"36"</span>: <span class="slctrl-string">"Delivery Loc (WRHS)"</span>,
        <span class="slctrl-attr">"37"</span>: <span class="slctrl-string">"Freight Release"</span>,
        <span class="slctrl-attr">"38"</span>: <span class="slctrl-string">"Customs Release"</span>,
        <span class="slctrl-attr">"39"</span>: <span class="slctrl-string">"Discharge"</span>,
        <span class="slctrl-attr">"40"</span>: <span class="slctrl-string">"LFD(latest)"</span>,
        <span class="slctrl-attr">"41"</span>: <span class="slctrl-string">"Full Out"</span>,
        <span class="slctrl-attr">"42"</span>: <span class="slctrl-string">"Empty In"</span>,
        <span class="slctrl-attr">"43"</span>: <span class="slctrl-string">"Demurrage Days"</span>,
        <span class="slctrl-attr">"44"</span>: <span class="slctrl-string">"Dmrg Rate Per Day"</span>,
        <span class="slctrl-attr">"45"</span>: <span class="slctrl-string">"Demurrage Total"</span>,
        <span class="slctrl-attr">"46"</span>: <span class="slctrl-string">"Per Diem Total"</span>,
        <span class="slctrl-attr">"47"</span>: <span class="slctrl-string">"Pier Pass Total"</span>,
        <span class="slctrl-attr">"48"</span>: <span class="slctrl-string">"Other Totals"</span>,
        <span class="slctrl-attr">"49"</span>: <span class="slctrl-string">"Customs Total"</span>,
        <span class="slctrl-attr">"50"</span>: <span class="slctrl-string">"Other Services Total"</span>,
        <span class="slctrl-attr">"51"</span>: <span class="slctrl-string">"Tracking Status"</span>,
        <span class="slctrl-attr">"52"</span>: <span class="slctrl-string">"Booking#"</span>
    },
    <span class="slctrl-attr">"by_container"</span>: {
        <span class="slctrl-attr">"1"</span>: <span class="slctrl-string">"Shifl Ref#"</span>,
        <span class="slctrl-attr">"2"</span>: <span class="slctrl-string">"PO#"</span>,
        <span class="slctrl-attr">"3"</span>: <span class="slctrl-string">"Shipper"</span>,
        <span class="slctrl-attr">"4"</span>: <span class="slctrl-string">"Factory Cargo Ready Date"</span>,
        <span class="slctrl-attr">"5"</span>: <span class="slctrl-string">"Supplier Cartons"</span>,
        <span class="slctrl-attr">"6"</span>: <span class="slctrl-string">"Booked Date"</span>,
        <span class="slctrl-attr">"7"</span>: <span class="slctrl-string">"POL"</span>,
        <span class="slctrl-attr">"8"</span>: <span class="slctrl-string">"POD"</span>,
        <span class="slctrl-attr">"9"</span>: <span class="slctrl-string">"Delivery Loc (WRHS)"</span>,
        <span class="slctrl-attr">"10"</span>: <span class="slctrl-string">"Consignee"</span>,
        <span class="slctrl-attr">"11"</span>: <span class="slctrl-string">"Type"</span>,
        <span class="slctrl-attr">"12"</span>: <span class="slctrl-string">"Mode"</span>,
        <span class="slctrl-attr">"14"</span>: <span class="slctrl-string">"Carrier"</span>,
        <span class="slctrl-attr">"15"</span>: <span class="slctrl-string">"Vessel Name"</span>,
        <span class="slctrl-attr">"16"</span>: <span class="slctrl-string">"Voyage#"</span>,
        <span class="slctrl-attr">"17"</span>: <span class="slctrl-string">"Total Cartons"</span>,
        <span class="slctrl-attr">"18"</span>: <span class="slctrl-string">"Container#"</span>,
        <span class="slctrl-attr">"19"</span>: <span class="slctrl-string">"Container Sizes"</span>,
        <span class="slctrl-attr">"20"</span>: <span class="slctrl-string">"Container Weight (kg)"</span>,
        <span class="slctrl-attr">"21"</span>: <span class="slctrl-string">"Container Cartons"</span>,
        <span class="slctrl-attr">"22"</span>: <span class="slctrl-string">"Container Volume (cbm)"</span>,
        <span class="slctrl-attr">"23"</span>: <span class="slctrl-string">"Container Seal#"</span>,
        <span class="slctrl-attr">"24"</span>: <span class="slctrl-string">"Freight Rate"</span>,
        <span class="slctrl-attr">"25"</span>: <span class="slctrl-string">"Status"</span>,
        <span class="slctrl-attr">"26"</span>: <span class="slctrl-string">"HBL#"</span>,
        <span class="slctrl-attr">"27"</span>: <span class="slctrl-string">"Telex Release"</span>,
        <span class="slctrl-attr">"28"</span>: <span class="slctrl-string">"Current ETD"</span>,
        <span class="slctrl-attr">"29"</span>: <span class="slctrl-string">"Current ETA"</span>,
        <span class="slctrl-attr">"30"</span>: <span class="slctrl-string">"Original ETA"</span>,
        <span class="slctrl-attr">"31"</span>: <span class="slctrl-string">"Empty out to FTY"</span>,
        <span class="slctrl-attr">"32"</span>: <span class="slctrl-string">"Gated In POL"</span>,
        <span class="slctrl-attr">"33"</span>: <span class="slctrl-string">"Arrival Notice Sent"</span>,
        <span class="slctrl-attr">"34"</span>: <span class="slctrl-string">"DO Sent"</span>,
        <span class="slctrl-attr">"35"</span>: <span class="slctrl-string">"Freight Release"</span>,
        <span class="slctrl-attr">"36"</span>: <span class="slctrl-string">"Customs Release"</span>,
        <span class="slctrl-attr">"37"</span>: <span class="slctrl-string">"Discharge"</span>,
        <span class="slctrl-attr">"38"</span>: <span class="slctrl-string">"Latest LFD"</span>,
        <span class="slctrl-attr">"39"</span>: <span class="slctrl-string">"Container Picked Up"</span>,
        <span class="slctrl-attr">"40"</span>: <span class="slctrl-string">"Empty Container Returned"</span>,
        <span class="slctrl-attr">"41"</span>: <span class="slctrl-string">"Dmrg Rate Per Day"</span>,
        <span class="slctrl-attr">"42"</span>: <span class="slctrl-string">"Demurrage Days"</span>,
        <span class="slctrl-attr">"43"</span>: <span class="slctrl-string">"Demurrage Total"</span>,
        <span class="slctrl-attr">"44"</span>: <span class="slctrl-string">"Per Diem Rate Day"</span>,
        <span class="slctrl-attr">"45"</span>: <span class="slctrl-string">"Per Diem Days"</span>,
        <span class="slctrl-attr">"46"</span>: <span class="slctrl-string">"Total Per Diem"</span>,
        <span class="slctrl-attr">"47"</span>: <span class="slctrl-string">"Pier Pass"</span>,
        <span class="slctrl-attr">"48"</span>: <span class="slctrl-string">"Customs Entry Cost"</span>,
        <span class="slctrl-attr">"49"</span>: <span class="slctrl-string">"Other Charges"</span>,
        <span class="slctrl-attr">"50"</span>: <span class="slctrl-string">"Other Services Total"</span>,
        <span class="slctrl-attr">"51"</span>: <span class="slctrl-string">"Tracking Status"</span>,
        <span class="slctrl-attr">"52"</span>: <span class="slctrl-string">"Booking#"</span>
    }
} </pre>

        <div style="margin-bottom: 10px">
            <div>
                <h4 style="display: inline-block">Responses</h4>
            </div>
            <div>
                <table class="table-bordered" style="width: 50%">
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
                        <td>by_reference</td>
                        <td><i>Object</i> Default: <code>NULL</code>,</td>
                    </tr>
                    <tr>
                        <td>by_container</td>
                        <td><i>Object</i> Default: <code>NULL</code>,</td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
        <p>
            {{ EndPointHelper::userGuide() }}
        </p>
    </div><!-- End Email Report Schedule specified resource -->




























    <div class="col-sm-12" id="get-email-report-schedule-details"><!-- Email Report Schedule Details -->
        <h3>{{ EndPointHelper::get('Email Report Schedule Details') }}</h3>
        <p>
            {{ EndPointHelper::getDescription('Email Report Schedule Details', 'userid') }};
        </p>
        <br>
        <div class="" style="position:relative;padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b>
            <span class="glyphicon1" rel="i-get-email-report-schedule-details" aria-hidden="true">
                    <span
                            data-toggle="collapse"
                            data-target="#route-get-email-report-schedule-details"
                            aria-expanded="false"
                            aria-controls="route-get-email-report-schedule-details"
                            style="cursor: pointer"
                            class="i-get-email-report-schedule-details"
                    > <code>api/shipment-report/{userid}</code>
                        <span class="glyphicon glyphicon-menu-right " aria-hidden="true">
                        </span>
                    </span>
                </span>
            <div class="collapse api-route" id="route-get-email-report-schedule-details">
                <div class="badge-success get-email-report-schedule-details hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">


                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="get-email-report-schedule-details">Copy</div>
                </div>

                <div class="api_ noselect" id="get-email-report-schedule-details">
                    {{ config('app.url')}}/api/shipment-report/{userid}
                </div>
            </div>  <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>userid</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Get Email Report Schedule Details</code> <small> </small><br>
        </div>

        <h4>Header</h4>

        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"Authorization"</span> : <span class="slctrl-string">"Bearer {access_token}"</span>
    <span class="slctrl-attr">"Content-Type"</span> : <span class="slctrl-string"> "application/json" </span>
    <span class="slctrl-attr">"Accept"</span> : <span class="slctrl-string"> "application/json" </span>
} </pre>


        <h4> Form Data </h4>
        <pre>
{
    <span class="slctrl-attr">"user_id"</span> : <span class="slctrl-number">{user_id}</span>,
 }</pre>
        <div>
            <div>
                <h4 style="display: inline-block">REQUEST</h4>
            </div>
            <table class="table-bordered" style="width: 50%">
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
                    <td>user_id<span class="badge-danger">required</span></td>
                    <td><i>Integer</i> The unique user_id for the user to be retrieved</td>
                </tr>
                </tbody>
            </table>
        </div>

        <h4>Response

            <span
                    data-toggle="collapse"
                    data-target="#get-email-report-schedule-details-response"
                    aria-expanded="false"
                    aria-controls="get-email-report-schedule-details-response"
                    style="cursor: pointer; top: 2px !important;"
                    class="glyphicon glyphicon-eye-open">
            </span>

        </h4>
        <pre  style="font-weight:bold" class="collapse" id="get-email-report-schedule-details-response">
{
    <span class="slctrl-attr">"report_recipients"</span>: [
        <span class="slctrl-string">"mary@shifl.com"</span>,
        <span class="slctrl-string">"gale@shifl.com"</span>
    ],
    <span class="slctrl-attr">"email_schedule"</span>: [
        {
        <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">191</span>,
        <span class="slctrl-attr">"customer_admin_id"</span>: <span class="slctrl-number">458</span>,
        <span class="slctrl-attr">"frequency"</span>: <span class="slctrl-string">"DAILYAT"</span>,
        <span class="slctrl-attr">"day"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"time"</span>: <span class="slctrl-string">"01:00:00"</span>,
        <span class="slctrl-attr">"active"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2022-10-27T00:17:10.000000Z"</span>,
        <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2022-10-27T00:17:10.000000Z"</span>,
        <span class="slctrl-attr">"month_day"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"report_type"</span>: <span class="slctrl-string">"BYCONTAINER"</span>,
        <span class="slctrl-attr">"report_columns"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"report"</span>: <span class="slctrl-literal">null</span>
        },{...},...
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
                        <th colspan="2"><span class="badge-success">200 collection of Carrier</span></th>
                    </tr>
                    <tr>
                        <th>RESPONSE SCHEMA: </th>
                        <th>application/json</th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr>
                        <td>report_recipients</td>
                        <td><i>Array</i> Default: <code>NULL</code>,</td>
                    </tr>
                    <tr>
                        <td
                                data-toggle="collapse"
                                data-target="#get-email-report-schedule-details-email_schedule"
                                aria-expanded="false"
                                aria-controls="get-email-report-schedule-details-email_schedule"
                                style="cursor: pointer;  "

                                class="glyphicon1 icn-get-email-report-schedule-details-email_schedule"
                                rel="icn-get-email-report-schedule-details-email_schedule"
                        >
                                <span class="icn-get-email-report-schedule-details-email_schedule">
                                   email_schedule <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                </span>
                        </td>
                        <td>object</td>
                    </tr>

                    <tr  class="collapse" id="get-email-report-schedule-details-email_schedule">
                        <td colspan="2">
                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                <table class="table-bordered" style="width: 100%;">
                                    <thead></thead>
                                    <tbody>
                                    <tr>
                                        <td>id</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>customer_admin_id</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>frequency</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>day</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>time</td>
                                        <td><i>Time</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>active</td>
                                        <td><i>Boolean</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>created_at</td>
                                        <td><i>Timestamp</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>updated_at</td>
                                        <td><i>Timestamp</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>month_day</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>report_type</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>report_columns</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>report</td>
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
    </div>









    <div class="col-sm-12" id="update-status"><!-- Update Status -->
        <h3>{{ EndPointHelper::update('Status') }}</h3>
        <p>
            {{ EndPointHelper::updateDescription('Status', array('emailReportSchedId')) }};
        </p>
        <br>
        <div class="" style="position:relative;padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b>
            <span class="glyphicon1" rel="i-update-status" aria-hidden="true">
                    <span
                            data-toggle="collapse"
                            data-target="#route-update-status"
                            aria-expanded="false"
                            aria-controls="route-update-status"
                            style="cursor: pointer"
                            class="i-update-status"
                    > <code>api/shipment-report/update/{emailReportSchedId}</code>
                        <span class="glyphicon glyphicon-menu-right " aria-hidden="true">
                        </span>
                    </span>
                </span>
            <div class="collapse api-route" id="route-update-status">
                <div class="badge-success update-status hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">


                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="update-status">Copy</div>
                </div>

                <div class="api_ noselect" id="update-status">
                    {{ config('app.url')}}/api/shipment-report/update/{emailReportSchedId}
                </div>
            </div>  <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>POST Form Data required: &nbsp;</b> <code>emailReportSchedId</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Update Email Report Schedule Status</code> <small> </small><br>
        </div>

        <h4>Header</h4>

        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"Authorization"</span> : <span class="slctrl-string">"Bearer {access_token}"</span>
    <span class="slctrl-attr">"Content-Type"</span> : <span class="slctrl-string"> "application/json" </span>
    <span class="slctrl-attr">"Accept"</span> : <span class="slctrl-string"> "application/json" </span>
} </pre>


        <h4> Form Data </h4>
        <pre>
{
    <span class="slctrl-attr">"emailReportSchedId"</span> : <span class="slctrl-number">{emailReportSchedId}</span>,
 }</pre>
        <div>
            <div>
                <h4 style="display: inline-block">REQUEST</h4>
            </div>
            <table class="table-bordered" style="width: 50%">
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
                    <td>emailReportSchedId<span class="badge-danger">required</span></td>
                    <td><i>Integer</i> The unique Email Report ID for the user to be retrieved</td>
                </tr>
                </tbody>
            </table>
        </div>

        <h4>Response

            <span
                    data-toggle="collapse"
                    data-target="#update-status-response"
                    aria-expanded="false"
                    aria-controls="update-status-response"
                    style="cursor: pointer; top: 2px !important;"
                    class="glyphicon glyphicon-eye-open">
            </span>

        </h4>
        <pre  style="font-weight:bold" class="collapse" id="update-status-response">
{
    <span class="slctrl-attr">"message"</span>: <span class="slctrl-string">"Email Report Schedule status has been updated"</span>,
    <span class="slctrl-attr">"data"</span>: {
        <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">198</span>,
        <span class="slctrl-attr">"customer_admin_id"</span>: <span class="slctrl-number">458</span>,
        <span class="slctrl-attr">"frequency"</span>: <span class="slctrl-string">"DAILYAT"</span>,
        <span class="slctrl-attr">"day"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"time"</span>: <span class="slctrl-string">"01:00:00"</span>,
        <span class="slctrl-attr">"active"</span>: <span class="slctrl-string">"1"</span>,
        <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2022-11-04T09:33:35.000000Z"</span>,
        <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2022-11-08T09:43:50.000000Z"</span>,
        <span class="slctrl-attr">"month_day"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"report_type"</span>: <span class="slctrl-string">"BYREFERENCE"</span>,
        <span class="slctrl-attr">"report_columns"</span>: <span class="slctrl-string">"{\"1\":\"Shipper\",\"2\":\"Consignee\",\"3\":\"MBL#\",\"4\":\"Status\",\"5\":\"Booked Date\",\"6\":\"PO#\",\"7\":\"Shifl Ref#\",\"8\":\"Supplier Cartons\",\"9\":\"HBL#\",\"10\":\"Telex Release\",\"11\":\"Type\",\"12\":\"Mode\",\"13\":\"Carrier\",\"14\":\"Vessel Name\",\"15\":\"Voyage #\",\"16\":\"Total Cartons\",\"17\":\"No. of Containers\",\"18\":\"Container #\",\"19\":\"Container Seal#\",\"20\":\"Container Size\",\"21\":\"Container Weight (kg)\",\"22\":\"Container Cartons\",\"23\":\"Container Volume\",\"24\":\"Freight Cost\",\"25\":\"Current ETD\",\"26\":\"Current ETA\",\"27\":\"Original ETA\",\"28\":\"POL\",\"29\":\"POD\",\"30\":\"Cargo Ready Date\",\"31\":\"Empty Out\",\"32\":\"Gated In\",\"33\":\"Terminal\",\"34\":\"Shifl AN Sent\",\"35\":\"Shifl DO Sent\"}"</span>,
        <span class="slctrl-attr">"report"</span>: <span class="slctrl-number">2</span>
    }
}
 </pre>

        <div style="margin-bottom: 10px">
            <div>
                <h4 style="display: inline-block">Responses</h4>
            </div>
            <div>
                <table class="table-bordered" style="width: 50%">
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
                        <td>message</td>
                        <td><i>String</i> Default: <code>NULL</code>,</td>
                    </tr>
                    <tr>
                        <td
                                data-toggle="collapse"
                                data-target="#update-status-data"
                                aria-expanded="false"
                                aria-controls="update-status-data"
                                style="cursor: pointer;  "

                                class="glyphicon1 icn-update-status-data"
                                rel="icn-get-update-status-data"
                        >
                                <span class="icn-update-status-data">
                                   data <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                </span>
                        </td>
                        <td>object</td>
                    </tr>

                    <tr  class="collapse" id="update-status-data">
                        <td colspan="2">
                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                <table class="table-bordered" style="width: 100%">
                                    <thead></thead>
                                    <tbody>
                                    <tr>
                                        <td>id</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>customer_admin_id</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>frequency</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>day</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>time</td>
                                        <td><i>Time</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>active</td>
                                        <td><i>Boolean</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>created_at</td>
                                        <td><i>Timestamp</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>updated_at</td>
                                        <td><i>Timestamp</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>month_day</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>report_type</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>report_columns</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>report</td>
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
    </div>











    <div class="col-sm-12" id="delete-email-report-schedule"><!-- Delete Email Report Schedule -->
        <h3>{{ EndPointHelper::delete('Email Report Schedule') }}</h3>
        <p>
            {{ EndPointHelper::deleteDescription('Email Report Schedule', array('emailReportSchedId')) }};
        </p>
        <br>
        <div class="" style="position:relative;padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b>
            <span class="glyphicon1" rel="i-delete-email-report-schedule" aria-hidden="true">
                    <span
                            data-toggle="collapse"
                            data-target="#route-delete-email-report-schedule"
                            aria-expanded="false"
                            aria-controls="route-delete-email-report-schedule"
                            style="cursor: pointer"
                            class="i-delete-email-report-schedule"
                    > <code>api/shipment-report/delete/{emailReportSchedId}</code>
                        <span class="glyphicon glyphicon-menu-right " aria-hidden="true">
                        </span>
                    </span>
                </span>
            <div class="collapse api-route" id="route-delete-email-report-schedule">
                <div class="badge-success delete-email-report-schedule hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="delete-email-report-schedule">Copy</div>
                </div>

                <div class="api_ noselect" id="delete-email-report-schedule">
                    {{ config('app.url')}}/api/shipment-report/delete/{emailReportSchedId}
                </div>
            </div>  <br>
            <b>Request Type: &nbsp;</b> <code>DELETE</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>DELETE Form Data required: &nbsp;</b> <code>emailReportSchedId</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Delete Email Report Schedule Status</code> <small> </small><br>
        </div>

        <h4>Header</h4>

        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"Authorization"</span> : <span class="slctrl-string">"Bearer {access_token}"</span>
    <span class="slctrl-attr">"Content-Type"</span> : <span class="slctrl-string"> "application/json" </span>
    <span class="slctrl-attr">"Accept"</span> : <span class="slctrl-string"> "application/json" </span>
} </pre>


        <h4> Form Data </h4>
        <pre>
{
    <span class="slctrl-attr">"emailReportSchedId"</span> : <span class="slctrl-number">{emailReportSchedId}</span>,
 }</pre>
        <div>
            <div>
                <h4 style="display: inline-block">REQUEST</h4>
            </div>
            <table class="table-bordered" style="width: 50%">
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
                    <td>emailReportSchedId<span class="badge-danger">required</span></td>
                    <td><i>Integer</i> The unique Email Report ID for the user to be retrieved</td>
                </tr>
                </tbody>
            </table>
        </div>

        <h4>Response

            <span
                    data-toggle="collapse"
                    data-target="#delete-email-report-schedule-response"
                    aria-expanded="false"
                    aria-controls="delete-email-report-schedule-response"
                    style="cursor: pointer; top: 2px !important;"
                    class="glyphicon glyphicon-eye-open">
            </span>

        </h4>
        <pre  style="font-weight:bold" class="collapse" id="delete-email-report-schedule-response">
 {
    <span class="slctrl-attr">"status"</span>: <span class="slctrl-string">"ok"</span>,
    <span class="slctrl-attr">"message"</span>: <span class="slctrl-string">"Email Report Schedule Deleted Successfully"</span>
 }</pre>

        <div style="margin-bottom: 10px">
            <div>
                <h4 style="display: inline-block">Responses</h4>
            </div>
            <div>
                <table class="table-bordered" style="width: 50%">
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
                        <td>status</td>
                        <td><i>String</i> Default: <code>NULL</code>,</td>
                    </tr>

                    <tr>
                        <td>message</td>
                        <td><i>String</i> Default: <code>NULL</code>,</td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
        <p>
            {{ EndPointHelper::userGuide() }}
        </p>
    </div>











    <div class="col-sm-12" id="send-shipment-report-via-email"><!-- Send Shipment Report Via Email -->
        <h3> Send Shipment Report Via Email</h3>
        <p>
            {{ EndPointHelper::getAllDescription('Send Shipment Report Via Email') }};
        </p>
        <br>
        <div class="" style="position:relative;padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b>
            <span class="glyphicon1" rel="i-send-shipment-report-via-email" aria-hidden="true">
                    <span
                            data-toggle="collapse"
                            data-target="#route-send-shipment-report-via-email"
                            aria-expanded="false"
                            aria-controls="route-send-shipment-report-via-email"
                            style="cursor: pointer"
                            class="i-send-shipment-report-via-email"
                    > <code>api/shipment-report/send-report</code>
                        <span class="glyphicon glyphicon-menu-right " aria-hidden="true">
                        </span>
                    </span>
                </span>
            <div class="collapse api-route" id="route-send-shipment-report-via-email">
                <div class="badge-success send-shipment-report-via-email hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="send-shipment-report-via-email">Copy</div>
                </div>

                <div class="api_ noselect" id="send-shipment-report-via-email">
                    {{ config('app.url')}}/api/shipment-report/send-report
                </div>
            </div>  <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>POST Form Data required: &nbsp;</b> <code>id</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Send Shipment Report Via Email</code> <small> </small><br>
        </div>

        <h4>Header</h4>

        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"Authorization"</span> : <span class="slctrl-string">"Bearer {access_token}"</span>
    <span class="slctrl-attr">"Content-Type"</span> : <span class="slctrl-string"> "application/json" </span>
    <span class="slctrl-attr">"Accept"</span> : <span class="slctrl-string"> "application/json" </span>
} </pre>


        <h4> Form Data </h4>
        <pre>
{
    <span class="slctrl-attr">"id"</span> : <span class="slctrl-number">{id}</span>,
 }</pre>
        <div>
            <div>
                <h4 style="display: inline-block">REQUEST</h4>
            </div>
            <table class="table-bordered" style="width: 50%">
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
                    <td><i>Integer</i> The unique Email Report ID for the user to be retrieved</td>
                </tr>
                </tbody>
            </table>
        </div>

        <h4>Response

            <span
                    data-toggle="collapse"
                    data-target="#send-shipment-report-via-email-response"
                    aria-expanded="false"
                    aria-controls="send-shipment-report-via-email-response"
                    style="cursor: pointer; top: 2px !important;"
                    class="glyphicon glyphicon-eye-open">
            </span>

        </h4>
        <pre  style="font-weight:bold" class="collapse" id="send-shipment-report-via-email-response">
 {
    <span class="slctrl-attr">"status"</span>: <span class="slctrl-string">"ok"</span>,
    <span class="slctrl-attr">"message"</span>: <span class="slctrl-string">"Shipment Report Mail Sent"</span>
 }</pre>

        <div style="margin-bottom: 10px">
            <div>
                <h4 style="display: inline-block">Responses</h4>
            </div>
            <div>
                <table class="table-bordered" style="width: 50%">
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
                        <td>status</td>
                        <td><i>String</i> Default: <code>NULL</code>,</td>
                    </tr>

                    <tr>
                        <td>message</td>
                        <td><i>String</i> Default: <code>NULL</code>,</td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
        <p>
            {{ EndPointHelper::userGuide() }}
        </p>
    </div>












    <div class="col-sm-12" id="download-shipment-report-excel"><!-- Download Shipment Report Excel -->
        <h3> Download Shipment Report Excel</h3>
        <p>
            {{ EndPointHelper::getAllDescription('Download Shipment Report Excel') }};
        </p>
        <br>
        <div class="" style="position:relative;padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b>
            <span class="glyphicon1" rel="i-download-shipment-report-excel" aria-hidden="true">
                    <span
                            data-toggle="collapse"
                            data-target="#route-download-shipment-report-excel"
                            aria-expanded="false"
                            aria-controls="route-download-shipment-report-excel"
                            style="cursor: pointer"
                            class="i-download-shipment-report-excel"
                    > <code>api/download/email-report/{emailReportId}</code>
                        <span class="glyphicon glyphicon-menu-right " aria-hidden="true">
                        </span>
                    </span>
                </span>
            <div class="collapse api-route" id="route-download-shipment-report-excel">
                <div class="badge-success download-shipment-report-excel hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="download-shipment-report-excel">Copy</div>
                </div>

                <div class="api_ noselect" id="download-shipment-report-excel">
                    {{ config('app.url')}}/api/download/email-report/{emailReportId}
                </div>
            </div>  <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>emailReportId</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Download Shipment Report Excel</code> <small> </small><br>
        </div>

        <h4>Header</h4>

        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"Authorization"</span> : <span class="slctrl-string">"Bearer {access_token}"</span>
    <span class="slctrl-attr">"Content-Type"</span> : <span class="slctrl-string"> "application/json" </span>
    <span class="slctrl-attr">"Accept"</span> : <span class="slctrl-string"> "application/json" </span>
} </pre>


        <h4> Form Data </h4>
        <pre>
{
    <span class="slctrl-attr">"emailReportId"</span> : <span class="slctrl-number">{emailReportId}</span>,
 }</pre>
        <div>
            <div>
                <h4 style="display: inline-block">REQUEST</h4>
            </div>
            <table class="table-bordered" style="width: 50%">
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
                    <td>emailReportId<span class="badge-danger">required</span></td>
                    <td><i>Integer</i> The unique Email Report ID for the user to be retrieved</td>
                </tr>
                </tbody>
            </table>
        </div>

        <h4>Response

            <span
                    data-toggle="collapse"
                    data-target="#download-shipment-report-excel-response"
                    aria-expanded="false"
                    aria-controls="download-shipment-report-excel-response"
                    style="cursor: pointer; top: 2px !important;"
                    class="glyphicon glyphicon-eye-open">
            </span>

        </h4>
        <pre  style="font-weight:bold" class="collapse" id="download-shipment-report-excel-response">
 {
    <span class="slctrl-attr">"Downloadable File"</span>,
 }</pre>

        <div style="margin-bottom: 10px">
            <div>
                <h4 style="display: inline-block">Responses</h4>
            </div>
            <div>
                <table class="table-bordered" style="width: 50%">
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
                            <td>NONE</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <p>
            {{ EndPointHelper::userGuide() }}
        </p>
    </div>

























    <div class="col-sm-12" id="create-new-email-report-schedule"><!-- Create New Email Report Schedule -->
        <h3> Create New Email Report Schedule</h3>
        <p>
            {{ EndPointHelper::createDescription('New Email Report Schedule', array('customer_admin_id', 'active')) }};
        </p>
        <br>
        <div class="" style="position:relative;padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b>
            <span class="glyphicon1" rel="i-create-new-email-report-schedule" aria-hidden="true">
                    <span
                            data-toggle="collapse"
                            data-target="#route-create-new-email-report-schedule"
                            aria-expanded="false"
                            aria-controls="route-create-new-email-report-schedule"
                            style="cursor: pointer"
                            class="i-create-new-email-report-schedule"
                    > <code>api/shipment-report/new</code>
                        <span class="glyphicon glyphicon-menu-right " aria-hidden="true">
                        </span>
                    </span>
                </span>
            <div class="collapse api-route" id="route-create-new-email-report-schedule">
                <div class="badge-success create-new-email-report-schedule hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="create-new-email-report-schedule">Copy</div>
                </div>

                <div class="api_ noselect" id="create-new-email-report-schedule">
                    {{ config('app.url')}}/api/shipment-report/new
                </div>
            </div>  <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>POST Form Data required: &nbsp;</b> <code>customer_admin_id</code>, <code>active</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Create New Email Report Schedule</code> <small> </small><br>
        </div>

        <h4>Header</h4>

        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"Authorization"</span> : <span class="slctrl-string">"Bearer {access_token}"</span>
    <span class="slctrl-attr">"Content-Type"</span> : <span class="slctrl-string"> "application/json" </span>
    <span class="slctrl-attr">"Accept"</span> : <span class="slctrl-string"> "application/json" </span>
} </pre>


        <h4> Form Data </h4>
        <pre>
{
    <span class="slctrl-attr">"customer_admin_id"</span> : <span class="slctrl-number">{customer_admin_id}</span>,
    <span class="slctrl-attr">"active"</span> : <span class="slctrl-number">{active}</span>,
 }</pre>
        <div>
            <div>
                <h4 style="display: inline-block">REQUEST</h4>
            </div>
            <table class="table-bordered" style="width: 50%">
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
                    <td>customer_admin_id<span class="badge-danger">required</span></td>
                    <td><i>Integer</i> The unique Customer Admin ID to be retrieved</td>
                </tr>
                <tr>
                    <td>active<span class="badge-danger">required</span></td>
                    <td><i>Integer</i> The Active either 1|0 to be retrieved</td>
                </tr>
                </tbody>
            </table>
        </div>

        <h4>Response

            <span
                    data-toggle="collapse"
                    data-target="#create-new-email-report-schedule-response"
                    aria-expanded="false"
                    aria-controls="create-new-email-report-schedule-response"
                    style="cursor: pointer; top: 2px !important;"
                    class="glyphicon glyphicon-eye-open">
            </span>

        </h4>
        <pre  style="font-weight:bold" class="collapse" id="create-new-email-report-schedule-response">
 {
    <span class="slctrl-attr">"status"</span>: <span class="slctrl-literal">true</span>,
    <span class="slctrl-attr">"message"</span>: <span class="slctrl-string">"Successfully created email report"</span>
} </pre>

        <div style="margin-bottom: 10px">
            <div>
                <h4 style="display: inline-block">Responses</h4>
            </div>
            <div>
                <table class="table-bordered" style="width: 50%">
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
                            <td>status</td>
                            <td><i>String</i> Default: <code>NULL</code>,</td>
                        </tr>

                        <tr>
                            <td>message</td>
                            <td><i>String</i> Default: <code>NULL</code>,</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <p>
            {{ EndPointHelper::userGuide() }}
        </p>
    </div>



















    <div class="col-sm-12" id="edit-email-report-schedule"><!-- Edit Email Report Schedule -->
        <h3> {{ EndPointHelper::edit('Email Report Schedule') }}</h3>
        <p>
            {{ EndPointHelper::updateDescription('Email Report Schedule', array('id', 'customer_admin_id', 'active')) }};
        </p>
        <br>
        <div class="" style="position:relative;padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b>
            <span class="glyphicon1" rel="i-edit-email-report-schedule" aria-hidden="true">
                    <span
                            data-toggle="collapse"
                            data-target="#route-edit-email-report-schedule"
                            aria-expanded="false"
                            aria-controls="route-edit-email-report-schedule"
                            style="cursor: pointer"
                            class="i-edit-email-report-schedule"
                    > <code>api/shipment-report/edit/</code>
                        <span class="glyphicon glyphicon-menu-right " aria-hidden="true">
                        </span>
                    </span>
                </span>
            <div class="collapse api-route" id="route-edit-email-report-schedule">
                <div class="badge-success edit-email-report-schedule hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="edit-email-report-schedule">Copy</div>
                </div>

                <div class="api_ noselect" id="edit-email-report-schedule">
                    {{ config('app.url')}}/api/shipment-report/edit/
                </div>
            </div>  <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>POST Form Data required: &nbsp;</b><code>id</code>, <code>customer_admin_id</code>, <code>active</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Edit Email Report Schedule</code> <small> </small><br>
        </div>

        <h4>Header</h4>

        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"Authorization"</span> : <span class="slctrl-string">"Bearer {access_token}"</span>
    <span class="slctrl-attr">"Content-Type"</span> : <span class="slctrl-string"> "application/json" </span>
    <span class="slctrl-attr">"Accept"</span> : <span class="slctrl-string"> "application/json" </span>
} </pre>


        <h4> Form Data </h4>
        <pre>
{
    <span class="slctrl-attr">"id"</span> : <span class="slctrl-number">{id}</span>,
    <span class="slctrl-attr">"customer_admin_id"</span> : <span class="slctrl-number">{customer_admin_id}</span>,
    <span class="slctrl-attr">"active"</span> : <span class="slctrl-number">{active}</span>,
 }</pre>
        <div>
            <div>
                <h4 style="display: inline-block">REQUEST</h4>
            </div>
            <table class="table-bordered" style="width: 50%">
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
                        <td><i>Integer</i> The unique Email Report Schedule ID to be retrieved</td>
                    </tr>
                    <tr>
                        <td>customer_admin_id<span class="badge-danger">required</span></td>
                        <td><i>Integer</i> The unique User ID to be retrieved</td>
                    </tr>
                    <tr>
                        <td>active<span class="badge-danger">required</span></td>
                        <td><i>Integer</i> The Active either 1|0 to be retrieved</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h4>Response

            <span
                    data-toggle="collapse"
                    data-target="#edit-email-report-schedule-response"
                    aria-expanded="false"
                    aria-controls="edit-email-report-schedule-response"
                    style="cursor: pointer; top: 2px !important;"
                    class="glyphicon glyphicon-eye-open">
            </span>

        </h4>
        <pre  style="font-weight:bold" class="collapse" id="edit-email-report-schedule-response">
 {
    <span class="slctrl-attr">"status"</span>: <span class="slctrl-literal">true</span>,
    <span class="slctrl-attr">"data"</span>: {
        <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">197</span>,
        <span class="slctrl-attr">"customer_admin_id"</span>: <span class="slctrl-string">"458"</span>,
        <span class="slctrl-attr">"frequency"</span>: <span class="slctrl-string">"DAILYAT"</span>,
        <span class="slctrl-attr">"day"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"time"</span>: <span class="slctrl-string">"01:00:00"</span>,
        <span class="slctrl-attr">"active"</span>: <span class="slctrl-string">"1"</span>,
        <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2022-10-27T01:06:44.000000Z"</span>,
        <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2022-11-07T16:10:34.000000Z"</span>,
        <span class="slctrl-attr">"month_day"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"report_type"</span>: <span class="slctrl-string">"BYREFERENCEADV"</span>,
        <span class="slctrl-attr">"report_columns"</span>: <span class="slctrl-string">"{\"1\":\"Consignee\",\"2\":\"MBL#\",\"3\":\"Shifl Ref#\",\"4\":\"Freight Cost\",\"5\":\"Booked Date\",\"6\":\"PO#\",\"7\":\"Shipper\",\"8\":\"Supplier Cartons\",\"9\":\"HBL#\",\"10\":\"Telex Release\",\"11\":\"Type\",\"12\":\"Mode\",\"13\":\"Carrier\",\"14\":\"Vessel Name\",\"15\":\"Voyage #\",\"16\":\"Total Cartons\",\"17\":\"No. of Containers\",\"18\":\"Container#\",\"19\":\"Container Seal#\",\"20\":\"Container Size\",\"21\":\"Container Weight (kg)\",\"22\":\"Container Cartons\",\"23\":\"Container Volume\",\"24\":\"Status\"}"</span>,
        <span class="slctrl-attr">"report"</span>: <span class="slctrl-string">"2"</span>,
        <span class="slctrl-attr">"selected_customer"</span>: <span class="slctrl-literal">0</span>
    },
    <span class="slctrl-attr">"message"</span>: <span class="slctrl-string">"Successfully created email report"</span>
} </pre>

        <div style="margin-bottom: 10px">
            <div>
                <h4 style="display: inline-block">Responses</h4>
            </div>
            <div>
                <table class="table-bordered" style="width: 50%">
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
                        <td>status</td>
                        <td><i>String</i> Default: <code>NULL</code>,</td>
                    </tr>

                    <tr>
                        <td>message</td>
                        <td><i>String</i> Default: <code>NULL</code>,</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <p>
            {{ EndPointHelper::userGuide() }}
        </p>
    </div>





</div> <!-- start Email Report Schedule -->

