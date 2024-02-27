<div class="col-sm-12 hidden" id="customer"> <!-- start customer -->
    <h3 class="page-header">{{ EndPointHelper::endPoint('Customer') }}</h3>
    <!-- customer list resource -->
    <div class="col-sm-12" id="customer-list">
        <h3>{{ EndPointHelper::getAll('Customer') }}</h3>
        <p>
            {{ EndPointHelper::getAllDescription("Customer")}}
        </p>
        <br>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-customers" aria-hidden="true">
                <span
                    data-toggle="collapse"
                    data-target="#route-customers"
                    aria-expanded="false"
                    aria-controls="route-customers"
                    style="cursor: pointer"
                    class="i-customers"
                > <code>api/customer/{id}</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true">
                    </span>
                </span>
            </span>
            <div class="collapse api-route" id="route-customers">
                <div class="badge-success customers hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="customers">Copy</div>
                </div>
                <div class="api_ noselect" id="customers">
                    {{ config('app.url')}}/api/customers
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>NONE</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>List of Customer</code> <small> </small><br>
        </div>
        <h4>Header</h4>
        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"Authorization"</span> : <span class="slctrl-string">"Bearer {access_token}"</span>
    <span class="slctrl-attr">"Content-Type"</span> : <span class="slctrl-string"> "application/json" </span>
    <span class="slctrl-attr">"Accept"</span> : <span class="slctrl-string"> "application/json" </span>
} </pre>
        <h4> Form Data </h4>
        <pre> <span class="slctrl-attr">NONE</span> </pre>
        <div>
            <div>
                <h4 style="display: inline-block">REQUEST</h4>
            </div>
            <table class="table-bordered">
                <thead>
                <tr>
                    <th colspan="2">Body Parameters</th>
                </tr>
                <tr>
                    <th>Attribute</th>
                    <th>Description</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>NONE</td>
                    <td>No attribute available</td>
                </tr>
                </tbody>
            </table>
        </div>
        <h4>Response
            <span
                data-toggle="collapse"
                data-target="#customers-response"
                aria-expanded="false"
                aria-controls="customers-response"
                style="cursor: pointer; top: 2px !important;"
                class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre class="collapse" id="customers-response">
{
    <span class="slctrl-attr">"data"</span>: [
       {
            <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">1</span>,
            <span class="slctrl-attr">"company_name"</span>: <span class="slctrl-string">"Goldner-Kunze</span>",
            <span class="slctrl-attr">"address"</span>": <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"phone"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2020-01-07T22:54:10.000000Z"</span>,
            <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2021-09-08T22:24:34.000000Z"</span>,
            <span class="slctrl-attr">"managers"</span>: <span class="slctrl-number">119</span>
       },
       {...}, ...

    ],
    <span class="slctrl-attr">"links"</span>: {
        <span class="slctrl-attr">"first"</span>: <span class="slctrl-string">"/api/customers?page=1"</span>,
        <span class="slctrl-attr">"last"</span>: <span class="slctrl-string">"/api/customers?page=1"</span>,
        <span class="slctrl-attr">"prev"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"next"</span>: <span class="slctrl-literal">null</span>
    },
    <span class="slctrl-attr">"meta"</span>: {
        <span class="slctrl-attr">"current_page"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"from"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"last_page"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"path"</span>: <span class="slctrl-string">"/api/customers"</span>,
        <span class="slctrl-attr">"per_page"</span>: <span class="slctrl-number">5</span>,
        <span class="slctrl-attr">"to"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"total"</span>: <span class="slctrl-number">0</span>
    }
} </pre>
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
                        <td
                            data-toggle="collapse"
                            data-target="#get-all-c-data"
                            aria-expanded="false"
                            aria-controls="get-all-c-data"
                            style="cursor: pointer;  "

                            class="glyphicon1 all-c-data"
                            rel="all-c-data"
                        >
                            <span class="all-c-data">
                               data <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                            </span>
                        </td>
                        <td>object</td>
                    </tr>
                    <tr  class="collapse" id="get-all-c-data">
                        <td colspan="2">
                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                <table class="table-bordered">
                                    <thead></thead>
                                    <tbody>
                                        <tr>
                                            <td>id</td>
                                            <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                        </tr>
                                        <tr>
                                            <td>company_name</td>
                                            <td><i>Integer</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>address</td>
                                            <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                        </tr>
                                        <tr>
                                            <td>phone</td>
                                            <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                        </tr>
                                        <tr>
                                            <td>created_at</td>
                                            <td><i>Integer</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>updated_at</td>
                                            <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                        </tr>
                                        <tr>
                                            <td>managers</td>
                                            <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td
                            data-toggle="collapse"
                            data-target="#get-all-c-links"
                            aria-expanded="false"
                            aria-controls="get-all-c-links"
                            style="cursor: pointer;  "
                            class="glyphicon1 all-c-links"
                            rel="all-c-links" s
                        >
                            <span class="all-c-links">
                               links <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                            </span>
                        </td>
                        <td>object</td>
                    </tr>
                    <tr  class="collapse" id="get-all-c-links">
                        <td colspan="2">
                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                <table class="table-bordered">
                                    <thead></thead>
                                    <tbody>
                                        <tr>
                                            <td>first</td>
                                            <td><i>String</i> Default: <code>NULL</code>, link to the first page</td>
                                        </tr>
                                        <tr>
                                            <td>last</td>
                                            <td><i>String</i> Default: <code>NULL</code>, link to the last page</td>
                                        </tr>
                                        <tr>
                                            <td>prev</td>
                                            <td><i>String</i> Default: <code>NULL</code>, link to the previous page</td>
                                        </tr>
                                        <tr>
                                            <td>next</td>
                                            <td><i>String</i> Default: <code>NULL</code>, link to the next page</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td
                            data-toggle="collapse"
                            data-target="#get-all-c-meta"
                            aria-expanded="false"
                            aria-controls="get-all-c-meta"
                            style="cursor: pointer;  "
                            class="glyphicon1 all-c-meta"
                            rel="all-c-meta"
                        >
                            <span class="all-c-meta">
                               meta <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                            </span>
                        </td>
                        <td>object</td>
                    </tr>
                    <tr  class="collapse" id="get-all-c-meta">
                        <td colspan="2">
                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                <table class="table-bordered">
                                    <thead></thead>
                                    <tbody>
                                        <tr>
                                            <td>current_page</td>
                                            <td><i>String</i> Default: <code>NULL</code></td>
                                        </tr>
                                        <tr>
                                            <td>from</td>
                                            <td><i>String</i> Default: <code>NULL</code></td>
                                        </tr>
                                        <tr>
                                            <td>last_page</td>
                                            <td><i>String</i> Default: <code>NULL</code></td>
                                        </tr>
                                        <tr>
                                            <td>path</td>
                                            <td><i>String</i> Default: <code>NULL</code></td>
                                        </tr>
                                        <tr>
                                            <td>per_page</td>
                                            <td><i>String</i> Default: <code>NULL</code></td>
                                        </tr>
                                        <tr>
                                            <td>to</td>
                                            <td><i>String</i> Default: <code>NULL</code></td>
                                        </tr>
                                        <tr>
                                            <td>total</td>
                                            <td><i>String</i> Default: <code>NULL</code></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <p>
            {{ EndPointHelper::userGuide() }}
        </p>
    </div><!-- End Customer list resource -->

    <div class="col-sm-12" id="customer-specified"><!-- customer specified resource -->
        <h3>{{ EndPointHelper::get('Customer') }}</h3>
        <p>
            {{ EndPointHelper::getDescription("Customer", 'id')}}
        </p>
        <br>
        <div id="carrier-type-specified" class="" style="position:relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-customer-id" aria-hidden="true">
                <span
                    data-toggle="collapse"
                    data-target="#route-customer-id"
                    aria-expanded="false"
                    aria-controls="route-customer-id"
                    style="cursor: pointer"
                    class="i-customer-id"
                > <code>api/customer/{id}</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true">
                    </span>
                </span>
            </span>
            <div class="collapse api-route" id="route-customer-id">
                <div class="badge-success customer-id hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="customer-id">Copy</div>
                </div>
                <div class="api_ noselect" id="customer-id">
                    {{ config('app.url')}}/api/customer/{id}
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Customer (Specific Customer) </code> <small> </small><br>
        </div>
        <h4>Header</h4>
        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"Authorization"</span>: <span class="slctrl-string">"Bearer {access_token}"</span>
    <span class="slctrl-attr">"Content-Type"</span>: <span class="slctrl-string"> "application/json" </span>
    <span class="slctrl-attr">"Accept"</span>: <span class="slctrl-string"> "application/json" </span>
} </pre>
        <h4> Form Data </h4>
        <pre>
{
    <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">{id}</span>,
} </pre>
        <div>
            <div>
                <h4 style="display: inline-block">REQUEST</h4>
            </div>
            <table class="table-bordered">
                <thead>
                <tr>
                    <th colspan="2">Body Parameters</th>
                </tr>
                <tr>
                    <th>Attribute</th>
                    <th>Description</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>id_id<span class="badge-danger">required</span></td>
                    <td><i>Integer</i> The unique id for the Customer to be retrieved</td>
                </tr>
                </tbody>
            </table>
        </div>
        <h4>Response
            <span
                data-toggle="collapse"
                data-target="#customer-id-response"
                aria-expanded="false"
                aria-controls="customer-id-response"
                style="cursor: pointer; top: 2px !important;"
                class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre class="collapse" id="customer-id-response">
{
    <span class="slctrl-attr">"data"</span>: {
        <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"company_name"</span>: <span class="slctrl-string">"Goldner-Kunze"</span>,
        <span class="slctrl-attr">"address"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"phone"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2020-01-07T22:54:10.000000Z"</span>,
        <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2021-09-08T22:24:34.000000Z"</span>,
        <span class="slctrl-attr">"managers"</span>: <span class="slctrl-number">119</span>,
        <span class="slctrl-attr">"sale_persons"</span>: <span class="slctrl-number">0</span>
    }
} </pre>
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
                        <td
                            data-toggle="collapse"
                            data-target="#get-c-data"
                            aria-expanded="false"
                            aria-controls="get-c-data"
                            style="cursor: pointer;  "
                            class="glyphicon1 c-data"
                            rel="c-results"
                        >
                            <span class="c-data">
                               data <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                            </span>
                        </td>
                        <td>object</td>
                    </tr>
                    <tr  class="collapse" id="get-c-data">
                        <td colspan="2">
                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                <table class="table-bordered">
                                    <thead></thead>
                                    <tbody>
                                        <tr>
                                            <td>id</td>
                                            <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                        </tr>
                                        <tr>
                                            <td>company_name</td>
                                            <td><i>Integer</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>address</td>
                                            <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                        </tr>
                                        <tr>
                                            <td>phone</td>
                                            <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                        </tr>
                                        <tr>
                                            <td>created_at</td>
                                            <td><i>Integer</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>updated_at</td>
                                            <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                        </tr>
                                        <tr>
                                            <td>managers</td>
                                            <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                        </tr>
                                        <tr>
                                            <td>sale_persons</td>
                                            <td><i>Integer</i> Default: <code>NULL</code>, Foreign key sales person</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
                    <p>
            {{ EndPointHelper::userGuide() }}
        </p>
    </div><!-- End Customer specified resource -->
</div> <!-- end of Customers -->

