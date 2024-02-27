<div class="col-sm-12" id="po-product">
    <h3 class="page-header">Product</h3>

    <!-- Get All Product -->
    <div class="col-sm-12" id="po-get-all-product" >

        <h3>Get All Product</h3>
        <p>
            Get All Product for Product endpoint.
            To access their Product details, they have to provide per_page and page.
            And the endpoint will return all their product regarding the given per_page and page.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/products</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Query Form Data required: &nbsp;</b> <code>per_page</code>, <code>page</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get All Product</code> <small> </small><br>

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
                "id": 250,
                "sku": "317788",
                "name": "Testing Panda Product",
                "category_id": 111,
                "description": null,
                "units_per_carton": 20,
                "image": null,
                "created_at": "2021-10-26T22:43:16.000000Z",
                "updated_at": "2021-10-26T22:43:16.000000Z",
                "customer_id": 25,
                "created_by": 206,
                "deleted_at": null,
                "is_system_generated": 1,
                "customer_admins": null,
                "unit_price": 20,
                "classification_code": null,
                "duty_rate": null,
                "carton_dimensions": "{\"length\":0,\"width\":0,\"height\":0,\"uom\":\"cm\"}",
                "is_for_classification_code": null,
                "upc_number": null,
                "country_from": null,
                "country_to": null,
                "product_classification_description": null,
                "unit_weight": null,
                "unit_dimensions": null,
                "carton_upc": null,
                "additional_classification_code": null,
                "category_sku": "111-317788",
                "inbound_associated": true
            },{...},...
        ],
        "first_page_url": "api/products?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "api/products?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "api/products?page=1",
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
        "path": "api/products",
        "per_page": 35,
        "prev_page_url": null,
        "to": 10,
        "total": 10
    }
}
                                                        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Get All Product -->




    <!-- Get Product -->
    <div class="col-sm-12" id="po-get-product" >

        <h3>Get Product</h3>
        <p>
            Get Product for Product endpoint.
            To access their specific Product details, they have to provide the id.
            And the endpoint will return their product regarding the given id.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/products/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Query Form Data required: &nbsp;</b> <code>id</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Product</code> <small> </small><br>

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
    "id": 250,
    "sku": "317788",
    "name": "Testing Panda Product",
    "category_id": 111,
    "description": null,
    "units_per_carton": 20,
    "image": null,
    "created_at": "2021-10-26T22:43:16.000000Z",
    "updated_at": "2021-10-26T22:43:16.000000Z",
    "customer_id": 25,
    "created_by": 206,
    "deleted_at": null,
    "is_system_generated": 1,
    "customer_admins": null,
    "unit_price": 20,
    "classification_code": null,
    "duty_rate": null,
    "carton_dimensions": "{\"length\":0,\"width\":0,\"height\":0,\"uom\":\"cm\"}",
    "is_for_classification_code": null,
    "upc_number": null,
    "country_from": null,
    "country_to": null,
    "product_classification_description": null,
    "unit_weight": null,
    "unit_dimensions": null,
    "carton_upc": null,
    "additional_classification_code": null,
    "category_sku": "111-317788",
    "inbound_associated": true
}
                                                        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Get Product -->














    <!-- Create Product -->
    <div class="col-sm-12" id="po-create-product" >

        <h3>Create Product</h3>
        <p>
            Create Product for Product endpoint.
            To create the Product we have to input the ff. data:
            sku, name, category_id, description, units_per_carton, image, is_system_generated, classifiation_code and duty_rate.
            And the endpoint will return json value once created.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/products/create</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>sku</code>,
            <code>name</code>,
            <code>category_id</code>,
            <code>description</code>,
            <code>units_per_carton</code>,
            <code>image</code>,
            <code>is_system_generated</code>,
            <code>classifiation_code</code>,
            <code>duty_rate</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Product</code> <small> </small><br>

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
    "sku" : "{sku}",
    "name" : "{name}",
    "category_id" : "{category_id}",
    "description" : "{description}",
    "units_per_carton" : "{units_per_carton}",
    "image" : "{image}",
    "is_system_generated" : "{is_system_generated}",
    "classifiation_code" : "{classifiation_code}",
    "duty_rate" : "{duty_rate}"
}
        </pre>

        <h4>Response</h4>
        <pre>
{
    "message": "Product has been created.",
    "data": {
        "sku": "dolorem",
        "name": "rerum",
        "category_id": "4",
        "description": "sed",
        "units_per_carton": "14",
        "unit_price": null,
        "classification_code": null,
        "duty_rate": "8",
        "carton_dimensions": null,
        "is_for_classification_code": null,
        "upc_number": null,
        "carton_upc": null,
        "country_from": null,
        "country_to": null,
        "product_classification_description": null,
        "unit_weight": null,
        "unit_dimensions": null,
        "additional_classification_code": null,
        "image": "products/images/PdewUBfWqSKFe9mslLeMu3UiBPwLoZrb8fvoTqFt.jpg",
        "created_by": 3,
        "is_system_generated": 0,
        "customer_id": 25,
        "updated_at": "2022-08-10T13:12:48.000000Z",
        "created_at": "2022-08-10T13:12:48.000000Z",
        "id": 873,
        "category_sku": "4-dolorem",
        "inbound_associated": false
    }
}
                                                        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Create Product -->















    <!-- Update Product -->
    <div class="col-sm-12" id="po-update-product" >

        <h3>Update Product</h3>
        <p>
            Update Product for Product endpoint.
            To update the Product we have to input the ff. data: id,
            sku, name, category_id, description, units_per_carton, image, is_system_generated, classifiation_code and duty_rate.
            And the endpoint will return json value once updated.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/products/update/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>PUT</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL and Body Form Data required: &nbsp;</b> <code>id</code>,<code>sku</code>,
            <code>name</code>,
            <code>category_id</code>,
            <code>description</code>,
            <code>units_per_carton</code>,
            <code>image</code>,
            <code>is_system_generated</code>,
            <code>classifiation_code</code>,
            <code>duty_rate</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Product</code> <small> </small><br>

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
    "sku" : "{sku}",
    "name" : "{name}",
    "category_id" : "{category_id}",
    "description" : "{description}",
    "units_per_carton" : "{units_per_carton}",
    "image" : "{image}",
    "is_system_generated" : "{is_system_generated}",
    "classifiation_code" : "{classifiation_code}",
    "duty_rate" : "{duty_rate}"
}
        </pre>

        <h4>Response</h4>
        <pre>
{
    "message": "Product has been updated.",
    "data": {
        "id": 873,
        "sku": "iusto",
        "name": "eaque",
        "category_id": "4",
        "description": "recusandae",
        "units_per_carton": "18",
        "image": "products/images/PdewUBfWqSKFe9mslLeMu3UiBPwLoZrb8fvoTqFt.jpg",
        "created_at": "2022-08-10T13:12:48.000000Z",
        "updated_at": "2022-08-10T13:22:02.000000Z",
        "customer_id": 25,
        "created_by": 3,
        "deleted_at": null,
        "is_system_generated": 0,
        "customer_admins": null,
        "unit_price": null,
        "classification_code": null,
        "duty_rate": "6",
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
        "category_sku": "4-iusto",
        "inbound_associated": false
    }
}
                                                        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Update Product -->












    <!-- Delete Product -->
    <div class="col-sm-12" id="po-delete-product" >

        <h3>Delete Product</h3>
        <p>
            Delete Product for Product endpoint.
            To delete the Product we should have valid id.
            And the endpoint will deleted the data.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/products/delete/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>DELETE</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL and Body Form Data required: &nbsp;</b> <code>id</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Product</code> <small> </small><br>

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
    "message": "Product has been deleted."
}
        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Delete Product -->



</div> <!-- end Amazon Sotre-->
