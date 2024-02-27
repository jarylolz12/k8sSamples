<!-- Search -->
<div class="col-sm-12" id="search">
    <h3 class="page-header">{{ EndPointHelper::endPoint('Search') }}</h3>
    <!-- search shipment -->
    <div class="col-sm-12" id="search-shipment">
        <h3>{{ EndPointHelper::get('Shipment Search') }}</h3>
        <div class="" style="position:relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-shipments-search" aria-hidden="true">
                <span
                    data-toggle="collapse"
                    data-target="#route-shipments-search"
                    aria-expanded="false"
                    aria-controls="route-shipments-search"
                    style="cursor: pointer"
                    class="i-shipments-search"
                > <code>api/shipments/search</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                </span>
            </span>
            <div class="collapse api-route" id="route-shipments-search">
                <div class="badge-success shipments-search hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="shipments-search">Copy</div>
                </div>
                <div class="api_ noselect" id="shipments-search">
                    {{ config('app.url')}}/api/shipments/search
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>search_text</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Search for Shipment</code> <small> </small><br>
        </div>
        <h4>Header</h4>
        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"Authorization"</span>: <span class="slctrl-string">"Bearer {access_token}"</span>
    <span class="slctrl-attr">"Content-Type"</span>: <span class="slctrl-string"> "application/json" </span>
    <span class="slctrl-attr">"Accept"</span>: <span class="slctrl-string"> "application/json" </span>
} </pre>
        <h4> Form Data </h4>
        <pre style="font-weight:bold">
{
   <span class="slctrl-attr">"search_text"</span>: <span class="slctrl-string">"{search_text}"</span>
} </pre>
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
                    <td>search_text<span class="badge-danger">required</span></td>
                    <td><i>String</i> The string or words for the Search to be retrieved</td>
                </tr>
                </tbody>
            </table>
        </div>
        <h4>Response
            <span
                data-toggle="collapse"
                data-target="#shipments-search-response"
                aria-expanded="false"
                aria-controls="shipments-search-response"
                style="cursor: pointer; top: 2px !important;"
                class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre style="font-weight:bold" class="collapse" id="shipments-search-response">
{
    <span class="slctrl-attr">"data"</span>: {
        <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">2</span>,
        <span class="slctrl-attr">"po_num"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"mbl_num"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"type"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"term"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"customer_id"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"shipment_status"</span>: <span class="slctrl-string">"Cancelled"</span>,
        <span class="slctrl-attr">"shifl_ref"</span>: <span class="slctrl-string">"60717"</span>,
        <span class="slctrl-attr">"origin_country"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"destination_country"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"etd"</span>: <span class="slctrl-string">"2020-01-24T00:00:00.000000Z"</span>,
        <span class="slctrl-attr">"eta"</span>: <span class="slctrl-string">"2020-02-08T00:00:00.000000Z"</span>,
        <span class="slctrl-attr">"custom_notes"</span>: <span class="slctrl-string">"SHIPMENT CANCELLED"</span>,
        <span class="slctrl-attr">"total_cbm"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"total_kg"</span>: <span class="slctrl-literal">"null</span>,
        <span class="slctrl-attr">"total_cartons"</span>: <span class="slctrl-literal">"null</span>,
        <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2020-01-08T00:35:22.000000Z"</span>,
        <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2020-03-09T23:23:22.000000Z"</span>,
        <span class="slctrl-attr">"carrier_id"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"suppliers_group"</span>: <span class="slctrl-string">"[]"</span>,
        <span class="slctrl-attr">"schedules_group"</span>: <span class="slctrl-string">"[{\"id\":1583767324377,\"eta\":\"2020-02-08\",\"etd\":\"2020-01-24\",\"location_from\":\"\",\"location_to\":\"\",\"mode\":\"\",\"etaError\":{},\"etdError\":{}}]"</span>,
        <span class="slctrl-attr">"containers_group"</span>: <span class="slctrl-string">"[]"</span>,
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
        <span class="slctrl-attr">"suppliers_group_bookings"</span>: <span class="slctrl-string">""</span>,
        <span class="slctrl-attr">"containers_group_bookings"</span>: <span class="slctrl-string">""</span>,
        <span class="slctrl-attr">"schedules_group_bookings"</span>: <span class="slctrl-string">"[{\"id\":1583767324377,\"eta\":\"2020-02-08\",\"etd\":\"2020-01-24\",\"location_from\":\"\",\"location_to\":\"\",\"mode\":\"\",\"etaError\":{},\"etdError\":{},\"legs\":[],\"type\":\"\",\"carrier_name\":\"\",\"size_id\":0,\"price\":null,\"selling_size_id\":0,\"selling_price\":null,\"sell_rates\":[],\"buy_rates\":[],\"is_confirmed\":0,\"location_from_name\":\"\",\"location_to_name\":\"\",\"size_name\":\"\",\"selling_size_name\":\"\"}]"</span>,
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
        <span class="slctrl-attr">"projected_profit"</span>: <span class="slctrl-string">"No selected schedule"</span>,
        <span class="slctrl-attr">"profitt"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"invoice"</span>: [],
        <span class="slctrl-attr">"bill"</span>: []
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
                            <td
                                data-toggle="collapse"
                                data-target="#get-shipment-seach-data"
                                aria-expanded="false"
                                aria-controls="get-shipment-seach-data"
                                style="cursor: pointer;  "

                                class="glyphicon1 icn-shipment-search-data"
                                rel="icn-shipment-search-data"
                            >
                                <span class="icn-shipment-search-data">
                                   data <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                </span>
                            </td>
                            <td>object</td>
                        </tr>
                        <tr  class="collapse" id="get-shipment-seach-data">
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
                                                <td>po_num</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>mbl_num</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>type</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>term</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>customer_id</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>shipment_status</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>shifl_ref</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>origin_country</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>destination_country</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>etd</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>eta</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>custom_notes</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>total_cbm</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>total_kg</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>total_cartons</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>custom_notes</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
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
                                                <td>carrier_id</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>suppliers_group</td>
                                                <td><i>Object</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>schedules_group</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>containers_group</td>
                                                <td><i>Object</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>mbl_copy</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>debit_note</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>vessel</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>booking_confirmed</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>arrival_notice_confirmed</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>freight_released_processed</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>customs_processed</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>DO_confirmed</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>freight_confirmed</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>customs_released</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>pending_refund</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>delivered</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>billed</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>cancelled</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>shipment_left</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>terminal_id</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>cloned_from</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>trucker_id</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>trucker_custom_note</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>delivery_order_left</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>custom_content</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>copy_customer</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>memo_customer</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>suppliers_group_bookings</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>containers_group_bookings</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>schedules_group_bookings</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>booking_confirmed_at</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>mbl_shipper</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>mbl_shipper_address</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>mbl_shipper_phone_number</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>mbl_consignee</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>mbl_consignee_address</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>mbl_consignee_phone_number</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>mbl_notify</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>mbl_notify_address</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>mbl_notify_phone_number</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>mbl_description</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>mbl_marks</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>entry_netchb_submitted</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>entry_netchb_no</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>entry_netchb_date</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>entry_data</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>tracking_request_id</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>do_sent_at</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>an_sent_at</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>retry_tracking_request</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>suppliers_commercial_docs</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>services_section</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>netchb_pdf</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>netchb_xml</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>shifl_office_origin_from_id</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>shifl_office_origin_to_id</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>rate_confirmed</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>so_released</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>released_at_terminal</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>isf_done</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>ams_done</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>is_tracking_shipment</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>containers_group_untracked</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>untracked_tool_last_updated_at</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>carrier_arrival_notice_eta</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>carrier_arrival_notice_firms</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>tracked_up_to</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>customs_sent</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>customs_sent_at</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>mbl_released_confirmed</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>mbl_copy_surrendered</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>is_schedule_requested</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>customs_broker_id</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>projected_profit</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>profitt</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>invoice</td>
                                                <td><i>Object</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>bill</td>
                                                <td><i>Object</i> Default: <code>NULL</code>,</td>
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
    </div><!-- End search shipment -->

    <!-- Specified resource -->
    <div class="col-sm-12" id="search-specified">
        <h3>{{ EndPointHelper::get('Search') }}</h3>
        <p>
            {{ EndPointHelper::getDescription('Search', 'search_text') }}
        </p>
        <br>
        <div class="" style="position:relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-api-search" aria-hidden="true">
                <span
                    data-toggle="collapse"
                    data-target="#route-api-search"
                    aria-expanded="false"
                    aria-controls="route-api-search"
                    style="cursor: pointer"
                    class="i-api-search"
                > <code>api/search</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                </span>
            </span>
            <div class="collapse api-route" id="route-api-search">
                <div class="badge-success api-search hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="api-search">Copy</div>
                </div>
                <div class="api_ noselect" id="api-search">
                    {{ config('app.url')}}/api/search
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>search_text</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Specified search</code> <small> </small><br>
        </div>
        <h4>Header</h4>
        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"Authorization"</span>: <span class="slctrl-string">"Bearer {access_token}"</span>
    <span class="slctrl-attr">"Content-Type"</span>: <span class="slctrl-string"> "application/json" </span>
    <span class="slctrl-attr">"Accept"</span>: <span class="slctrl-string"> "application/json" </span>
} </pre>
        <h4> Form Data </h4>
        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"search_text"</span>: <span class="slctrl-string">"{search_text}"</span>,
}
</pre>
        <div>
            <div>
                <h4 style="display: inline-block">REQUEST</h4>
            </div>
            <table class="table-bordered"  style="width: 50%">
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
                        <td>search_text<span class="badge-danger">required</span></td>
                        <td><i>String</i> The string or words for the Search to be retrieved</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <h4>Response
            <span
                data-toggle="collapse"
                data-target="#api-search-response"
                aria-expanded="false"
                aria-controls="api-search-response"
                style="cursor: pointer; top: 2px !important;"
                class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre style="font-weight:bold" class="collapse" id="api-search-response">
{
    <span class="slctrl-attr">"data"</span>: {
        <span class="slctrl-attr">"name"</span>: <span class="slctrl-string">"Demetris Bartell"</span>,
        <span class="slctrl-attr">"email"</span>: <span class="slctrl-string">"bahringer.kristina@example.net"</span>,
        <span class="slctrl-attr">"email_verified_at"</span>: <span class="slctrl-string">"2022-08-02T16:31:12.000000Z"</span>
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
                                <td
                                    data-toggle="collapse"
                                    data-target="#get-seach-data"
                                    aria-expanded="false"
                                    aria-controls="get-seach-data"
                                    style="cursor: pointer;  "

                                    class="glyphicon1 icn-search-data"
                                    rel="icn-search-data"
                                >
                                    <span class="icn-search-data">
                                       data <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                    </span>
                                </td>
                                <td>object</td>
                            </tr>
                            <tr  class="collapse" id="get-seach-data">
                                <td colspan="2">
                                    <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                        <table class="table-bordered" style="width: 100%">
                                            <thead></thead>
                                            <tbody>
                                                <tr>
                                                    <td>name</td>
                                                    <td><i>String</i> Default: <code>NULL</code>,</td>
                                                </tr>
                                                <tr>
                                                    <td>email</td>
                                                    <td><i>String</i> Default: <code>NULL</code>,</td>
                                                </tr>
                                                <tr>
                                                    <td>email_verified_at</td>
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
    </div><!-- End specified search -->

    <!-- Specified resource -->
    <div class="col-sm-12" id="search-customer">
        <h3>{{ EndPointHelper::get('Customer Search') }}</h3>
        <p>
            {{ EndPointHelper::getDescription('Customer Search', 'search_text') }}
        </p>
        <br>
        <div class="" style="position:relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-customers-search" aria-hidden="true">
                <span
                    data-toggle="collapse"
                    data-target="#route-customers-search"
                    aria-expanded="false"
                    aria-controls="route-customers-search"
                    style="cursor: pointer"
                    class="i-customers-search"
                > <code>api/customers/search</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                </span>
            </span>
            <div class="collapse api-route" id="route-customers-search">
                <div class="badge-success customers-search hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="customers-search">Copy</div>
                </div>

                <div class="api_ noselect" id="customers-search">
                    {{ config('app.url')}}/api/customers/search
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>search_text</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Specified search</code> <small> </small><br>
        </div>
        <h4>Header</h4>
        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"Authorization"</span>: <span class="slctrl-string">"Bearer {access_token}"</span>
    <span class="slctrl-attr">"Content-Type"</span>: <span class="slctrl-string"> "application/json" </span>
    <span class="slctrl-attr">"Accept"</span>: <span class="slctrl-string"> "application/json" </span>
} </pre>
        <h4> Form Data </h4>
        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"search_text"</span>: <span class="slctrl-string">"{search_text}"</span>,
}
</pre>
        <div>
            <div>
                <h4 style="display: inline-block">REQUEST</h4>
            </div>
            <table class="table-bordered"  style="width: 50%">
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
                    <td>search_text<span class="badge-danger">required</span></td>
                    <td><i>String</i> The string or words for the Search to be retrieved</td>
                </tr>
                </tbody>
            </table>
        </div>
        <h4>Response
            <span
                data-toggle="collapse"
                data-target="#customers-search-response"
                aria-expanded="false"
                aria-controls="customers-search-response"
                style="cursor: pointer; top: 2px !important;"
                class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre style="font-weight:bold" class="collapse" id="customers-search-response">
{
    <span class="slctrl-attr">"data"</span>: {
        <span class="slctrl-attr">"name"</span>: <span class="slctrl-string">"John Doe"</span>,
        <span class="slctrl-attr">"email"</span>: <span class="slctrl-string">"johndoe@example.net"</span>,
        <span class="slctrl-attr">"email_verified_at"</span>: <span class="slctrl-string">"2022-08-02T16:31:12.000000Z"</span>
    }
} </pre>
        <div style="margin-bottom: 10px">
            <div>
                <h4 style="display: inline-block">Responses</h4>
            </div>
            <div>
                <table class="table-bordered"  style="width: 50%">
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
                                data-target="#get-customers-seach-data"
                                aria-expanded="false"
                                aria-controls="get-customers-seach-data"
                                style="cursor: pointer;  "

                                class="glyphicon1 icn-customers-search-data"
                                rel="icn-customers-search-data"
                            >
                                <span class="icn-customers-search-data">
                                   data <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                </span>
                            </td>
                            <td>object</td>
                        </tr>
                        <tr  class="collapse" id="get-customers-seach-data">
                            <td colspan="2">
                                <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                    <table class="table-bordered"  style="width: 100%">
                                        <thead></thead>
                                        <tbody>
                                        <tr>
                                            <td>name</td>
                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                        </tr>
                                        <tr>
                                            <td>email</td>
                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                        </tr>
                                        <tr>
                                            <td>email_verified_at</td>
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
    </div><!-- End specified search -->
</div> <!-- End Search -->

