
<div class="col-sm-12 hidden" id="carrier-type">
    <h2 class="page-header">{{ EndPointHelper::endPoint('Carrier Type') }}</h2>
    <!-- Carrier type list resource -->
    <div class="col-sm-12"  id="carrier-type-list" >

        <h3>
            {{ EndPointHelper::getAll('Carrier Type') }}
        </h3>
        <p>
            {{ EndPointHelper::getAllDescription('Carrier Type') }}
        </p>
        <br>

        <div class="" style="position:relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;





            <span
                    data-toggle="collapse"
                    data-target="#route-carrier-types"
                    aria-expanded="false"
                    aria-controls="route-carrier-types"
                    style="cursor: pointer"
            > <code>api/carrier-types</code> > </span>

            <div class="collapse api-route" id="route-carrier-types">
                <div class="badge-success c-carrier-types hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="c-carrier-types">Copy</div>
                </div>

                <div class="api_ noselect" id="c-carrier-types">
                    {{ config('app.url')}}/api/carrier-types
                </div>
            </div>


             <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>NONE</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>List of Carrier Type </code> <small> </small><br>

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
             "id": {id},
             "name": "{name}",
             "created_at": "{created_at}",
             "updated_at": "{created_at}"
         },
         {...}, ...
      ],
      "links": {
         "first": "/?page=1",
         "last": "/?page=1",
         "prev": null,
         "next": null
      },
      "meta": {
         "current_page": 1,
         "from": 1,
         "last_page": 1,
         "path": "/api/carrier-types",
         "per_page": 5,
         "to": 2,
         "total": 2
      }
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
                    <th colspan="2"><span class="badge-success">200 collection of Carrier Type</span></th>
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
                        <td><i>Integer</i> Unique identifier for the Carrier Type, autoincrement</td>
                    </tr>
                    <tr>
                        <td>name</td>
                        <td><i>String</i> The Carrier Type Name</td>
                    </tr>
                    <tr>
                        <td>created_at</td>
                        <td><i>Timestamp</i> The Carrier Type created at</td>
                    </tr>
                    <tr>
                        <td>updated_at</td>
                        <td><i>Timestamp</i> The Carrier Type updated at</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <p>
            {{ EndPointHelper::userGuide() }}
        </p>

    </div><!-- End Carrier type list resource -->


    <div class="col-sm-12" id="carrier-type-specified"><!-- Carrier type specified resource -->

        <h3>Get Carrier Type</h3>
        <p>
            Get Carrier Type for Carrier type endpoint.
            To access their Carrier type specific detail, we need to have the id.
            And the endpoint will return the specific Carrier regarding the given id.
        </p>
        <br>


        <div class="" style="position:relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;

            <span
                    data-toggle="collapse"
                    data-target="#route-carrier-type-id"
                    aria-expanded="false"
                    aria-controls="route-carrier-type-id"
                    style="cursor: pointer"
            > <code>api/carrier-type/{id}</code> > </span>

            <div class="collapse api-route" id="route-carrier-type-id">
                <div class="badge-success carrier-type-id hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="carrier-type-id">Copy</div>
                </div>

                <div class="api_ noselect" id="carrier-type-id">
                    {{ config('app.url')}}/api/carrier-type/{id}
                </div>
            </div>

            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Carrier Type (Specific Carrier Type) </code> <small> </small><br>

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
                    <td><i>Integer</i> The unique id for the Carrier Type Shipment Central to be retrieved</td>
                </tr>
                </tbody>
            </table>
        </div>
        <h4>Response</h4>
        <pre>
{
    "data": {
        "id": {id},
        "name": "{name}",
        "created_at": "{created_at}",
        "updated_at": "{updated_at}"
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
                    <th colspan="2"><span class="badge-success">200 collection of Carrier Type</span></th>
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
                        <td><i>Integer</i> Unique identifier for the Carrier Type, autoincrement</td>
                    </tr>
                    <tr>
                        <td>name</td>
                        <td><i>String</i> The Carrier Type Name</td>
                    </tr>
                    <tr>
                        <td>created_at</td>
                        <td><i>Timestamp</i> The Carrier Type created at</td>
                    </tr>
                    <tr>
                        <td>updated_at</td>
                        <td><i>Timestamp</i> The Carrier Type updated at</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <p>
            {{ EndPointHelper::userGuide() }}
        </p>
    </div><!-- End Carrier type specified resource -->
</div>
