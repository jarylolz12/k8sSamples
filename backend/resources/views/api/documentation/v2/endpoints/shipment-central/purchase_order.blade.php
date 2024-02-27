<!-- Purchase Order -->
<div class="col-sm-12" id="purchase-order">
    <h3 class="page-header">{{ EndPointHelper::endPoint('Purchase Order') }}</h3>
    <!-- List resource -->
    <div class="col-sm-12" id="purchase-order-list">
        <h3>{{ EndPointHelper::getAll('Purchase Order') }}</h3>
        <p>
            {{ EndPointHelper::getAllDescription('Purchase Order') }}
        </p>
        <br>
        <div class="" style="position:relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-purchase-orders" aria-hidden="true">
                <span
                        data-toggle="collapse"
                        data-target="#route-purchase-orders"
                        aria-expanded="false"
                        aria-controls="route-purchase-orders"
                        style="cursor: pointer"
                        class="i-purchase-orders"
                > <code>api/purchase-orders</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                </span>
            </span>
            <div class="collapse api-route" id="route-purchase-orders">
                <div class="badge-success purchase-orders hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="purchase-orders">Copy</div>
                </div>

                <div class="api_ noselect" id="purchase-orders">
                    {{ config('app.url')}}/api/purchase-orders
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>per_page</code>, <code>page</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>List of purchase order</code> <small> </small><br>
        </div>
        <h4>Header</h4>
        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"Authorization"</span>: <span class="slctrl-string">"Bearer {access_token}"</span>
    <span class="slctrl-attr">"Content-Type"</span>: <span class="slctrl-string"> "application/json" </span>
    <span class="slctrl-attr">"Accept"</span>: <span class="slctrl-string"> "application/json" </span>
} </pre>
        <h4> Form Data </h4>
        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"per_page"</span>: <span class="slctrl-number">5</span>,
    <span class="slctrl-attr">"page"</span>: <span class="slctrl-number">1</span>,
}
</pre>
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
                        <td>per_page<span class="badge-danger">required</span></td>
                        <td><i>Integer</i> Count of items in each page. Defaults to 5. Maximum allowed value is 60.</td>
                    </tr>
                    <tr>
                        <td>page<span class="badge-danger">required</span></td>
                        <td><i>Integer</i> Page number of the page to retrieve. Defaults to page 1.</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <h4>Response
            <span
                data-toggle="collapse"
                data-target="#purchase-orders-response"
                aria-expanded="false"
                aria-controls="purchase-orders-response"
                style="cursor: pointer; top: 2px !important;"
                class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre style="font-weight:bold" class="collapse" id="purchase-orders-response">
{
    <span class="slctrl-attr">"data"</span>: [
        {
            <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">1</span>,
            <span class="slctrl-attr">"customer_id"</span>: <span class="slctrl-number">1</span>,
            <span class="slctrl-attr">"po_num"</span>: <span class="slctrl-string">"1"</span> ,
            <span class="slctrl-attr">"supplier_id"</span>: <span class="slctrl-number">11</span>,
            <span class="slctrl-attr">"po_items"</span>: <span class="slctrl-string">"[]"</span>,
            <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2022-01-14T15:04:33.000000Z"</span>,
            <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2022-01-14T15:04:33.000000Z"</span>
        },
        {...}, ...
    ],
    <span class="slctrl-attr">"links"</span>: {
        <span class="slctrl-attr">"first"</span>: <span class="slctrl-string">"api/purchase-orders?page=1"</span>,
        <span class="slctrl-attr">"last"</span>: <span class="slctrl-string">"api/purchase-orders?page=1"</span>,
        <span class="slctrl-attr">"prev"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"next"</span>: <span class="slctrl-literal">null</span>
    },
    <span class="slctrl-attr">"meta"</span>: {
        <span class="slctrl-attr">"current_page"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"from"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"last_page"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"path"</span>: <span class="slctrl-string">"api/purchase-orders"</span>,
        <span class="slctrl-attr">"per_page"</span>: <span class="slctrl-number">5</span>,
        <span class="slctrl-attr">"to"</span>: <span class="slctrl-number">5</span>,
        <span class="slctrl-attr">"total</span>: <span class="slctrl-number">5</span>
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
                                data-target="#get-all-purchase-order-data"
                                aria-expanded="false"
                                aria-controls="get-all-purchase-order-data"
                                style="cursor: pointer;  "
                                class="glyphicon1 all-purchase-order-data"
                                rel="all-purchase-order-data"
                            >
                                <span class="all-purchase-order-data">
                                   data <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                </span>
                            </td>
                            <td>object</td>
                        </tr>
                        <tr  class="collapse" id="get-all-purchase-order-data">
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
                                            <td>customer_id</td>
                                            <td><i>Integer</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>po_num</td>
                                            <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                        </tr>
                                        <tr>
                                            <td>supplier_id</td>
                                            <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                        </tr>
                                        <tr>
                                            <td>po_items</td>
                                            <td><i>Object</i></td>
                                        </tr>
                                        <tr>
                                            <td>created_at</td>
                                            <td><i>Timestamp</i> Default: <code>NULL</code>,</td>
                                        </tr>
                                        <tr>
                                            <td>updated_at</td>
                                            <td><i>Timestamp</i> Default: <code>NULL</code>,</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td
                                data-toggle="collapse"
                                data-target="#get-all-purchase-order-links"
                                aria-expanded="false"
                                aria-controls="get-all-purchase-order-links"
                                style="cursor: pointer;  "
                                class="glyphicon1 all-purchase-order-links"
                                rel="all-purchase-order-links"
                            >
                                <span class="all-purchase-order-links">
                                   links <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                </span>
                            </td>
                            <td>object</td>
                        </tr>
                        <tr  class="collapse" id="get-all-purchase-order-links">
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
                                data-target="#get-all-purchase-order-meta"
                                aria-expanded="false"
                                aria-controls="get-all-purchase-order-meta"
                                style="cursor: pointer;  "

                                class="glyphicon1 icn-all-purchase-order-meta"
                                rel="icn-all-purchase-order-meta">
                                    <span class="icn-all-purchase-order-meta">
                                       meta <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                    </span>
                            </td>
                            <td>object</td>
                        </tr>
                        <tr  class="collapse" id="get-all-purchase-order-meta">
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
    </div><!-- End List resource -->

    <!-- Specified resource -->
    <div class="col-sm-12" id="purchase-order-specified">
        <h3>{{ EndPointHelper::get('Purchase Order') }}</h3>
        <p>
            {{ EndPointHelper::getDescription('Purchase Order', 'id') }}
        </p>
        <br>
        <div class="" style="position:relative;padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-purchase-orders-id" aria-hidden="true">
                <span
                    data-toggle="collapse"
                    data-target="#route-purchase-orders-id"
                    aria-expanded="false"
                    aria-controls="route-purchase-orders-id"
                    style="cursor: pointer"
                    class="i-purchase-orders-id"
                > <code>api/purchase-order/{id}</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                </span>
            </span>

            <div class="collapse api-route" id="route-purchase-orders-id">
                <div class="badge-success purchase-orders-id hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="purchase-orders-id">Copy</div>
                </div>
                <div class="api_ noselect" id="purchase-orders-id">
                    {{ config('app.url')}}/api/purchase-order/{id}
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Specific resources</code> <small> </small><br>
        </div>
        <h4>Header</h4>
        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"Authorization"</span>: <span class="slctrl-string">"Bearer {access_token}"</span>
    <span class="slctrl-attr">"Content-Type"</span>: <span class="slctrl-string"> "application/json" </span>
    <span class="slctrl-attr">"Accept"</span>: <span class="slctrl-string"> "application/json" </span>
} </pre>
        <h4> Form Data </h4>
        <pre style="font-weight:bold">
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
                    <td>id<span class="badge-danger">required</span></td>
                    <td><i>Integer</i> The unique id for the Purchase Order to be retrieved</td>
                </tr>
                </tbody>
            </table>
        </div>
        <h4>Response
            <span
                data-toggle="collapse"
                data-target="#purchase-orders-id-response"
                aria-expanded="false"
                aria-controls="purchase-orders-id-response"
                style="cursor: pointer; top: 2px !important;"
                class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre style="font-weight:bold" class="collapse" id="purchase-orders-id-response">
{
    <span class="slctrl-attr">"data"</span>: {
        <span class="slctrl-attr">"id"</span>: <span class="slctrl-string">1</span>,
        <span class="slctrl-attr">"customer_id"</span>: <span class="slctrl-string">1</span>,
        <span class="slctrl-attr">"po_num"</span>: <span class="slctrl-number">"1"</span>,
        <span class="slctrl-attr">"supplier_id"</span>: <span class="slctrl-number">11</span>,
        <span class="slctrl-attr">"po_items"</span>: <span class="slctrl-string">"[]"</span>,
        <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2022-01-14T15:04:33.000000Z"</span>,
        <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2022-01-14T15:04:33.000000Z"</span>
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
                            data-target="#get-purchase-order-data"
                            aria-expanded="false"
                            aria-controls="get-purchase-order-data"
                            style="cursor: pointer;  "

                            class="glyphicon1 purchase-order-data"
                            rel="purchase-order-results"
                        >
                            <span class="purchase-order-data">
                               data <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                            </span>
                        </td>
                        <td>object</td>
                    </tr>
                    <tr  class="collapse" id="get-purchase-order-data">
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
                                            <td>customer_id</td>
                                            <td><i>Integer</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>po_num</td>
                                            <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                        </tr>
                                        <tr>
                                            <td>supplier_id</td>
                                            <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                        </tr>
                                        <tr>
                                            <td>po_items</td>
                                            <td><i>Object</i></td>
                                        </tr>
                                        <tr>
                                            <td>created_at</td>
                                            <td><i>Timestamp</i> Default: <code>NULL</code>,</td>
                                        </tr>
                                        <tr>
                                            <td>updated_at</td>
                                            <td><i>Timestamp</i> Default: <code>NULL</code>,</td>
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
    </div><!-- End specified resource -->
</div> <!-- End Purchase Order -->

