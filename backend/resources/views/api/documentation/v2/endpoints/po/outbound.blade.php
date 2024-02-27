<div class="col-sm-12" id="po-outbound">
    <h3 class="page-header">Outbound</h3>

    <!-- Get All Outbound -->
    <div class="col-sm-12" id="po-get-all-outbound" >

        <h3>Get All Outbound</h3>
        <p>
            Get All Outbound for Outbound endpoint.
            To access their Outbound details, they have to provide warehouse_id.
            And the endpoint will return all their Outbound regarding the given warehouse_id.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/{warehouse_id}/outbounds</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>warehouse_id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get All Outbound</code> <small> </small><br>

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
        "pending_outbounds": {
            "current_page": 1,
            "data": [],
            "first_page_url": "api/warehouses/241/outbounds?page=1",
            "from": null,
            "last_page": 1,
            "last_page_url": "api/warehouses/241/outbounds?page=1",
            "links": [
                {
                    "url": null,
                    "label": "&laquo; Previous",
                    "active": false
                },
                {
                    "url": "api/warehouses/241/outbounds?page=1",
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
            "path": "api/warehouses/241/outbounds",
            "per_page": 35,
            "prev_page_url": null,
            "to": null,
            "total": 0
        },
        "floor_outbounds": {
            "current_page": 1,
            "data": [
                {
                    "id": 347,
                    "name": "testgg44",
                    "ref_no": "testred",
                    "warehouse_id": 241,
                    "customer_id": 447,
                    "deleted_at": null,
                    "created_at": "2022-04-27T01:26:27.000000Z",
                    "updated_at": "2022-04-27T01:36:07.000000Z",
                    "estimated_time": "16:16",
                    "arrival_time": null,
                    "outbound_status": "cancelled",
                    "deliver_to": "testtwo April",
                    "order_id": "915627",
                    "customer": "testcompleteAril",
                    "estimated_date": "2022-04-29",
                    "products": null,
                    "bol_number": "testbol123",
                    "notes": "ggg",
                    "outbound_products": [
                        {
                            "id": 429,
                            "warehouse_id": 241,
                            "product_id": 450,
                            "customer_id": 447,
                            "carton_count": null,
                            "in_each_carton": null,
                            "total_unit": 1,
                            "outbound_id": 347,
                            "sku": "961621",
                            "shipping_unit": "single_item",
                            "deleted_at": null,
                            "created_at": "2022-04-27T01:26:27.000000Z",
                            "updated_at": "2022-04-27T01:26:27.000000Z",
                            "expected_carton_count": 0,
                            "expected_total_unit": 0,
                            "actual_carton_count": 0,
                            "actual_total_unit": 0,
                            "status": null,
                            "product_name": "Test Add Product 1",
                            "storable_units": [],
                            "remaining_carton_count": 0,
                            "remaining_total_unit": 1,
                            "product": {
                                "id": 450,
                                "sku": "961621",
                                "name": "Test Add Product 1",
                                "category_id": 187,
                                "description": "Please provide more details of the item, like the material and use",
                                "units_per_carton": 35,
                                "image": null,
                                "created_at": "2021-12-16T17:16:14.000000Z",
                                "updated_at": "2022-04-26T02:29:35.000000Z",
                                "customer_id": 447,
                                "created_by": 240,
                                "deleted_at": null,
                                "is_system_generated": 1,
                                "customer_admins": null,
                                "unit_price": 20.05,
                                "classification_code": null,
                                "duty_rate": 100,
                                "carton_dimensions": "{}",
                                "is_for_classification_code": 0,
                                "upc_number": "null",
                                "country_from": null,
                                "country_to": null,
                                "product_classification_description": null,
                                "unit_weight": "{}",
                                "unit_dimensions": "{}",
                                "carton_upc": "null",
                                "additional_classification_code": null,
                                "category_sku": "187-961621",
                                "inbound_associated": true
                            }
                        }
                    ],
                    "outbound_storable_units": [
                        {
                            "id": 362,
                            "outbound_id": 347,
                            "warehouse_id": 241,
                            "customer_id": 447,
                            "action": null,
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
                            "status": "picked",
                            "no_of_sku": 1,
                            "deleted_at": null,
                            "created_at": "2022-04-27T01:26:27.000000Z",
                            "updated_at": "2022-04-27T01:26:27.000000Z",
                            "storable_unit_id": 13,
                            "location_id": 17,
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
                            },
                            "outbound_storable_unit_products": [
                                {
                                    "id": 540,
                                    "outbound_product_id": null,
                                    "outbound_storable_unit_id": 362,
                                    "carton_count": 5,
                                    "total_unit": 175,
                                    "created_at": "2022-04-27T01:26:27.000000Z",
                                    "updated_at": "2022-04-27T01:26:27.000000Z",
                                    "in_each_carton": 35,
                                    "storable_unit_product_id": 15
                                }
                            ]
                        }
                    ],
                    "outbound_documents": [
                        {
                            "id": 260,
                            "path": "public/outbounds/documents/outbound-960962_347_1650993987.txt",
                            "name": "outbound-960962_347_1650993987.txt",
                            "outbound_id": 347,
                            "created_at": "2022-04-27T01:26:27.000000Z",
                            "updated_at": "2022-04-27T01:26:27.000000Z",
                            "type": "text/plain",
                            "original_name": "outbound-960962"
                        }
                    ]
                },{...},...
            ],
            "first_page_url": "api/warehouses/241/outbounds?page=1",
            "from": 1,
            "last_page": 1,
            "last_page_url": "api/warehouses/241/outbounds?page=1",
            "links": [
                {
                    "url": null,
                    "label": "&laquo; Previous",
                    "active": false
                },
                {
                    "url": "api/warehouses/241/outbounds?page=1",
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
            "path": "api/warehouses/241/outbounds",
            "per_page": 35,
            "prev_page_url": null,
            "to": 19,
            "total": 19
        }
    }
}                             </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Get All Outbound -->




















    <!-- Get Outbound -->
    <div class="col-sm-12" id="po-get-outbound" >

        <h3>Get Outbound</h3>
        <p>
            Get Outbound for Outbound endpoint.
            To access their Outbound details, they have to provide id and warehouse_id.
            And the endpoint will return their Outbound regarding the given id and warehouse_id.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/{warehouse_id}/outbounds/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>id</code>, <code>warehouse_id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Outbound</code> <small> </small><br>

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
    "warehouse_id" : "{warehouse_id}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "data": {
        "id": 42,
        "name": "Test Hotpoint",
        "ref_no": "BA9212320",
        "warehouse_id": 241,
        "customer_id": 447,
        "deleted_at": null,
        "created_at": "2022-03-02T23:46:29.000000Z",
        "updated_at": "2022-04-20T16:05:09.000000Z",
        "estimated_time": "14:30",
        "arrival_time": null,
        "outbound_status": "ready",
        "deliver_to": "Test Address",
        "order_id": "401120",
        "customer": "Test Stark Industries",
        "estimated_date": "2022-03-18",
        "products": null,
        "bol_number": null,
        "notes": null,
        "outbound_products": [
            {
                "id": 111,
                "warehouse_id": 241,
                "product_id": 395,
                "customer_id": 447,
                "carton_count": null,
                "in_each_carton": null,
                "total_unit": 3,
                "outbound_id": 42,
                "sku": "513494",
                "shipping_unit": "single_item",
                "deleted_at": null,
                "created_at": "2022-03-18T19:30:05.000000Z",
                "updated_at": "2022-03-18T19:36:43.000000Z",
                "expected_carton_count": 0,
                "expected_total_unit": 0,
                "actual_carton_count": 0,
                "actual_total_unit": 0,
                "status": "picked",
                "product_name": "Testing Final",
                "storable_units": [
                    {
                        "id": 283,
                        "outbound_id": 42,
                        "warehouse_id": 241,
                        "customer_id": 447,
                        "action": null,
                        "label": "904549",
                        "type": "drum",
                        "dimension": {
                            "l": "1",
                            "w": "1",
                            "h": "",
                            "uom": "cm"
                        },
                        "weight": {
                            "value": "40",
                            "unit": "KG"
                        },
                        "products": null,
                        "status": null,
                        "no_of_sku": 2,
                        "deleted_at": null,
                        "created_at": "2022-04-20T16:05:09.000000Z",
                        "updated_at": "2022-04-20T16:05:09.000000Z",
                        "storable_unit_id": null,
                        "location_id": null
                    }
                ],
                "remaining_carton_count": 0,
                "remaining_total_unit": 2,
                "product": {
                    "id": 395,
                    "sku": "513494",
                    "name": "Testing Final",
                    "category_id": 188,
                    "description": "...",
                    "units_per_carton": 18,
                    "image": "products/images/5yoRwwemUz35WPGBU6ucaDEOWkLSF8clBjHcrBce.png",
                    "created_at": "2021-11-18T23:57:18.000000Z",
                    "updated_at": "2022-03-25T01:09:57.000000Z",
                    "customer_id": 447,
                    "created_by": 240,
                    "deleted_at": null,
                    "is_system_generated": 1,
                    "customer_admins": null,
                    "unit_price": 24.53,
                    "classification_code": null,
                    "duty_rate": 2.22,
                    "carton_dimensions": "{\"l\":\"10\",\"w\":\"20\",\"h\":\"30\",\"uom\":\"cm\"}",
                    "is_for_classification_code": 0,
                    "upc_number": "null",
                    "country_from": null,
                    "country_to": null,
                    "product_classification_description": null,
                    "unit_weight": "{\"value\":\"70\",\"unit\":\"kg\"}",
                    "unit_dimensions": "{\"l\":\"40\",\"w\":\"50\",\"h\":\"60\",\"uom\":\"cm\"}",
                    "carton_upc": "null",
                    "additional_classification_code": null,
                    "category_sku": "188-513494",
                    "inbound_associated": true
                },
                "picked_outbound_products": []
            },
            {
                "id": 112,
                "warehouse_id": 241,
                "product_id": 450,
                "customer_id": 447,
                "carton_count": 1,
                "in_each_carton": 35,
                "total_unit": 35,
                "outbound_id": 42,
                "sku": "961621",
                "shipping_unit": "carton",
                "deleted_at": null,
                "created_at": "2022-03-18T19:30:05.000000Z",
                "updated_at": "2022-03-18T19:30:05.000000Z",
                "expected_carton_count": 0,
                "expected_total_unit": 0,
                "actual_carton_count": 0,
                "actual_total_unit": 0,
                "status": null,
                "product_name": "Test Add Product 1",
                "storable_units": [
                    {
                        "id": 283,
                        "outbound_id": 42,
                        "warehouse_id": 241,
                        "customer_id": 447,
                        "action": null,
                        "label": "904549",
                        "type": "drum",
                        "dimension": {
                            "l": "1",
                            "w": "1",
                            "h": "",
                            "uom": "cm"
                        },
                        "weight": {
                            "value": "40",
                            "unit": "KG"
                        },
                        "products": null,
                        "status": null,
                        "no_of_sku": 2,
                        "deleted_at": null,
                        "created_at": "2022-04-20T16:05:09.000000Z",
                        "updated_at": "2022-04-20T16:05:09.000000Z",
                        "storable_unit_id": null,
                        "location_id": null
                    }
                ],
                "remaining_carton_count": 1,
                "remaining_total_unit": 33,
                "product": {
                    "id": 450,
                    "sku": "961621",
                    "name": "Test Add Product 1",
                    "category_id": 187,
                    "description": "Please provide more details of the item, like the material and use",
                    "units_per_carton": 35,
                    "image": null,
                    "created_at": "2021-12-16T17:16:14.000000Z",
                    "updated_at": "2022-04-26T02:29:35.000000Z",
                    "customer_id": 447,
                    "created_by": 240,
                    "deleted_at": null,
                    "is_system_generated": 1,
                    "customer_admins": null,
                    "unit_price": 20.05,
                    "classification_code": null,
                    "duty_rate": 100,
                    "carton_dimensions": "{}",
                    "is_for_classification_code": 0,
                    "upc_number": "null",
                    "country_from": null,
                    "country_to": null,
                    "product_classification_description": null,
                    "unit_weight": "{}",
                    "unit_dimensions": "{}",
                    "carton_upc": "null",
                    "additional_classification_code": null,
                    "category_sku": "187-961621",
                    "inbound_associated": true
                },
                "picked_outbound_products": []
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
        },
        "outbound_documents": [
            {
                "id": 79,
                "path": "public/outbounds/documents/PO-469958 (1)_42_1647603005.txt",
                "name": "PO-469958 (1)_42_1647603005.txt",
                "outbound_id": 42,
                "created_at": "2022-03-18T19:30:05.000000Z",
                "updated_at": "2022-03-18T19:30:05.000000Z",
                "type": "text/plain",
                "original_name": "PO-469958 (1)"
            },{...},...,
        ],
        "outbound_storable_units": [
            {
                "id": 283,
                "outbound_id": 42,
                "warehouse_id": 241,
                "customer_id": 447,
                "action": null,
                "label": "904549",
                "type": "drum",
                "dimension": {
                    "l": "1",
                    "w": "1",
                    "h": "",
                    "uom": "cm"
                },
                "weight": {
                    "value": "40",
                    "unit": "KG"
                },
                "products": null,
                "status": null,
                "no_of_sku": 2,
                "deleted_at": null,
                "created_at": "2022-04-20T16:05:09.000000Z",
                "updated_at": "2022-04-20T16:05:09.000000Z",
                "storable_unit_id": null,
                "location_id": null,
                "outbound_storable_unit_products": [
                    {
                        "id": 360,
                        "outbound_product_id": 111,
                        "outbound_storable_unit_id": 283,
                        "carton_count": 0,
                        "total_unit": 1,
                        "created_at": "2022-04-20T16:05:09.000000Z",
                        "updated_at": "2022-04-20T16:05:09.000000Z",
                        "in_each_carton": null,
                        "storable_unit_product_id": null,
                        "storable_unit_product": null
                    },{...},...
                ],
                "location": null
            }
        ]
    },
    "no_of_sku": 2,
    "total_cartons": 1,
    "total_units": 38
}
        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Get Outbound -->










    <!-- Create Outbound -->
    <div class="col-sm-12" id="po-create-outbound" >

        <h3>Create Outbound</h3>
        <p>
            Create resource for Outbound endpoint.
            To create the Outbound we have to input the ff. data: warehouse_id, name,
            ref_no, order_id, customer_id, outbound_status, estimated_time.
            And the endpoint will return json value once created.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/{warehouse_id}/outbounds/create</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b><code>warehouse_id</code>,
            <code>name</code>,
            <code>ref_no</code>,
            <code>order_id</code>,
            <code>customer_id</code>,
            <code>outbound_status</code>,
            <code>estimated_time</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Create Outbound</code> <small> </small><br>

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
    "order_id" : "{order_id}",
    "customer_id" : "{customer_id}",
    "outbound_status" : "{outbound_status}",
    "estimated_time" : "{estimated_time}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "message": "New outbound details successfully created.",
    "data": {
        "order_id": "ut",
        "name": "perspiciatis",
        "ref_no": "eaque",
        "warehouse_id": "241",
        "customer_id": 25,
        "customer": null,
        "deliver_to": null,
        "estimated_time": "necessitatibus",
        "estimated_date": null,
        "outbound_status": "fugiat",
        "bol_number": null,
        "notes": null,
        "updated_at": "2022-08-10T08:33:11.000000Z",
        "created_at": "2022-08-10T08:33:11.000000Z",
        "id": 355
    }
}
        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Create Outbound -->
















    <!-- Update Outbound -->
    <div class="col-sm-12" id="po-update-outbound" >

        <h3>Update Outbound</h3>
        <p>
            Update resource for Outbound endpoint.
            To update the Outbound we have to input the ff. data: warehouse_id, name,
            ref_no, order_id, customer_id, outbound_status, estimated_time.
            And the endpoint will return json value once updated.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/{warehouse_id}/outbounds/update/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>PUT</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b><code>id</code>,
            <code>warehouse_id</code>,
            <code>name</code>,
            <code>ref_no</code>,
            <code>customer_id</code>,
            <code>outbound_status</code>,
            <code>estimated_time</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Update Outbound</code> <small> </small><br>

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
    "warehouse_id" : "{warehouse_id}",
    "name" : "{name}",
    "ref_no" : "{ref_no}",
    "customer_id" : "{customer_id}",
    "outbound_status" : "{outbound_status}",
    "estimated_time" : "{estimated_time}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "message": "Outbound details successfully updated.",
    "data": {
        "order_id": "ut",
        "name": "atque",
        "ref_no": "quo",
        "warehouse_id": "241",
        "customer_id": 25,
        "customer": null,
        "deliver_to": null,
        "estimated_time": "quos",
        "estimated_date": null,
        "outbound_status": "nesciunt",
        "bol_number": null,
        "notes": null
    }
}
        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Update Outbound -->












    <!-- Cancel Outbound -->
    <div class="col-sm-12" id="po-cancel-outbound" >

        <h3>Cancel Outbound</h3>
        <p>
            Cancel Outbound for Outbound endpoint.
            To Cancel the Outbound we should have valid warehouse_id and id. And the endpoint will cancel the data.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/{warehouse_id}/outbounds/cancel/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b><code>id</code>,
            <code>warehouse_id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Cancel Outbound</code> <small> </small><br>

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
    "warehouse_id" : "{warehouse_id}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "message": "Outbound successfully cancelled.",
    "data": {
        "outbound_status": "cancelled"
    }
}
        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Cancel Outbound -->

















    <!-- Pick Product Outbound -->
    <div class="col-sm-12" id="po-pick-product-outbound" >

        <h3>Pick Product Outbound</h3>
        <p>
            Pick Product Outbound for Outbound endpoint.
            To Cancel the Outbound we should have valid warehouse_id and id. And the endpoint will cancel the data.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/{warehouse_id}/outbounds/{outbound_id}/pick-product/{outbound_product_id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b><code>id</code>,
            <code>warehouse_id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Pick Product</code> <small> </small><br>

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
    "outbound_id" : "{outbound_id}",
    "outbound_product_id" : "{outbound_product_id}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "message": "Product picked sucessfully.",
    "data": {
        "id": 266,
        "warehouse_id": 241,
        "product_id": 450,
        "customer_id": 447,
        "carton_count": 1,
        "in_each_carton": 35,
        "total_unit": 35,
        "outbound_id": 262,
        "sku": "961621",
        "shipping_unit": "carton",
        "deleted_at": null,
        "created_at": "2022-03-30T18:36:02.000000Z",
        "updated_at": "2022-03-30T18:36:44.000000Z",
        "expected_carton_count": 0,
        "expected_total_unit": 0,
        "actual_carton_count": 0,
        "actual_total_unit": 0,
        "status": "picked",
        "product_name": "Test Add Product 1",
        "storable_units": [
            {
                "id": 160,
                "outbound_id": 262,
                "warehouse_id": 241,
                "customer_id": 447,
                "action": null,
                "label": "228432",
                "type": "box",
                "dimension": {
                    "l": "1",
                    "w": 0,
                    "h": 0,
                    "uom": "cm"
                },
                "weight": {
                    "value": "2",
                    "unit": "KG"
                },
                "products": null,
                "status": "loaded",
                "no_of_sku": 1,
                "deleted_at": null,
                "created_at": "2022-03-30T18:37:28.000000Z",
                "updated_at": "2022-03-30T18:38:01.000000Z",
                "storable_unit_id": null,
                "location_id": null
            }
        ],
        "remaining_carton_count": 0,
        "remaining_total_unit": 33
    }
}
        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Cancel Outbound -->












    <!-- Delete Outbound -->
    <div class="col-sm-12" id="po-delete-outbound" >

        <h3>Delete Outbound</h3>
        <p>
            Delete Outbound for Inbound endpoint.
            To delete the Outbound we should have valid warehouse_id and id.
            And the endpoint will delete the data.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/{warehouse_id}/outbounds/delete/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>DELETE</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b><code>id</code>,
            <code>warehouse_id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Delete Product</code> <small> </small><br>

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
    "outbound_id" : "{outbound_id}",
    "outbound_product_id" : "{outbound_product_id}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
  {
    "message": "Outbound details successfully deleted.",
    "data": []
}
        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Delete Outbound -->








    <!-- New Storable Unit Outbound -->
    <div class="col-sm-12" id="po-new-storable-unit-outbound" >

        <h3>New Storable Unit</h3>

        <p>
            New Storable Unit for Outbound endpoint.
            New Storable Unit for Outbound  we have to input the ff. data: warehouse_id, outbound_id,
            action, label, type, dimension, weight, copies, products.*.outbound_product_id, products.*.carton_count and products.*.total_unit.
            And the endpoint will return json value once updated.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/{warehouse_id}/outbounds/{outbound_id}/new-storable-unit</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL and Body Form Data required: &nbsp;</b>
            <code>warehouse_id</code>,
            <code>outbound_id</code>,
            <code>action</code>,
            <code>label</code>,
            <code>type</code>,
            <code>dimension</code>,
            <code>weight</code>,
            <code>copies</code>,
            <code>products.*.outbound_product_id</code>,
            <code>products.*.carton_count</code>,
            <code>products.*.total_unit</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Delete Product</code> <small> </small><br>

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
    "outbound_id" : "{outbound_id}",
    "action" : "{action}",
    "label" : "{label}",
    "type" : "{type}",
    "dimension" : "{dimension}",
    "weight" : "{weight}",
    "copies" : "{copies}",
    "products.*.outbound_product_id" : "{products.*.outbound_product_id}",
    "products.*.carton_count" : "{products.*.carton_count}",
    "products.*.total_unit" : "{products.*.total_unit}"
       </pre>

        <h4>Response</h4>
        <pre>


Pending
        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End New Storable Unit Outbound -->


































    <!-- Download Document Outbound -->
    <div class="col-sm-12" id="po-download-document-outbound" >

        <h3>Download Document</h3>

        <p>
            Download Document for Outbound endpoint.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/{warehouse_id}/outbounds/{outbound_id}/download-document/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL and Body Form Data required: &nbsp;</b>
            <code>id</code>,
            <code>warehouse_id</code>,
            <code>outbound_id</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>FILE</code> <br>
            <b>Will Return: &nbsp;</b> <code>Download Document for Outbound</code> <small> </small><br>

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
    "warehouse_id" : "{warehouse_id}",
    "outbound_id" : "{outbound_id}
}
       </pre>

        <h4>Response</h4>
        <pre>
Donloadable File
        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Download Document Outbound -->








    <!-- Upload Document Outbound -->
    <div class="col-sm-12" id="po-upload-document-outbound" >

        <h3>Upload Document</h3>

        <p>
            Upload Document for Outbound endpoint.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/{warehouse_id}/outbounds/{outbound_id}/upload-document</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL and Body Form Data required: &nbsp;</b>
            <code>warehouse_id</code>,
            <code>outbound_id</code>,
            <code>file</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>FILE</code> <br>
            <b>Will Return: &nbsp;</b> <code>Download Document for Outbound</code> <small> </small><br>

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
    "outbound_id" : "{outbound_id},
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


    </div>
    <!-- End Upload Document Outbound -->







    <!-- Load Storable Unit Outbound -->
    <div class="col-sm-12" id="po-load-storable-unit-outbound" >

        <h3>Load Storable Unit</h3>

        <p>
            Load Storable Unit for Outbound endpoint.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/{warehouse_id}/outbounds/{outbound_id}/load-storable-unit/{storable_unit_id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b>
            <code>warehouse_id</code>,
            <code>outbound_id</code>,
            <code>storable_unit_id</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Load Storable Unit for Outbound</code> <small> </small><br>

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
    "outbound_id" : "{outbound_id},
    "storable_unit_id" : "{storable_unit_id}"
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


    </div>
    <!-- End Load Storable Unit Outbound -->





















    <!-- Get Pending Outbound -->
    <div class="col-sm-12" id="po-get-pending-outbound" >

        <h3>Get Pending</h3>

        <p>
            Get Pending for Outbound endpoint. .
            To access their Outbound details, they have to provide warehouse_id. And the endpoint will return all their peding Outbound.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/outbound/pending</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b>
            <code>warehouse_id</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Pending for Outbound</code> <small> </small><br>

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
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "name": "Tesr",
                "ref_no": "Test",
                "warehouse_id": 224,
                "customer_id": 24,
                "deleted_at": null,
                "created_at": "2021-11-11T09:44:14.000000Z",
                "updated_at": "2021-11-11T09:44:14.000000Z",
                "estimated_time": null,
                "arrival_time": null,
                "outbound_status": "pending",
                "deliver_to": null,
                "order_id": null,
                "customer": null,
                "estimated_date": null,
                "products": null,
                "bol_number": null,
                "notes": null,
                "outbound_products": [],
                "outbound_storable_units": [],
                "outbound_documents": []
            }
        ],
        "first_page_url": "api/warehouse/224/outbound/pending?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "api/warehouse/224/outbound/pending?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "api/warehouse/224/outbound/pending?page=1",
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
        "path": "api/warehouse/224/outbound/pending",
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

    </div>
    <!-- End Get Pending Outbound -->









    <!-- Get Floor Outbound -->
    <div class="col-sm-12" id="po-get-floor" >

        <h3>Get Floor</h3>

        <p>
            Get Floor for Outbound endpoint. .
            To access their Outbound details, they have to provide warehouse_id. And the endpoint will return all their floor Outbound.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/outbound/floor</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b>
            <code>warehouse_id</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Floor for Outbound</code> <small> </small><br>

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
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "name": "Tesr",
                "ref_no": "Test",
                "warehouse_id": 224,
                "customer_id": 24,
                "deleted_at": null,
                "created_at": "2021-11-11T09:44:14.000000Z",
                "updated_at": "2021-11-11T09:44:14.000000Z",
                "estimated_time": null,
                "arrival_time": null,
                "outbound_status": "floor",
                "deliver_to": null,
                "order_id": null,
                "customer": null,
                "estimated_date": null,
                "products": null,
                "bol_number": null,
                "notes": null,
                "outbound_products": [],
                "outbound_storable_units": [],
                "outbound_documents": []
            }
        ],
        "first_page_url": "api/warehouse/224/outbound/floor?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "api/warehouse/224/outbound/floor?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "api/warehouse/224/outbound/floor?page=1",
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
        "path": "api/warehouse/224/outbound/floor",
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

    </div>
    <!-- End Get Floor Outbound -->





















    <!-- Get Completed Outbound -->
    <div class="col-sm-12" id="po-get-completed-outbound" >

        <h3>Get Completed</h3>

        <p>
            Get Completed for Outbound endpoint. .
            To access their Outbound details, they have to provide warehouse_id. And the endpoint will return all their completed Outbound.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/outbound/completed</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b>
            <code>warehouse_id</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Completed for Outbound</code> <small> </small><br>

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
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "name": "Tesr",
                "ref_no": "Test",
                "warehouse_id": 224,
                "customer_id": 24,
                "deleted_at": null,
                "created_at": "2021-11-11T09:44:14.000000Z",
                "updated_at": "2021-11-11T09:44:14.000000Z",
                "estimated_time": null,
                "arrival_time": null,
                "outbound_status": "completed",
                "deliver_to": null,
                "order_id": null,
                "customer": null,
                "estimated_date": null,
                "products": null,
                "bol_number": null,
                "notes": null,
                "outbound_products": [],
                "outbound_storable_units": [],
                "outbound_documents": []
            }
        ],
        "first_page_url": "api/warehouse/224/outbound/completed?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "api/warehouse/224/outbound/completed?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "api/warehouse/224/outbound/completed?page=1",
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
        "path": "api/warehouse/224/outbound/completed",
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

    </div>
    <!-- End Get Completed Outbound -->


    <!-- Get Cancelled Outbound -->
    <div class="col-sm-12" id="po-get-cancelled-outbound" >

        <h3>Get Cancelled</h3>

        <p>
            Get Cancelled for Outbound endpoint. .
            To access their Outbound details, they have to provide warehouse_id. And the endpoint will return all their cancelled Outbound.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/outbound/cancelled</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b>
            <code>warehouse_id</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Cancelled for Outbound</code> <small> </small><br>

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
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "name": "Tesr",
                "ref_no": "Test",
                "warehouse_id": 224,
                "customer_id": 24,
                "deleted_at": null,
                "created_at": "2021-11-11T09:44:14.000000Z",
                "updated_at": "2021-11-11T09:44:14.000000Z",
                "estimated_time": null,
                "arrival_time": null,
                "outbound_status": "cancelled",
                "deliver_to": null,
                "order_id": null,
                "customer": null,
                "estimated_date": null,
                "products": null,
                "bol_number": null,
                "notes": null,
                "outbound_products": [],
                "outbound_storable_units": [],
                "outbound_documents": []
            }
        ],
        "first_page_url": "api/warehouse/224/outbound/completed?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "api/warehouse/224/outbound/completed?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "api/warehouse/224/outbound/completed?page=1",
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
        "path": "api/warehouse/224/outbound/completed",
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

    </div>
    <!-- End Get Cancelled Outbound -->















    <!-- Get Print Order Outbound -->
    <div class="col-sm-12" id="po-print-order-outbound" >

        <h3>Print Order</h3>

        <p>
            Get Print Order for Outbound endpoint. .
            To access their Outbound details, they have to provide warehouse_id. And the endpoint will return all their print order Outbound.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/outbound/{outbound_id}/print-order</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b>
            <code>warehouse_id</code>,
            <code>outbound_id</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>FILE</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Print Order for Outbound</code> <small> </small><br>

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
    "outbound_id" : "{outbound_id}"
}
       </pre>

        <h4>Response</h4>
        <pre>
            Print Order
        </pre>

        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>

    </div>
    <!-- End Get Print Order Outbound -->



    <!-- Pick Multiple Product Outbound -->
    <div class="col-sm-12" id="po-pick-multiple-product-outbound" >

        <h3>Pick Multiple Product</h3>

        <p>
            Pick Multiple Product for Outbound endpoint. .
            To access their Outbound details, they have to provide warehouse_id. And the endpoint will return all their pick multiple product Outbound.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/outbound/{outbound_id}/pick-batch</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b>
            <code>warehouse_id</code>,
            <code>outbound_id</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Print Order for Outbound</code> <small> </small><br>

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
    "outbound_id" : "{outbound_id}"
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

    </div>
    <!-- End Pick Multiple Product Outbound -->














    <!-- Upload Multiple Document Outbound -->
    <div class="col-sm-12" id="po-upload-multiple-document-outbound" >

        <h3>Upload Multiple Document</h3>

        <p>
            Upload Multiple Document for Outbound endpoint. .
            To access their Outbound details, they have to provide warehouse_id, outbound_id and documents. And the endpoint will return all their upload multiple product Outbound.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/outbound/{outbound_id}/upload-documents</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b>
            <code>warehouse_id</code>,
            <code>outbound_id</code>,
            <code>documents</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>FILE</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Print Order for Outbound</code> <small> </small><br>

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
    "outbound_id" : "{outbound_id}",
    "documents" : "{documents}"
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

    </div>
    <!-- End Upload Multiple Document Outbound -->










    <!-- Pick Product Outbound -->
    <div class="col-sm-12" id="po-pick-outbound-product" >

        <h3>Pick Product</h3>

        <p>
            Pick Product for Outbound endpoint. .
            To access Pick Product Outbound details, they have to provide warehouse_id, outbound_id and documents. And the endpoint will return all their pick multiple product Outbound.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/outbound/{outbound_id}/pick-outbound-product</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL and Body Form Data required: &nbsp;</b>
            <code>warehouse_id</code>,
            <code>outbound_id</code>,
            <code>outbound_product_id</code>,
            <code>storable_units.*.carton_count</code>,
            <code>storable_units.*.total_unit</code>,
            <code>storable_units.*.location_id</code>,
            <code>storable_units.*.whole</code>,
            <code>storable_units.*.label</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Pick Product for Outbound</code> <small> </small><br>

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
    "outbound_id" : "{outbound_id}",
    "outbound_product_id" : "{documents}",
    "storable_units.*.carton_count" : "{storable_units.*.carton_count}",
    "storable_units.*.total_unit" : "{storable_units.*.total_unit}",
    "storable_units.*.location_id" : "{storable_units.*.location_id}",
    "storable_units.*.whole" : "{storable_units.*.whole}",
    "storable_units.*.label" : "{storable_units.*.label}"
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

    </div>
    <!-- End Pick Product Outbound -->













    <!-- Pick Multiple Product Outbound -->
    <div class="col-sm-12" id="po-pick-multiple-outbound-product" >

        <h3>Pick Multiple Product</h3>

        <p>
            Pick Multiple Product for Outbound endpoint. .
            To access Pick Multiple Product Outbound details, they have to provide warehouse_id, outbound_id and data.
            And the endpoint will return all their pick multiple product Outbound.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/outbound/{outbound_id}/pick-multiple-outbound-product</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL and Body Form Data required: &nbsp;</b>
            <code>warehouse_id</code>,
            <code>outbound_id</code>,
            <code>data.*.outbound_product_id</code>,
            <code>data.*.storable_units.*.carton_count</code>,
            <code>data.*.storable_units.*.total_unit</code>,
            <code>data.*.storable_units.*.label</code>,
            <code>data.*.storable_units.*.location_id</code>,
            <code>data.*.storable_units.*.whole</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Pick Multiple Product for Outbound</code> <small> </small><br>

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
    "outbound_id" : "{outbound_id}",
    "data.*.outbound_product_id": "{data.*.outbound_product_id}",
    "data.*.storable_units.*.carton_count": "{data.*.storable_units.*.carton_count}",
    "data.*.storable_units.*.total_unit": "{data.*.storable_units.*.total_unit}",
    "data.*.storable_units.*.label": "{data.*.storable_units.*.label}",
    "data.*.storable_units.*.location_id": "{data.*.storable_units.*.location_id}",
    "data.*.storable_units.*.whole": "{data.*.storable_units.*.whole}"
}
       </pre>

        <h4>Response</h4>
        <pre>
{
    "message": "Product picked sucessfully.",
    "data": {
        "id": 1,
        "warehouse_id": 243,
        "product_id": 266,
        "customer_id": 25,
        "carton_count": 2,
        "in_each_carton": 29,
        "total_unit": 29,
        "outbound_id": 2,
        "sku": "505193",
        "shipping_unit": null,
        "deleted_at": null,
        "created_at": "2021-11-24T18:43:07.000000Z",
        "updated_at": "2022-05-17T11:17:11.000000Z",
        "expected_carton_count": 0,
        "expected_total_unit": 0,
        "actual_carton_count": 0,
        "actual_total_unit": 0,
        "status": "picked",
        "product_name": "Bags test",
        "storable_units": [],
        "remaining_carton_count": 2,
        "remaining_total_unit": 29
    }
}
        </pre>

        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>

    </div>
    <!-- End Pick Multiple Product Outbound -->











    <!-- Pick Storable Unit Outbound -->
    <div class="col-sm-12" id="po-pick-storable-unit-outbound" >

        <h3>Pick Storable Unit</h3>

        <p>
            Pick Storable Unit for Outbound endpoint. .
            To access Pick Multiple Product Outbound details, they have to provide warehouse_id, outbound_id and outbound_storable_unit_id.
            And the endpoint will return all their pick storable unit Outbound.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/outbound/{outbound_id}/pick-outbound-storable-unit/{outbound_storable_unit_id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b>
            <code>warehouse_id</code>,
            <code>outbound_id</code>,
            <code>outbound_storable_unit_id</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Pick Multiple Product for Outbound</code> <small> </small><br>

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
    "outbound_id" : "{outbound_id}",
    "outbound_storable_unit_id": "{outbound_storable_unit_id}"
}
       </pre>

        <h4>Response</h4>
        <pre>

{
    "message": "Storable Unit picked sucessfully.",
    "data": {
        "id": 16,
        "outbound_id": 113,
        "warehouse_id": 241,
        "customer_id": 447,
        "action": null,
        "label": null,
        "type": "pallet",
        "dimension": {
            "l": "15",
            "w": "12",
            "h": "25",
            "uom": "cm"
        },
        "weight": {
            "value": "25",
            "unit": "KG"
        },
        "products": null,
        "status": "picked",
        "no_of_sku": 1,
        "deleted_at": null,
        "created_at": "2022-03-15T22:49:54.000000Z",
        "updated_at": "2022-03-15T22:49:54.000000Z",
        "storable_unit_id": 2,
        "location_id": null
    }
}
        </pre>

        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>

    </div>
    <!-- End Pick Storable Unit Outbound -->













    <!-- Storable Unit Product Outbound -->
    <div class="col-sm-12" id="po-storable-unit-outbound-product" >

        <h3>Storable Unit Product </h3>

        <p>
            Storable Unit Product  for Outbound endpoint. .
            To access Storable Unit Product  Outbound details, they have to provide warehouse_id, outbound_id and outbound_storable_unit_id.
            And the endpoint will return all their storable unit product Outbound.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/outbound/{outbound_id}/get-storable-units/{outbound_product_id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b>
            <code>warehouse_id</code>,
            <code>outbound_id</code>,
            <code>outbound_storable_unit_id</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Storable Unit Product for Outbound</code> <small> </small><br>

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
    "outbound_id" : "{outbound_id}",
    "outbound_storable_unit_id": "{outbound_storable_unit_id}"
}
       </pre>

        <h4>Response</h4>
        <pre>
 {
    "results": [
        {
            "id": 215,
            "warehouse_id": 241,
            "customer_id": 25,
            "action": "recieve",
            "label": null,
            "type": "pallet",
            "dimensions": "{\"l\":\"15\",\"w\":\"12\",\"h\":\"25\",\"uom\":\"cm\"}",
            "weight": "{\"value\":\"25\",\"unit\":\"KG\"}",
            "products": null,
            "deleted_at": null,
            "created_at": "2022-08-09T09:26:26.000000Z",
            "updated_at": "2022-08-09T09:26:26.000000Z",
            "location_id": 4,
            "is_active": 1,
            "storable_unit_products": [
                {
                    "id": 236,
                    "product_id": 395,
                    "carton_count": 5,
                    "total_unit": 90,
                    "created_at": "2022-08-09T09:26:26.000000Z",
                    "updated_at": "2022-08-09T09:26:26.000000Z",
                    "storable_unit_id": 215,
                    "product": {
                        "id": 395,
                        "sku": "513494",
                        "name": "Testing Final",
                        "category_id": 188,
                        "description": "...",
                        "units_per_carton": 18,
                        "image": "products/images/5yoRwwemUz35WPGBU6ucaDEOWkLSF8clBjHcrBce.png",
                        "created_at": "2021-11-18T23:57:18.000000Z",
                        "updated_at": "2022-03-25T01:09:57.000000Z",
                        "customer_id": 447,
                        "created_by": 240,
                        "deleted_at": null,
                        "is_system_generated": 1,
                        "customer_admins": null,
                        "unit_price": 24.53,
                        "classification_code": null,
                        "duty_rate": 2.22,
                        "carton_dimensions": "{\"l\":\"10\",\"w\":\"20\",\"h\":\"30\",\"uom\":\"cm\"}",
                        "is_for_classification_code": 0,
                        "upc_number": "null",
                        "country_from": null,
                        "country_to": null,
                        "product_classification_description": null,
                        "unit_weight": "{\"value\":\"70\",\"unit\":\"kg\"}",
                        "unit_dimensions": "{\"l\":\"40\",\"w\":\"50\",\"h\":\"60\",\"uom\":\"cm\"}",
                        "carton_upc": "null",
                        "additional_classification_code": null,
                        "category_sku": "188-513494",
                        "inbound_associated": true
                    }
                }
            ]
        },{...},...
    ]
}
        </pre>

        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>

    </div>
    <!-- End Storable Unit Product Outbound -->




    <!-- Location Pick Product Outbound -->
    <div class="col-sm-12" id="po-location-pick-outbound-product" >

        <h3>Location Pick Product</h3>

        <p>
            Location Pick Product for Outbound endpoint. .
            To access Location Pick Product Outbound details, they have to provide warehouse_id, outbound_id and outbound_storable_unit_id.
            And the endpoint will return all their location pick product Outbound.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/outbound/{outbound_id}/get-locations/{outbound_product_id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b>
            <code>warehouse_id</code>,
            <code>outbound_id</code>,
            <code>outbound_storable_unit_id</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Location Pick Product for Outbound</code> <small> </small><br>

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
    "outbound_id" : "{outbound_id}",
    "outbound_storable_unit_id": "{outbound_storable_unit_id}"
}
       </pre>

        <h4>Response</h4>
        <pre>
{
    "results": [
        {
            "id": 27,
            "type": "storage",
            "row": "Test12",
            "rack": "Test12",
            "shelf": "Test12",
            "bin": null,
            "gate_name": null,
            "position": null,
            "warehouse_id": 241,
            "customer_id": 447,
            "deleted_at": null,
            "created_at": "2022-03-09T20:53:21.000000Z",
            "updated_at": "2022-03-09T20:53:21.000000Z",
            "ref_no": [
                {
                    "ref_no": "123456",
                    "inbound_id": 155
                }
            ],
            "storable_units": [
                {
                    "id": 16,
                    "warehouse_id": 241,
                    "customer_id": 447,
                    "action": "recieve",
                    "label": "803419",
                    "type": "drum",
                    "dimensions": "{\"l\":\"10\",\"w\":\"5\",\"h\":\"25\",\"uom\":\"cm\"}",
                    "weight": "{\"value\":\"50\",\"unit\":\"LB\"}",
                    "products": null,
                    "deleted_at": null,
                    "created_at": "2022-03-25T22:11:30.000000Z",
                    "updated_at": "2022-03-25T22:11:30.000000Z",
                    "location_id": 27,
                    "is_active": 1,
                    "storable_unit_products": [
                        {
                            "id": 18,
                            "product_id": 395,
                            "carton_count": 0,
                            "total_unit": 24,
                            "created_at": "2022-03-25T22:11:30.000000Z",
                            "updated_at": "2022-04-27T01:45:26.000000Z",
                            "storable_unit_id": 16,
                            "product": {
                                "id": 395,
                                "sku": "513494",
                                "name": "Testing Final",
                                "category_id": 188,
                                "description": "...",
                                "units_per_carton": 18,
                                "image": "products/images/5yoRwwemUz35WPGBU6ucaDEOWkLSF8clBjHcrBce.png",
                                "created_at": "2021-11-18T23:57:18.000000Z",
                                "updated_at": "2022-03-25T01:09:57.000000Z",
                                "customer_id": 447,
                                "created_by": 240,
                                "deleted_at": null,
                                "is_system_generated": 1,
                                "customer_admins": null,
                                "unit_price": 24.53,
                                "classification_code": null,
                                "duty_rate": 2.22,
                                "carton_dimensions": "{\"l\":\"10\",\"w\":\"20\",\"h\":\"30\",\"uom\":\"cm\"}",
                                "is_for_classification_code": 0,
                                "upc_number": "null",
                                "country_from": null,
                                "country_to": null,
                                "product_classification_description": null,
                                "unit_weight": "{\"value\":\"70\",\"unit\":\"kg\"}",
                                "unit_dimensions": "{\"l\":\"40\",\"w\":\"50\",\"h\":\"60\",\"uom\":\"cm\"}",
                                "carton_upc": "null",
                                "additional_classification_code": null,
                                "category_sku": "188-513494",
                                "inbound_associated": true
                            }
                        },{...},...
                    ]
                },{...},...
            ]
        },{...},...
    ]
}
        </pre>

        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>

    </div>
    <!-- End Location Pick Product Outbound -->




    <!-- Cancel Multiple Outbound -->
    <div class="col-sm-12" id="po-cancel-multiple-outbound" >

        <h3>Cancel Multiple Outbound</h3>

        <p>
            Cancel Multiple Outbound for Outbound endpoint. .
            To access Cancel Multiple Outbound Outbound details, they have to provide warehouse_id, outbound_id and outbound_storable_unit_id.
            And the endpoint will return all their cancel multiple outbound.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/outbound/cancel-multiple-outbound</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b>
            <code>warehouse_id</code>,
            <code>ids</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Location Pick Product for Outbound</code> <small> </small><br>

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
    "ids" : "{ids}"
}
       </pre>

        <h4>Response</h4>
        <pre>


{
    "results": {
        "pending_outbounds": {
            "current_page": 1,
            "data": [],
            "first_page_url": "api/warehouses/241/outbounds?page=1",
            "from": null,
            "last_page": 1,
            "last_page_url": "api/warehouses/241/outbounds?page=1",
            "links": [
                {
                    "url": null,
                    "label": "&laquo; Previous",
                    "active": false
                },
                {
                    "url": "api/warehouses/241/outbounds?page=1",
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
            "path": "api/warehouses/241/outbounds",
            "per_page": 35,
            "prev_page_url": null,
            "to": null,
            "total": 0
        },
        "floor_outbounds": {
            "current_page": 1,
            "data": [

        {
                    "id": 45,
                    "name": "Test Gekko & Co",
                    "ref_no": "SD9212969",
                    "warehouse_id": 241,
                    "customer_id": 447,
                    "deleted_at": null,
                    "created_at": "2022-03-03T00:36:29.000000Z",
                    "updated_at": "2022-03-19T18:20:01.000000Z",
                    "estimated_time": "15:15",
                    "arrival_time": null,
                    "outbound_status": "cancelled",
                    "deliver_to": "Testing Address",
                    "order_id": "487152",
                    "customer": "Testing Customer",
                    "estimated_date": "2022-03-19",
                    "products": null,
                    "bol_number": null,
                    "notes": null,
                    "outbound_products": [
                        {
                            "id": 137,
                            "warehouse_id": 241,
                            "product_id": 528,
                            "customer_id": 447,
                            "carton_count": 5,
                            "in_each_carton": 12,
                            "total_unit": 60,
                            "outbound_id": 45,
                            "sku": "499082",
                            "shipping_unit": "carton",
                            "deleted_at": null,
                            "created_at": "2022-03-18T22:28:26.000000Z",
                            "updated_at": "2022-03-18T22:28:26.000000Z",
                            "expected_carton_count": 0,
                            "expected_total_unit": 0,
                            "actual_carton_count": 0,
                            "actual_total_unit": 0,
                            "status": null,
                            "product_name": "Test Product with Codes",
                            "storable_units": [
                                {
                                    "id": 39,
                                    "outbound_id": 45,
                                    "warehouse_id": 241,
                                    "customer_id": 447,
                                    "action": null,
                                    "label": "789277",
                                    "type": "drum",
                                    "dimension": {
                                        "l": "2",
                                        "w": "4",
                                        "h": "3",
                                        "uom": "cm"
                                    },
                                    "weight": {
                                        "value": "2",
                                        "unit": "KG"
                                    },
                                    "products": null,
                                    "status": "loaded",
                                    "no_of_sku": 1,
                                    "deleted_at": null,
                                    "created_at": "2022-03-19T18:20:01.000000Z",
                                    "updated_at": "2022-04-13T09:34:55.000000Z",
                                    "storable_unit_id": null,
                                    "location_id": null
                                },
                                {
                                    "id": 41,
                                    "outbound_id": 45,
                                    "warehouse_id": 241,
                                    "customer_id": 447,
                                    "action": null,
                                    "label": "857599",
                                    "type": "loose box",
                                    "dimension": {
                                        "l": "1",
                                        "w": "3",
                                        "h": "2",
                                        "uom": "in"
                                    },
                                    "weight": {
                                        "value": "3",
                                        "unit": "LB"
                                    },
                                    "products": null,
                                    "status": null,
                                    "no_of_sku": 1,
                                    "deleted_at": null,
                                    "created_at": "2022-03-19T18:23:55.000000Z",
                                    "updated_at": "2022-03-19T18:23:55.000000Z",
                                    "storable_unit_id": null,
                                    "location_id": null
                                }
                            ],
                            "remaining_carton_count": 3,
                            "remaining_total_unit": 58,
                            "product": {
                                "id": 528,
                                "sku": "499082",
                                "name": "Test Product with Codes",
                                "category_id": 187,
                                "description": null,
                                "units_per_carton": 12,
                                "image": null,
                                "created_at": "2022-01-03T17:52:30.000000Z",
                                "updated_at": "2022-01-05T01:03:10.000000Z",
                                "customer_id": 447,
                                "created_by": 190,
                                "deleted_at": null,
                                "is_system_generated": 1,
                                "customer_admins": null,
                                "unit_price": 21.21,
                                "classification_code": "TESTCODES1234",
                                "duty_rate": 0,
                                "carton_dimensions": "{\"l\":\"10\",\"w\":\"11\",\"h\":\"12\",\"uom\":\"in\"}",
                                "is_for_classification_code": 0,
                                "upc_number": "null",
                                "country_from": null,
                                "country_to": null,
                                "product_classification_description": null,
                                "unit_weight": "{\"value\":0,\"unit\":\"kg\"}",
                                "unit_dimensions": "{\"l\":\"10\",\"w\":\"11\",\"h\":\"12\",\"uom\":\"cm\"}",
                                "carton_upc": "null",
                                "additional_classification_code": "TESTCODES4321",
                                "category_sku": "187-499082",
                                "inbound_associated": true
                            }
                        }
                    ],
                    "outbound_storable_units": [
                        {
                            "id": 34,
                            "outbound_id": 45,
                            "warehouse_id": 241,
                            "customer_id": 447,
                            "action": null,
                            "label": null,
                            "type": "pallet",
                            "dimension": "{\"l\":\"15\",\"w\":\"12\",\"h\":\"25\",\"uom\":\"cm\"}",
                            "weight": "{\"value\":\"25\",\"unit\":\"KG\"}",
                            "products": null,
                            "status": "loaded",
                            "no_of_sku": 1,
                            "deleted_at": null,
                            "created_at": "2022-03-18T22:28:26.000000Z",
                            "updated_at": "2022-04-15T01:23:01.000000Z",
                            "storable_unit_id": null,
                            "location_id": null,
                            "location": null,
                            "outbound_storable_unit_products": [
                                {
                                    "id": 36,
                                    "outbound_product_id": null,
                                    "outbound_storable_unit_id": 34,
                                    "carton_count": 5,
                                    "total_unit": 90,
                                    "created_at": "2022-03-18T22:28:26.000000Z",
                                    "updated_at": "2022-03-18T22:28:26.000000Z",
                                    "in_each_carton": 18,
                                    "storable_unit_product_id": 2
                                }
                            ]
                        },
                        {
                            "id": 35,
                            "outbound_id": 45,
                            "warehouse_id": 241,
                            "customer_id": 447,
                            "action": null,
                            "label": "701312",
                            "type": "drum",
                            "dimension": "{\"l\":\"12\",\"w\":\"13\",\"h\":\"15\",\"uom\":\"cm\"}",
                            "weight": "{\"value\":\"15.23\",\"unit\":\"KG\"}",
                            "products": null,
                            "status": "loaded",
                            "no_of_sku": 1,
                            "deleted_at": null,
                            "created_at": "2022-03-18T22:28:26.000000Z",
                            "updated_at": "2022-04-13T09:35:17.000000Z",
                            "storable_unit_id": null,
                            "location_id": null,
                            "location": null,
                            "outbound_storable_unit_products": [
                                {
                                    "id": 37,
                                    "outbound_product_id": null,
                                    "outbound_storable_unit_id": 35,
                                    "carton_count": 10,
                                    "total_unit": 100,
                                    "created_at": "2022-03-18T22:28:26.000000Z",
                                    "updated_at": "2022-03-18T22:28:26.000000Z",
                                    "in_each_carton": 12,
                                    "storable_unit_product_id": 3
                                }
                            ]
                        },
                        {
                            "id": 39,
                            "outbound_id": 45,
                            "warehouse_id": 241,
                            "customer_id": 447,
                            "action": null,
                            "label": "789277",
                            "type": "drum",
                            "dimension": {
                                "l": "2",
                                "w": "4",
                                "h": "3",
                                "uom": "cm"
                            },
                            "weight": {
                                "value": "2",
                                "unit": "KG"
                            },
                            "products": null,
                            "status": "loaded",
                            "no_of_sku": 1,
                            "deleted_at": null,
                            "created_at": "2022-03-19T18:20:01.000000Z",
                            "updated_at": "2022-04-13T09:34:55.000000Z",
                            "storable_unit_id": null,
                            "location_id": null,
                            "location": null,
                            "outbound_storable_unit_products": [
                                {
                                    "id": 39,
                                    "outbound_product_id": 137,
                                    "outbound_storable_unit_id": 39,
                                    "carton_count": 1,
                                    "total_unit": 1,
                                    "created_at": "2022-03-19T18:20:01.000000Z",
                                    "updated_at": "2022-03-19T18:20:01.000000Z",
                                    "in_each_carton": null,
                                    "storable_unit_product_id": null
                                }
                            ]
                        },
                        {
                            "id": 41,
                            "outbound_id": 45,
                            "warehouse_id": 241,
                            "customer_id": 447,
                            "action": null,
                            "label": "857599",
                            "type": "loose box",
                            "dimension": {
                                "l": "1",
                                "w": "3",
                                "h": "2",
                                "uom": "in"
                            },
                            "weight": {
                                "value": "3",
                                "unit": "LB"
                            },
                            "products": null,
                            "status": null,
                            "no_of_sku": 1,
                            "deleted_at": null,
                            "created_at": "2022-03-19T18:23:55.000000Z",
                            "updated_at": "2022-03-19T18:23:55.000000Z",
                            "storable_unit_id": null,
                            "location_id": null,
                            "location": null,
                            "outbound_storable_unit_products": [
                                {
                                    "id": 40,
                                    "outbound_product_id": 137,
                                    "outbound_storable_unit_id": 41,
                                    "carton_count": 1,
                                    "total_unit": 1,
                                    "created_at": "2022-03-19T18:23:55.000000Z",
                                    "updated_at": "2022-03-19T18:23:55.000000Z",
                                    "in_each_carton": null,
                                    "storable_unit_product_id": null
                                }
                            ]
                        },
                        {
                            "id": 43,
                            "outbound_id": 45,
                            "warehouse_id": 241,
                            "customer_id": 447,
                            "action": null,
                            "label": "438126",
                            "type": "drum",
                            "dimension": {
                                "l": "1",
                                "w": "1",
                                "h": "1",
                                "uom": "cm"
                            },
                            "weight": {
                                "value": "3",
                                "unit": "KG"
                            },
                            "products": null,
                            "status": "loaded",
                            "no_of_sku": 1,
                            "deleted_at": null,
                            "created_at": "2022-03-19T18:44:30.000000Z",
                            "updated_at": "2022-04-13T09:35:03.000000Z",
                            "storable_unit_id": null,
                            "location_id": null,
                            "location": null,
                            "outbound_storable_unit_products": [
                                {
                                    "id": 42,
                                    "outbound_product_id": 147,
                                    "outbound_storable_unit_id": 43,
                                    "carton_count": 1,
                                    "total_unit": 1,
                                    "created_at": "2022-03-19T18:44:30.000000Z",
                                    "updated_at": "2022-03-19T18:44:30.000000Z",
                                    "in_each_carton": null,
                                    "storable_unit_product_id": null
                                }
                            ]
                        }
                    ],
                    "outbound_documents": []
                },
                {
                    "id": 102,
                    "name": "test document",
                    "ref_no": "testddd2",
                    "warehouse_id": 241,
                    "customer_id": 447,
                    "deleted_at": null,
                    "created_at": "2022-03-11T22:20:56.000000Z",
                    "updated_at": "2022-04-21T14:06:52.000000Z",
                    "estimated_time": "05:23",
                    "arrival_time": null,
                    "outbound_status": "cancelled",
                    "deliver_to": "testds",
                    "order_id": "604760",
                    "customer": "test document",
                    "estimated_date": "2022-03-31",
                    "products": null,
                    "bol_number": "test434",
                    "notes": "asd",
                    "outbound_products": [
                        {
                            "id": 269,
                            "warehouse_id": 241,
                            "product_id": 395,
                            "customer_id": 447,
                            "carton_count": null,
                            "in_each_carton": null,
                            "total_unit": 3,
                            "outbound_id": 102,
                            "sku": "513494",
                            "shipping_unit": "single_item",
                            "deleted_at": null,
                            "created_at": "2022-03-30T23:06:48.000000Z",
                            "updated_at": "2022-03-30T23:06:48.000000Z",
                            "expected_carton_count": 0,
                            "expected_total_unit": 0,
                            "actual_carton_count": 0,
                            "actual_total_unit": 0,
                            "status": null,
                            "product_name": "Testing Final",
                            "storable_units": [
                                {
                                    "id": 318,
                                    "outbound_id": 102,
                                    "warehouse_id": 241,
                                    "customer_id": 447,
                                    "action": null,
                                    "label": "823004",
                                    "type": "pallet",
                                    "dimension": {
                                        "l": "1",
                                        "w": "1",
                                        "h": "1",
                                        "uom": "cm"
                                    },
                                    "weight": {
                                        "value": "12",
                                        "unit": "KG"
                                    },
                                    "products": null,
                                    "status": null,
                                    "no_of_sku": 1,
                                    "deleted_at": null,
                                    "created_at": "2022-04-21T14:06:53.000000Z",
                                    "updated_at": "2022-04-21T14:06:53.000000Z",
                                    "storable_unit_id": null,
                                    "location_id": null
                                }
                            ],
                            "remaining_carton_count": 0,
                            "remaining_total_unit": 2,
                            "product": {
                                "id": 395,
                                "sku": "513494",
                                "name": "Testing Final",
                                "category_id": 188,
                                "description": "...",
                                "units_per_carton": 18,
                                "image": "products/images/5yoRwwemUz35WPGBU6ucaDEOWkLSF8clBjHcrBce.png",
                                "created_at": "2021-11-18T23:57:18.000000Z",
                                "updated_at": "2022-03-25T01:09:57.000000Z",
                                "customer_id": 447,
                                "created_by": 240,
                                "deleted_at": null,
                                "is_system_generated": 1,
                                "customer_admins": null,
                                "unit_price": 24.53,
                                "classification_code": null,
                                "duty_rate": 2.22,
                                "carton_dimensions": "{\"l\":\"10\",\"w\":\"20\",\"h\":\"30\",\"uom\":\"cm\"}",
                                "is_for_classification_code": 0,
                                "upc_number": "null",
                                "country_from": null,
                                "country_to": null,
                                "product_classification_description": null,
                                "unit_weight": "{\"value\":\"70\",\"unit\":\"kg\"}",
                                "unit_dimensions": "{\"l\":\"40\",\"w\":\"50\",\"h\":\"60\",\"uom\":\"cm\"}",
                                "carton_upc": "null",
                                "additional_classification_code": null,
                                "category_sku": "188-513494",
                                "inbound_associated": true
                            }
                        }
                    ],
                    "outbound_storable_units": [
                        {
                            "id": 318,
                            "outbound_id": 102,
                            "warehouse_id": 241,
                            "customer_id": 447,
                            "action": null,
                            "label": "823004",
                            "type": "pallet",
                            "dimension": {
                                "l": "1",
                                "w": "1",
                                "h": "1",
                                "uom": "cm"
                            },
                            "weight": {
                                "value": "12",
                                "unit": "KG"
                            },
                            "products": null,
                            "status": null,
                            "no_of_sku": 1,
                            "deleted_at": null,
                            "created_at": "2022-04-21T14:06:53.000000Z",
                            "updated_at": "2022-04-21T14:06:53.000000Z",
                            "storable_unit_id": null,
                            "location_id": null,
                            "location": null,
                            "outbound_storable_unit_products": [
                                {
                                    "id": 431,
                                    "outbound_product_id": 269,
                                    "outbound_storable_unit_id": 318,
                                    "carton_count": 0,
                                    "total_unit": 1,
                                    "created_at": "2022-04-21T14:06:53.000000Z",
                                    "updated_at": "2022-04-21T14:06:53.000000Z",
                                    "in_each_carton": null,
                                    "storable_unit_product_id": null
                                }
                            ]
                        }
                    ],
                    "outbound_documents": [
                        {
                            "id": 159,
                            "path": "public/outbounds/documents/PO-001122 (1)_102_1648652808.txt",
                            "name": "PO-001122 (1)_102_1648652808.txt",
                            "outbound_id": 102,
                            "created_at": "2022-03-30T23:06:48.000000Z",
                            "updated_at": "2022-03-30T23:06:48.000000Z",
                            "type": "text/plain",
                            "original_name": "PO-001122 (1)"
                        },
                        {
                            "id": 160,
                            "path": "public/outbounds/documents/PO-469958_102_1648652808.txt",
                            "name": "PO-469958_102_1648652808.txt",
                            "outbound_id": 102,
                            "created_at": "2022-03-30T23:06:48.000000Z",
                            "updated_at": "2022-03-30T23:06:48.000000Z",
                            "type": "text/plain",
                            "original_name": "PO-469958"
                        },
                        {
                            "id": 161,
                            "path": "public/outbounds/documents/PO-001122_102_1648652808.txt",
                            "name": "PO-001122_102_1648652808.txt",
                            "outbound_id": 102,
                            "created_at": "2022-03-30T23:06:48.000000Z",
                            "updated_at": "2022-03-30T23:06:48.000000Z",
                            "type": "text/plain",
                            "original_name": "PO-001122"
                        },
                        {
                            "id": 162,
                            "path": "public/outbounds/documents/outbound-960962_102_1648652808.txt",
                            "name": "outbound-960962_102_1648652808.txt",
                            "outbound_id": 102,
                            "created_at": "2022-03-30T23:06:48.000000Z",
                            "updated_at": "2022-03-30T23:06:48.000000Z",
                            "type": "text/plain",
                            "original_name": "outbound-960962"
                        }
                    ]
                },
            ],
            "first_page_url": "api/warehouses/241/outbounds?page=1",
            "from": 1,
            "last_page": 1,
            "last_page_url": "api/warehouses/241/outbounds?page=1",
            "links": [
                {
                    "url": null,
                    "label": "&laquo; Previous",
                    "active": false
                },
                {
                    "url": "api/warehouses/241/outbounds?page=1",
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
            "path": "api/warehouses/241/outbounds",
            "per_page": 35,
            "prev_page_url": null,
            "to": 19,
            "total": 19
        }
    }
}

        </pre>

        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>

    </div>
    <!-- End Cancel Multiple Outbound -->














    <!-- Edit Storable Unit Outbound -->
    <div class="col-sm-12" id="po-edit-storable-unit-outbound" >

        <h3>Edit Storable Unit</h3>

        <p>
            Edit Storable Unit Outbound endpoint.
            To edit or update the Outbound we have to input the ff.
            data: warehouse_id, outbound_id, storable_unit_id, action, label, type, dimension, weight,
            products.*.outbound_product_id,  products.*.carton_count and products.*.total_unit.
            And the endpoint will return json value once updated.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/outbound/{outbound_id}/edit-storable-unit/{storable_unit_id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL and Body Form Data required: &nbsp;</b>
            <code>warehouse_id</code>,
            <code>outbound_id</code>,
            <code>storable_unit_id</code>,
            <code>action</code>,
            <code>label</code>,
            <code>type</code>,
            <code>weight</code>,
            <code>products.*.outbound_product_id</code>,
            <code>products.*.carton_count</code>,
            <code>products.*.total_unit</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Location Pick Product for Outbound</code> <small> </small><br>

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
    "outbound_id" : "{outbound_id}",
    "storable_unit_id" : "{storable_unit_id}",
    "action" : "{action}",
    "label" : "{label}",
    "type" : "{type}",
    "weight" : "{weight}",
    "products.*.outbound_product_id" : "{products.*.outbound_product_id}",
    "products.*.carton_count" : "{products.*.carton_count}",
    "products.*.total_unit" : "{products.*.total_unit}"
}
       </pre>

        <h4>Response</h4>
        <pre>
 {
    "message": "Storable Unit has been updated successfully.",
    "data": true
}
        </pre>

        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>

    </div>
    <!-- End Cancel Multiple Outbound -->




</div> <!-- end Outbound -->
