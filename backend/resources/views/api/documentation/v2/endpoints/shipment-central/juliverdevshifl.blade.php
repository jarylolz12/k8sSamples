



{{-- /////////////////////////////// --}}

<div class="col-sm-12 hidden" id="juliverdevshifl"> <!-- start Juliverdevshifl -->
    <h3 class="page-header">Juliverdevshifl</h3>
    <!-- Juliverdevshifl Get Index Terminal -->
    <div class="col-sm-12" id="juliverdevshifl-get-index-terminal">

        <h3>Get Index Terminal</h3>
        <p>
            Get Index Terminal for Juliverdevshifl endpoint.
            To access their Juliverdevshifl Terminal, the api already display the data
            and the endpoint return json.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/sell-buy-rates-index/data/get/indexes/terminal</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>NONE</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>NONE</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Index Terminal</code> <small> </small><br>

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
    "success": true,
    "message": "",
    "data": [
        {
            "name": "NY/NJ",
            "data": {
                "total_items": 0,
                "totals": "0.00",
                "average": "0.00",
                "percentage": 100
            }
        },
        {
            "name": "LOS ANGELES",
            "data": {
                "total_items": 0,
                "totals": "0.00",
                "average": "0.00",
                "percentage": 100
            }
        }
    ]
}
                                                        </pre>
        <p>
            In this endpoints user can access api without providing valid token on header
            {{--Everytime users want to access other api endpoints they have to provide validate token on header with every request.--}}
            {{--Otherwise The request will be considered as unauthorized attempt.--}}
        </p>
    </div><!-- End Juliverdevshifl Get Index Terminal -->

    <div class="col-sm-12" id="juliverdevshifl-get-container"><!-- Juliverdevshifl Get Container -->

        <h3>Get Container</h3>
        <p>
            Get Container for Juliverdevshifl endpoint.
            To access their Juliverdevshifl Get Container, we need to have the id.
            And the endpoint will return the Container regarding the given id.
        </p>
        <br>


        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/sell-buy-rates-index/data/get/container/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>NONE</code> <br>
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
[]
                                                        </pre>
        <p>
            In this endpoints user can access api without providing valid token on header
            {{--Everytime users want to access other api endpoints they have to provide validate token on header with every request.--}}
            {{--Otherwise The request will be considered as unauthorized attempt.--}}
        </p>
    </div><!-- End Juliverdevshifl Get Container -->

    <div class="col-sm-12" id="juliverdevshifl-get-index"><!-- Juliverdevshifl Get Index -->

        <h3>Juliverdevshifl Get Index</h3>
        <p>
            Get Index for Juliverdevshifl endpoint.
            To access their Juliverdevshifl Get Index, we need to have the id.
            And the endpoint will return the Container regarding the given id.
        </p>
        <br>


        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/sell-buy-rates-index/data/get/indexes/{id}</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>NONE</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Index</code> <small> </small><br>

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
    "success": true,
    "message": "",
    "data": [
        {
            "name": "NY/NJ",
            "data": {
                "total_items": 0,
                "totals": "0.00",
                "average": "0.00",
                "percentage": 100
            }
        },
        {
            "name": "LOS ANGELES",
            "data": {
                "total_items": 0,
                "totals": "0.00",
                "average": "0.00",
                "percentage": 100
            }
        }
    ]
}
                                                        </pre>
        <p>
            In this endpoints user can access api without providing valid token on header
            {{--Everytime users want to access other api endpoints they have to provide validate token on header with every request.--}}
            {{--Otherwise The request will be considered as unauthorized attempt.--}}
        </p>
    </div><!-- End Juliverdevshifl Get Container -->

    <!-- Juliverdevshifl Get Graph Terminal -->
    <div class="col-sm-12" id="juliverdevshifl-get-graph-terminal">

        <h3>Get Graph Terminal</h3>
        <p>
            Get Graph Terminal for Juliverdevshifl endpoint.
            To access their Juliverdevshifl Graph Terminal, the api already display the data
            and the endpoint return json.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/sell-buy-rates-index/data/get/graph/terminal</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>NONE</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>NONE</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Get Graph Terminal</code> <small> </small><br>

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
    "success": true,
    "message": "",
    "data": [
        {
            "name": "NY/NJ",
            "color": "#0171A1",
            "data": {
                "data": {
                    "2021-04-28": 0,
                    "2021-04-30": 0,
                    "2021-05-01": 0,
                    ...,
                },
                "total_items": 287,
                "totals": "0.00",
                "average": "0.00"
            }
        },
        {
            "name": "LOS ANGELES",
            "color": "#eb9534",
            "data": {
                "data": {
                    "2021-04-28": 0,
                    "2021-04-30": 0,
                    "2021-05-01": 0,
                    ...,
                },
                "total_items": 287,
                "totals": "0.00",
                "average": "0.00"
            }
        }
    ],
    "labels": [
        "2021-04-28",
        "2021-04-30",
         ...,
    ],
    "all_time_average": 0
}
                                                        </pre>
        <p>
            In this endpoints user can access api without providing valid token on header
            {{--Everytime users want to access other api endpoints they have to provide validate token on header with every request.--}}
            {{--Otherwise The request will be considered as unauthorized attempt.--}}
        </p>
    </div><!-- End Juliverdevshifl Get Graph Terminal -->

</div> <!-- end of Juliverdevshifl -->