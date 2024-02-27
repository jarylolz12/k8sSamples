<div class="col-sm-12 hidden" id="shipment-document"><!-- Shipment Documents -->
    <h3 class="page-header">Shipment Documents</h3>

    <!-- Document Insert -->
    <div class="col-sm-12" id="shipment-document-insert">

        <h3>Document Insert</h3>
        <p>
            Create document for Shipment Documents endpoint.
            To create the Shipment Documents we have to input the ff. data: shipment_id, supplier_id, name, type and file.
            And the endpoint will return json value once created.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/v2/shipment/document</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b>
            <code>shipment_id</code>,
            <code>supplier_id</code>,
            <code>name</code>,
            <code>type</code>,
            <code>file</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Document Insert</code> <small> </small><br>

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
    "shipment_id" : "{shipment_id}",
    "supplier_id" : "{supplier_id}",
    "name" : "{name}",
    "type" : "{type}",
    "file" : "{file}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "status": "ok",
    "result": {
        "name": "aperiam",
        "type": "commercial invoice",
        "shipment_id": "2",
        "path": "shipment/documents-customer/f80bd7b1c8e68d4e51b81bad28006230.png",
        "updated_at": "2022-08-04T14:10:35.000000Z",
        "created_at": "2022-08-04T14:10:35.000000Z",
        "id": 1
    }
}
                                                        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Document Insert -->







    <!-- Get Shipment Document -->
    <div class="col-sm-12" id="shipment-document-get">

        <h3>Get Shipment Document</h3>
        <p>
            Get Shipment Documents endpoint.
            To access their Shipment Document specific detail, we need to have the id.
            And the endpoint will return the specific Shipment Document regarding the given id.


        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/v2/shipment/document/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b>
            <code>id</code>,
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Document Insert</code> <small> </small><br>

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
    "result": {
        "name": "aperiam",
        "type": "commercial invoice",
        "shipment_id": "2",
        "path": "shipment/documents-customer/f80bd7b1c8e68d4e51b81bad28006230.png",
        "updated_at": "2022-08-04T14:10:35.000000Z",
        "created_at": "2022-08-04T14:10:35.000000Z",
        "id": 1
    }
}
                                                        </pre>


        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>


    </div>
    <!-- End Get Shipment Document -->





    <!-- Delete Shipment Document -->
    <div class="col-sm-12"  id="shipment-document-delete" >

        <h3>Delete Shipment Document</h3>
        <p>
            Destroy or delete resource for Shipment Document endpoint.
            To delete the Shipment Document we should have valid id.
            And the endpoint will destroy or delete the data.
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/v2/shipment/document/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>DELETE</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Delete Shipment Document</code> <small> </small><br>

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
    {
        "status": "ok"
    }
}
                                                        </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div><!-- end Delete Shipment Document -->








    <!-- Shipment Document Update -->
    <div class="col-sm-12" id="shipment-document-update">

        <h3>Shipment Document Update</h3>
        <p>
            Update document for Shipment Documents endpoint.
            To create the Shipment Documents we have to input the ff. data: id, shipment_id, supplier_id, name, type and file.
            And the endpoint will return json value once created.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/v2/shipment/document/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b>
            <code>id</code>,
            <code>shipment_id</code>,
            <code>supplier_id</code>,
            <code>name</code>,
            <code>type</code>,
            <code>file</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Document Insert</code> <small> </small><br>

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
    "shipment_id" : "{shipment_id}",
    "supplier_id" : "{supplier_id}",
    "name" : "{name}",
    "type" : "{type}",
    "file" : "{file}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "status": "ok",
    "result": {
        "name": "aperiam",
        "type": "commercial invoice",
        "shipment_id": "2",
        "path": "shipment/documents-customer/f80bd7b1c8e68d4e51b81bad28006230.png",
        "updated_at": "2022-08-04T14:10:35.000000Z",
        "created_at": "2022-08-04T14:10:35.000000Z",
        "id": 1
    }
}
                                                        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Shipment Document Update -->












    <!-- Fetch Documents -->
    <div class="col-sm-12" id="shipment-document-fetch">

        <h3>Fetch Documents</h3>
        <p>
            Get Shipment Documents endpoint.
            To access their Shipment Document specific detail, we need to have the shipment_id.
            And the endpoint will return the specific Shipment Document regarding the given shipment_id.


        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/v2/shipment/document/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Query Form Data required: &nbsp;</b>
            <code>shipment_id</code>,
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Fetch Documents</code> <small> </small><br>

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
    "shipment_id" : "{shipment_id}",
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "results": {
        "current_page": 1,
        "data": [
            {
                "id": 4,
                "name": "image (18).png",
                "type": "Other",
                "path": "shipment/documents-customer/db6258277323d39e5a02a586e6fd205c.png",
                "shipment_id": 12438,
                "created_at": "2022-04-21T04:03:55.000000Z",
                "updated_at": "2022-04-21T04:03:55.000000Z",
                "supplier_ids": [
                    {
                        "id": 1831,
                        "company_name": "Test Supplier - Dream Catcher Company",
                        "address": "2464 Royal Ln. Mesa, New Jersey 45463",
                        "phone": "+1 234-567-8900",
                        "admin_user_id": null,
                        "created_at": null,
                        "updated_at": "2022-03-30T13:54:18.000000Z",
                        "emails": "[\"jiu@gmail.com\", \"sua@gmail.com\", \"siyeon@gmail.com\"]",
                        "name": "Test Supplier - Dream Catcher Company"
                    }
                ]
            }
        ],
        "first_page_url": "api/v2/shipment/documents?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "api/v2/shipment/documents?page=1",
        "next_page_url": null,
        "path": "api/v2/shipment/documents",
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
    <!-- End Fetch Documents -->










    <!-- Insert Multiple Documents -->
    <div class="col-sm-12" id="shipment-document-multiple-insert">

        <h3>Insert Multiple Documents</h3>
        <p>
            Create document for Shipment Documents endpoint.
            To create the Shipment Documents we have to input the ff. data: shipment_id, document_infos.*.name, document_infos.*.type, document_infos.*.file and document_infos.*.supplier_id.
            And the endpoint will return json value once created.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/v2/shipment/document/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Query Form Data required: &nbsp;</b>
            <code>shipment_id</code>,
            <code>document_infos.*.name</code>,
            <code>document_infos.*.type</code>,
            <code>document_infos.*.file</code>,
            <code>document_infos.*.supplier_id</code>,
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Insert Multiple Documents</code> <small> </small><br>

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
    "shipment_id" : "{shipment_id}",
    "name" : "{document_infos.*.name}",
    "type" : "{document_infos.*.type}",
    "file" : "{document_infos.*.file}",
    "supplier_id" : "{document_infos.*.supplier_id}",
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "results": {
        "current_page": 1,
        "data": [
            {
                "id": 4,
                "name": "image (18).png",
                "type": "Other",
                "path": "shipment/documents-customer/db6258277323d39e5a02a586e6fd205c.png",
                "shipment_id": 12438,
                "created_at": "2022-04-21T04:03:55.000000Z",
                "updated_at": "2022-04-21T04:03:55.000000Z",
                "supplier_ids": [
                    {
                        "id": 1831,
                        "company_name": "Test Supplier - Dream Catcher Company",
                        "address": "2464 Royal Ln. Mesa, New Jersey 45463",
                        "phone": "+1 234-567-8900",
                        "admin_user_id": null,
                        "created_at": null,
                        "updated_at": "2022-03-30T13:54:18.000000Z",
                        "emails": "[\"jiu@gmail.com\", \"sua@gmail.com\", \"siyeon@gmail.com\"]",
                        "name": "Test Supplier - Dream Catcher Company"
                    }
                ]
            }
        ],
    }
}
                                                        </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>
    </div>
    <!-- End Insert Multiple Documents -->






    <!-- Submit Multiple Documents -->
    <div class="col-sm-12" id="shipment-document-multiple-submit">

        <h3>Submit Multiple Documents</h3>
        {{--<p>--}}
            {{--Create document for Shipment Documents endpoint.--}}
            {{--To create the Shipment Documents we have to input the ff. data: shipment_id, document_infos.*.name, document_infos.*.type, document_infos.*.file and document_infos.*.supplier_id.--}}
            {{--And the endpoint will return json value once created.--}}
        {{--</p>--}}
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/v2/shipment/submit-multiple-documents</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Query Form Data required: &nbsp;</b>
            <code>shipment_id</code>,
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Submit Multiple Documents</code> <small> </small><br>

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
    "shipment_id" : "{shipment_id}",
    "document_ids" : "{document_ids}",
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "status": "ok"
}
                                                        </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>
    </div>
    <!-- End Submit Multiple Documents -->










    <!-- Delete Multiple Documents -->
    <div class="col-sm-12" id="shipment-document-multiple-delete">

        <h3>Delete Multiple Documents</h3>
        <p>
            Delete Multiple for Shipment Documents endpoint.
            To delete multiple we have to provide valid multiple ids.
            And the endpoint will destroy or delete the data and return status to okay.
        </p>

        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/v2/shipment/delete-multiple-documents</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b>
            <code>ids</code>,
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Delete Multiple Documents</code> <small> </small><br>

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
    "ids" : "{ids}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "status": "ok"
}
                                                        </pre>

        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>
    </div>
    <!-- End Delete Multiple Documents -->









    <!-- Update Name Type -->
    <div class="col-sm-12" id="shipment-document-update-name-type">

        <h3>Update Name Type</h3>
        <p>
            {{--Update document for Shipment Documents endpoint.--}}
            {{--To create the Shipment Documents we have to input the ff. data: id, shipment_id, supplier_id, name, type and file.--}}
            {{--And the endpoint will return json value once created.--}}
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/v2/shipment/update-name-type</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b>
            <code>document_id</code>,
            <code>supplier_ids</code>,
            <code>name</code>,
            <code>type</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Document Insert</code> <small> </small><br>

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
    "document_id" : "{document_id}",
    "supplier_ids" : "{supplier_ids}",
    "name" : "{name}",
    "type" : "{type}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
{
    "status": "ok",
    "result": {
        "name": "aperiam",
        "type": "commercial invoice",
        "shipment_id": "2",
        "path": "shipment/documents-customer/f80bd7b1c8e68d4e51b81bad28006230.png",
        "updated_at": "2022-08-04T14:10:35.000000Z",
        "created_at": "2022-08-04T14:10:35.000000Z",
        "id": 1
    }
}
                                                        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Shipment Document Update -->











</div> <!-- end Shipment Documents -->
