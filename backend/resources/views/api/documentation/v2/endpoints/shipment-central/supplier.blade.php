
{{-- /////////////////////////////// --}}

<!-- Supplier -->
<div class="col-sm-12 hidden" id="supplier">
    <h3 class="page-header">Supplier (Pending)</h3>



    <!-- List resource -->
    <div class="col-sm-12" id="supplier-list">
        <h3>List resource</h3>
        <p>
            List resource for Supplier endpoint.
            To access their Supplier details, the api already get the data per page
            and the endpoint return json.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/suppliers</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>per_page</code>, <code>page</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>List of Supplier</code> <small> </small><br>

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
    "per_page" : "{per_page}",
    "page" : "{page}",
}
</pre>

        <h4>Response</h4>
        <pre style="font-weight:bold">
{
    "data": [
        {
            "id": 425,
            "company_name": "YOAU ELECTRIC CO.,LTD.",
            "address": "SOUND AROUND INC.bbb",
            "phone": "03239808958",
            "admin_user_id": null,
            "created_at": "2020-06-22T11:49:48.000000Z",
            "updated_at": "2022-01-16T05:35:55.000000Z",
            "emails": [
                "faizamalik928@gmail.com",
                "admin@gmail.com"
            ],
            "supplier_customer_company_name": "SOUND AROUND",
            "invitation_status": "not_invited",
            "pivot": {
                "customer_id": 25,
                "supplier_id": 425
            }
        },{...},...
    ],
    "links": {
        "first": "api/suppliers?page=1",
        "last": "api/suppliers?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "path": "api/suppliers",
        "per_page": 50,
        "to": 47,
        "total": 47
    }
}

 </pre>
        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>
    </div><!-- End List resource -->








    <!-- List resource  version 2  -->
    <div class="col-sm-12" id="supplier-list-v2">
        <h3>List resource version 2 </h3>
        <p>
            List resource version 2 for Supplier endpoint.
            To access their Supplier details, the api already get the data per page
            and the endpoint return json.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/v2/suppliers</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>per_page</code>, <code>page</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>List of Supplier Version 2</code> <small> </small><br>

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
    "per_page" : "{per_page}",
    "page" : "{page}",
}
</pre>

        <h4>Response</h4>
        <pre style="font-weight:bold">
{
    "current_page": 1,
    "data": [
        {
            "id": 425,
            "company_name": "YOAU ELECTRIC CO.,LTD.",
            "address": "SOUND AROUND INC.bbb",
            "phone": "03239808958",
            "admin_user_id": null,
            "created_at": "2020-06-22T11:49:48.000000Z",
            "updated_at": "2022-01-16T05:35:55.000000Z",
            "emails": [
                "faizamalik928@gmail.com",
                "admin@gmail.com"
            ],
            "supplier_customer_company_name": "SOUND AROUND",
            "invitation_status": "not_invited",
            "company_key": null
        }, {...},...
    ],
    "first_page_url": "api/v2/suppliers?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "api/v2/suppliers?page=1",
    "next_page_url": null,
    "path": "api/v2/suppliers",
    "per_page": 50,
    "prev_page_url": null,
    "to": 47,
    "total": 47
}

 </pre>
        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>
    </div><!-- End List resource version 2 -->










    <!-- Supplier Create -->
    <div class="col-sm-12" id="supplier-create">
        <h3>Create resource</h3>
        <p>
            Create resource for Supplier endpoint.
            To create the Supplier we have to input the ff. data:
            company_name, address, phone, custom_customers_id and emails.
            And the endpoint will return json value once created.


        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/suppliers</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>company_name</code>, <code>address</code>, <code>phone</code>,
            <code>custom_customers_id</code>,
            <code>emails</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Create Supplier</code> <small> </small><br>

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
    "company_name" : "{company_name}",
    "address" : "{address}",
    "phone" : "{phone}",
    "emails" : "{emails}",
    "custom_customers_id" : "{custom_customers_id}"
}
</pre>

        <h4>Response</h4>
        <pre style="font-weight:bold">
{
    "success": true,
    "data": {
        "id": 2535,
        "company_name": "sed",
        "address": "nihil",
        "phone": "id",
        "admin_user_id": null,
        "created_at": null,
        "updated_at": null,
        "emails": "[\"mike@gmail.com\",\"mike1@gmail.com\"]"
    },
    "message": "Supplier created successfully."
}

 </pre>
        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>
    </div><!-- End Create Supplier -->









    <!-- Supplier Update -->
    <div class="col-sm-12" id="supplier-update">
        <h3>Update resource</h3>
        <p>
            Update resource for Supplier endpoint.
            To create the Supplier we have to input the ff. data:
            id, company_name, address and phone.
            And the endpoint will return json value once updated.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/suppliers/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>PUT</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>company_name</code>, <code>address</code>, <code>phone</code>,
            <code>custom_customers_id</code>,
            <code>emails</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Update Supplier</code> <small> </small><br>

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
    "company_name" : "{company_name}",
    "address" : "{address}",
    "phone" : "{phone}",
    "id" : "{id}"
}
</pre>

        <h4>Response</h4>
        <pre style="font-weight:bold">
{
    "success": true,
    "data": {
        "id": 2535,
        "company_name": "sapiente",
        "address": "USA",
        "phone": "855-399-9945",
        "admin_user_id": null,
        "created_at": null,
        "updated_at": "2022-08-12T08:24:31.000000Z",
        "emails": "[\"mike@gmail.com\",\"mike1@gmail.com\"]"
    },
    "message": "Supplier updated successfully."
}
 </pre>
        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>
    </div><!-- End Update Supplier -->










    <!-- Supplier Delete -->
    <div class="col-sm-12" id="supplier-delete">
        <h3>Delete resource</h3>
        <p>
            Destroy or delete resource for Supplier endpoint.
            To delete the Supplier we should have valid id.
            And the endpoint will destroy or delete the data.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/suppliers/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>DELETE</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> ,
            <code>id</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Delete Supplier</code> <small> </small><br>

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
    "id" : "{id}"
}
</pre>

        <h4>Response</h4>
        <pre style="font-weight:bold">
{
    "message": "Supplier deleted successfully."
}
 </pre>
        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>
    </div><!-- End Supplier Delete -->













    <!-- Shipment Supplier -->
    <div class="col-sm-12" id="supplier-shipment-supplier">
        <h3>Shipment Supplier</h3>
        <p>
            Shipment for Supplier endpoint.
            To access their Shipment Supplier details, the api already get the data and the endpoint return json.


        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/shipment-suppliers</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> ,
            <code>NONE</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Shipment Supplier</code> <small> </small><br>

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
NONE
</pre>

        <h4>Response</h4>
        <pre style="font-weight:bold">
{
    "data": []
}
 </pre>
        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>
    </div><!-- End Shipment Supplier -->






    <!-- Vendor Supplier -->
    <div class="col-sm-12" id="supplier-vendor">
        <h3>Vendor Supplier</h3>

        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/send/invite/vendor</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> ,
            <code>vendorId</code>,
            <code>defaultCustomerId</code>,
            <code>emailAddress</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Shipment Supplier</code> <small> </small><br>

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
      "vendorId": "{vendorId}",
      "defaultCustomerId": "{defaultCustomerId}",
      "emailAddress": "{emailAddress}"
}
</pre>

        <h4>Response</h4>
        <pre style="font-weight:bold">
{
     "message": "Supplier successfully invited!"
}
 </pre>
        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>
    </div><!-- End Shipment Supplier -->



</div> <!-- End Supplier -->

