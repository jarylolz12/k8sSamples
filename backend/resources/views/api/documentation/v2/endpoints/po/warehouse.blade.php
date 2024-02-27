<div class="col-sm-12" id="po-warehouse">
    <h3 class="page-header">Warehouse</h3>

    <!-- Get All Warehouse -->
    <div class="col-sm-12" id="po-get-all-warehouse" >

        <h3>Get All Warehouse</h3>
        <p>
            Get All Warehouse for Warehouse endpoint.
            To access their Warehouse details, they have to provide per_page and page.
            And the endpoint will return all their Warehouse regarding the given per_page and page.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>per_page</code>, <code>page</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get All Warehouse</code> <small> </small><br>

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
    "per_page" : "{per_page}",
    "page" : "{page}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "results": [
        {
            "id": 60,
            "customer_id": 25,
            "name": "Test Only",
            "phone": "+63 940 238 2345",
            "address": "Door 123 Qwerty Appartment",
            "country": "Philippines",
            "state": "Cebu",
            "city": "Cebu",
            "zipcode": "8000",
            "created_at": "2021-09-02T22:13:44.000000Z",
            "updated_at": "2021-09-02T22:35:36.000000Z",
            "warehouse_type_id": 2,
            "created_by": 206,
            "email_address": [],
            "total_products": 3,
            "total_cartons": 6,
            "total_units": 0,
            "warehouse_type": "Own"
        }, {...},...
    ]
}
                                                        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Get All Warehouse -->






    <!-- Get Product Warehouse -->
    <div class="col-sm-12" id="po-get-product-warehouse" >

        <h3>Get Product Warehouse</h3>
        <p>
            Get Product Warehouse for Warehouse endpoint.
            To access their Product Warehouse details, they have to provide per_page and page.
            And the endpoint will return all their Product Warehouse regarding the given per_page and page.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/products</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL and Query Form Data required: &nbsp;</b> <code>per_page</code>, <code>page</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Product Warehouse</code> <small> </small><br>

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
    "per_page" : "{per_page}",
    "page" : "{page}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
            {
    "results": {
        "current_page": 1,
        "data": [
            {
                "id": 123,
                "sku": "602807",
                "name": "test product",
                "category_id": 83,
                "description": "test description",
                "units_per_carton": 25,
                "image": null,
                "created_at": "2021-09-02T21:30:28.000000Z",
                "updated_at": "2021-09-03T22:40:37.000000Z",
                "customer_id": 25,
                "created_by": 11,
                "deleted_at": "2021-09-03T22:40:37.000000Z",
                "is_system_generated": 1,
                "customer_admins": null,
                "unit_price": null,
                "classification_code": null,
                "duty_rate": null,
                "carton_dimensions": null,
                "is_for_classification_code": null,
                "upc_number": null,
                "country_from": null,
                "country_to": null,
                "product_classification_description": null,
                "unit_weight": null,
                "unit_dimensions": null,
                "carton_upc": null,
                "additional_classification_code": null,
                "status": "deleted",
                "category_sku": "83-602807",
                "inbound_associated": false
            },{...},...
        ],
        "first_page_url": "api/warehouses/products?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "api/warehouses/products?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "api/warehouses/products?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "next_page_url": null,
        "path": "hapi/warehouses/products",
        "per_page": 500,
        "prev_page_url": null,
        "to": 76,
        "total": 76
    }
}
        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Get Product Warehouse -->








    <!-- Get Warehouse -->
    <div class="col-sm-12" id="po-get-warehouse" >

        <h3>Get Warehouse</h3>
        <p>
            Get Warehouse for Warehouse endpoint.
            To access their specific Warehouse detail, they have to provide id.
            And the endpoint will return all their specific Warehouse regarding the id.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>id</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Warehouse</code> <small> </small><br>

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
    "id" : "{id}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "id": 60,
    "customer_id": 25,
    "name": "Test Only",
    "phone": "+63 940 238 2345",
    "address": "Door 123 Qwerty Appartment",
    "country": "Philippines",
    "state": "Cebu",
    "city": "Cebu",
    "zipcode": "8000",
    "created_at": "2021-09-02T22:13:44.000000Z",
    "updated_at": "2021-09-02T22:35:36.000000Z",
    "warehouse_type_id": 2,
    "created_by": 206,
    "email_address": [],
    "total_products": 3,
    "total_cartons": 6,
    "total_units": 0
}
        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Get Warehouse -->












    <!-- Create Warehouse -->
    <div class="col-sm-12" id="po-create-warehouse" >

        <h3>Create Warehouse</h3>
        <p>
            Create Warehouse for Warehouse endpoint.
            To create the Warehouse we have to input the ff. data:
            warehouse_type_id, name, phone, address, country, state, city, zipcode, email_address.
            And the endpoint will return json value once created.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/create</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>warehouse_type_id</code>,
            code>name</code>,
            code>phone</code>,
            code>address</code>,
            code>country</code>,
            code>state</code>,
            code>city</code>,
            code>zipcode</code>,
            code>email_address</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Create Warehouse</code> <small> </small><br>

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
    "warehouse_type_id" : "{warehouse_type_id}",
    "name" : "{name}",
    "phone" : "{phone}",
    "address" : "{address}",
    "country" : "{country}",
    "state" : "{state}",
    "city" : "{city}",
    "zipcode" : "{zipcode}",
    "email_address" : "{email_address}"
}
        </pre>


        <h4>Response</h4>
        <pre>
{
    "message": "Warehouse has been created.",
    "data": {
        "name": "impedit",
        "phone": "beatae",
        "address": "a",
        "country": "eum",
        "state": "odio",
        "city": "voluptatem",
        "zipcode": "eum",
        "customer_id": 25,
        "created_by": 3,
        "warehouse_type_id": 1,
        "updated_at": "2022-08-10T14:56:54.000000Z",
        "created_at": "2022-08-10T14:56:54.000000Z",
        "id": 274
    }
}
        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Create Warehouse -->




    <!-- Update Warehouse -->
    <div class="col-sm-12" id="po-update-warehouse" >

        <h3>Update Warehouse</h3>
        <p>
            Update Warehouse for Warehouse endpoint.
            To update the Warehouse we have to input the ff. data:
            id, warehouse_type_id, name, phone, address, country, state, city, zipcode, email_address.
            And the endpoint will return json value once updated.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/update/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>PUT</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL and Body Form Data required: &nbsp;</b> <code>id</code>, <code>warehouse_type_id</code>,
            code>name</code>,
            code>phone</code>,
            code>address</code>,
            code>country</code>,
            code>state</code>,
            code>city</code>,
            code>zipcode</code>,
            code>email_address</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Update Warehouse</code> <small> </small><br>

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
    "warehouse_type_id" : "{warehouse_type_id}",
    "name" : "{name}",
    "phone" : "{phone}",
    "address" : "{address}",
    "country" : "{country}",
    "state" : "{state}",
    "city" : "{city}",
    "zipcode" : "{zipcode}",
    "email_address" : "{email_address}"
}
        </pre>


        <h4>Response</h4>
        <pre>
{
    "message": "Warehouse has been updated.",
    "data": {
        "id": 274,
        "customer_id": 25,
        "name": "aliquid",
        "phone": "ea",
        "address": "dolor",
        "country": "sed",
        "state": "repellendus",
        "city": "et",
        "zipcode": "aliquid",
        "created_at": "2022-08-10T14:56:54.000000Z",
        "updated_at": "2022-08-10T15:03:03.000000Z",
        "warehouse_type_id": 2,
        "created_by": 3
    }
}
        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Update Warehouse -->


    <!-- Delete Warehouse -->
    <div class="col-sm-12" id="po-delete-warehouse" >

        <h3>Delete Warehouse</h3>
        <p>
            Update Warehouse for Warehouse endpoint.
            To update the Warehouse we have to input the ff. data:
            id, warehouse_type_id, name, phone, address, country, state, city, zipcode, email_address.
            And the endpoint will return json value once updated.

            Delete Warehouse for Warehouse endpoint.
            To Delete the Warehouse we should have valid id.
            And the endpoint will deleted the data.


        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/delete/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>DELETE</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>id</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Delete Warehouse</code> <small> </small><br>

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
    "id" : "{id}"
}
        </pre>


        <h4>Response</h4>
        <pre>
{
    "message": "Warehouse has been deleted."
}
        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Delete Warehouse -->

</div> <!-- end Warehouse-->
