
{{-- /////////////////////////////// --}}

<div class="col-sm-12 hidden" id="eta-log"> <!-- start Eta logs -->
    <h3 class="page-header">Eta logs</h3>
    <div class="col-sm-12" id="eta-log-specified"><!-- Eta logs specified resource -->

        <h3>Specified resource</h3>
        <p>
            Specified resource for Eta logs endpoint.
            To access their Eta logs specific detail, we need to have the mbl_num.
            And the endpoint will return the specific Eta logs regarding the given mbl_num.
        </p>
        <br>


        <div id="carrier-type-specified" class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/eta_logs/{mbl_num}</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>mbl_num</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Eta logs (Specific Eta logs) </code> <small> </small><br>

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
    "mbl_num" : "{mbl_num}",
}
</pre>

        <h4>Response</h4>
        <pre>
[
    {
        "id": 1,
        "mbl_num": "WHLC031B545167",
        "old_eta": "2021-07-19T00:00:00.000000Z",
        "eta": "2021-07-22T00:00:00.000000Z",
        "created_at": "2021-07-19T20:44:37.000000Z"
    }
]
                                                        </pre>
        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>
    </div><!-- End Eta logs specified resource -->
</div>   <!-- End Eta logs -->
