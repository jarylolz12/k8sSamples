<div class="col-sm-12" id="po-purchase-order">
    <h3 class="page-header">Purchase Order</h3>

    <!-- Download Multiple Purchase Order -->
    <div class="col-sm-12" id="po-download-multiple-purchase-order" >

        <h3>Download Multiple</h3>
        <p>
            Download Multiple for Purchase Order endpoint.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/purchaseorders/download-multiple</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Query Form Data required: &nbsp;</b> <code>id</code> <br>
            <b>Return Type: &nbsp;</b> <code>File</code> <br>
            <b>Will Return: &nbsp;</b> <code>Download Multiple Purchase Order</code> <small> </small><br>

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
Downloadable File
                                                        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Download Multiple Purchase Order -->









    <!-- Get All Purchase Order -->
    <div class="col-sm-12" id="po-get-all-purchase-order" >

        <h3>Get All Purchase Order</h3>
        <p>
            Get All Purchase Order for Purchase Order endpoint.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/purchaseorders</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>NONE</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get All Purchase Order</code> <small> </small><br>

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
        "pending": {
            "current_page": 1,
            "data": [
                {
                    "id": 142,
                    "po_number": "926260",
                    "supplier_id": 1908,
                    "customer_id": 25,
                    "notes": "This is a test create PO only",
                    "created_by": 11,
                    "created_at": "2021-12-20T22:07:32.000000Z",
                    "updated_at": "2022-01-07T20:43:39.000000Z",
                    "warehouse_id": 60,
                    "is_system_generated": 1,
                    "terms": null,
                    "due_by": null,
                    "ship_via": null,
                    "status": "pending",
                    "sub_total": 720,
                    "tax": 0,
                    "shipping": 0,
                    "discount": 0,
                    "total": 720,
                    "cargo_ready_date": null,
                    "packing_method": null,
                    "payment_term": null,
                    "pdf": "public/purchase-order/po_142_1641559419.pdf",
                    "ship_to": "Test Only, Door 123 Qwerty Appartment",
                    "total_products": 1,
                    "total_quantity": 10,
                    "total_units": 48,
                    "total_amount": 720,
                    "purchase_order_products": [
                        {
                            "id": 177,
                            "purchase_order_id": 142,
                            "product_id": 262,
                            "customer_id": 25,
                            "quantity": 10,
                            "unit_price": 15,
                            "amount": 720,
                            "deleted_at": null,
                            "created_at": "2021-12-20T22:07:32.000000Z",
                            "updated_at": "2021-12-20T22:07:32.000000Z",
                            "sku": null,
                            "classification_code": null,
                            "duty_rate": null,
                            "units_per_carton": 5,
                            "units": 48,
                            "unship_cartons": 10,
                            "product": {
                                "id": 262,
                                "sku": "727714",
                                "name": "Phone test",
                                "category_id": 122,
                                "description": "Testing this one",
                                "units_per_carton": 5,
                                "image": "http://po.shifl.test:82/storage/products/images/20QY2SxlboZka1bVW41A7CDayXgVzNYIbsiHORMQ.jpg",
                                "created_at": "2021-10-27T19:27:32.000000Z",
                                "updated_at": "2021-10-27T19:27:45.000000Z",
                                "customer_id": 25,
                                "created_by": 206,
                                "deleted_at": null,
                                "is_system_generated": 1,
                                "customer_admins": null,
                                "unit_price": 1500,
                                "classification_code": null,
                                "duty_rate": null,
                                "carton_dimensions": "{\"length\":0,\"width\":0,\"height\":0,\"uom\":\"cm\"}",
                                "is_for_classification_code": 0,
                                "upc_number": null,
                                "country_from": null,
                                "country_to": null,
                                "product_classification_description": null,
                                "unit_weight": null,
                                "unit_dimensions": null,
                                "carton_upc": null,
                                "additional_classification_code": null,
                                "category_sku": "122-727714",
                                "inbound_associated": false
                            }
                        }
                    ]
                }
            ],
            "first_page_url": "api/purchaseorders?page=1",
            "from": 1,
            "last_page": 1,
            "last_page_url": "api/purchaseorders?page=1",
            "links": [
                {
                    "url": null,
                    "label": "&laquo; Previous",
                    "active": false
                },
                {
                    "url": "api/purchaseorders?page=1",
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
            "path": "api/purchaseorders",
            "per_page": 35,
            "prev_page_url": null,
            "to": 1,
            "total": 1
        },
        "shipped": {
            "current_page": 1,
            "data": [],
            "first_page_url": "api/purchaseorders?page=1",
            "from": null,
            "last_page": 1,
            "last_page_url": "api/purchaseorders?page=1",
            "links": [
                {
                    "url": null,
                    "label": "&laquo; Previous",
                    "active": false
                },
                {
                    "url": "api/purchaseorders?page=1",
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
            "path": "api/purchaseorders",
            "per_page": 35,
            "prev_page_url": null,
            "to": null,
            "total": 0
        }
    }
}
                                                        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Get All Purchase Order -->









    <!-- Get All Purchase Order Product-->
    <div class="col-sm-12" id="po-get-all-purchase-order-product" >

        <h3>Get All Product</h3>
        <p>
            Get All Product for Purchase Order endpoint.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/purchaseorders/products</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>NONE</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get All Product for Purchase Order</code> <small> </small><br>

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
    "results": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
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
        "first_page_url": "api/purchaseorders/products?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "api/purchaseorders/products?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "api/purchaseorders/products?page=1",
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
        "path": "api/purchaseorders/products",
        "per_page": 500,
        "prev_page_url": null,
        "to": 76,
        "total": 76
    }
}
        </pre>

        <h4>Response</h4>
        <pre>

        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Get All Purchase Order -->













    <!-- Get Purchase Order-->
    <div class="col-sm-12" id="po-get-purchase-order" >

        <h3>Get Purchase Order</h3>
        <p>
            Get Purchase Order for Purchase Order endpoint.
            To access their specific Purchase Order details, they have to provide the id.
            And the endpoint will return their purchase order regarding the given id.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/purchaseorders/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Purchase Order</code> <small> </small><br>

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
    "id": "{id}"
}
        </pre>

        <h4>Response</h4>
        <pre>
{
    "id": 123,
    "po_number": "678419",
    "supplier_id": 1363,
    "customer_id": 25,
    "notes": null,
    "created_by": 124,
    "created_at": "2021-11-11T03:56:41.000000Z",
    "updated_at": "2021-11-11T04:00:19.000000Z",
    "warehouse_id": 236,
    "is_system_generated": 1,
    "terms": null,
    "due_by": null,
    "ship_via": null,
    "status": null,
    "sub_total": 34.4,
    "tax": 0,
    "shipping": 0,
    "discount": 0,
    "total": 34.4,
    "cargo_ready_date": null,
    "packing_method": null,
    "payment_term": null,
    "pdf": null,
    "ship_to": "Walden, 39 edmunds ln walden ny 12586",
    "total_products": 1,
    "total_quantity": 20,
    "total_amount": 34.4,
    "shipments": [],
    "purchase_order_products": [
        {
            "id": 159,
            "purchase_order_id": 123,
            "product_id": 348,
            "customer_id": 257,
            "quantity": 20,
            "unit_price": 1.72,
            "amount": 34.4,
            "deleted_at": null,
            "created_at": "2021-11-11T03:56:41.000000Z",
            "updated_at": "2021-11-11T03:56:41.000000Z",
            "sku": null,
            "classification_code": null,
            "duty_rate": 0,
            "units_per_carton": null,
            "units": null,
            "unship_cartons": 20,
            "product": {
                "id": 348,
                "sku": "ABT05BK",
                "name": "microphone cable",
                "category_id": null,
                "description": null,
                "units_per_carton": 125,
                "image": null,
                "created_at": "2021-11-11T03:53:36.000000Z",
                "updated_at": "2021-11-11T05:58:43.000000Z",
                "customer_id": 257,
                "created_by": 124,
                "deleted_at": "2021-11-11T05:58:43.000000Z",
                "is_system_generated": 0,
                "customer_admins": null,
                "unit_price": 1.72,
                "classification_code": null,
                "duty_rate": 0,
                "carton_dimensions": "{\"l\":0,\"w\":0,\"h\":0,\"uom\":\"cm\"}",
                "is_for_classification_code": 1,
                "upc_number": null,
                "country_from": null,
                "country_to": null,
                "product_classification_description": null,
                "unit_weight": null,
                "unit_dimensions": null,
                "carton_upc": null,
                "additional_classification_code": null,
                "category_sku": "ABT05BK",
                "inbound_associated": false
            }
        }
    ]
}
        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Get Purchase Order -->























    <!-- Get List Supplier Email Purchase Order-->
    <div class="col-sm-12" id="po-get-list-supplier-email-purchase-order" >

        <h3>Get List Supplier Email</h3>
        <p>
            Get List Supplier Email for Purchase Order endpoint.
            To access their Supplier Email Purchase Order details, they have to provide the id and purchase_order_id.
            And the endpoint will return their purchase order regarding the given id and purchase_order_id.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/purchaseorders/supplier-emails/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>id</code>, <code>purchase_order_id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Purchase Order</code> <small> </small><br>

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
                "id": "{id}",
                "purchase_order_id": "{purchase_order_id}"
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
    <!-- End Get List Supplier Email Purchase Order-->















    <!-- Send Email Purchase Order -->
    <div class="col-sm-12" id="po-send-email-purchase-order" >

        <h3>Send Email</h3>
        <p>
            Send Email for Purchase Order endpoint.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/purchaseorders/send-email/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>id</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Send Email</code> <small> </small><br>

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
                "id": "{id}"
            }
        </pre>

        <h4>Response</h4>
        <pre>
{
    "meassage": "Mail Sent Successfully"
}
        </pre>


        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>


    </div>
    <!-- End Send Email Purchase Order -->











    <!-- Create Purchase Order -->
    <div class="col-sm-12" id="po-create-purchase-order" >

        <h3>Create Purchase Order</h3>
        <p>
            Create Product for Product endpoint.
            To create the Product we have to input the ff. data:
            sys_gen, po_number, supplier_id, notes, warehouse_id, cargo_ready_date, packing_method, ship_via, terms, payment_term, sub_total, tax, ship_to, shipping, discount, total, products.*.id, products.*.quantity, products.*.units, products.*.unit_price and products.*.amount.
            And the endpoint will return json value once created.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/purchaseorders/create</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>sys_gen</code>,
            <code>po_number</code>,
            <code>supplier_id</code>,
            <code>notes</code>,
            <code>warehouse_id</code>,
            <code>cargo_ready_date</code>,
            <code>packing_method</code>,
            <code>ship_via</code>,
            <code>terms</code>,
            <code>payment_term</code>,
            <code>sub_total</code>,
            <code>ship_to</code>,
            <code>shipping</code>,
            <code>discount</code>,
            <code>total</code>,
            <code>products.*.id</code>,
            <code>products.*.quantity</code>,
            <code>products.*.units</code>,
            <code>products.*.unit_price</code>,
            <code>products.*.amount</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Create Purchase Order</code> <small> </small><br>

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
                "sys_gen": "{sys_gen}",
                "po_number": "{po_number}",
                "supplier_id": "{supplier_id}",
                "notes": "{notes}",
                "warehouse_id": "{warehouse_id}",
                "cargo_ready_date": "{cargo_ready_date}",
                "packing_method": "{packing_method}",
                "ship_via": "{ship_via}",
                "terms": "{terms}",
                "payment_term": "{payment_term}",
                "sub_total": "{sub_total}",
                "ship_to": "{ship_to}",
                "shipping": "{shipping}",
                "total": "{total}",
                "products.*.id": "{products.*.id}",
                "products.*.quantity": "{products.*.quantity}",
                "products.*.units": "{products.*.units}",
                "products.*.unit_price": "{products.*.unit_price}",
                "products.*.amount": "{products.*.amount}"
            }
        </pre>

        <h4>Response</h4>
        <pre>
{
    "message": "Purchase Order has been created.",
    "data": {
        "po_number": "352929",
        "supplier_id": "1",
        "warehouse_id": "64",
        "notes": "voluptatem",
        "created_by": 3,
        "customer_id": 25,
        "is_system_generated": 1,
        "ship_via": "unde",
        "packing_method": "omnis",
        "terms": "non",
        "payment_term": "ad",
        "cargo_ready_date": "2022-04-26",
        "sub_total": "369",
        "tax": "0",
        "ship_to": "amet",
        "shipping": "0",
        "discount": "0",
        "total": "450",
        "status": "pending",
        "updated_at": "2022-08-11T07:44:24.000000Z",
        "created_at": "2022-08-11T07:44:24.000000Z",
        "id": 296
    },
    "product_details": [
        {
            "product_id": "1",
            "purchase_order_id": 296,
            "customer_id": 25,
            "quantity": "1",
            "units": "29",
            "unit_price": "29",
            "amount": "29",
            "classification_code": null,
            "duty_rate": null,
            "units_per_carton": 12,
            "updated_at": "2022-08-11T07:44:24.000000Z",
            "created_at": "2022-08-11T07:44:24.000000Z",
            "id": 355,
            "unship_cartons": 1,
            "product": {
                "id": 1,
                "sku": "10001",
                "name": "Test",
                "category_id": 8,
                "description": "Test",
                "units_per_carton": 12,
                "image": null,
                "created_at": "2021-06-29T18:27:58.000000Z",
                "updated_at": "2021-07-01T21:59:51.000000Z",
                "customer_id": 42,
                "created_by": 42,
                "deleted_at": "2021-07-01T21:59:51.000000Z",
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
                "category_sku": "8-10001",
                "inbound_associated": false
            }
        }
    ]
}
        </pre>


        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>


    </div>
    <!-- End Create Purchase Order -->











    <!-- Update Purchase Order -->
    <div class="col-sm-12" id="po-update-purchase-order" >

        <h3>Update Purchase Order</h3>
        <p>
            Update Product for Product endpoint.
            To update the Product we have to input the ff. data:
            id, sys_gen, po_number, supplier_id, notes, warehouse_id, cargo_ready_date, packing_method, ship_via, terms, payment_term, sub_total, tax, ship_to, shipping, discount, total, products.*.id, products.*.quantity, products.*.units, products.*.unit_price and products.*.amount.
            And the endpoint will return json value once updated.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/purchaseorders/update/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>PUT</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL and Body Form Data required: &nbsp;</b> <code>id</code>, <code>sys_gen</code>,
            <code>po_number</code>,
            <code>supplier_id</code>,
            <code>notes</code>,
            <code>warehouse_id</code>,
            <code>cargo_ready_date</code>,
            <code>packing_method</code>,
            <code>ship_via</code>,
            <code>terms</code>,
            <code>payment_term</code>,
            <code>sub_total</code>,
            <code>ship_to</code>,
            <code>shipping</code>,
            <code>discount</code>,
            <code>total</code>,
            <code>products.*.id</code>,
            <code>products.*.quantity</code>,
            <code>products.*.units</code>,
            <code>products.*.unit_price</code>,
            <code>products.*.amount</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Update Purchase Order</code> <small> </small><br>

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
                "id": "{id}",
                "sys_gen": "{sys_gen}",
                "po_number": "{po_number}",
                "supplier_id": "{supplier_id}",
                "notes": "{notes}",
                "warehouse_id": "{warehouse_id}",
                "cargo_ready_date": "{cargo_ready_date}",
                "packing_method": "{packing_method}",
                "ship_via": "{ship_via}",
                "terms": "{terms}",
                "payment_term": "{payment_term}",
                "sub_total": "{sub_total}",
                "ship_to": "{ship_to}",
                "shipping": "{shipping}",
                "total": "{total}",
                "products.*.id": "{products.*.id}",
                "products.*.quantity": "{products.*.quantity}",
                "products.*.units": "{products.*.units}",
                "products.*.unit_price": "{products.*.unit_price}",
                "products.*.amount": "{products.*.amount}"
            }
        </pre>

        <h4>Response</h4>
        <pre>
{
    "message": "Purchase Order has been updated.",
    "data": {
        "id": 296,
        "po_number": "483324",
        "supplier_id": "1",
        "customer_id": 25,
        "notes": "asperiores",
        "created_by": 3,
        "created_at": "2022-08-11T07:44:24.000000Z",
        "updated_at": "2022-08-11T08:14:39.000000Z",
        "warehouse_id": "64",
        "is_system_generated": 0,
        "terms": "reiciendis",
        "due_by": null,
        "ship_via": "consectetur",
        "status": "pending",
        "sub_total": "369",
        "tax": "0",
        "shipping": "0",
        "discount": "0",
        "total": "450",
        "cargo_ready_date": "2022-04-26",
        "packing_method": "est",
        "payment_term": "quasi",
        "pdf": null,
        "ship_to": "sunt",
        "purchase_order_products": [
            {
                "id": 355,
                "purchase_order_id": 296,
                "product_id": 1,
                "customer_id": 25,
                "quantity": 1,
                "unit_price": 29,
                "amount": 29,
                "deleted_at": null,
                "created_at": "2022-08-11T07:44:24.000000Z",
                "updated_at": "2022-08-11T07:44:24.000000Z",
                "sku": null,
                "classification_code": null,
                "duty_rate": null,
                "units_per_carton": 12,
                "units": 29,
                "unship_cartons": 1,
                "product": {
                    "id": 1,
                    "sku": "10001",
                    "name": "Test",
                    "category_id": 8,
                    "description": "Test",
                    "units_per_carton": 12,
                    "image": null,
                    "created_at": "2021-06-29T18:27:58.000000Z",
                    "updated_at": "2021-07-01T21:59:51.000000Z",
                    "customer_id": 42,
                    "created_by": 42,
                    "deleted_at": "2021-07-01T21:59:51.000000Z",
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
                    "category_sku": "8-10001",
                    "inbound_associated": false
                }
            }
        ]
    }
}
        </pre>


        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>


    </div>
    <!-- End Update Purchase Order -->

    <!-- Delete Purchase Order -->
    <div class="col-sm-12" id="po-delete-purchase-order" >

        <h3>Delete Purchase Order</h3>
        <p>
            Delete Purchase Order for Purchase Order endpoint. To delete the Purchase Order we should have valid id.
            And the endpoint will deleted the data.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/purchaseorders/delete/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>DELETE</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>id</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Delete Purchase Order</code> <small> </small><br>

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
    "id": "{id}"
}
        </pre>

        <h4>Response</h4>
        <pre>
{
    "message": "Purchase Order has been deleted."
}
        </pre>


        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>


    </div>
    <!-- End Delete Purchase Order -->



    <!-- Download Purchase Order -->
    <div class="col-sm-12" id="po-download-purchase-order" >

        <h3>Download Purchase Order</h3>
        <p>
            Download Purchase Order for Purchase Order endpoint.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/purchaseorders/{purchase_order_id}/download</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>purchase_order_id</code><br>
            <b>Return Type: &nbsp;</b> <code>File</code> <br>
            <b>Will Return: &nbsp;</b> <code>Download Purchase Order</code> <small> </small><br>

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
    "purchase_order_id": "{purchase_order_id}"
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


    </div>
    <!-- End Download Purchase Order -->







    <!-- Delete Multiple Purchase Order -->
    <div class="col-sm-12" id="po-delete-multiple-purchase-order" >

        <h3>Delete Multiple Purchase Order</h3>
        <p>
            Delete Multiple Purchase Order for Purchase Order endpoint.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/purchaseorders/delete-multiple</code> <br>
            <b>Request Type: &nbsp;</b> <code>DELETE</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>id</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Delete Multiple Purchase Order</code> <small> </small><br>

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
    "id": "{id}"
}
        </pre>

        <h4>Response</h4>
        <pre>
{
    "message": "Purchase Orders has been deleted."
}
        </pre>


        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>


    </div>
    <!-- End Delete Multiple Purchase Order -->













    <!-- Get List Pending Purchase Order-->
    <div class="col-sm-12" id="po-get-list-pending-purchase-order" >

        <h3>Get List Pending</h3>
        <p>
            Get List Pending for Purchase Order endpoint.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/purchaseorder/pending</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>NONE</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get List Pending for Purchase Order endpoint</code> <small> </small><br>

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
        "current_page": 1,
        "data": [
            {
                "id": 142,
                "po_number": "926260",
                "supplier_id": 1908,
                "customer_id": 25,
                "notes": "This is a test create PO only",
                "created_by": 11,
                "created_at": "2021-12-20T22:07:32.000000Z",
                "updated_at": "2022-01-07T20:43:39.000000Z",
                "warehouse_id": 60,
                "is_system_generated": 1,
                "terms": null,
                "due_by": null,
                "ship_via": null,
                "status": "pending",
                "sub_total": 720,
                "tax": 0,
                "shipping": 0,
                "discount": 0,
                "total": 720,
                "cargo_ready_date": null,
                "packing_method": null,
                "payment_term": null,
                "pdf": "public/purchase-order/po_142_1641559419.pdf",
                "ship_to": "Test Only, Door 123 Qwerty Appartment",
                "total_products": 1,
                "total_quantity": 10,
                "total_units": 48,
                "total_amount": 720,
                "purchase_order_products": [
                    {
                        "id": 177,
                        "purchase_order_id": 142,
                        "product_id": 262,
                        "customer_id": 25,
                        "quantity": 10,
                        "unit_price": 15,
                        "amount": 720,
                        "deleted_at": null,
                        "created_at": "2021-12-20T22:07:32.000000Z",
                        "updated_at": "2021-12-20T22:07:32.000000Z",
                        "sku": null,
                        "classification_code": null,
                        "duty_rate": null,
                        "units_per_carton": 5,
                        "units": 48,
                        "unship_cartons": 10,
                        "product": {
                            "id": 262,
                            "sku": "727714",
                            "name": "Phone test",
                            "category_id": 122,
                            "description": "Testing this one",
                            "units_per_carton": 5,
                            "image": "http://po.shifl.test:82/storage/products/images/20QY2SxlboZka1bVW41A7CDayXgVzNYIbsiHORMQ.jpg",
                            "created_at": "2021-10-27T19:27:32.000000Z",
                            "updated_at": "2021-10-27T19:27:45.000000Z",
                            "customer_id": 25,
                            "created_by": 206,
                            "deleted_at": null,
                            "is_system_generated": 1,
                            "customer_admins": null,
                            "unit_price": 1500,
                            "classification_code": null,
                            "duty_rate": null,
                            "carton_dimensions": "{\"length\":0,\"width\":0,\"height\":0,\"uom\":\"cm\"}",
                            "is_for_classification_code": 0,
                            "upc_number": null,
                            "country_from": null,
                            "country_to": null,
                            "product_classification_description": null,
                            "unit_weight": null,
                            "unit_dimensions": null,
                            "carton_upc": null,
                            "additional_classification_code": null,
                            "category_sku": "122-727714",
                            "inbound_associated": false
                        }
                    }
                ]
            }
        ],
        "first_page_url": "api/purchaseorder/pending?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "api/purchaseorder/pending?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "api/purchaseorder/pending?page=1",
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
        "path": "api/purchaseorder/pending",
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
    <!-- End Get List Pending Purchase Order-->













    <!-- Get List Shipped Purchase Order-->
    <div class="col-sm-12" id="po-get-list-shipped-purchase-order" >

        <h3>Get List Shipped</h3>
        <p>
            Get List Shipped for Purchase Order endpoint.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/purchaseorder/shipped</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>NONE</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get List Shipped for Purchase Order endpoint</code> <small> </small><br>

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
        "current_page": 1,
        "data": [
            {
                "id": 142,
                "po_number": "926260",
                "supplier_id": 1908,
                "customer_id": 25,
                "notes": "This is a test create PO only",
                "created_by": 11,
                "created_at": "2021-12-20T22:07:32.000000Z",
                "updated_at": "2022-01-07T20:43:39.000000Z",
                "warehouse_id": 60,
                "is_system_generated": 1,
                "terms": null,
                "due_by": null,
                "ship_via": null,
                "status": "shipped",
                "sub_total": 720,
                "tax": 0,
                "shipping": 0,
                "discount": 0,
                "total": 720,
                "cargo_ready_date": null,
                "packing_method": null,
                "payment_term": null,
                "pdf": "public/purchase-order/po_142_1641559419.pdf",
                "ship_to": "Test Only, Door 123 Qwerty Appartment",
                "total_products": 1,
                "total_quantity": 10,
                "total_units": 48,
                "total_amount": 720,
                "purchase_order_products": [
                    {
                        "id": 177,
                        "purchase_order_id": 142,
                        "product_id": 262,
                        "customer_id": 25,
                        "quantity": 10,
                        "unit_price": 15,
                        "amount": 720,
                        "deleted_at": null,
                        "created_at": "2021-12-20T22:07:32.000000Z",
                        "updated_at": "2021-12-20T22:07:32.000000Z",
                        "sku": null,
                        "classification_code": null,
                        "duty_rate": null,
                        "units_per_carton": 5,
                        "units": 48,
                        "unship_cartons": 10,
                        "product": {
                            "id": 262,
                            "sku": "727714",
                            "name": "Phone test",
                            "category_id": 122,
                            "description": "Testing this one",
                            "units_per_carton": 5,
                            "image": "http://po.shifl.test:82/storage/products/images/20QY2SxlboZka1bVW41A7CDayXgVzNYIbsiHORMQ.jpg",
                            "created_at": "2021-10-27T19:27:32.000000Z",
                            "updated_at": "2021-10-27T19:27:45.000000Z",
                            "customer_id": 25,
                            "created_by": 206,
                            "deleted_at": null,
                            "is_system_generated": 1,
                            "customer_admins": null,
                            "unit_price": 1500,
                            "classification_code": null,
                            "duty_rate": null,
                            "carton_dimensions": "{\"length\":0,\"width\":0,\"height\":0,\"uom\":\"cm\"}",
                            "is_for_classification_code": 0,
                            "upc_number": null,
                            "country_from": null,
                            "country_to": null,
                            "product_classification_description": null,
                            "unit_weight": null,
                            "unit_dimensions": null,
                            "carton_upc": null,
                            "additional_classification_code": null,
                            "category_sku": "122-727714",
                            "inbound_associated": false
                        }
                    }
                ]
            }
        ],
        "first_page_url": "api/purchaseorder/shipped?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "api/purchaseorder/shipped?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "api/purchaseorder/shipped?page=1",
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
        "path": "api/purchaseorder/shipped",
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
    <!-- End Get List Shipped Purchase Order-->


















    <!-- Get List Pending Version 3 Purchase Order-->
    <div class="col-sm-12" id="po-get-list-pending-purchase-order-v3" >

        <h3>Get List of Pending Version 3</h3>
        <p>
            Get List of Pending Version 3 for Purchase Order endpoint.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/v3/purchaseorder/pending</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>NONE</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get List of Pending Version 3  for Purchase Order endpoint</code> <small> </small><br>

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
    "data": [
        {
            "id": 142,
            "po_number": "926260",
            "supplier_id": 1908,
            "customer_id": 25,
            "notes": "This is a test create PO only",
            "created_by": 11,
            "created_at": "2021-12-20T22:07:32.000000Z",
            "updated_at": "2022-01-07T20:43:39.000000Z",
            "warehouse_id": 60,
            "is_system_generated": 1,
            "terms": null,
            "due_by": null,
            "ship_via": null,
            "status": "pending",
            "sub_total": 720,
            "tax": 0,
            "shipping": 0,
            "discount": 0,
            "total": 720,
            "cargo_ready_date": null,
            "packing_method": null,
            "payment_term": null,
            "pdf": "public/purchase-order/po_142_1641559419.pdf",
            "ship_to": "Test Only, Door 123 Qwerty Appartment",
            "total_products": 1,
            "total_quantity": 10,
            "total_units": 48,
            "total_amount": 720,
            "supplier_name": "Test Dami Supplier",
            "purchase_order_products": [
                {
                    "id": 177,
                    "purchase_order_id": 142,
                    "product_id": 262,
                    "customer_id": 25,
                    "quantity": 10,
                    "unit_price": 15,
                    "amount": 720,
                    "deleted_at": null,
                    "created_at": "2021-12-20T22:07:32.000000Z",
                    "updated_at": "2021-12-20T22:07:32.000000Z",
                    "sku": null,
                    "classification_code": null,
                    "duty_rate": null,
                    "units_per_carton": 5,
                    "units": 48,
                    "unship_cartons": 10,
                    "product": {
                        "id": 262,
                        "sku": "727714",
                        "name": "Phone test",
                        "category_id": 122,
                        "description": "Testing this one",
                        "units_per_carton": 5,
                        "image": "http://po.shifl.test:82/storage/products/images/20QY2SxlboZka1bVW41A7CDayXgVzNYIbsiHORMQ.jpg",
                        "created_at": "2021-10-27T19:27:32.000000Z",
                        "updated_at": "2021-10-27T19:27:45.000000Z",
                        "customer_id": 25,
                        "created_by": 206,
                        "deleted_at": null,
                        "is_system_generated": 1,
                        "customer_admins": null,
                        "unit_price": 1500,
                        "classification_code": null,
                        "duty_rate": null,
                        "carton_dimensions": "{\"length\":0,\"width\":0,\"height\":0,\"uom\":\"cm\"}",
                        "is_for_classification_code": 0,
                        "upc_number": null,
                        "country_from": null,
                        "country_to": null,
                        "product_classification_description": null,
                        "unit_weight": null,
                        "unit_dimensions": null,
                        "carton_upc": null,
                        "additional_classification_code": null,
                        "category_sku": "122-727714",
                        "inbound_associated": false
                    }
                }
            ]
        }
    ],
    "links": {
        "first": "api/v3/purchaseorder/shipped?page=1",
        "last": "api/v3/purchaseorder/shipped?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "api/v3/purchaseorder/shipped?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "path": "api/v3/purchaseorder/shipped",
        "per_page": 35,
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
    <!-- End Get List Pending Version 3 Purchase Order-->








    <!-- Get List Shipped Version 3 Purchase Order-->
    <div class="col-sm-12" id="po-get-list-shipped-purchase-order-v3" >

        <h3>Get List of Shipped Version 3</h3>
        <p>
            Get List of Shipped Version 3 for Purchase Order endpoint.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/v3/purchaseorder/shipped</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>NONE</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get List of Shipped Version 3  for Purchase Order endpoint</code> <small> </small><br>

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
    "data": [
        {
            "id": 142,
            "po_number": "926260",
            "supplier_id": 1908,
            "customer_id": 25,
            "notes": "This is a test create PO only",
            "created_by": 11,
            "created_at": "2021-12-20T22:07:32.000000Z",
            "updated_at": "2022-01-07T20:43:39.000000Z",
            "warehouse_id": 60,
            "is_system_generated": 1,
            "terms": null,
            "due_by": null,
            "ship_via": null,
            "status": "shipped",
            "sub_total": 720,
            "tax": 0,
            "shipping": 0,
            "discount": 0,
            "total": 720,
            "cargo_ready_date": null,
            "packing_method": null,
            "payment_term": null,
            "pdf": "public/purchase-order/po_142_1641559419.pdf",
            "ship_to": "Test Only, Door 123 Qwerty Appartment",
            "total_products": 1,
            "total_quantity": 10,
            "total_units": 48,
            "total_amount": 720,
            "supplier_name": "Test Dami Supplier",
            "purchase_order_products": [
                {
                    "id": 177,
                    "purchase_order_id": 142,
                    "product_id": 262,
                    "customer_id": 25,
                    "quantity": 10,
                    "unit_price": 15,
                    "amount": 720,
                    "deleted_at": null,
                    "created_at": "2021-12-20T22:07:32.000000Z",
                    "updated_at": "2021-12-20T22:07:32.000000Z",
                    "sku": null,
                    "classification_code": null,
                    "duty_rate": null,
                    "units_per_carton": 5,
                    "units": 48,
                    "unship_cartons": 10,
                    "product": {
                        "id": 262,
                        "sku": "727714",
                        "name": "Phone test",
                        "category_id": 122,
                        "description": "Testing this one",
                        "units_per_carton": 5,
                        "image": "http://po.shifl.test:82/storage/products/images/20QY2SxlboZka1bVW41A7CDayXgVzNYIbsiHORMQ.jpg",
                        "created_at": "2021-10-27T19:27:32.000000Z",
                        "updated_at": "2021-10-27T19:27:45.000000Z",
                        "customer_id": 25,
                        "created_by": 206,
                        "deleted_at": null,
                        "is_system_generated": 1,
                        "customer_admins": null,
                        "unit_price": 1500,
                        "classification_code": null,
                        "duty_rate": null,
                        "carton_dimensions": "{\"length\":0,\"width\":0,\"height\":0,\"uom\":\"cm\"}",
                        "is_for_classification_code": 0,
                        "upc_number": null,
                        "country_from": null,
                        "country_to": null,
                        "product_classification_description": null,
                        "unit_weight": null,
                        "unit_dimensions": null,
                        "carton_upc": null,
                        "additional_classification_code": null,
                        "category_sku": "122-727714",
                        "inbound_associated": false
                    }
                }
            ]
        }
    ],
    "links": {
        "first": "api/v3/purchaseorder/shipped?page=1",
        "last": "api/v3/purchaseorder/shipped?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "api/v3/purchaseorder/shipped?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "path": "api/v3/purchaseorder/shipped",
        "per_page": 35,
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
    <!-- End Get List Shipped Version 3 Purchase Order-->















    <!-- Get All Purchase Order Version 3-->
    <div class="col-sm-12" id="po-get-all-purchase-order-v3" >

        <h3>Get All Purchase Order Version 3</h3>
        <p>
            Get All Purchase Order Version 3 for Purchase Order endpoint.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/v3/purchaseorder/all</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>NONE</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get List of Shipped Version 3  for Purchase Order endpoint</code> <small> </small><br>

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
    "data": [
        {
            "id": 142,
            "po_number": "926260",
            "supplier_id": 1908,
            "customer_id": 25,
            "notes": "This is a test create PO only",
            "created_by": 11,
            "created_at": "2021-12-20T22:07:32.000000Z",
            "updated_at": "2022-01-07T20:43:39.000000Z",
            "warehouse_id": 60,
            "is_system_generated": 1,
            "terms": null,
            "due_by": null,
            "ship_via": null,
            "status": "shipped",
            "sub_total": 720,
            "tax": 0,
            "shipping": 0,
            "discount": 0,
            "total": 720,
            "cargo_ready_date": null,
            "packing_method": null,
            "payment_term": null,
            "pdf": "public/purchase-order/po_142_1641559419.pdf",
            "ship_to": "Test Only, Door 123 Qwerty Appartment",
            "total_products": 1,
            "total_quantity": 10,
            "total_units": 48,
            "total_amount": 720,
            "supplier_name": "Test Dami Supplier",
            "purchase_order_products": [
                {
                    "id": 177,
                    "purchase_order_id": 142,
                    "product_id": 262,
                    "customer_id": 25,
                    "quantity": 10,
                    "unit_price": 15,
                    "amount": 720,
                    "deleted_at": null,
                    "created_at": "2021-12-20T22:07:32.000000Z",
                    "updated_at": "2021-12-20T22:07:32.000000Z",
                    "sku": null,
                    "classification_code": null,
                    "duty_rate": null,
                    "units_per_carton": 5,
                    "units": 48,
                    "unship_cartons": 10,
                    "product": {
                        "id": 262,
                        "sku": "727714",
                        "name": "Phone test",
                        "category_id": 122,
                        "description": "Testing this one",
                        "units_per_carton": 5,
                        "image": "http://po.shifl.test:82/storage/products/images/20QY2SxlboZka1bVW41A7CDayXgVzNYIbsiHORMQ.jpg",
                        "created_at": "2021-10-27T19:27:32.000000Z",
                        "updated_at": "2021-10-27T19:27:45.000000Z",
                        "customer_id": 25,
                        "created_by": 206,
                        "deleted_at": null,
                        "is_system_generated": 1,
                        "customer_admins": null,
                        "unit_price": 1500,
                        "classification_code": null,
                        "duty_rate": null,
                        "carton_dimensions": "{\"length\":0,\"width\":0,\"height\":0,\"uom\":\"cm\"}",
                        "is_for_classification_code": 0,
                        "upc_number": null,
                        "country_from": null,
                        "country_to": null,
                        "product_classification_description": null,
                        "unit_weight": null,
                        "unit_dimensions": null,
                        "carton_upc": null,
                        "additional_classification_code": null,
                        "category_sku": "122-727714",
                        "inbound_associated": false
                    }
                }
            ]
        }
    ],
    "links": {
        "first": "api/v3/purchaseorder/shipped?page=1",
        "last": "api/v3/purchaseorder/shipped?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "api/v3/purchaseorder/shipped?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "path": "api/v3/purchaseorder/shipped",
        "per_page": 35,
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
    <!-- End Get All Purchase Order Version 3 -->
























    <!-- Search Purchase Order-->
    <div class="col-sm-12" id="po-search-purchase-order" >

        <h3>Search Purchase Order</h3>
        <p>
            Search Purchase Order for Purchase Order endpoint.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/v3/purchaseorder/search</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Query Form Data required: &nbsp;</b> <code>s</code>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Search Purchase Order  for Purchase Order endpoint</code> <small> </small><br>

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
    "s": "{s}"
}
        </pre>

        <h4>Response</h4>
        <pre>
{
    "current_page": 1,
    "data": [
        {
            "id": 94,
            "po_number": "703490",
            "supplier_id": 1909,
            "customer_id": 25,
            "notes": "TEST THIS",
            "created_by": 206,
            "created_at": "2021-10-18T21:57:44.000000Z",
            "updated_at": "2021-10-18T21:57:44.000000Z",
            "warehouse_id": 121,
            "is_system_generated": 1,
            "terms": null,
            "due_by": null,
            "ship_via": null,
            "status": null,
            "sub_total": 900,
            "tax": 0,
            "shipping": 0,
            "discount": 0,
            "total": 900,
            "cargo_ready_date": null,
            "packing_method": null,
            "payment_term": null,
            "pdf": null,
            "ship_to": "Dami Test Warehouse, 3681 Burnside Avenue\nPrice",
            "total_products": 0,
            "total_quantity": 0,
            "total_units": 0,
            "total_amount": 0,
            "purchase_order_products": [
                {
                    "id": 125,
                    "purchase_order_id": 94,
                    "product_id": 214,
                    "customer_id": 25,
                    "quantity": 15,
                    "unit_price": 10,
                    "amount": 150,
                    "deleted_at": null,
                    "created_at": "2021-10-18T21:57:44.000000Z",
                    "updated_at": "2021-10-18T21:57:44.000000Z",
                    "sku": null,
                    "classification_code": null,
                    "duty_rate": null,
                    "units_per_carton": null,
                    "units": null,
                    "unship_cartons": 15,
                    "product": {
                        "id": 214,
                        "sku": "732397",
                        "name": "TESTING PRODUCT",
                        "category_id": 114,
                        "description": "SAMPLESSS",
                        "units_per_carton": 20,
                        "image": "products/images/t9IP63q5OmNDAjRXA7xh0zOXXBFqz7xsbT6FQ4bk.jpg",
                        "created_at": "2021-10-18T21:55:37.000000Z",
                        "updated_at": "2021-10-18T21:58:56.000000Z",
                        "customer_id": 25,
                        "created_by": 206,
                        "deleted_at": "2021-10-18T21:58:56.000000Z",
                        "is_system_generated": 1,
                        "customer_admins": null,
                        "unit_price": 10,
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
                        "category_sku": "114-732397",
                        "inbound_associated": false
                    }
                },{...},...
            ]
        },{...},...
    ],
    "first_page_url": "api/v3/purchaseorder/search?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "api/v3/purchaseorder/search?page=1",
    "links": [
        {
            "url": null,
            "label": "&laquo; Previous",
            "active": false
        },
        {
            "url": "api/v3/purchaseorder/search?page=1",
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
    "path": "api/v3/purchaseorder/search",
    "per_page": 35,
    "prev_page_url": null,
    "to": 18,
    "total": 18
}
        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Search Purchase Order -->




</div> <!-- end Purchase Order -->
