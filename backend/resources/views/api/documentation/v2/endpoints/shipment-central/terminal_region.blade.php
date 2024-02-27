
{{-- /////////////////////////////// --}}


<!-- Terminal Region -->
<div class="col-sm-12 hidden" id="terminal-region">
    <h3 class="page-header">Terminal Region</h3>
    <!-- Specified resource -->
    <div class="col-sm-12" id="terminal-region-specified">
        <h3>Specified resource</h3>

        <p>
            Specified resource for Terminal Region endpoint.
            To access their Terminal Region specific detail, we need to have the id.
            And the endpoint will return the specific Terminal Region regarding the given id.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/terminal/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Specific resources</code> <small> </small><br>

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
    "id" : {id},
}
</pre>

        <h4>Response</h4>
        <pre style="font-weight:bold">
{
    "data": {
        "id": 1,
        "name": "NY/NJ",
        "code": "4601",
        "created_at": "2020-02-28T07:00:49.000000Z",
        "updated_at": "2021-06-11T22:31:52.000000Z",
        "country_id": 2
    }
}
                                                        </pre>
        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>
    </div><!-- End specified resource -->
</div> <!-- End Terminal Region -->

