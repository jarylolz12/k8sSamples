<div class="col-sm-12 hidden" id="container-size"> <!-- start container size -->

    <h3 class="page-header">{{ EndPointHelper::endPoint('Container Size') }}</h3>
    <!-- Container Size list resource -->
    <div class="col-sm-12" id="container-size-list">
        <h3>{{ EndPointHelper::getAll('Container Size') }}</h3>
        <p>
            {{ EndPointHelper::getAllDescription('Container Size') }}
        </p>
        <br>

        <div class="" style="position:relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;



            <span
                    data-toggle="collapse"
                    data-target="#route-container-sizes"
                    aria-expanded="false"
                    aria-controls="route-container-sizes"
                    style="cursor: pointer"
            > <code>api/container-sizes</code> > </span>

            <div class="collapse api-route" id="route-container-sizes">
                <div class="badge-success c-container-sizes hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="c-container-sizes">Copy</div>
                </div>

                <div class="api_ noselect" id="c-container-sizes">
                    {{ config('app.url')}}/api/container-sizes
                </div>
            </div>


            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Query Form Data required: &nbsp;</b> <code>per_page</code> and <code>page</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>List of Container size</code> <small> </small><br>

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
    "per_page": "{per_page}",
    "page": "{page}"
}
    </pre>

        <div>
            <div>
                <h4 style="display: inline-block">REQUEST</h4>
            </div>
            <table class="table-bordered">
                <thead>
                <th colspan="2">Query Parameters</th>
                </thead>
                <thead>
                <th>Attribute</th>
                <th>Description</th>
                </thead>
                <tbody>
                <tr>
                    <td>per_page<span class="badge-danger">required</span></td>
                    <td><i>Integer</i> Count of items in each page. Maximum allowed value is 5. Defaults: 5. </td>
                </tr>
                <tr>
                    <td>Page<span class="badge-danger">required</span></td>
                    <td><i>Integer</i> Page number of the page to retrieve. Defaults: 1.</td>
                </tr>
                </tbody>
            </table>
        </div>


        <h4>Response</h4>
        <pre>
{
    "data": [
        {
            "id": 1,
            "name": "John Doe",
            "created_at": "{created_at}",
            "updated_at": "{updated_at}"
        },
        {...}, ..
    ],
    "links": {
        "first": "api/container-sizes?page=1",
        "last": "api/container-sizes?page=4",
        "prev": null,
        "next": "api/container-sizes?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 4,
        "path": "api/container-sizes",
        "per_page": 5,
        "to": 5,
        "total": 16
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
                    <th colspan="2"><span class="badge-success">200 collection of Container Size</span></th>
                    </thead>
                    <thead>
                    <th>RESPONSE SCHEMA: </th>
                    <th>application/json</th>
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
                        <td><i>Integer</i> Unique identifier for the Container Size, autoincrement</td>
                    </tr>
                    <tr>
                        <td>name</td>
                        <td><i>Integer</i>  The Container Size name</td>
                    </tr>
                    <tr>
                        <td>created_at</td>
                        <td><i>Date Time</i> The Container Size created at</td>
                    </tr>
                    <tr>
                        <td>updated_at</td>
                        <td><i>Date Time</i> The Container Size updated at</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>


        <p>
            {{ EndPointHelper::userGuide() }}
        </p>

    </div><!-- End container size list resource -->


    <div class="col-sm-12" id="container-size-specified"><!-- container size specified resource -->

        <h3>{{ EndPointHelper::get("Container Size") }}</h3>
        <p>
            {{ EndPointHelper::getDescription('Container Size', 'id') }}
        </p>
        <br>


        <div id="carrier-type-specified" class="" style="position:relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;




            <span
                    data-toggle="collapse"
                    data-target="#route-container-size-id"
                    aria-expanded="false"
                    aria-controls="route-container-size-id"
                    style="cursor: pointer"
            > <code>api/container-size/{id}</code> > </span>

            <div class="collapse api-route" id="route-container-size-id">
                <div class="badge-success container-size-id hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="container-size-id">Copy</div>
                </div>

                <div class="api_ noselect" id="container-size-id">
                    {{ config('app.url')}}/api/container-size/{id}
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Container size (Specific Container size) </code> <small> </small><br>

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
                <th colspan="2">Query Parameters</th>
                </thead>
                <thead>
                <th>Attribute</th>
                <th>Description</th>
                </thead>
                <tbody>
                <tr>
                    <td>id<span class="badge-danger">required</span></td>
                    <td><i>Integer</i>  The unique id for the Container size Shipment Central to be retrieved </td>
                </tr>
                </tbody>
            </table>
        </div>


        <h4>Response</h4>
        <pre>
{
    "data": {
        "id": 1,
        "name": "John Doe",
        "created_at": "2020-02-28T07:02:49.000000Z",
        "updated_at": "2021-06-28T14:16:53.000000Z"
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
                    <th colspan="2"><span class="badge-success">200 collection of Container Size</span></th>
                    </thead>
                    <thead>
                    <th>RESPONSE SCHEMA: </th>
                    <th>application/json</th>
                    </thead>
                    <tbody>
                    <tr>
                        <td>data</td>
                        <td>object</td>
                    </tr>
                    <tr>
                        <td>id</td>
                        <td><i>Integer</i> Unique identifier for the Container Size, autoincrement</td>
                    </tr>
                    <tr>
                        <td>name</td>
                        <td><i>Integer</i>  The Container Size name</td>
                    </tr>
                    <tr>
                        <td>created_at</td>
                        <td><i>Date Time</i> The Container Size created at</td>
                    </tr>
                    <tr>
                        <td>updated_at</td>
                        <td><i>Date Time</i> The Container Size updated at</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <p>
            {{ EndPointHelper::userGuide() }}
        </p>
    </div><!-- End container size specified resource -->
</div> <!-- end of container size -->
