<div class="col-sm-12" id="po-purchase-order-main">
    <h3 class="page-header">Purchase Order Main</h3>

    <!-- Update Product Purchase Order Main -->
    <div class="col-sm-12" id="po-update-product-purchase-order-main" >

        <h3>Update Purchase</h3>
        <p>
            Update Purchase for Purchase Order Main endpoint.
            To update the Product Order Main we have to input the ff. data: ...
            And the endpoint will return json value once updated.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/po/purchase-orders/products</code> <br>
            <b>Request Type: &nbsp;</b> <code>PUT</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code></code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Update Purchase for Purchase Order Main</code> <small> </small><br>

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
    "" : "{}"
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
    <!-- End Update Product Purchase Order Main -->













    <!-- Get List Purchase Order Main -->
    <div class="col-sm-12" id="po-get-list-purchase-order-main" >

        <h3>Get List</h3>
        <p>
            Get List for Purchase Order Main endpoint.
            To access their Product details, they have to provide per_page and page.
            And the endpoint will return all their product regarding the given per_page and page.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/po/customers/{customerId}/purchase-orders</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL and Query Form Data required: &nbsp;</b> <code>customerId</code>, <code>searchText</code>, <code>supplierId</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get List for Purchase Order Main</code> <small> </small><br>

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
    "customerId" : "{customerId}",
    "searchText" : "{searchText}",
    "supplierId" : "{supplierId}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
[]
        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Get List Purchase Order Main  -->











    <!-- Get List Product Purchase Order Main -->
    <div class="col-sm-12" id="po-get-list-product-purchase-order-main" >

        <h3>Get List Product</h3>
        <p>
            Get List Product for Purchase Order Main endpoint.
            To access their Product List details, they have to provide purchaseOrderId.
            And the endpoint will return all their product list regarding the given purchaseOrderId.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/po/purchase-orders/{purchaseOrderId}/products</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL and Query Form Data required: &nbsp;</b> <code>purchaseOrderId</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get List Product for Purchase Order Main</code> <small> </small><br>

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
    "purchaseOrderId" : "{purchaseOrderId}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
[
    {
        "id": 25,
        "purchase_order_id": 20,
        "product_id": 131,
        "customer_id": 214,
        "quantity": 2,
        "unit_price": 2.99,
        "amount": 5.98,
        "deleted_at": null,
        "created_at": "2021-09-03T03:53:52.000000Z",
        "updated_at": "2021-09-03T03:53:52.000000Z",
        "sku": "",
        "classification_code": null,
        "duty_rate": null,
        "units_per_carton": null,
        "units": null,
        "unship_cartons": 2,
        "product": {
            "id": 131,
            "sku": "168299",
            "name": "Mattress",
            "category_id": 81,
            "description": "Blue mattress",
            "units_per_carton": 22,
            "image": "https://po.shifl.com/storage/products/images/9zGTbe1MaDxQKa5UyLPWKJs6suR1Si3Qjujcrm8n.jpg",
            "created_at": "2021-09-02T23:45:40.000000Z",
            "updated_at": "2021-10-28T22:03:25.000000Z",
            "customer_id": 214,
            "created_by": 82,
            "deleted_at": "2021-10-28T22:03:25.000000Z",
            "is_system_generated": 1,
            "customer_admins": null,
            "unit_price": 2,
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
            "category_sku": "81-168299",
            "inbound_associated": false
        },
        "shipment_distribution": []
    }
]
        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Get List Product Purchase Order Main -->









    <!-- Get Shipment Purchase Order Main -->
    <div class="col-sm-12" id="po-get-shipment-purchase-order-main" >

        <h3>Get Shipment</h3>
        <p>
            Get List Product for Purchase Order Main endpoint.
            To access their Product List details, they have to provide purchaseOrderId.
            And the endpoint will return all their product list regarding the given purchaseOrderId.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/po/shipments/{shipment_id}/purchase-orders</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL and Query Form Data required: &nbsp;</b> <code>shipment_id</code>, <code>supplierId</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Shipment for Purchase Order Main</code> <small> </small><br>

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
    "shipment_id" : "{shipment_id}"
    "supplierId" : "{supplierId}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
[
    {
        "id": 236,
        "po_number": "474193",
        "supplier_id": 1834,
        "customer_id": 447,
        "notes": null,
        "created_by": 190,
        "created_at": "2022-02-17T23:17:09.000000Z",
        "updated_at": "2022-03-24T11:04:38.000000Z",
        "warehouse_id": 217,
        "is_system_generated": 1,
        "terms": null,
        "due_by": null,
        "ship_via": "Air",
        "status": "partial_shipped",
        "sub_total": 13188,
        "tax": 0,
        "shipping": 0,
        "discount": 0,
        "total": 13188,
        "cargo_ready_date": "2022-02-17",
        "packing_method": null,
        "payment_term": null,
        "pdf": null,
        "ship_to": "Testing DC Warehouse, Blk 13 Lot Testing New Address Here, SK 80990",
        "purchase_order_products": [
            {
                "id": 278,
                "purchase_order_id": 236,
                "product_id": 394,
                "customer_id": 447,
                "quantity": 15,
                "unit_price": 25.12,
                "amount": 13188,
                "deleted_at": null,
                "created_at": "2022-02-17T23:17:09.000000Z",
                "updated_at": "2022-02-17T23:17:09.000000Z",
                "sku": null,
                "classification_code": "TESTING123456",
                "duty_rate": 0,
                "units_per_carton": 35,
                "units": 525,
                "shipment_distribution": [
                    {
                        "id": 10,
                        "created_at": "2022-03-24T11:04:38.000000Z",
                        "updated_at": "2022-03-24T11:04:38.000000Z",
                        "purchase_order_product_id": 278,
                        "shipment_id": 14610,
                        "ship_cartons": 10,
                        "is_shipment": 1,
                        "supplier_id": 1834
                    }
                ],
                "unship_cartons": -10,
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
                    "shipped_units": 350,
                    "unshipped_units": 175,
                    "category_sku": "187-952468",
                    "inbound_associated": true
                }
            }
        ]
    }
]
        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Get Shipment Purchase Order Main -->





    <!-- Get Shipment Distribution Purchase Order Main -->
    <div class="col-sm-12" id="po-shipment-distribution-purchase-order-main" >

        <h3>Get Shipment Distribution</h3>
        <p>
            Get Shipment Distribution for Purchase Order Main endpoint.
            To access their shipment distribution details, they have to provide po_num and customer_ids.
            And the endpoint will return all their product list regarding the given po_num and customer_ids.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/po/shipments/{po_num}</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL and Query Form Data required: &nbsp;</b> <code>po_num</code>, <code>customer_ids</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Shipment Distribution for Purchase Order Main</code> <small> </small><br>

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
    "po_num" : "{po_num}",
    "customer_ids" : "{customer_ids}"
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
    <!-- End Get Shipment Distribution Purchase Order Main -->


</div> <!-- end Purchase Order Main-->
