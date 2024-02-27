<div class="col-sm-12" id="po-category">
    <h3 class="page-header">Get All Category</h3>

    <!-- Get All Category -->
    <div class="col-sm-12" id="po-get-all-category" >

        <h3>Get All Category</h3>
        <p>
            Get all category for Category endpoint.
            To access their Category details, they have to provide per_page and page.
            And the endpoint will return all their card.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/categories</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Query Form Data required: &nbsp;</b> <code>per_page</code>, <code>page</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get All Category</code> <small> </small><br>

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
    "default_customer_id" : "{default_customer_id}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "results": {
        "current_page": 1,
        "data": [
            {
                "id": 85,
                "name": "Dc Lightstick",
                "created_at": "2021-09-03T22:41:57.000000Z",
                "updated_at": "2021-09-03T22:41:57.000000Z",
                "description": "Test",
                "customer_id": 25,
                "created_by": 206,
                "image": null,
                "no_of_products": 0
            },{...}, ...
        ],
        "first_page_url": "api/categories?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "api/categories?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "api/categories?page=1",
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
        "path": "http://po.shifl.test:82/api/categories",
        "per_page": 35,
        "prev_page_url": null,
        "to": 13,
        "total": 13
    }
}
                                                        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Get All Category -->


    <!-- Get Category -->
    <div class="col-sm-12" id="po-get-category">

        <h3>Get Category</h3>
        <p>
            Get Category for Category endpoint.
            To access their Category specific detail, we need to have the id.
            And the endpoint will return the specific Category regarding the given id.
        </p>
        <br>

        <div  class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/carrier/{id}</code> <br>
                <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Category </code> <small> </small><br>

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
    "id": 1,
    "name": "John Doe",
    "created_at": "2021-09-03T22:41:57.000000Z",
    "updated_at": "2021-09-03T22:41:57.000000Z",
    "description": "Test",
    "customer_id": 1,
    "created_by": 1,
    "image": null
}
                                                        </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>
    </div><!-- End Get Category  -->


<!-- Create Category -->
        <div class="col-sm-12" id="po-create-category" >

            <h3>Create Category</h3>
            <p>
                Create resource for Category endpoint.
                To create the Category we have to input the ff. data: name, description and customer_id.
                And the endpoint will return json value once created.
            </p>
            <br>

            <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

                <b>Route: </b> &nbsp;<code>api/categories/create</code> <br>
                <b>Request Type: &nbsp;</b> <code>POST</code> <br>
                <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
                <b>Body Form Data required: &nbsp;</b> <code>name</code>, <code>description</code> , <code>customer_id</code> <br>
                <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
                <b>Will Return: &nbsp;</b> <code>Create Category</code> <small> </small><br>

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
    "name" : "{name}",
    "description" : "{description}",
    "customer_id" : "{customer_id}"
}
                                                        </pre>

            <h4>Response</h4>
            <pre>
{
    "message": "Category has been created.",
    "data": {
        "name": "et",
        "description": "et",
        "customer_id": 25,
        "created_by": 3,
        "updated_at": "2022-08-08T11:27:09.000000Z",
        "created_at": "2022-08-08T11:27:09.000000Z",
        "id": 417
    }
}
                                                        </pre>


            <p>

                Everytime users want to access other api endpoints they have to provide validate token on header with every request.
                Otherwise The request will be considered as unauthorized attempt.

            </p>


        </div>

    <!-- End Create Category -->



    <!-- Update Category -->
    <div class="col-sm-12"  id="po-update-category">

        <h3>Update Category</h3>
        <p>
            Update for Category endpoint.
            To update the Card we have to input the ff. data: first_name, last_name and is_default and the card_id is default.
            And the endpoint will return json value once updated.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/categories/update/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>PUT</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>id</code>, <code>name</code>, <code>description</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Update Category</code> <small> </small><br>

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
    "name" : "{name}",
    "description" : "{description}",
}
                                                        </pre>
        <h4>Response</h4>
        <pre>
{
    "message": "Category has been updated.",
    "data": {
        "id": 1,
        "name": "John Doe",
        "created_at": "2022-08-08T11:27:09.000000Z",
        "updated_at": "2022-08-08T11:39:06.000000Z",
        "description": "repellat",
        "customer_id": 25,
        "created_by": 3,
        "image": null
    }
}
                                                        </pre>
        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>
    </div><!-- end Update Category  -->










    <!-- Category Delete -->
    <div class="col-sm-12"  id="po-delete-category" >

        <h3>Delete Category</h3>
        <p>
            Destroy or delete resource for Category endpoint.
            To delete the Category we should have valid card_id and default_customer_id.
            And the endpoint will destroy or delete the data.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/categories/delete/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>DELETE</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>id</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Delete Category </code> <small> </small><br>

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
    "message": "Category has been deleted."
}
                                                        </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div><!-- end Delete Category -->

</div> <!-- end Category -->
