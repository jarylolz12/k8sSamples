<div class="col-sm-12" id="po-inventory">
    <h3 class="page-header">Inventory</h3>


    <!--  Get All Inventory -->
    <div class="col-sm-12"  id="po-get-all-inventory" >

        <h3>Get All Inventory</h3>
        <p>
            Get all Inventory for Inventory endpoint.
            To access their Inventory details, they have to provide warehouse_id.
            And the endpoint will return all their Inventory.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/{warehouse_id}/products</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b>
            <code>warehouse_id</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get All Inventory</code> <small> </small><br>

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
    "results": [
        {
            "id": 174,
            "warehouse_id": 241,
            "product_id": 2,
            "customer_id": 447,
            "carton_count": 100,
            "in_each_carton": 100,
            "total_unit": 225,
            "deleted_at": null,
            "created_at": "2022-03-23T15:33:38.000000Z",
            "updated_at": "2022-03-23T15:33:38.000000Z",
            "sku": null,
            "classification_code": null,
            "duty_rate": null,
            "inbound": 0,
            "products_allocated": 0,
            "category_sku": null,
            "product_status": "deleted",
            "product": {
                "id": 2,
                "sku": "786595",
                "name": "test product name",
                "category_id": 8,
                "description": "test description",
                "units_per_carton": 2,
                "image": "http://po.shifl.test:82/storage/products/images/luQGSVpearFPgVkqT7tDlAUIQU2zDPCEkewiiASk.jpg",
                "created_at": "2021-06-30T18:41:54.000000Z",
                "updated_at": "2021-07-01T22:03:23.000000Z",
                "customer_id": 42,
                "created_by": 42,
                "deleted_at": "2021-07-01T22:03:23.000000Z",
                "is_system_generated": null,
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
                "category_sku": "8-786595",
                "inbound_associated": false
            }
        },
    ]
}
                                                        </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div><!-- end Get All Inventory -->







    <!--  Get Inventory -->
    <div class="col-sm-12"  id="po-get-inventory" >

        <h3>Get Inventory</h3>
        <p>
            Get Inventory for Inventory endpoint.
            To access their Inventory details, they have to provide warehouse_id.
            And the endpoint will return their Inventory.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/{warehouse_id}/products/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b>
            <code>warehouse_id</code>,
            <code>id</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Inventory</code> <small> </small><br>

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
    "id": 174,
    "warehouse_id": 241,
    "product_id": 2,
    "customer_id": 447,
    "carton_count": 100,
    "in_each_carton": 100,
    "total_unit": 225,
    "deleted_at": null,
    "created_at": "2022-03-23T15:33:38.000000Z",
    "updated_at": "2022-03-23T15:33:38.000000Z",
    "sku": null,
    "classification_code": null,
    "duty_rate": null,
    "category_sku": null,
    "product_status": "deleted",
    "product": {
        "id": 2,
        "sku": "786595",
        "name": "test product name",
        "category_id": 8,
        "description": "test description",
        "units_per_carton": 2,
        "image": "public/products/images/luQGSVpearFPgVkqT7tDlAUIQU2zDPCEkewiiASk.jpg",
        "created_at": "2021-06-30T18:41:54.000000Z",
        "updated_at": "2021-07-01T22:03:23.000000Z",
        "customer_id": 42,
        "created_by": 42,
        "deleted_at": "2021-07-01T22:03:23.000000Z",
        "is_system_generated": null,
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
        "category_sku": "8-786595",
        "inbound_associated": false
    }
}                                   </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div><!-- end Get Inventory -->








    <!--  Create Inventory -->
    <div class="col-sm-12"  id="po-create-inventory" >

        <h3>Create Inventory</h3>
        <p>
            Create Inventory for Inventory endpoint.
            To create the Inventory we have to input the ff. data:
            warehouse_id, customer_id, products.*.id, products.*.carton_count, products.*.in_each_carton and
            products.*.total_unit
            And the endpoint will return json value once created.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/{warehouse_id}/products/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL and Body Form Data required: &nbsp;</b>
            <code>warehouse_id</code>,
            <code>customer_id</code>,
            <code>products.*.id</code>,
            <code>products.*.carton_count</code>,
            <code>products.*.in_each_carton</code>,
            <code>products.*.total_unit</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Create Inventory</code> <small> </small><br>

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
    "customer_id" : "{customer_id}",
    "products.*.id" : "{products.*.id}",
    "products.*.carton_count" : "{products.*.carton_count}",
    "products.*.in_each_carton" : "{products.*.in_each_carton}",
    "products.*.total_unit" : "{products.*.total_unit}",


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

    </div><!-- end Create Inventory -->








    <!--  Update Inventory -->
    <div class="col-sm-12"  id="po-update-inventory" >

        <h3>Update Inventory</h3>
        <p>
            Update Inventory for Inventory endpoint.
            To update the Inventory we have to input the ff. data:
            warehouse_id, customer_id, products.*.id, products.*.carton_count, products.*.in_each_carton and
            products.*.total_unit
            And the endpoint will return json value once updated.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/{warehouse_id}/products/update/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>PUT</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL and Body Form Data required: &nbsp;</b>
            <code>warehouse_id</code>,
            <code>id</code>,
            <code>product_id</code>,
            <code>carton_count</code>,
            <code>in_each_carton</code>,
            <code>total_unit</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Update Inventory</code> <small> </small><br>

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
    "product_id" : "{product_id}",
    "carton_count" : "{carton_count}",
    "in_each_carton" : "{in_each_carton}",
    "total_unit" : "{total_unit}"
}


                                                        </pre>

        <h4>Response</h4>
        <pre>

{
    "message": "Inventory has been updated.",
    "data": {
        "id": 1,
        "warehouse_id": 12,
        "product_id": "17",
        "customer_id": 447,
        "carton_count": "19",
        "in_each_carton": "1",
        "total_unit": "16",
        "deleted_at": null,
        "created_at": "2021-08-02T18:42:41.000000Z",
        "updated_at": "2022-08-09T11:42:07.000000Z",
        "sku": "",
        "classification_code": null,
        "duty_rate": null,
        "category_sku": "",
        "product_status": "deleted"
    }
}
                                </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div><!-- end Update Inventory -->





    <!--  Delete Inventory -->
    <div class="col-sm-12"  id="po-delete-inventory" >

        <h3>Delete Inventory</h3>
        <p>
            Destroy or delete resource for Inventory endpoint.
            To delete the Inventory we should have valid warehouse_id and id.
            And the endpoint will destroy or delete the data.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/{warehouse_id}/products/delete/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>DELETE</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b>
            <code>warehouse_id</code>,
            <code>id</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Delete Inventory</code> <small> </small><br>

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
    "message": "Inventory has been deleted."
}
        </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div><!-- end Delete Inventory -->





    <!--  Storable Unit Inventory -->
    <div class="col-sm-12"  id="po-storable-unit-inventory" >

        <h3>Get Storable Units</h3>
        <p>
            Get storable units for Inventory endpoint.
            To access their Inventory details, they have to provide warehouse_id.
            And the endpoint will return all their Storable Unit Inventory.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouses/{warehouse_id}/storable-units</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b>
            <code>warehouse_id</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Storable Unit Inventory</code> <small> </small><br>

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
    "active": {
        "current_page": 1,
        "data": [
            {
                "id": 2,
                "warehouse_id": 241,
                "customer_id": 447,
                "action": "recieve",
                "label": null,
                "type": "pallet",
                "dimensions": "{\"l\":\"15\",\"w\":\"12\",\"h\":\"25\",\"uom\":\"cm\"}",
                "weight": "{\"value\":\"25\",\"unit\":\"KG\"}",
                "products": null,
                "deleted_at": null,
                "created_at": "2022-03-01T01:31:48.000000Z",
                "updated_at": "2022-03-01T01:31:48.000000Z",
                "location_id": 4,
                "is_active": 1,
                "total_carton_count": 5,
                "total_units": 90,
                "location": {
                    "id": 4,
                    "type": "storage",
                    "row": "R01",
                    "rack": "C01",
                    "shelf": "F02",
                    "bin": null,
                    "gate_name": null,
                    "position": null,
                    "warehouse_id": 241,
                    "customer_id": 447,
                    "deleted_at": null,
                    "created_at": "2022-01-07T18:13:55.000000Z",
                    "updated_at": "2022-01-07T18:13:55.000000Z",
                    "ref_no": [
                        {
                            "ref_no": "SD9212969",
                            "inbound_id": 69
                        },
                        {
                            "ref_no": "SD9212969",
                            "inbound_id": 69
                        }
                    ]
                },
                "storable_unit_products": [
                    {
                        "id": 2,
                        "product_id": 395,
                        "carton_count": 5,
                        "total_unit": 90,
                        "created_at": "2022-03-01T01:31:48.000000Z",
                        "updated_at": "2022-03-01T01:31:48.000000Z",
                        "storable_unit_id": 2,
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
            }, {...}, ...,
        ],
        "first_page_url": "api/warehouses/241/storable-units?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "api/warehouses/241/storable-units?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "api/warehouses/241/storable-units?page=1",
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
        "path": "api/warehouses/241/storable-units",
        "per_page": 35,
        "prev_page_url": null,
        "to": 18,
        "total": 18
    },
    "history": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "warehouse_id": 241,
                "customer_id": 447,
                "action": "recieve",
                "label": null,
                "type": "pallet",
                "dimensions": "{\"l\":\"15\",\"w\":\"15\",\"h\":\"15\",\"uom\":\"cm\"}",
                "weight": "{\"value\":\"15\",\"unit\":\"KG\"}",
                "products": null,
                "deleted_at": null,
                "created_at": "2022-03-01T01:13:55.000000Z",
                "updated_at": "2022-03-30T18:38:21.000000Z",
                "location_id": 1,
                "is_active": 0,
                "total_carton_count": 5,
                "total_units": 175,
                "location": {
                    "id": 1,
                    "type": "storage",
                    "row": "AISLE01",
                    "rack": "COL01",
                    "shelf": "F01",
                    "bin": null,
                    "gate_name": null,
                    "position": null,
                    "warehouse_id": 241,
                    "customer_id": 447,
                    "deleted_at": null,
                    "created_at": "2022-01-05T23:17:10.000000Z",
                    "updated_at": "2022-01-28T22:13:52.000000Z",
                    "ref_no": []
                },
                "storable_unit_products": [
                    {
                        "id": 1,
                        "product_id": 450,
                        "carton_count": 5,
                        "total_unit": 175,
                        "created_at": "2022-03-01T01:13:55.000000Z",
                        "updated_at": "2022-03-01T01:13:55.000000Z",
                        "storable_unit_id": 1,
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
                ]
            }, {...}, ...
                ]
            }
        ],
        "first_page_url": "api/warehouses/241/storable-units?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "api/warehouses/241/storable-units?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "api/warehouses/241/storable-units?page=1",
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
        "path": "api/warehouses/241/storable-units",
        "per_page": 35,
        "prev_page_url": null,
        "to": 4,
        "total": 4
    }
}
        </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div><!-- end Storable Unit Inventory -->













    <!--  Add Storable Unit Inventory -->
    <div class="col-sm-12"  id="po-add-storable-unit-inventory" >

        <h3>Add Storable Unit</h3>
        <p>
            Add Storable Unit for Inventory endpoint.
            To Add Unit for inventory, they have to provide warehouse_id.
            And the endpoint will return the added storable unit.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/add-storable-units</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b>
            <code>warehouse_id</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Add Storable Unit</code> <small> </small><br>

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
                "id": 2,
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
                "total_carton_count": 5,
                "total_units": 90,
                "location": {
                    "id": 4,
                    "type": "storage",
                    "row": "R01",
                    "rack": "C01",
                    "shelf": "F02",
                    "bin": null,
                    "gate_name": null,
                    "position": null,
                    "warehouse_id": 241,
                    "customer_id": 447,
                    "deleted_at": null,
                    "created_at": "2022-01-07T18:13:55.000000Z",
                    "updated_at": "2022-01-07T18:13:55.000000Z",
                    "ref_no": [
                        {
                            "ref_no": "SD9212969",
                            "inbound_id": 69
                        },
                        {
                            "ref_no": "SD9212969",
                            "inbound_id": 69
                        }
                    ]
                },
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
            }
        ],
        "first_page_url": "api/warehouse/241/add-storable-units?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "api/warehouse/241/add-storable-units?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "api/warehouse/241/add-storable-units?page=1",
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
        "path": "api/warehouse/241/add-storable-units",
        "per_page": 35,
        "prev_page_url": null,
        "to": 18,
        "total": 18
    }
}
        </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div><!-- end Add Storable Unit Inventory -->



    <!--  Active Storable Unit Inventory -->
    <div class="col-sm-12"  id="po-active-storable-unit-inventory" >

        <h3>Active Storable Unit</h3>
        <p>
            Active Storable Unit for Inventory endpoint.
            To Active Unit for inventory, they have to provide warehouse_id.
            And the endpoint will return the active storable unit.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/storable-units/active</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b>
            <code>warehouse_id</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Active Storable Unit</code> <small> </small><br>

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
                "total_carton_count": 5,
                "total_units": 90,
                "location": {
                    "id": 4,
                    "type": "storage",
                    "row": "R01",
                    "rack": "C01",
                    "shelf": "F02",
                    "bin": null,
                    "gate_name": null,
                    "position": null,
                    "warehouse_id": 241,
                    "customer_id": 447,
                    "deleted_at": null,
                    "created_at": "2022-01-07T18:13:55.000000Z",
                    "updated_at": "2022-01-07T18:13:55.000000Z",
                    "ref_no": [
                        {
                            "ref_no": "SD9212969",
                            "inbound_id": 69
                        },
                        {
                            "ref_no": "SD9212969",
                            "inbound_id": 69
                        }
                    ]
                },
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
            }
        ],
        "first_page_url": "api/warehouse/241/storable-units/active?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "api/warehouse/241/storable-units/active?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "api/warehouse/241/storable-units/active?page=1",
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
        "path": "api/warehouse/241/storable-units/active",
        "per_page": 35,
        "prev_page_url": null,
        "to": 18,
        "total": 18
    }
}
        </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div><!-- end Active Storable Unit Inventory -->











    <!--  History Storable Unit Inventory -->
    <div class="col-sm-12"  id="po-history-storable-unit-inventory" >

        <h3>History Storable Unit</h3>
        <p>
            History Storable Unit for Inventory endpoint.
            To display history Unit for inventory, they have to provide warehouse_id.
            And the endpoint will return the history storable unit.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/storable-units/history</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b>
            <code>warehouse_id</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>History Storable Unit</code> <small> </small><br>

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
                "id": 12,
                "warehouse_id": 241,
                "customer_id": 447,
                "action": "build",
                "label": null,
                "type": "pallet",
                "dimensions": "{\"l\":\"10\",\"w\":\"10\",\"h\":\"10\",\"uom\":\"cm\"}",
                "weight": "{\"value\":\"12\",\"unit\":\"KG\"}",
                "products": null,
                "deleted_at": null,
                "created_at": "2022-03-23T22:11:05.000000Z",
                "updated_at": "2022-03-29T20:35:34.000000Z",
                "location_id": 16,
                "is_active": 0,
                "total_carton_count": 10,
                "total_units": 350,
                "location": {
                    "id": 16,
                    "type": "storage",
                    "row": "Test1",
                    "rack": "Test1",
                    "shelf": "Test1",
                    "bin": null,
                    "gate_name": null,
                    "position": null,
                    "warehouse_id": 241,
                    "customer_id": 447,
                    "deleted_at": null,
                    "created_at": "2022-03-09T20:50:47.000000Z",
                    "updated_at": "2022-03-09T20:50:47.000000Z",
                    "ref_no": [
                        {
                            "ref_no": "SD9212969",
                            "inbound_id": 69
                        }
                    ]
                },
                "storable_unit_products": [
                    {
                        "id": 14,
                        "product_id": 450,
                        "carton_count": 10,
                        "total_unit": 350,
                        "created_at": "2022-03-23T22:11:05.000000Z",
                        "updated_at": "2022-03-23T22:11:05.000000Z",
                        "storable_unit_id": 12,
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
                ]
            }, {...},...,
        ],
        "first_page_url": "api/warehouse/241/storable-units/history?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "api/warehouse/241/storable-units/history?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "api/warehouse/241/storable-units/history?page=1",
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
        "path": "api/warehouse/241/storable-units/history",
        "per_page": 35,
        "prev_page_url": null,
        "to": 4,
        "total": 4
    }
}
        </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div><!-- end History Storable Unit Inventory -->







    <!-- Inventory With Pagination -->
    <div class="col-sm-12"  id="po-inventory-with-pagination" >

        <h3>Inventory with Pagination</h3>
        <p>
            Inventory with Pagination for Inventory endpoint.
            To display with pagination for inventory, they have to provide warehouse_id.
            And the endpoint will return the pagination.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/warehouse/{warehouse_id}/products</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b>
            <code>warehouse_id</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Inventory with Pagination</code> <small> </small><br>

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
                "warehouse_id": 241,
                "product_id": 395,
                "customer_id": 447,
                "carton_count": 5,
                "in_each_carton": 18,
                "total_unit": 90,
                "deleted_at": null,
                "created_at": "2022-08-09T09:26:26.000000Z",
                "updated_at": "2022-08-09T09:26:26.000000Z",
                "sku": "513494",
                "classification_code": null,
                "duty_rate": null,
                "inbound": 224,
                "products_allocated": 232,
                "category_sku": "188-513494",
                "product_status": "active",
                "product": {
                    "id": 395,
                    "sku": "513494",
                    "name": "Testing Final",
                    "category_id": 188,
                    "description": "...",
                    "units_per_carton": 18,
                    "image": "storage/products/images/5yoRwwemUz35WPGBU6ucaDEOWkLSF8clBjHcrBce.png",
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
            }, {...}, ...,
        ],
        "first_page_url": "warehouse/241/products?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "api/warehouse/241/products?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "api/warehouse/241/products?page=1",
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
        "path": "api/warehouse/241/products",
        "per_page": 35,
        "prev_page_url": null,
        "to": 6,
        "total": 6
    }
}
        </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div><!-- end Inventory With Pagination -->






</div> <!-- end Inventory -->
