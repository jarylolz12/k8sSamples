<div class="col-sm-12" id="po-inbound">
    <h3 class="page-header">Get All Inbound</h3>

    <!-- Get All Inbound -->
    <div class="col-sm-12" id="po-get-all-inbound" >

        <h3>Get All Inbound</h3>
        <p>
            Get all Inbound for Inbound endpoint.
            To access their Inbound details, they have to provide warehouse_id.
            And the endpoint will return all their Inbound.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/{warehouse_id}/inbounds</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>warehouse_id</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get All Inbound</code> <small> </small><br>

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
    "warehouse_id" : "{warehouse_id}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "results": {
        "pending_inbounds": {
            "current_page": 1,
            "data": [],
            "first_page_url": "api/warehouses/252/inbounds?page=1",
            "from": null,
            "last_page": 1,
            "last_page_url": "api/warehouses/252/inbounds?page=1",
            "links": [
                {
                    "url": null,
                    "label": "&laquo; Previous",
                    "active": false
                },
                {
                    "url": "api/warehouses/252/inbounds?page=1",
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
            "path": "api/warehouses/252/inbounds",
            "per_page": 35,
            "prev_page_url": null,
            "to": null,
            "total": 0
        },
        "floor_inbounds": {
            "current_page": 1,
            "data": [],
            "first_page_url": "api/warehouses/252/inbounds?page=1",
            "from": null,
            "last_page": 1,
            "last_page_url": "api/warehouses/252/inbounds?page=1",
            "links": [
                {
                    "url": null,
                    "label": "&laquo; Previous",
                    "active": false
                },
                {
                    "url": "api/warehouses/252/inbounds?page=1",
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
            "path": "api/warehouses/252/inbounds",
            "per_page": 35,
            "prev_page_url": null,
            "to": null,
            "total": 0
        },
        "completed_inbounds": {
            "current_page": 1,
            "data": [],
            "first_page_url": "api/warehouses/252/inbounds?page=1",
            "from": null,
            "last_page": 1,
            "last_page_url": "api/warehouses/252/inbounds?page=1",
            "links": [
                {
                    "url": null,
                    "label": "&laquo; Previous",
                    "active": false
                },
                {
                    "url": "api/warehouses/252/inbounds?page=1",
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
            "path": "api/warehouses/252/inbounds",
            "per_page": 35,
            "prev_page_url": null,
            "to": null,
            "total": 0
        },
        "cancelled_inbounds": {
            "current_page": 1,
            "data": [
                {
                    "id": 24,
                    "name": null,
                    "ref_no": null,
                    "warehouse_id": 252,
                    "customer_id": 447,
                    "deleted_at": null,
                    "created_at": "2022-01-10T23:17:30.000000Z",
                    "updated_at": "2022-01-10T23:52:01.000000Z",
                    "estimated_time": null,
                    "arrival_time": null,
                    "inbound_status": "cancelled",
                    "estimated_date": null,
                    "order_id": "787431",
                    "customer": "Test K",
                    "documents": [],
                    "is_recieved": 0,
                    "products": null,
                    "notes": null,
                    "shipping_from": null,
                    "storable_units": null,
                    "no_of_sku": 0,
                    "no_of_cartons": 0,
                    "no_of_storable_units": 0,
                    "no_of_units": 0,
                    "eta": null,
                    "arrival": null,
                    "is_undershipped": 0,
                    "inbound_products": [],
                    "warehouse": {
                        "id": 252,
                        "customer_id": 447,
                        "name": "Testing Warehouse 123",
                        "phone": "+1 727-551-6212",
                        "address": "2124 Maryland Avenue St Petersburg, FL 3370",
                        "country": "United States",
                        "state": "Florida",
                        "city": null,
                        "zipcode": "3370",
                        "created_at": "2022-01-10T18:52:03.000000Z",
                        "updated_at": "2022-01-10T18:52:03.000000Z",
                        "warehouse_type_id": 2,
                        "created_by": 206
                    }
                }
            ],
            "first_page_url": "api/warehouses/252/inbounds?page=1",
            "from": 1,
            "last_page": 1,
            "last_page_url": "api/warehouses/252/inbounds?page=1",
            "links": [
                {
                    "url": null,
                    "label": "&laquo; Previous",
                    "active": false
                },
                {
                    "url": "api/warehouses/252/inbounds?page=1",
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
            "path": "warehouses/252/inbounds",
            "per_page": 35,
            "prev_page_url": null,
            "to": 1,
            "total": 1
        }
    }
}
                                                        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Get All Inbound -->










    <!-- Get Inbound -->
    <div class="col-sm-12" id="po-get-inbound" >

        <h3>Get Inbound</h3>
        <p>
            Get Inbound for Inbound endpoint.
            To access their Inbound details, they have to provide warehouse_id and id.
            And the endpoint will return all their Inbound.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/{warehouse_id}/inbounds/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>warehouse_id</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Inbound</code> <small> </small><br>

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
    "warehouse_id" : "{warehouse_id}",
    "id" : "{id}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "data": {
        "id": 24,
        "name": null,
        "ref_no": null,
        "warehouse_id": 252,
        "customer_id": 447,
        "deleted_at": null,
        "created_at": "2022-01-10T23:17:30.000000Z",
        "updated_at": "2022-01-10T23:52:01.000000Z",
        "estimated_time": null,
        "arrival_time": null,
        "inbound_status": "cancelled",
        "estimated_date": null,
        "order_id": "787431",
        "customer": "Test K",
        "documents": [],
        "is_recieved": 0,
        "products": null,
        "notes": null,
        "shipping_from": null,
        "storable_units": null,
        "no_of_sku": 0,
        "no_of_cartons": 0,
        "no_of_storable_units": 0,
        "no_of_units": 0,
        "eta": null,
        "arrival": null,
        "is_undershipped": 0,
        "inbound_products": [],
        "warehouse": {
            "id": 252,
            "customer_id": 447,
            "name": "Testing Warehouse 123",
            "phone": "+1 727-551-6212",
            "address": "2124 Maryland Avenue St Petersburg, FL 3370",
            "country": "United States",
            "state": "Florida",
            "city": null,
            "zipcode": "3370",
            "created_at": "2022-01-10T18:52:03.000000Z",
            "updated_at": "2022-01-10T18:52:03.000000Z",
            "warehouse_type_id": 2,
            "created_by": 206
        },
        "inbound_storable_units": []
    },
    "no_of_sku": 0,
    "no_of_storable_units": 0,
    "total_expected_cartons": null,
    "total_expected_units": null,
    "total_actual_cartons": null,
    "total_actual_units": null
}
                                                        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Get Inbound -->










    <!-- Create Inbound -->
    <div class="col-sm-12" id="po-create-inbound" >

        <h3>Create Inbound</h3>
        <p>
            Create Inbound for Inbound endpoint.
            To create the Inbound we have to input the ff. data:
            warehouse_id, name, ref_no, shipping_from, eta, estimated_time, customer, inbound_status, notes, customer_id,
            order_id, documents, products.*.product_id, products.*.shipping_unit, products.*.quantity and products.*.in_each_carton.
            And the endpoint will return json value once created.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/{warehouse_id}/inbounds/create</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body and URL Form Data required: &nbsp;</b> <code>warehouse_id</code>, <code>name</code>,
            <code>ref_no</code>,
            <code>shipping_from</code>,
            <code>eta</code>,
            <code>estimated_time</code>,
            <code>customer</code>,
            <code>inbound_status</code>,
            <code>notes</code>,
            <code>customer_id</code>,
            <code>documents</code>,
            <code>products.*.product_id</code>,
            <code>products.*.shipping_unit</code>,
            <code>products.*.quantity</code>,
            <code>products.*.in_each_carton</code>,
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Create Inbound</code> <small> </small><br>

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
    "warehouse_id" : "{warehouse_id}",
    "name" : "{name}",
    "ref_no" : "{ref_no}",
    "shipping_from" : "{shipping_from}",
    "eta" : "{eta}",
    "estimated_time" : "{estimated_time}",
    "customer" : "{customer}",
    "inbound_status" : "{inbound_status}",
    "notes" : "{notes}",
    "customer_id" : "{customer_id}",
    "documents" : "{documents}",
    "products.*.product_id" : "{products.*.product_id}",
    "products.*.shipping_unit" : "{products.*.shipping_unit}",
    "products.*.quantity" : "{products.*.quantity}",
    "products.*.in_each_carton" : "{products.*.in_each_carton}",
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "message": "New inbound details successfully created.",
    "data": {
        "order_id": "12",
        "name": "saepe",
        "ref_no": "incidunt",
        "warehouse_id": "11",
        "customer_id": 25,
        "shipping_from": "velit",
        "eta": null,
        "estimated_time": "incidunt",
        "customer": "laborum",
        "estimated_date": null,
        "inbound_status": "blanditiis",
        "notes": "architecto",
        "updated_at": "2022-08-08T13:48:46.000000Z",
        "created_at": "2022-08-08T13:48:46.000000Z",
        "id": 204
    }
}

                                                        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Create Inbound -->








    <!-- Update Inbound -->
    <div class="col-sm-12" id="po-update-inbound" >

        <h3>Update Inbound</h3>
        <p>
            Update Inbound for Inbound endpoint.
            To update the Inbound we have to input the ff. data:
            warehouse_id, name, ref_no, shipping_from, eta, estimated_time, customer, inbound_status, notes, customer_id,
            order_id, documents, products.*.product_id, products.*.shipping_unit, products.*.quantity and products.*.in_each_carton.
            And the endpoint will return json value once updated.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/{warehouse_id}/inbounds/update/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>PUT</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>warehouse_id</code>, <code>id</code>, <code>name</code>,
            <code>ref_no</code>,
            <code>shipping_from</code>,
            <code>eta</code>,
            <code>estimated_time</code>,
            <code>customer</code>,
            <code>inbound_status</code>,
            <code>notes</code>,
            <code>customer_id</code>,
            <code>documents</code>,
            <code>products.*.product_id</code>,
            <code>products.*.shipping_unit</code>,
            <code>products.*.quantity</code>,
            <code>products.*.in_each_carton</code>,
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Create Inbound</code> <small> </small><br>

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
    "warehouse_id" : "{warehouse_id}",
    "id" : "{id}",
    "name" : "{name}",
    "ref_no" : "{ref_no}",
    "shipping_from" : "{shipping_from}",
    "eta" : "{eta}",
    "estimated_time" : "{estimated_time}",
    "customer" : "{customer}",
    "inbound_status" : "{inbound_status}",
    "notes" : "{notes}",
    "customer_id" : "{customer_id}",
    "documents" : "{documents}",
    "products.*.product_id" : "{products.*.product_id}",
    "products.*.shipping_unit" : "{products.*.shipping_unit}",
    "products.*.quantity" : "{products.*.quantity}",
    "products.*.in_each_carton" : "{products.*.in_each_carton}",
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "message": "Inbound details successfully updated.",
    "data": {
        "order_id": "17",
        "name": "est",
        "ref_no": "est",
        "warehouse_id": "11",
        "shipping_from": "modi",
        "customer_id": 25,
        "estimated_time": "et",
        "customer": "delectus",
        "estimated_date": "12/30/2021",
        "inbound_status": "quam",
        "notes": "blanditiis"
    }
}

                                                        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Update Inbound -->







    <!-- Cancel Inbound -->
    <div class="col-sm-12"  id="po-cancel-inbound" >

        <h3>Cancel Inbound</h3>
        <p>
            Cancel Inbound for Inbound endpoint.
            To cancel the Inbound we should have valid warehouse_id and id.
            And the endpoint will cancel the data.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/{warehouse_id}/inbounds/cancel/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>warehouse_id</code>, <code>id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Cancel Inbound</code> <small> </small><br>

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
    "warehouse_id" : "{warehouse_id}",
    "id": "{id}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "message": "Inbound successfully cancelled.",
    "data": {
        "inbound_status": "cancelled"
    }
}
                                                        </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div><!-- end Cancel Inbound -->



    <!-- Delete Inbound -->
    <div class="col-sm-12"  id="po-delete-inbound" >

        <h3>Delete Inbound</h3>
        <p>
            Delete Inbound for Inbound endpoint.
            To delete the Inbound we should have valid warehouse_id and id.
            And the endpoint will delete the data.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/{warehouse_id}/inbounds/delete/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>DELETE</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>warehouse_id</code>, <code>id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Delete Inbound</code> <small> </small><br>

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
    "warehouse_id" : "{warehouse_id}",
    "id": "{id}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "message": "Inbound details successfully deleted.",
    "data": []
}
                                                        </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div><!-- end Delete Inbound -->











    <!-- Truck Arrived Inbound -->
    <div class="col-sm-12"  id="po-truck-arrived" >

        <h3>Truck Arrived</h3>
        <p>
            Truck Arrived for Inbound endpoint.
            To access the Truck Arrived Inbound endpoint we should have valid warehouse_id, id, name and ref_no.
            And the endpoint will return a success message.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/{warehouse_id}/inbounds/truck-arrived/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>PUT</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL and Body Form Data required: &nbsp;</b> <code>warehouse_id</code>, <code>id</code>, <code>name</code>, <code>ref_no</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Truck Arrived for Inbound</code> <small> </small><br>

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
    "warehouse_id" : "{warehouse_id}",
    "id": "{id}",
    "name" : "{name}",
    "ref_no": "{ref_no}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "message": "Truck Arrived Successfully"
}
                                                        </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div><!-- end Truck Arrived Inbound -->









    <!-- Recieve Product Inbound -->
    <div class="col-sm-12"  id="po-truck-arrived" >

        <h3>Recieve Product</h3>
        <p>
            Recieve Product for Inbound endpoint.
            To access the Recieve Product Inbound endpoint we should have valid warehouse_id, id, name and ref_no.
            And the endpoint will return json value and success message.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/{warehouse_id}/inbounds/{inbound_id}/recieve-inbound-products/{inbound_product_id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>PUT</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL and Body Form Data required: &nbsp;</b> <code>warehouse_id</code>, <code>inbound_id</code>, <code>inbound_product_id</code>
            , <code>carton_count</code>, <code>in_each_carton</code>, <code>total_unit</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Recieve Product for Inbound</code> <small> </small><br>

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
    "warehouse_id" : "{warehouse_id}",
    "inbound_id": "{inbound_id}",
    "inbound_product_id" : "{inbound_product_id}",
    "carton_count": "{carton_count}",
    "in_each_carton": "{in_each_carton}",
    "total_unit": "{total_unit}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "message": "Product Recieved Successfully",
    "data": {
        "id": 2,
        "warehouse_id": 241,
        "product_id": 394,
        "customer_id": 447,
        "carton_count": 1,
        "in_each_carton": 35,
        "total_unit": 35,
        "inbound_id": 9,
        "sku": "952468",
        "deleted_at": null,
        "created_at": "2021-12-13T23:06:51.000000Z",
        "updated_at": "2021-12-13T23:06:51.000000Z",
        "status": null,
        "is_transact": 0,
        "is_recieved": 0,
        "actual_carton_count": 0,
        "actual_in_each_carton": 0,
        "actual_total_unit": 0,
        "shipping_unit": null,
        "expected_carton_count": 0,
        "expected_total_unit": 0,
        "product_name": "Test Product 1",
        "remaining_carton_count": 0,
        "remaining_total_unit": 0,
        "storable_units": []
    }
}
                                                        </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div><!-- end Recieve Product Inbound -->




    <!-- Download Document Inbound -->
    <div class="col-sm-12"  id="po-download-document" >

        <h3>Recieve Product</h3>
        <p>
            Download Document for Inbound endpoint.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/{warehouse_id}/inbounds/{inbound_id}/download-document/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>warehouse_id</code>, <code>inbound_id</code>, <code>id</code> <br>
            <b>Return Type: &nbsp;</b> <code>FILE</code> <br>
            <b>Will Return: &nbsp;</b> <code>Download Document for Inbound</code> <small> </small><br>

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
    "warehouse_id" : "{warehouse_id}",
    "inbound_id": "{inbound_id}",
    "id" : "{id}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>

Downloadable File
                                                        </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div><!-- end Download Document Inbound -->










    <!-- Upload Document Inbound -->
    <div class="col-sm-12"  id="po-upload-document" >

        <h3>Upload Document</h3>
        <p>
            Upload Document for Inbound endpoint.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/{warehouse_id}/inbounds/{inbound_id}/upload-document</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body and URL Form Data required: &nbsp;</b> <code>warehouse_id</code>, <code>inbound_id</code><br>
            <b>Return Type: &nbsp;</b> <code>FILE</code> <br>
            <b>Will Return: &nbsp;</b> <code>Upload Document for Inbound</code> <small> </small><br>

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
    "warehouse_id" : "{warehouse_id}",
    "inbound_id": "{inbound_id}",
    "file" : "{file}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "message": "Document uploaded successfully."
}
                                                        </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div><!-- end Upload Document Inbound -->

    <!-- New Storeable Unit Inbound -->
    <div class="col-sm-12"  id="po-new-storeable-unit" >

        <h3>New Storeable Unit</h3>
        <p>
            New Storeable Unit for Inbound endpoint.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/{warehouse_id}/inbounds/{inbound_id}/new-storable-unit</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b>
            <code>warehouse_id</code>,
            <code>inbound_id</code>,
            <code>action</code>,
            <code>label</code>,
            <code>type</code>,
            <code>dimension</code>,
            <code>weight</code>,
            <code>products.*.inbound_product_id</code>,
            <code>products.*.carton_count</code>,
            <code>products.*.total_unit</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> New Storeable Unit for Inbound</code> <small> </small><br>

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
    "warehouse_id" : "{warehouse_id}",
    "inbound_id": "{inbound_id}",
    "action" : "{action}",
    "label" : "{action}",
    "type" : "{action}",
    "dimension" : "{dimension}",
    "weight" : "{weight}",
    "products.*.inbound_product_id" : "{products.*.inbound_product_id}",
    "products.*.carton_count" : "{products.*.carton_count}",
    "products.*.total_unit" : "{products.*.total_unit}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
 Pending
                                                        </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div><!-- end New Storeable Unit -->








    <!-- New Storeable Unit Inbound -->
    <div class="col-sm-12"  id="po-new-storeable-unit" >

        <h3>New Storeable Unit</h3>
        <p>
            New Storeable Unit for Inbound endpoint.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/{warehouse_id}/inbounds/{inbound_id}/place-storable-unit/{storable_unit_id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body and URL Form Data required: &nbsp;</b>
            <code>warehouse_id</code>,
            <code>inbound_id</code>,
            <code>storable_unit_id</code>,
            <code>location_id</code>,
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> New Storeable Unit for Inbound</code> <small> </small><br>

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
    "warehouse_id" : "{warehouse_id}",
    "inbound_id": "{inbound_id}",
    "storable_unit_id" : "{storable_unit_id}",
    "location_id" : "{location_id}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "message": "Storable Unit Placed Successfully",
    "data": {
        "location_id": "18",
        "action": "recieve",
        "label": "447937",
        "warehouse_id": "19",
        "customer_id": 25,
        "type": "pallet",
        "dimensions": "{\"l\":\"10\",\"w\":\"10\",\"h\":\"10\",\"uom\":\"cm\"}",
        "weight": "{\"value\":\"15\",\"unit\":\"KG\"}",
        "updated_at": "2022-08-08T15:19:26.000000Z",
        "created_at": "2022-08-08T15:19:26.000000Z",
        "id": 208,
        "is_active": 1
    }
}
                                                        </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div><!-- end New Storeable Unit -->










    <!-- Get Pending Inbound -->
    <div class="col-sm-12"  id="po-get-pending-inbound" >

        <h3>Get Pending Inbound</h3>
        <p>
            Get Pending Inbound for Inbound endpoint.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/inbound/pending</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL and Query Form Data required: &nbsp;</b>
            <code>warehouse_id</code>,
            <code>search</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Pending Inbound for Inbound</code> <small> </small><br>

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
    "warehouse_id" : "{warehouse_id}",
    "search": "{search}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "results": {
        "current_page": 1,
        "data": [
            {
                "id": 15,
                "name": "asperiores",
                "ref_no": "sed",
                "warehouse_id": 241,
                "customer_id": 447,
                "deleted_at": null,
                "created_at": "2021-12-16T19:38:44.000000Z",
                "updated_at": "2022-08-08T14:21:08.000000Z",
                "estimated_time": null,
                "arrival_time": null,
                "inbound_status": "arrived",
                "estimated_date": null,
                "order_id": "535575",
                "customer": "Test",
                "documents": [],
                "is_recieved": 0,
                "products": null,
                "notes": null,
                "shipping_from": null,
                "storable_units": null,
                "no_of_sku": 0,
                "no_of_cartons": 0,
                "no_of_storable_units": 0,
                "no_of_units": 0,
                "eta": null,
                "arrival": null,
                "is_undershipped": 0,
                "inbound_products": [
                    {
                        "id": 9,
                        "warehouse_id": 241,
                        "product_id": 394,
                        "customer_id": 447,
                        "carton_count": 0,
                        "in_each_carton": 35,
                        "total_unit": 0,
                        "inbound_id": 15,
                        "sku": "952468",
                        "deleted_at": null,
                        "created_at": "2021-12-16T19:38:44.000000Z",
                        "updated_at": "2021-12-16T19:38:44.000000Z",
                        "status": null,
                        "is_transact": 0,
                        "is_recieved": 0,
                        "actual_carton_count": 0,
                        "actual_in_each_carton": 0,
                        "actual_total_unit": 0,
                        "shipping_unit": null,
                        "expected_carton_count": 0,
                        "expected_total_unit": 0,
                        "product_name": "Test Product 1",
                        "remaining_carton_count": 0,
                        "remaining_total_unit": 0,
                        "storable_units": [],
                        "product": {
                            "id": 394,
                            "sku": "952468",
                            "name": "Test Product 1",
                            "category_id": 187,
                            "description": "Please provide more details of the item, like the material and use",
                            "units_per_carton": 35,
                            "image": "products/images/y777NjfllxEor3W9kqDkluRuHUHisww9TdCTwBRX.jpg",
                            "created_at": "2021-11-18T23:46:29.000000Z",
                            "updated_at": "2022-03-24T03:31:33.000000Z",
                            "customer_id": 447,
                            "created_by": 240,
                            "deleted_at": null,
                            "is_system_generated": 1,
                            "customer_admins": null,
                            "unit_price": 25.12,
                            "classification_code": "null",
                            "duty_rate": 100,
                            "carton_dimensions": "\"{}\"",
                            "is_for_classification_code": 2,
                            "upc_number": "12002155",
                            "country_from": "China",
                            "country_to": "United States",
                            "product_classification_description": "https://examplelink.com\r\n\r\nthis is an example only",
                            "unit_weight": "\"{}\"",
                            "unit_dimensions": "\"{}\"",
                            "carton_upc": "null",
                            "additional_classification_code": "null",
                            "category_sku": "187-952468",
                            "inbound_associated": true
                        }
                    }
                ],
                "warehouse": {
                    "id": 241,
                    "customer_id": 447,
                    "name": "Test First Warehouse",
                    "phone": "+1 234-567-8955",
                    "address": "55 Bergenline Ave Unit C Westwood NJ 07675",
                    "country": "Andorra",
                    "state": "Andorra la Vella",
                    "city": null,
                    "zipcode": "12345",
                    "created_at": "2021-11-12T02:36:53.000000Z",
                    "updated_at": "2022-01-10T20:35:37.000000Z",
                    "warehouse_type_id": 2,
                    "created_by": 190
                }
            }
        ],
        "first_page_url": "api/warehouse/241/inbound/pending?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "api/warehouse/241/inbound/pending?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "api/warehouse/241/inbound/pending?page=1",
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
        "path": "api/warehouse/241/inbound/pending",
        "per_page": 35,
        "prev_page_url": null,
        "to": 1,
        "total": 1
    }
}
                                                        </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div><!-- end Get Pending Inbound -->













    <!-- Get Floor Inbound -->
    <div class="col-sm-12"  id="po-get-floor-inbound" >

        <h3>Get Floor Inbound</h3>
        <p>
            Get Floor Inbound for Inbound endpoint.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/inbound/floor</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL and Query Form Data required: &nbsp;</b>
            <code>warehouse_id</code>,
            <code>search</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Floor Inbound for Inbound</code> <small> </small><br>

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
    "warehouse_id" : "{warehouse_id}",
    "search": "{search}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "results": {
        "current_page": 1,
        "data": [
            {
                "id": 72,
                "name": "Test to Arrived",
                "ref_no": "123445",
                "warehouse_id": 241,
                "customer_id": 447,
                "deleted_at": null,
                "created_at": "2022-02-15T00:04:59.000000Z",
                "updated_at": "2022-04-07T01:16:02.000000Z",
                "estimated_time": "03:30",
                "arrival_time": null,
                "inbound_status": "floor",
                "estimated_date": "2022-02-28",
                "order_id": "660778",
                "customer": "Test to Arrived",
                "documents": [
                    {
                        "id": 40,
                        "path": "public/inbounds/documents/PO-469958_72_1646240507.txt",
                        "inbound_id": 72,
                        "created_at": "2022-03-03T01:01:47.000000Z",
                        "updated_at": "2022-03-03T01:01:47.000000Z",
                        "name": "PO-469958_72_1646240507.txt",
                        "type": "text/plain",
                        "original_name": "PO-469958"
                    },
                    {
                        "id": 41,
                        "path": "public/inbounds/documents/PO-414411_72_1646387481.pdf",
                        "inbound_id": 72,
                        "created_at": "2022-03-04T17:51:21.000000Z",
                        "updated_at": "2022-03-04T17:51:21.000000Z",
                        "name": "PO-414411_72_1646387481.pdf",
                        "type": "application/pdf",
                        "original_name": "PO-414411"
                    },
                    {
                        "id": 42,
                        "path": "public/inbounds/documents/PO-530506_72_1646387481.pdf",
                        "inbound_id": 72,
                        "created_at": "2022-03-04T17:51:21.000000Z",
                        "updated_at": "2022-03-04T17:51:21.000000Z",
                        "name": "PO-530506_72_1646387481.pdf",
                        "type": "application/pdf",
                        "original_name": "PO-530506"
                    }
                ],
                "is_recieved": 0,
                "products": null,
                "notes": "null",
                "shipping_from": "Test to Arrived",
                "storable_units": null,
                "no_of_sku": 0,
                "no_of_cartons": 0,
                "no_of_storable_units": 0,
                "no_of_units": 0,
                "eta": null,
                "arrival": null,
                "is_undershipped": 0,
                "inbound_products": [
                    {
                        "id": 56,
                        "warehouse_id": 241,
                        "product_id": 394,
                        "customer_id": 447,
                        "carton_count": null,
                        "in_each_carton": 35,
                        "total_unit": null,
                        "inbound_id": 72,
                        "sku": "952468",
                        "deleted_at": null,
                        "created_at": "2022-03-03T01:01:47.000000Z",
                        "updated_at": "2022-04-07T01:16:02.000000Z",
                        "status": "recieved",
                        "is_transact": 0,
                        "is_recieved": 0,
                        "actual_carton_count": 12,
                        "actual_in_each_carton": 35,
                        "actual_total_unit": 420,
                        "shipping_unit": "carton",
                        "expected_carton_count": 12,
                        "expected_total_unit": 420,
                        "product_name": "Test Product 1",
                        "remaining_carton_count": 12,
                        "remaining_total_unit": 420,
                        "storable_units": [],
                        "product": {
                            "id": 394,
                            "sku": "952468",
                            "name": "Test Product 1",
                            "category_id": 187,
                            "description": "Please provide more details of the item, like the material and use",
                            "units_per_carton": 35,
                            "image": "products/images/y777NjfllxEor3W9kqDkluRuHUHisww9TdCTwBRX.jpg",
                            "created_at": "2021-11-18T23:46:29.000000Z",
                            "updated_at": "2022-03-24T03:31:33.000000Z",
                            "customer_id": 447,
                            "created_by": 240,
                            "deleted_at": null,
                            "is_system_generated": 1,
                            "customer_admins": null,
                            "unit_price": 25.12,
                            "classification_code": "null",
                            "duty_rate": 100,
                            "carton_dimensions": "\"{}\"",
                            "is_for_classification_code": 2,
                            "upc_number": "12002155",
                            "country_from": "China",
                            "country_to": "United States",
                            "product_classification_description": "https://examplelink.com\r\n\r\nthis is an example only",
                            "unit_weight": "\"{}\"",
                            "unit_dimensions": "\"{}\"",
                            "carton_upc": "null",
                            "additional_classification_code": "null",
                            "category_sku": "187-952468",
                            "inbound_associated": true
                        }
                    },
            },{...}, ...
        ],
        "first_page_url": "api/warehouse/241/inbound/floor?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "api/warehouse/241/inbound/floor?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "api/warehouse/241/inbound/floor?page=1",
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
        "path": "api/warehouse/241/inbound/floor",
        "per_page": 35,
        "prev_page_url": null,
        "to": 11,
        "total": 11
    }
}


                                                        </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div><!-- end Get Floor Inbound -->













    <!-- Get Complete Inbound -->
    <div class="col-sm-12"  id="po-get-completed-inbound" >

        <h3>Get Complete Inbound</h3>
        <p>
            Get Complete Inbound for Inbound endpoint.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/inbound/completed</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL and Query Form Data required: &nbsp;</b>
            <code>warehouse_id</code>,
            <code>search</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Complete Inbound for Inbound</code> <small> </small><br>

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
    "warehouse_id" : "{warehouse_id}",
    "search": "{search}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>

{
    "results": {
        "current_page": 1,
        "data": [
            {
                "id": 68,
                "name": "Test Black & Decker",
                "ref_no": "012921097",
                "warehouse_id": 241,
                "customer_id": 447,
                "deleted_at": null,
                "created_at": "2022-02-09T22:38:40.000000Z",
                "updated_at": "2022-03-23T22:13:04.000000Z",
                "estimated_time": "10:30",
                "arrival_time": null,
                "inbound_status": "completed",
                "estimated_date": "2022-03-04",
                "order_id": "574970",
                "customer": "Test Stark Industries",
                "documents": [
                    {
                        "id": 36,
                        "path": "public/inbounds/documents/PO-PO1234567_68_1645007548.txt",
                        "inbound_id": 68,
                        "created_at": "2022-02-16T18:32:28.000000Z",
                        "updated_at": "2022-02-16T18:32:28.000000Z",
                        "name": "PO-PO1234567_68_1645007548.txt",
                        "type": "text/plain",
                        "original_name": "PO-PO1234567"
                    },
                    {
                        "id": 37,
                        "path": "public/inbounds/documents/PO-665099_68_1645007548.txt",
                        "inbound_id": 68,
                        "created_at": "2022-02-16T18:32:28.000000Z",
                        "updated_at": "2022-02-16T18:32:28.000000Z",
                        "name": "PO-665099_68_1645007548.txt",
                        "type": "text/plain",
                        "original_name": "PO-665099"
                    },
                    {
                        "id": 45,
                        "path": "public/inbounds/documents/PO-530506_68_1646387984.pdf",
                        "inbound_id": 68,
                        "created_at": "2022-03-04T17:59:44.000000Z",
                        "updated_at": "2022-03-04T17:59:44.000000Z",
                        "name": "PO-530506_68_1646387984.pdf",
                        "type": "application/pdf",
                        "original_name": "PO-530506"
                    }
                ],
                "is_recieved": 0,
                "products": null,
                "notes": "Test notes here",
                "shipping_from": "Test Address Here",
                "storable_units": null,
                "no_of_sku": 0,
                "no_of_cartons": 0,
                "no_of_storable_units": 0,
                "no_of_units": 0,
                "eta": null,
                "arrival": null,
                "is_undershipped": 0,
                "inbound_products": [
                    {
                        "id": 38,
                        "warehouse_id": 241,
                        "product_id": 394,
                        "customer_id": 447,
                        "carton_count": null,
                        "in_each_carton": 35,
                        "total_unit": null,
                        "inbound_id": 68,
                        "sku": "952468",
                        "deleted_at": null,
                        "created_at": "2022-02-16T18:32:28.000000Z",
                        "updated_at": "2022-02-18T17:35:17.000000Z",
                        "status": "recieved",
                        "is_transact": 0,
                        "is_recieved": 0,
                        "actual_carton_count": 10,
                        "actual_in_each_carton": 35,
                        "actual_total_unit": 345,
                        "shipping_unit": "carton",
                        "expected_carton_count": 10,
                        "expected_total_unit": 350,
                        "product_name": "Test Product 1",
                        "remaining_carton_count": 0,
                        "remaining_total_unit": 0,
                        "storable_units": [
                            {
                                "id": 10,
                                "inbound_id": 68,
                                "warehouse_id": 241,
                                "customer_id": 447,
                                "action": "build",
                                "label": "618835",
                                "type": "drum",
                                "dimension": {
                                    "l": "12",
                                    "w": "12",
                                    "h": "12",
                                    "uom": "cm"
                                },
                                "weight": {
                                    "value": "12.50",
                                    "unit": "KG"
                                },
                                "products": null,
                                "deleted_at": null,
                                "created_at": "2022-03-03T20:08:08.000000Z",
                                "updated_at": "2022-03-23T22:13:04.000000Z",
                                "location_id": 17,
                                "status": "placed",
                                "location": {
                                    "id": 17,
                                    "type": "storage",
                                    "row": "Test2",
                                    "rack": "Test2",
                                    "shelf": "Test2",
                                    "bin": null,
                                    "gate_name": null,
                                    "position": null,
                                    "warehouse_id": 241,
                                    "customer_id": 447,
                                    "deleted_at": null,
                                    "created_at": "2022-03-09T20:51:02.000000Z",
                                    "updated_at": "2022-03-09T20:51:02.000000Z",
                                    "ref_no": [
                                        {
                                            "ref_no": "012921097",
                                            "inbound_id": 68
                                        }
                                    ]
                                }
                            },
                            {
                                "id": 13,
                                "inbound_id": 68,
                                "warehouse_id": 241,
                                "customer_id": 447,
                                "action": "build",
                                "label": "514167",
                                "type": "pallet",
                                "dimension": {
                                    "l": "56",
                                    "w": "55",
                                    "h": "59",
                                    "uom": "cm"
                                },
                                "weight": {
                                    "value": "56",
                                    "unit": "KG"
                                },
                                "products": null,
                                "deleted_at": null,
                                "created_at": "2022-03-08T10:14:22.000000Z",
                                "updated_at": "2022-03-08T10:14:33.000000Z",
                                "location_id": 15,
                                "status": "placed",
                                "location": {
                                    "id": 15,
                                    "type": "storage",
                                    "row": "R32",
                                    "rack": "COL06",
                                    "shelf": "F8",
                                    "bin": null,
                                    "gate_name": null,
                                    "position": null,
                                    "warehouse_id": 241,
                                    "customer_id": 447,
                                    "deleted_at": null,
                                    "created_at": "2022-03-03T17:31:28.000000Z",
                                    "updated_at": "2022-03-03T17:31:28.000000Z",
                                    "ref_no": [
                                        {
                                            "ref_no": "012921097",
                                            "inbound_id": 68
                                        }
                                    ]
                                }
                            }
                        ],
                        "product": {
                            "id": 394,
                            "sku": "952468",
                            "name": "Test Product 1",
                            "category_id": 187,
                            "description": "Please provide more details of the item, like the material and use",
                            "units_per_carton": 35,
                            "image": "products/images/y777NjfllxEor3W9kqDkluRuHUHisww9TdCTwBRX.jpg",
                            "created_at": "2021-11-18T23:46:29.000000Z",
                            "updated_at": "2022-03-24T03:31:33.000000Z",
                            "customer_id": 447,
                            "created_by": 240,
                            "deleted_at": null,
                            "is_system_generated": 1,
                            "customer_admins": null,
                            "unit_price": 25.12,
                            "classification_code": "null",
                            "duty_rate": 100,
                            "carton_dimensions": "\"{}\"",
                            "is_for_classification_code": 2,
                            "upc_number": "12002155",
                            "country_from": "China",
                            "country_to": "United States",
                            "product_classification_description": "https://examplelink.com\r\n\r\nthis is an example only",
                            "unit_weight": "\"{}\"",
                            "unit_dimensions": "\"{}\"",
                            "carton_upc": "null",
                            "additional_classification_code": "null",
                            "category_sku": "187-952468",
                            "inbound_associated": true
                        }
                    },{...}, ...,
        ],
        "first_page_url": "api/warehouse/241/inbound/completed?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "api/warehouse/241/inbound/completed?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "api/warehouse/241/inbound/completed?page=1",
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
        "path": "api/warehouse/241/inbound/completed",
        "per_page": 35,
        "prev_page_url": null,
        "to": 7,
        "total": 7
    }
}
                                                        </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div><!-- end Get Complete Inbound -->














    <!-- Get Cancelled Inbound -->
    <div class="col-sm-12"  id="po-cancelled-inbound" >

        <h3>Get Cancelled Inbound</h3>
        <p>
            Get Cancelled Inbound for Inbound endpoint.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/inbound/cancelled</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL and Query Form Data required: &nbsp;</b>
            <code>warehouse_id</code>,
            <code>search</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Cancelled Inbound for Inbound</code> <small> </small><br>

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
    "warehouse_id" : "{warehouse_id}",
    "search": "{search}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>

{
    "results": {
        "current_page": 1,
        "data": [
            {
                "id": 21,
                "name": "Test Truck 1",
                "ref_no": "TESTREF521455",
                "warehouse_id": 241,
                "customer_id": 447,
                "deleted_at": null,
                "created_at": "2022-01-07T21:53:32.000000Z",
                "updated_at": "2022-01-10T21:04:38.000000Z",
                "estimated_time": "15:00",
                "arrival_time": null,
                "inbound_status": "cancelled",
                "estimated_date": "2022-01-22",
                "order_id": "138168",
                "customer": "Test Customer Inbound 2",
                "documents": [],
                "is_recieved": 0,
                "products": "[{\"id\":\"80984f43-e95a-48ac-a21d-1bed927e22dd\",\"shipping_unit\":\"carton\",\"product_id\":394,\"expected\":{\"carton_count\":24,\"in_each_carton\":35,\"total_unit\":840},\"actual\":{\"carton_count\":0,\"in_each_carton\":35,\"total_unit\":0},\"sku\":\"952468\",\"status\":\"\"},{\"id\":\"9eb4656b-2b9f-4e10-8acd-4d30a1a02725\",\"shipping_unit\":\"single\",\"product_id\":395,\"expected\":null,\"actual\":null,\"sku\":\"513494\",\"status\":\"\"}]",
                "notes": null,
                "shipping_from": null,
                "storable_units": null,
                "no_of_sku": 0,
                "no_of_cartons": 0,
                "no_of_storable_units": 0,
                "no_of_units": 0,
                "eta": null,
                "arrival": null,
                "is_undershipped": 0,
                "inbound_products": [],
                "warehouse": {
                    "id": 241,
                    "customer_id": 447,
                    "name": "Test First Warehouse",
                    "phone": "+1 234-567-8955",
                    "address": "55 Bergenline Ave Unit C Westwood NJ 07675",
                    "country": "Andorra",
                    "state": "Andorra la Vella",
                    "city": null,
                    "zipcode": "12345",
                    "created_at": "2021-11-12T02:36:53.000000Z",
                    "updated_at": "2022-01-10T20:35:37.000000Z",
                    "warehouse_type_id": 2,
                    "created_by": 190
                }
            },{...},...,
        ],
        "first_page_url": "api/warehouse/241/inbound/cancelled?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "api/warehouse/241/inbound/cancelled?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "api/warehouse/241/inbound/cancelled?page=1",
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
        "path": "api/warehouse/241/inbound/cancelled",
        "per_page": 35,
        "prev_page_url": null,
        "to": 8,
        "total": 8
    }
}
                                                        </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div><!-- end Get Cancelled Inbound -->







    <!-- Get Cancelled Inbound -->
    <div class="col-sm-12"  id="po-cancelled-inbound" >

        <h3>Get Cancelled Inbound</h3>
        <p>
            Get Cancelled Inbound for Inbound endpoint.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/inbound/cancelled</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL and Query Form Data required: &nbsp;</b>
            <code>warehouse_id</code>,
            <code>search</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Cancelled Inbound for Inbound</code> <small> </small><br>

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
    "warehouse_id" : "{warehouse_id}",
    "search": "{search}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>

{
    "results": {
        "current_page": 1,
        "data": [
            {
                "id": 21,
                "name": "Test Truck 1",
                "ref_no": "TESTREF521455",
                "warehouse_id": 241,
                "customer_id": 447,
                "deleted_at": null,
                "created_at": "2022-01-07T21:53:32.000000Z",
                "updated_at": "2022-01-10T21:04:38.000000Z",
                "estimated_time": "15:00",
                "arrival_time": null,
                "inbound_status": "cancelled",
                "estimated_date": "2022-01-22",
                "order_id": "138168",
                "customer": "Test Customer Inbound 2",
                "documents": [],
                "is_recieved": 0,
                "products": "[{\"id\":\"80984f43-e95a-48ac-a21d-1bed927e22dd\",\"shipping_unit\":\"carton\",\"product_id\":394,\"expected\":{\"carton_count\":24,\"in_each_carton\":35,\"total_unit\":840},\"actual\":{\"carton_count\":0,\"in_each_carton\":35,\"total_unit\":0},\"sku\":\"952468\",\"status\":\"\"},{\"id\":\"9eb4656b-2b9f-4e10-8acd-4d30a1a02725\",\"shipping_unit\":\"single\",\"product_id\":395,\"expected\":null,\"actual\":null,\"sku\":\"513494\",\"status\":\"\"}]",
                "notes": null,
                "shipping_from": null,
                "storable_units": null,
                "no_of_sku": 0,
                "no_of_cartons": 0,
                "no_of_storable_units": 0,
                "no_of_units": 0,
                "eta": null,
                "arrival": null,
                "is_undershipped": 0,
                "inbound_products": [],
                "warehouse": {
                    "id": 241,
                    "customer_id": 447,
                    "name": "Test First Warehouse",
                    "phone": "+1 234-567-8955",
                    "address": "55 Bergenline Ave Unit C Westwood NJ 07675",
                    "country": "Andorra",
                    "state": "Andorra la Vella",
                    "city": null,
                    "zipcode": "12345",
                    "created_at": "2021-11-12T02:36:53.000000Z",
                    "updated_at": "2022-01-10T20:35:37.000000Z",
                    "warehouse_type_id": 2,
                    "created_by": 190
                }
            },{...},...,
        ],
        "first_page_url": "api/warehouse/241/inbound/cancelled?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "api/warehouse/241/inbound/cancelled?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "api/warehouse/241/inbound/cancelled?page=1",
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
        "path": "api/warehouse/241/inbound/cancelled",
        "per_page": 35,
        "prev_page_url": null,
        "to": 8,
        "total": 8
    }
}
                                                        </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div><!-- end Get Cancelled Inbound -->








    <!-- Recieve Multiple Product Inbound -->
    <div class="col-sm-12"  id="po-recieve-multiple-product" >

        <h3>Recieve Multiple Product</h3>
        <p>
            Recieve Multiple Product Inbound for Inbound endpoint.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/inbound/{inbound_id}/recieve-multiple-inbound-products</code> <br>
            <b>Request Type: &nbsp;</b> <code>PUT</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL and Body Form Data required: &nbsp;</b>
            <code>warehouse_id</code>,
            <code>inbound_id</code>,
            <code>products.*.inbound_product_id</code>,
            <code>products.*.carton_count</code>,
            <code>products.*.in_each_carton</code>,
            <code>products.*.total_unit</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Recieve Multiple Product for Inbound</code> <small> </small><br>

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
    "warehouse_id" : "{warehouse_id}",
    "inbound_id  ": "{inbound_id}",
    "products.*.inbound_product_id  ": "{products.*.inbound_product_id}",
    "products.*.carton_count": "{products.*.carton_count}",
    "products.*.in_each_carton": "{products.*.in_each_carton}",
    "products.*.total_unit": "{products.*.total_unit}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
 Pending
                                                        </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div><!-- end Recieve Multiple Product Inbound -->











    <!-- Print Order Inbound -->
    <div class="col-sm-12"  id="po-print-order" >

        <h3>Print Order</h3>
        <p>
            Print Order for Inbound endpoint.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/inbound/{inbound_id}/print-order</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b>
            <code>warehouse_id</code>,
            <code>inbound_id</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>FILE</code> <br>
            <b>Will Return: &nbsp;</b> <code>Print Order for Inbound</code> <small> </small><br>

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
    "warehouse_id" : "{warehouse_id}",
    "inbound_id  ": "{inbound_id}",
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
 Printable File
                                                        </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div><!-- end Print Order Inbound -->









    <!-- Upload Multiple Document Inbound -->
    <div class="col-sm-12"  id="po-upload-multiple-document" >

        <h3>Upload Multiple Document</h3>
        <p>
            Upload Multiple Document Inbound for Inbound endpoint.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/inbound/{inbound_id}/upload-documents</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL and Body Form Data required: &nbsp;</b>
            <code>warehouse_id</code>,
            <code>inbound_id</code>,
            <code>documents</code>,
            <br>
            <b>Return Type: &nbsp;</b> <code>FILE</code> <br>
            <b>Will Return: &nbsp;</b> <code>Upload Multiple Document for Inbound</code> <small> </small><br>

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
    "warehouse_id" : "{warehouse_id}",
    "inbound_id": "{inbound_id}",
    "documents": "{documents}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "message": "Documents uploaded successfully."
}
                                                        </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div><!-- end Upload Multiple Document Inbound -->












    <!-- Mail Customer Inbound -->
    <div class="col-sm-12"  id="po-mail-customer" >

        <h3>Mail Customer </h3>
        <p>
            Mail Customer Inbound for Inbound endpoint.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/inbound/{inbound_id}/send-email</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL and Body Form Data required: &nbsp;</b>
            <code>warehouse_id</code>,
            <code>inbound_id</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Mail Customer for Inbound</code> <small> </small><br>

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
    "warehouse_id" : "{warehouse_id}",
    "inbound_id": "{inbound_id}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "message": "Reported to Customer Successfully"
}
                                                        </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div><!-- end Mail Customer Inbound -->


















    <!-- Edit Storable Unit Inbound -->
    <div class="col-sm-12"  id="po-edit-storable-unit" >

        <h3>Edit Storable Unit </h3>
        <p>
            Edit Storable Unit for Inbound endpoint.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/inbound/{inbound_id}/edit-storable-unit/{storable_unit_id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL and Body Form Data required: &nbsp;</b>
            <code>warehouse_id</code>,
            <code>inbound_id</code>,
            <code>storable_unit_id</code>,
            <code>action</code>,
            <code>label</code>,
            <code>type</code>,
            <code>dimension</code>,
            <code>weight</code>,
            <code>products.*.inbound_product_id</code>,
            <code>products.*.carton_count</code>,
            <code>products.*.total_unit</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Edit Storable Unit for Inbound</code> <small> </small><br>

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
    "warehouse_id" : "{warehouse_id}",
    "inbound_id": "{inbound_id}",
    "storable_unit_id": "{storable_unit_id}",
    "action": "{action}",
    "label": "{label}",
    "type": "{type}",
    "dimension": "{dimension}",
    "weight": "{weight}",
    "products.*.inbound_product_id": "{products.*.inbound_product_id}",
    "products.*.carton_count": "{products.*.carton_count}",
    "products.*.total_unit": "{products.*.total_unit}"
}

                                                        </pre>

        <h4>Response</h4>
        <pre>
Pending
                                                        </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div><!-- end Mail Customer Inbound -->

    <!-- Truck Arrived Confirmation Inbound -->
    <div class="col-sm-12"  id="po-truck-arrived-confirmation" >

        <h3>Truck Arrived Confirmation</h3>
        <p>
            Truck Arrived Confirmation for Inbound endpoint.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/inbound/{inbound_id}/truck-arrived-confirm</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b>
            <code>warehouse_id</code>,
            <code>inbound_id</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Truck Arrived Confirmation for Inbound</code> <small> </small><br>

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
    "warehouse_id" : "{warehouse_id}",
    "inbound_id": "{inbound_id}"
}

                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "message": "Truck Arrived Successfully"
}
                                                        </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div><!-- end Truck Arrived Confirmation Inbound -->







    <!-- Cancel Multiple Inbound -->
    <div class="col-sm-12"  id="po-cancel-multiple-inbound" >

        <h3>Cancel Multiple</h3>
        <p>
            Cancel Multiple for Inbound endpoint.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/inbound/cancel-multiple-inbound</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL and Query Form Data required: &nbsp;</b>
            <code>warehouse_id</code>,
            <code>ids</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Cancel Multiple for Inbound</code> <small> </small><br>

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
    "warehouse_id" : "{warehouse_id}",
    "ids": "{ids}"
}

                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "message": "Inbound successfully cancelled.",
    "data": {
        "inbound_status": "cancelled"
    }
}
                                                        </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div><!-- end Cancel Multiple Inbound -->








    <!-- Place Multiple Storable Unit Inbound -->
    <div class="col-sm-12"  id="po-place-multiple-storable-unit" >

        <h3>Place Multiple Storable Unit</h3>
        <p>
            Place Multiple Storable Unit for Inbound endpoint.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/inbound/{inbound_id}/place-multiple-storable-units</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL and Body Form Data required: &nbsp;</b>
            <code>warehouse_id</code>,
            <code>inbound_id</code>,
            <code>location_id</code>,
            <code>storable_units</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Place Multiple Storable Unit for Inbound</code> <small> </small><br>

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
    "warehouse_id" : "{warehouse_id}",
    "inbound_id": "{inbound_id}",
    "location_id": "{location_id}",
    "storable_units": "{storable_units}"
}

                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "message": "Storable Unit Placed Successfully",
    "data": {
        "location_id": "4",
        "action": "recieve",
        "label": null,
        "warehouse_id": "241",
        "customer_id": 25,
        "type": "pallet",
        "dimensions": "{\"l\":\"15\",\"w\":\"12\",\"h\":\"25\",\"uom\":\"cm\"}",
        "weight": "{\"value\":\"25\",\"unit\":\"KG\"}",
        "updated_at": "2022-08-09T09:26:26.000000Z",
        "created_at": "2022-08-09T09:26:26.000000Z",
        "id": 215,
        "is_active": 1
    }
}



                                                        </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div><!-- end Cancel Multiple Inbound -->


</div> <!-- end Inbound-->
