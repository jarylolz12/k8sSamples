<!-- Carrier resource -->
<div class="col-sm-12 hidden" id="carrier">

    <h2 class="page-header">{{ EndPointHelper::endPoint("Carrier") }}</h2>
    <!-- Carrier list resource -->
    <div class="col-sm-12"  id="carrier-list">

        <h3>{{ EndPointHelper::getAll("Carrier") }}</h3>
        <p>
            {{ EndPointHelper::getAllDescription("Carrier")}}
        </p>
        <br>

        <div class="" style="position: relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;

            <span
                    data-toggle="collapse"
                    data-target="#route-carrier"
                    aria-expanded="false"
                    aria-controls="route-carrier"
                    style="cursor: pointer"
            > <code>api/carrier</code> > </span>

            <div class="collapse api-route" id="route-carrier">
                <div class="badge-success c-carrier hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="c-carrier">Copy</div>
                </div>

                <div class="api_ noselect" id="c-carrier">
                    {{ config('app.url')}}/api/carrier
                </div>
            </div>

            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>NONE</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>List of Carrier </code> <small> </small><br>

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

        <div>
            <div>
                <h4 style="display: inline-block">REQUEST</h4>
            </div>
            <table class="table-bordered">
                <thead>
                    <th colspan="2">URL Parameters</th>
                </thead>
                <thead>
                    <th>Attribute</th>
                    <th>Description</th>
                </thead>
                <tbody>
                <tr>
                    <td>NONE</td>
                    <td>NONE</td>
                </tr>
                </tbody>
            </table>
        </div>

        <h4>Response</h4>
        <pre>
{
    "data": [
        {
            "id":{id},
            "name": "{name}",
            "carrier_type_id": {carrier_type_id},
        },
        {...}, ...
    ],
    "links": {
        "first": "/api/carriers?page=1",
        "last": "/api/carriers?page=17",
        "prev": null,
        "next": "1/api/carriers?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 17,
        "path": "/api/carriers",
        "per_page": 5,
        "to": 5,
        "total": 83
    }
}
       </pre>
        <div style="margin-bottom: 10px">
            <div>
                <h4 style="display: inline-block">Responses</h4>
            </div>
            <div>
                <table class="table-bordered">
                    <thead>
                        <tr>
                            <th colspan="2"><span class="badge-success">200 collection of Carrier</span></th>
                        </tr>
                        <tr>
                            <th>RESPONSE SCHEMA: </th>
                            <th>application/json</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>links</td>
                        <td>
                            <ul class="list-unstyled">
                                <li>first</li>
                                <li>last</li>
                                <li>prev</li>
                                <li>next</li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td>meta</td>
                        <td>
                            <ul class="list-unstyled">
                                <li>current_page</li>
                                <li>from</li>
                                <li>last_page</li>
                                <li>path</li>
                                <li>per_page</li>
                                <li>to</li>
                                <li>total</li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td>data</td>
                        <td>object</td>
                    </tr>
                    <tr>
                        <td>id</td>
                        <td><i>Integer</i> Unique identifier for the Carrier, autoincrement</td>
                    </tr>
                    <tr>
                        <td>name</td>
                        <td><i>String</i> The Carrier name</td>
                    </tr>
                    <tr>
                        <td>carrier_type_id</td>
                        <td><i>Integer</i> The Carrier types id</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <p>
            {{ EndPointHelper::userGuide() }}
        </p>

    </div><!-- End Carrier list resource -->


    <!-- Carrier Specified resource -->
    <div class="col-sm-12" id="carrier-specific">

        <h3>{{ EndPointHelper::get("Carrier") }}</h3>
        <p>
           {{ EndPointHelper::getDescription("Carrier", "id") }}
        </p>
        <br>

        <div  class="" style="position: relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;

            <span
                    data-toggle="collapse"
                    data-target="#route-carrier-id"
                    aria-expanded="false"
                    aria-controls="route-carrier-id"
                    style="cursor: pointer"
            > <code>api/carrier/{id}</code> > </span>

            <div class="collapse api-route" id="route-carrier-id">
                <div class="badge-success carrier-id hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="carrier-id">Copy</div>
                </div>

                <div class="api_ noselect" id="carrier-id">
                    {{ config('app.url')}}/api/carrier/{id}
                </div>
            </div>

            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Carrier (Specific Carrier) </code> <small> </small><br>

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
        <div>
            <div>
                <h4 style="display: inline-block">REQUEST</h4>
            </div>
            <table class="table-bordered">
                <thead>
                <th colspan="2">URL Parameters</th>
                </thead>
                <thead>
                <th>Attribute</th>
                <th>Description</th>
                </thead>
                <tbody>
                <tr>
                    <td>id<span class="badge-danger">required</span></td>
                    <td><i>Integer</i> The unique id for the Carrier Shipment Central to be retrieved</td>
                </tr>
                </tbody>
            </table>
        </div>
        <h4>Response</h4>
        <pre>
{
    "data": {
        "id": {id},
        "name": "{name}"
    }
}
                                                        </pre>

        <div style="margin-bottom: 10px">
            <div>
                <h4 style="display: inline-block">Responses</h4>
            </div>
            <div>
                <table class="table-bordered">
                        <tr>
                            <th colspan="2"><span class="badge-success">200 collection of Carrier</span></th>
                        </tr>
                        <tr>
                            <th>RESPONSE SCHEMA: </th>
                            <th>application/json</th>
                        </tr>
                    <tbody>
                    <tr>
                        <td>data</td>
                        <td>object</td>
                    </tr>
                    <tr>
                        <td>id</td>
                        <td><i>Integer</i> Unique identifier for the Carrier, autoincrement</td>
                    </tr>
                    <tr>
                        <td>name</td>
                        <td><i>String</i> The Carrier name</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <p>
            {{ EndPointHelper::userGuide() }}
        </p>
    </div><!-- End Carrier Specified resource -->
</div>
