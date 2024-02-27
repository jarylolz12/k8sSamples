
{{-- /////////////////////////////// --}}


<!-- Statement -->
<div class="col-sm-12" id="statement">
    <h3 class="page-header">Statement</h3>
    <!-- List resource -->
    <div class="col-sm-12" id="statement-list">
        <h3>List resource</h3>
        <p>
            List resource for Statement endpoint.
            To access their Statement details, the api already get the data per page
            and the endpoint return json.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/suppliers</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>per_page</code>, <code>page</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>List of Statement</code> <small> </small><br>

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
    "per_page" : 5,
    "page" : 1,
}
</pre>

        <h4>Response</h4>
        <pre style="font-weight:bold">
  {
    "data": [],
    "links": {
        "first": "api/suppliers?page=1",
        "last": "api/suppliers?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": null,
        "last_page": 1,
        "path": "api/suppliers",
        "per_page": 50,
        "to": null,
        "total": 0
    }
}
 </pre>
        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>
    </div><!-- End List resource -->
</div> <!-- End Purchase Order -->

