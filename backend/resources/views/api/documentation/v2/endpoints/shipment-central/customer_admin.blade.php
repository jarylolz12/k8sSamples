{{-- /////////////////////////////// --}}

<div class="col-sm-12 hidden" id="customer-admin"> <!-- start customer admin -->
    <h3 class="page-header">Customer Admin</h3>
    <div class="col-sm-12" id="customer-admin-specified"><!-- Customer Admin specified resource -->

        <h3>Specified resource</h3>
        <p>
            Specified resource for Customer Admin endpoint.
            To access their Customer Admin specific detail, we need to have the id.
            And the endpoint will return the specific Customer Admin regarding the given id.
        </p>
        <br>


        <div id="carrier-type-specified" class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/customer-admins/{customer_id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>customer_id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Customer Admin (Specific Customer) </code> <small> </small><br>

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
    "id" : "{id}",
}
</pre>

        <h4>Response</h4>
        <pre>
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "John Doe",
            "email": "solomon@starbestbuy.com",
            "created_at": "2020-05-15T01:01:29.000000Z",
            "updated_at": "2022-03-21T21:42:33.000000Z",
            "customer_admin_pk": 19
        }
    ],
    "message": "Customer admin fetched successfully"
}
                                                        </pre>
        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>
    </div><!-- End Customer Admin specified resource -->




    <!-- Customer Admin Delete -->
    <div class="col-sm-12"  id="card-destroy" >

        <h3>Destroy resource</h3>
        <p>
            Destroy or delete resource for Customer Admin endpoint.
            To delete the Customer Admin we should have valid id.
            And the endpoint will destroy or delete the data.
        </p>
        <br>
        <div style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/customer-admins-delete/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>id</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Customer Admin (Delete Customer Admin) </code> <small> </small><br>

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
    "id" : "{id}",
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "message": "User Deleted Successfully"
}
                                                        </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div><!-- end Customer Admin Delete -->


</div> <!-- end of Customers -->