



{{-- /////////////////////////////// --}}

<div class="col-sm-12 hidden" id="item"> <!-- start item -->
    <h3 class="page-header">Item</h3>
    <!-- Item list resource -->
    <div class="col-sm-12" id="item-list">

        <h3>List resource</h3>
        <p>
            List resource for Item endpoint.
            To access their Item details, the api already get the data per page
            and the endpoint return json.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/items</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>NONE</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>List of Item</code> <small> </small><br>

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
    "data": [],
    "links": {
        "first": "api/items?page=1",
        "last": "api/items?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": null,
        "last_page": 1,
        "path": "api/items",
        "per_page": 5,
        "to": null,
        "total": 0
    }
}
                                                        </pre>
        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div><!-- End Item list resource -->


    <div class="col-sm-12" id="item-specified"><!-- item specified resource -->

        <h3>Specified resource</h3>
        <p>
            Specified resource for item endpoint.
            To access their item specific detail, we need to have the id.
            And the endpoint will return the specific item regarding the given id.
        </p>
        <br>


        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/item/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Item (Specific Item) </code> <small> </small><br>

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
    "data": {
    },
    "message": "No results found"
}
                                                        </pre>
        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>
    </div><!-- End Item specified resource -->
</div> <!-- end of Item -->
