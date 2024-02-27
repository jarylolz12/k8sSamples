
{{-- /////////////////////////////// --}}


<!-- Terminal 49 Shipment -->
<div class="col-sm-12 hidden" id="terminal-49-shipment">
    <h3 class="page-header">Terminal 49 Shipment</h3>


    <!-- Get Terminal 49 Shipement by MBL_NUM -->
    <div class="col-sm-12" id="get-terminal-49-shipment">
        <h3>Get Terminal 49 Shipement by MBL_NUM</h3>

        <p>
            Get Terminal 49 Shipement by mbl_num endpoint.
            To access their Terminal 49 Shipement detail, we need to have the mbl_num.
            And the endpoint will return the specific Terminal 49 Shipement regarding the given mbl_num.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/terminal49/shipment/{mbl_num}</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>mbl_num</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Terminal 49 Shipement by MBL_NUM</code> <small> </small><br>

        </div>
        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Authorization" : "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>


        <h4> Form Data </h4>
        <pre style="font-weight:bold">
{
    "mbl_num" : {mbl_num},
}
</pre>

        <h4>Response</h4>
        <pre style="font-weight:bold">
{
    "attributes": {
        "created_at": "2021-04-06T16:05:27Z",
        "ref_numbers": null,
        "tags": [],
        "bill_of_lading_number": "COSU6290946880",
        "shipping_line_scac": "COSU",
        "shipping_line_name": "COSCO",
        "shipping_line_short_name": "COSCO",
        "port_of_lading_locode": "MYWSP",
        "port_of_lading_name": "Port Klang - Westport",
        "port_of_discharge_locode": "USLAX",
        "port_of_discharge_name": "Los Angeles",
        "pod_vessel_name": "APL SENTOSA",
        "pod_vessel_imo": "9632040",
        "pod_voyage_number": "0TUGFE1MA",
        "destination_locode": null,
        "destination_name": null,
        "destination_timezone": null,
        "destination_ata_at": null,
        "destination_eta_at": null,
        "pol_etd_at": null,
        "pol_atd_at": "2021-03-04T04:48:00Z",
        "pol_timezone": "Asia/Kuala_Lumpur",
        "pod_eta_at": "2021-04-10T15:00:00Z",
        "pod_ata_at": "2021-04-10T21:52:00Z",
        "pod_timezone": "America/Los_Angeles",
        "line_tracking_last_attempted_at": "2021-04-28T08:24:05Z",
        "line_tracking_last_succeeded_at": "2021-04-28T08:25:41Z",
        "line_tracking_stopped_at": "2021-04-28T08:25:48Z",
        "line_tracking_stopped_reason": "all_containers_terminated"
    },
    "containers": {
        "CSNU1945531": {
            "last_free_day": "2021-04-21T07:00:00Z",
            "released_at_terminal": false,
            "holds": [],
            "fees": [],
            "pickup_appointment_at": null,
            "container_details": {
                "number": "CSNU1945531",
                "seal_number": "17023647",
                "created_at": "2021-04-08T03:10:55Z",
                "equipment_type": "dry",
                "equipment_length": 20,
                "equipment_height": "standard",
                "weight_in_lbs": 48546,
                "fees_at_pod_terminal": [],
                "holds_at_pod_terminal": [],
                "pickup_lfd": "2021-04-21T07:00:00Z",
                "pickup_appointment_at": null,
                "pod_full_out_chassis_number": null,
                "location_at_pod_terminal": "T-XP88011-Y (OUT)",
                "availability_known": true,
                "available_for_pickup": false,
                "pod_arrived_at": "2021-04-10T21:52:00Z",
                "pod_discharged_at": "2021-04-15T22:00:00Z",
                "final_destination_full_out_at": null,
                "pod_full_out_at": "2021-04-20T16:44:00Z",
                "empty_terminated_at": "2021-04-28T04:59:00Z"
            }
        },
        "CSNU1779153": {
            "last_free_day": "2021-04-20T07:00:00Z",
            "released_at_terminal": false,
            "holds": [],
            "fees": [],
            "pickup_appointment_at": null,
            "container_details": {
                "number": "CSNU1779153",
                "seal_number": "17023646",
                "created_at": "2021-04-08T03:10:55Z",
                "equipment_type": "dry",
                "equipment_length": 20,
                "equipment_height": "standard",
                "weight_in_lbs": 49339,
                "fees_at_pod_terminal": [],
                "holds_at_pod_terminal": [],
                "pickup_lfd": "2021-04-20T07:00:00Z",
                "pickup_appointment_at": null,
                "pod_full_out_chassis_number": null,
                "location_at_pod_terminal": "T-9E90541-TIP-Y (OUT)",
                "availability_known": true,
                "available_for_pickup": false,
                "pod_arrived_at": "2021-04-10T21:52:00Z",
                "pod_discharged_at": "2021-04-14T15:40:00Z",
                "final_destination_full_out_at": null,
                "pod_full_out_at": "2021-04-19T23:40:00Z",
                "empty_terminated_at": "2021-04-27T18:54:00Z"
            }
        }
    }
}
                                                        </pre>
        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>
    </div><!-- End Get Terminal 49 Shipement by MBL_NUM -->
</div> <!-- End Terminal 149 Shipment -->

