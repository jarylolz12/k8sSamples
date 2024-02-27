



{{-- /////////////////////////////// --}}

<div class="col-sm-12 hidden" id="profit-monitor"> <!-- start Profit Monitor -->
    <h3 class="page-header">Profit Monitor</h3>




    <div class="col-sm-12" id="profit-monitor-calculated-shipment-v2"><!-- Calculated Shipment V2 -->

        <h3>Calculated Shipment V2</h3>
        <p>
            Calculated Shipment V2 for Profit Monitor endpoint.
            To access their Profit Monitor Calculated Shipment V2, the api already display the data
            and the endpoint return json.
        </p>
        <br>


        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/get-shipments-v2</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>NONE</code> <br>
            <b>Body Form Data required: &nbsp;</b>  <code>None</code>,
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Calculated Shipment V2</code> <small> </small><br>

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
   data: []
}
                                                        </pre>
        <p>
            In this endpoints user can access api without providing valid token on header
        </p>
    </div><!-- End Calculated Shipment V2 -->








    <div class="col-sm-12" id="profit-monitor-qbo-companies"><!-- Get QBO Companies-->

        <h3>Get QBO Companies</h3>
        <p>
            Get QBO Companies for Profile Monitor endpoint.
            To access their Profile Monitor QBO Companies, the api already display the data
            and the endpoint return json.
        </p>
        <br>


        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/get-qbo-companies</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>NONE</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>NONE</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Get QBO Companies</code> <small> </small><br>

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
[
    {
        "id": 1,
        "company_realm_id": "123146157027444",
        "company_name": "shifl Inc"
    },
    {
        "id": 2,
        "company_realm_id": "9130350316636896",
        "company_name": "SHIFL INDIA PVT LTD"
    },
    {
        "id": 3,
        "company_realm_id": "9130350295704376",
        "company_name": "Shifl China"
    }
]
                                                        </pre>
        <p>
            In this endpoints user can access api without providing valid token on header
        </p>
    </div><!-- End profit monitor Get QBO Companies -->


    <div class="col-sm-12" id="profit-monitor-sale-representatives"><!-- sale representatives-->

        <h3>Sale Representatives</h3>
        <p>
            Sale Representatives for Profile Monitor endpoint.
            To access their Profile Monitor Sale Representatives, the api already display the data
            and the endpoint return json.
        </p>
        <br>


        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/get-sales-representatives</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>NONE</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>NONE</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Sale Representatives </code> <small> </small><br>

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
[
    {
        "id": 1,
        "name": "John Doe"
    },
    {...},...

]
                                                        </pre>
        <p>
            In this endpoints user can access api without providing valid token on header
        </p>
    </div><!-- End profit monitor sale representatives -->


    <div class="col-sm-12" id="profit-monitor-total-profit-by-request"><!-- Total profit by request-->

        <h3>Total profit by request</h3>
        <p>
            Total profit by request for Profile Monitor endpoint.
            To access their Profile Monitor Total profit by request, the api already display the data
            and the endpoint return json.
        </p>
        <br>


        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/get-profit-by-request</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>NONE</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>NONE</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Total profit by request </code> <small> </small><br>

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
    "message": "No results found"
}
                                                        </pre>
        <p>
            In this endpoints user can access api without providing valid token on header
        </p>
    </div><!-- End profit monitor Total profit by request -->


</div> <!-- end of profit monitor -->