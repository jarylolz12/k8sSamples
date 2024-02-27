

{{-- /////////////////////////////// --}}

<div class="col-sm-12 hidden" id="custom-supplier"> <!-- start custom-supplier -->
    <h3 class="page-header">Custom Supplier</h3>
    <!-- Custom Supplier Specified resource -->
    <h3 id="custom-supplier-specified">Specified resource</h3>
    <p>
        Specified resource for Custom Supplier endpoint.
        To access their Custom Supplier specific detail, we need to have the id.
        And the endpoint will return the specific Custom Supplier regarding the given id.
    </p>
    <br>


    <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

        <b>Route: </b> &nbsp;<code>api/custom/suppliers/{id}</code> <br>
        <b>Request Type: &nbsp;</b> <code>GET</code> <br>
        <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
        <b>Body Form Data required: &nbsp;</b> <code>id</code> <br>
        <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
        <b>Will Return: &nbsp;</b> <code> Custom Supplier (Specific Custom Supplier) </code> <small> </small><br>

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
    "results": {
        "id": 7,
        "company_name": "Goldner-Kunze",
        "address": "NO.608 LINGDOU WEST ROAD,SIMING DISTRICT XIAMEN, CHINA",
        "phone": "408-231-7678",
        "admin_user_id": null,
        "created_at": "2020-02-28T13:55:09.000000Z",
        "updated_at": "2020-02-28T13:55:09.000000Z",
        "emails": null
    }
}
                                                        </pre>
    <p>
        Everytime users want to access other api endpoints they have to provide validate token on header with every request.
        Otherwise The request will be considered as unauthorized attempt.
    </p>

</div><!-- End Custom Supplier Specified resource -->

</div><!-- end of Custom Supplier -->