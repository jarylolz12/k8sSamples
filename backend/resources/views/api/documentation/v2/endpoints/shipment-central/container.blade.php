<div class="col-sm-12 hidden" id="container"> <!-- start container -->
    <h3 class="page-header">{{ EndPointHelper::endPoint('Container') }}</h3>
    <!-- Container list resource -->
    <div class="col-sm-12" id="container-list">

        <h3>{{ EndPointHelper::getAll('Container') }}</h3>
        <p>
            {{ EndPointHelper::getAllDescription('Container', 'default_customer_id') }}
        </p>
        <br>

        <div class="" style="position: relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;



            <span
                    data-toggle="collapse"
                    data-target="#route-containers"
                    aria-expanded="false"
                    aria-controls="route-containers"
                    style="cursor: pointer"
            > <code>api/containers/{id}</code> > </span>

            <div class="collapse api-route" id="route-containers">
                <div class="badge-success c-containers hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>

                    <div  class="copy-api" rel="c-containers">Copy</div>
                </div>

                <div class="api_ noselect" id="c-containers">
                    {{ config('app.url')}}/api/containers
                </div>
            </div>


            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>NONE</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>List of Container</code> <small> </small><br>

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
            "container_num": "{container_num}",
            "type": {type},
            "seal_num": {seal_num},
            "cartons": {cartons},
            "cbm": "{cbm}",
            "kg": "{kg}",
            "shipment_id": {shipment_id},
            "chargeable_weight": {chargeable_weight},
            "created_at": {created_at},
            "updated_at": {updated_at},
            "unique_id": "{unique_id}",
            "size": {size}
        },
        {...}, ...
    ],
    "links": {
        "first": "/api/containers?page=1",
        "last": "/api/containers?page=2722",
        "prev": null,
        "next": "/api/containers?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 2722,
        "path": "/api/containers",
        "per_page": 5,
        "to": 5,
        "total": 13609
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
                    <th colspan="2"><span class="badge-success">200 collection of Container</span></th>
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
                        <td><i>Integer</i> Unique identifier for the Container, autoincrement</td>
                    </tr>
                    <tr>
                        <td>container_num</td>
                        <td><i>String</i> Container number, string or null</td>
                    </tr>
                    <tr>
                        <td>type</td>
                        <td><i>String</i> Container Type, string or null</td>
                    </tr>
                    <tr>
                        <td>seal_num</td>
                        <td><i>String</i> Container Seal Number, string or null</td>
                    </tr>
                    <tr>
                        <td>cartons</td>
                        <td><i>Integer</i> Cartonds, integer or null</td>
                    </tr>
                    <tr>
                        <td>cbm</td>
                        <td><i>Decimal</i> CBM, decimal or Null</td>
                    </tr>
                    <tr>
                        <td>kg</td>
                        <td><i>Decimal</i> Kg, decimal or Null</td>
                    </tr>
                    <tr>
                        <td>shipment_id</td>
                        <td><i>Integer</i>  Shipment ID, foreign key, unsigned</td>
                    </tr>
                    <tr>
                        <td>chargeable_weight</td>
                        <td><i>String</i> Chargeable Weight, string or null</td>
                    </tr>
                    <tr>
                        <td>unique_id</td>
                        <td><i>String</i> Unique ID, string or none</td>
                    </tr>
                    <tr>
                        <td>size</td>
                        <td><i>Integer</i> Size of Container, integer or none</td>
                    </tr>
                    <tr>
                        <td>created_at</td>
                        <td><i>Date Time</i> The Container created at</td>
                    </tr>
                    <tr>
                        <td>updated_at</td>
                        <td><i>Date Time</i> The Container updated at</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <p>
            {{ EndPointHelper::userGuide() }}
        </p>

    </div><!-- End Container list resource -->


    <div class="col-sm-12" id="container-specified"><!-- Container specified resource -->

        <h3>{{ EndPointHelper::get('container') }}</h3>
        <p>
            {{ EndPointHelper::getDescription('Container', 'id') }}
        </p>
        <br>
        <div class="" style="position:relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;

            <span
                    data-toggle="collapse"
                    data-target="#route-container-id"
                    aria-expanded="false"
                    aria-controls="route-container-id"
                    style="cursor: pointer"
            > <code>api/containers/{id}</code> > </span>

            <div class="collapse api-route" id="route-container-id">
                <div class="badge-success container-id hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="container-id">Copy</div>
                </div>

                <div class="api_ noselect" id="container-id">
                    {{ config('app.url')}}/api/containers
                </div>
            </div>

            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Container (Specific Container) </code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
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
                    <td><i>Integer</i> The unique id for the Container Shipment Central to be retrieved</td>
                </tr>
                </tbody>
            </table>
        </div>



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
            "id": {id},
            "container_num": "{container_num}",
            "type": {type},
            "seal_num": {seal_num},
            "cartons": {cartons},
            "cbm": "{cbm}",
            "kg": "{kg}",
            "shipment_id": {shipment_id},
            "chargeable_weight": {chargeable_weight},
            "created_at": {created_at},
            "updated_at": {updated_at},
            "unique_id": "{unique_id}",
            "size": {size}
    },
}
      </pre>


        <div style="margin-bottom: 10px">
            <div>
                <h4 style="display: inline-block">Responses</h4>
            </div>
            <div>
                <table class="table-bordered">
                    <thead>
                    <th colspan="2"><span class="badge-success">200 collection of Container</span></th>
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
                        <td><i>Integer</i> Unique identifier for the Container, autoincrement</td>
                    </tr>
                    <tr>
                        <td>container_num</td>
                        <td><i>String</i> Container number, string or null</td>
                    </tr>
                    <tr>
                        <td>type</td>
                        <td><i>String</i> Container Type, string or null</td>
                    </tr>
                    <tr>
                        <td>seal_num</td>
                        <td><i>String</i> Container Seal Number, string or null</td>
                    </tr>
                    <tr>
                        <td>cartons</td>
                        <td><i>Integer</i> Cartonds, integer or null</td>
                    </tr>
                    <tr>
                        <td>cbm</td>
                        <td><i>Decimal</i> CBM, decimal or Null</td>
                    </tr>
                    <tr>
                        <td>kg</td>
                        <td><i>Decimal</i> Kg, decimal or Null</td>
                    </tr>
                    <tr>
                        <td>shipment_id</td>
                        <td><i>Integer</i>  Shipment ID, foreign key, unsigned</td>
                    </tr>
                    <tr>
                        <td>chargeable_weight</td>
                        <td><i>String</i> Chargeable Weight, string or null</td>
                    </tr>
                    <tr>
                        <td>unique_id</td>
                        <td><i>String</i> Unique ID, string or none</td>
                    </tr>
                    <tr>
                        <td>size</td>
                        <td><i>Integer</i> Size of Container, integer or none</td>
                    </tr>
                    <tr>
                        <td>created_at</td>
                        <td><i>Date Time</i> The Container created at</td>
                    </tr>
                    <tr>
                        <td>updated_at</td>
                        <td><i>Date Time</i> The Container updated at</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <p>
            {{ EndPointHelper::userGuide() }}
        </p>
    </div><!-- End container specified resource -->
</div> <!-- end of container -->
