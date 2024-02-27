<!-- Central PO Purchase Order by ID-->
<div class="col-sm-12" id="central-po-purchase-order">
    <h3 class="page-header">{{ EndPointHelper::endPoint('PO Purchase Order') }}</h3>

    <!-- Specified resource -->
    <div class="col-sm-12" id="get-central-po-purchase-order-by-id">
        <h3>{{ EndPointHelper::get('Purchase Order by ID') }}</h3>
        <p>
            {{ EndPointHelper::getDescription('Purchase Order by ID', 'id') }}
        </p>
        <br>
        <div class="" style="position:relative;padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-get-central-po-purchase-order" aria-hidden="true">
                <span
                        data-toggle="collapse"
                        data-target="#route-get-central-po-purchase-order"
                        aria-expanded="false"
                        aria-controls="route-get-central-po-purchase-order"
                        style="cursor: pointer"
                        class="i-get-central-po-purchase-order"
                > <code>api/purchaseorders/{id}</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                </span>
            </span>

            <div class="collapse api-route" id="route-get-central-po-purchase-order">
                <div class="badge-success get-central-po-purchase-order hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="get-central-po-purchase-order">Copy</div>
                </div>
                <div class="api_ noselect" id="get-central-po-purchase-order">
                    {{ getenv('PO_URL')}}/api/purchaseorders/{id}
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Specific Purchase Order by ID</code> <small> </small><br>
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
                    <td><i>Integer</i> The unique id for the Purchase Order by ID to be retrieved</td>
                </tr>
                </tbody>
            </table>
        </div>
        <h4>Response
            <span
                    data-toggle="collapse"
                    data-target="#get-central-po-purchase-order-response"
                    aria-expanded="false"
                    aria-controls="get-central-po-purchase-order-response"
                    style="cursor: pointer; top: 2px !important;"
                    class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre style="font-weight:bold" class="collapse" id="get-central-po-purchase-order-response">
{
    <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">142</span>,
    <span class="slctrl-attr">"po_number"</span>: <span class="slctrl-string">"926260"</span>,
    <span class="slctrl-attr">"supplier_id"</span>: <span class="slctrl-number">1908</span>,
    <span class="slctrl-attr">"customer_id"</span>: <span class="slctrl-number">25</span>,
    <span class="slctrl-attr">"notes"</span>: <span class="slctrl-string">"This is a test create PO only"</span>,
    <span class="slctrl-attr">"created_by"</span>: <span class="slctrl-number">11</span>,
    <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2021-12-20T22:07:32.000000Z"</span>,
    <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2022-01-07T20:43:39.000000Z"</span>,
    <span class="slctrl-attr">"warehouse_id"</span>: <span class="slctrl-number">60</span>,
    <span class="slctrl-attr">"is_system_generated"</span>: <span class="slctrl-number">1</span>,
    <span class="slctrl-attr">"terms"</span>: <span class="slctrl-literal">null</span>,
    <span class="slctrl-attr">"due_by"</span>: <span class="slctrl-literal">null</span>,
    <span class="slctrl-attr">"ship_via"</span>: <span class="slctrl-literal">null</span>,
    <span class="slctrl-attr">"status"</span>: <span class="slctrl-string">"pending"</span>,
    <span class="slctrl-attr">"sub_total"</span>: <span class="slctrl-number">720</span>,
    <span class="slctrl-attr">"tax"</span>: <span class="slctrl-number">0</span>,
    <span class="slctrl-attr">"shipping"</span>: <span class="slctrl-number">0</span>,
    <span class="slctrl-attr">"discount"</span>: <span class="slctrl-number">0</span>,
    <span class="slctrl-attr">"total"</span>: <span class="slctrl-number">720</span>,
    <span class="slctrl-attr">"cargo_ready_date"</span>: <span class="slctrl-literal">null</span>,
    <span class="slctrl-attr">"packing_method"</span>: <span class="slctrl-literal">null</span>,
    <span class="slctrl-attr">"payment_term"</span>: <span class="slctrl-literal">null</span>,
    <span class="slctrl-attr">"pdf"</span>: <span class="slctrl-string">"public/purchase-order/po_142_1641559419.pdf"</span>,
    <span class="slctrl-attr">"ship_to"</span>: <span class="slctrl-string">"Test Only, Door 123 Qwerty Appartment"</span>,
    <span class="slctrl-attr">"total_products"</span>: <span class="slctrl-number">1</span>,
    <span class="slctrl-attr">"total_quantity"</span>: <span class="slctrl-number">10</span>,
    <span class="slctrl-attr">"total_amount"</span>: <span class="slctrl-number">720</span>,
    <span class="slctrl-attr">"shipments"</span>: [],
    <span class="slctrl-attr">"purchase_order_products"</span>: [
        {
            <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">177</span>,
            <span class="slctrl-attr">"purchase_order_id"</span>: <span class="slctrl-number">142</span>,
            <span class="slctrl-attr">"product_id"</span>: <span class="slctrl-number">262</span>,
            <span class="slctrl-attr">"customer_id"</span>: <span class="slctrl-number">25</span>,
            <span class="slctrl-attr">"quantity"</span>: <span class="slctrl-number">10</span>,
            <span class="slctrl-attr">"unit_price"</span>: <span class="slctrl-number">15</span>,
            <span class="slctrl-attr">"amount"</span>: <span class="slctrl-number">720</span>,
            <span class="slctrl-attr">"deleted_at"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2021-12-20T22:07:32.000000Z"</span>,
            <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2021-12-20T22:07:32.000000Z"</span>,
            <span class="slctrl-attr">"sku"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"classification_code"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"duty_rate"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"units_per_carton"</span>: <span class="slctrl-number">5</span>,
            <span class="slctrl-attr">"units"</span>: <span class="slctrl-number">48</span>,
            <span class="slctrl-attr">"unship_cartons"</span>: <span class="slctrl-number">10</span>,
            <span class="slctrl-attr">"product"</span>: {
                <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">262</span>,
                <span class="slctrl-attr">"sku"</span>: <span class="slctrl-string">"727714"</span>,
                <span class="slctrl-attr">"name"</span>: <span class="slctrl-string">"Phone test"</span>,
                <span class="slctrl-attr">"category_id"</span>: <span class="slctrl-number">122</span>,
                <span class="slctrl-attr">"description"</span>: <span class="slctrl-string">"Testing this one"</span>,
                <span class="slctrl-attr">"units_per_carton"</span>: <span class="slctrl-number">5</span>,
                <span class="slctrl-attr">"image"</span>: <span class="slctrl-string">"/storage/products/images/20QY2SxlboZka1bVW41A7CDayXgVzNYIbsiHORMQ.jpg"</span>,
                <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2021-10-27T19:27:32.000000Z"</span>,
                <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2021-10-27T19:27:45.000000Z"</span>,
                <span class="slctrl-attr">"customer_id"</span>: <span class="slctrl-number">25</span>,
                <span class="slctrl-attr">"created_by"</span>: <span class="slctrl-number">206</span>,
                <span class="slctrl-attr">"deleted_at"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"is_system_generated"</span>: <span class="slctrl-number">1</span>,
                <span class="slctrl-attr">"customer_admins"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"unit_price"</span>: <span class="slctrl-number">1500</span>,
                <span class="slctrl-attr">"classification_code"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"duty_rate"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"carton_dimensions"</span>: <span class="slctrl-string">"{\"length\":0,\"width\":0,\"height\":0,\"uom\":\"cm\"}"</span>,
                <span class="slctrl-attr">"is_for_classification_code"</span>: <span class="slctrl-number">0</span>,
                <span class="slctrl-attr">"upc_number"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"country_from"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"country_to"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"product_classification_description"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"unit_weight"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"unit_dimensions"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"carton_upc"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"additional_classification_code"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"category_sku"</span>: <span class="slctrl-string">"122-727714"</span>,
                <span class="slctrl-attr">"inbound_associated"</span>: <span class="slctrl-literal">false</span>
            }
        }
    ]
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
                                data-target="#get-central-po-purchase-order-data"
                                aria-expanded="false"
                                aria-controls="get-central-po-purchase-order-data"
                                style="cursor: pointer;  "
                                class="glyphicon1 get-central-po-purchase-order-data"
                                rel="get-central-po-purchase-order-data"
                        >
                            <span class="get-central-po-purchase-order-data">
                               data <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                            </span>
                        </td>
                        <td>object</td>
                    </tr>
                    <tr  class="collapse" id="get-central-po-purchase-order-data">
                        <td colspan="2">
                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                <table class="table-bordered">
                                    <thead></thead>
                                    <tbody>
                                    <tr>
                                        <td>id</td>
                                        <td><i>Integer</i> Default: <code>Auto Increment</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>po_number</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>, </td>
                                    </tr>
                                    <tr>
                                        <td>supplier_id</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>customer_id</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>notes</td>
                                        <td><i>String</i></td>
                                    </tr>
                                    <tr>
                                        <td>created_by</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>created_at</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>updated_at</td>
                                        <td><i>Timestamp</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>warehouse_id</td>
                                        <td><i>Timestamp</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>is_system_generated</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>terms</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>due_by</td>
                                        <td><i>Timestamp</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>ship_via</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>status</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>sub_total</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>tax</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>shipping</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>discount</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>total</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>cargo_ready_date</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>packing_method</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>payment_term</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>pdf</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>ship_to</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>total_products</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>total_quantity</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>total_amount</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>shipments</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td
                                                data-toggle="collapse"
                                                data-target="#get-central-po-purchase-order-pop"
                                                aria-expanded="false"
                                                aria-controls="get-central-po-purchase-order-pop"
                                                style="cursor: pointer;  "
                                                class="glyphicon1 all-ictrm-data"
                                                rel="c-get-central-po-purchase-order-pop"
                                        >
                                            <span class="c-get-central-po-purchase-order-pop">
                                               purchase_order_products <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                            </span>
                                        </td>
                                        <td>object</td>
                                    </tr>


                                    <tr  class="collapse" id="get-central-po-purchase-order-pop">
                                        <td colspan="2">
                                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                <table class="table-bordered">
                                                    <thead></thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>id</td>
                                                        <td><i>Int</i> Default: <code>Auto Increment</code></td>
                                                    </tr>
                                                    <tr>
                                                        <td>purchase_order_id</td>
                                                        <td><i>Int</i> Default: <code>NULL</code></td>
                                                    </tr>
                                                    <tr>
                                                        <td>product_id</td>
                                                        <td><i>Int</i> Default: <code>NULL</code></td>
                                                    </tr>
                                                    <tr>
                                                        <td>customer_id</td>
                                                        <td><i>Int</i> Default: <code>NULL</code></td>
                                                    </tr>
                                                    <tr>
                                                        <td>quantity</td>
                                                        <td><i>Int</i> Default: <code>NULL</code></td>
                                                    </tr>
                                                    <tr>
                                                        <td>unit_price</td>
                                                        <td><i>Int</i> Default: <code>NULL</code></td>
                                                    </tr>
                                                    <tr>
                                                        <td>amount</td>
                                                        <td><i>Int</i> Default: <code>NULL</code></td>
                                                    </tr>
                                                    <tr>
                                                        <td>deleted_at</td>
                                                        <td><i>Int</i> Default: <code>NULL</code></td>
                                                    </tr>
                                                    <tr>
                                                        <td>created_at</td>
                                                        <td><i>Int</i> Default: <code>NULL</code></td>
                                                    </tr>
                                                    <tr>
                                                        <td>updated_at</td>
                                                        <td><i>Int</i> Default: <code>NULL</code></td>
                                                    </tr>
                                                    <tr>
                                                        <td>sku</td>
                                                        <td><i>Int</i> Default: <code>NULL</code></td>
                                                    </tr>
                                                    <tr>
                                                        <td>classification_code</td>
                                                        <td><i>Int</i> Default: <code>NULL</code></td>
                                                    </tr>
                                                    <tr>
                                                        <td>duty_rate</td>
                                                        <td><i>Int</i> Default: <code>NULL</code></td>
                                                    </tr>
                                                    <tr>
                                                        <td>units_per_carton</td>
                                                        <td><i>Int</i> Default: <code>NULL</code></td>
                                                    </tr>
                                                    <tr>
                                                        <td>units</td>
                                                        <td><i>Int</i> Default: <code>NULL</code></td>
                                                    </tr>
                                                    <tr>
                                                        <td>unship_cartons</td>
                                                        <td><i>Int</i> Default: <code>NULL</code></td>
                                                    </tr>


                                                    <tr>
                                                        <td
                                                                data-toggle="collapse"
                                                                data-target="#get-central-po-purchase-order-product"
                                                                aria-expanded="false"
                                                                aria-controls="get-central-po-purchase-order-product"
                                                                style="cursor: pointer;  "
                                                                class="glyphicon1 c-get-central-po-purchase-order-product"
                                                                rel="c-get-central-po-purchase-order-product"
                                                        >
                                                        <span class="c-get-central-po-purchase-order-product">
                                                           product <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                                        </span>
                                                        </td>
                                                        <td>object</td>
                                                    </tr>

                                                    <tr  class="collapse" id="get-central-po-purchase-order-product">
                                                        <td colspan="2">
                                                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                                <table class="table-bordered">
                                                                    <thead></thead>
                                                                    <tbody>
                                                                    <tr>
                                                                        <td>id</td>
                                                                        <td><i>Integer</i> Default: <code>Auto Increment</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>sku</td>
                                                                        <td><i>Integer</i> Default: <code>NULL</code>, </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>name</td>
                                                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>category_id</td>
                                                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>description</td>
                                                                        <td><i>String</i></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>units_per_carton</td>
                                                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>image</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>created_at</td>
                                                                        <td><i>Timestamp</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>updated_at</td>
                                                                        <td><i>Timestamp</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>customer_id</td>
                                                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>created_by</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>deleted_at</td>
                                                                        <td><i>Timestamp</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>is_system_generated</td>
                                                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>customer_admins</td>
                                                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>unit_price</td>
                                                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>classification_code</td>
                                                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>duty_rate</td>
                                                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>carton_dimensions</td>
                                                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>is_for_classification_code</td>
                                                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>upc_number</td>
                                                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>country_from</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>country_to</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>product_classification_description</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>unit_weight</td>
                                                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>unit_dimensions</td>
                                                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>carton_upc</td>
                                                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>additional_classification_code</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>category_sku</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>inbound_associated</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>



                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
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




    <!-- Create resource -->
    <div class="col-sm-12" id="create-po-purchase-order-sd">
        <h3>{{ EndPointHelper::create('Purchase Order') }}</h3>
        <p>
            {{ EndPointHelper::createDescription('Purchase Order',

            array('sys_gen', 'po_number', 'supplier_id', 'notes',
            'warehouse_id', 'cargo_ready_date', 'packing_method', 'ship_via',
            'terms', 'payment_term', 'sub_total', 'tax',
            'ship_to', 'shipping', 'discount', 'total',
            'products.id', 'products.quantity', 'products.units', 'products.unit_price', 'products.amount'
            )) }}
        </p>
        <br>
        <div class="" style="position:relative;padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-create-po-purchase-order" aria-hidden="true">
                <span
                        data-toggle="collapse"
                        data-target="#route-create-po-purchase-order"
                        aria-expanded="false"
                        aria-controls="route-create-po-purchase-order"
                        style="cursor: pointer"
                        class="i-create-po-purchase-order"
                > <code>api/purchaseorders/create</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                </span>
            </span>

            <div class="collapse api-route" id="route-create-po-purchase-order">
                <div class="badge-success create-po-purchase-order hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="create-po-purchase-order">Copy</div>
                </div>
                <div class="api_ noselect" id="create-po-purchase-order">
                    {{ getenv('PO_URL')}}api/purchaseorders/create
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>sys_gen</code>,
            <code>po_number</code>,
            <code>supplier_id</code>,
            <code>notes</code>,
            <code>warehouse_id</code>,
            <code>cargo_ready_date</code>,
            <code>packing_method</code>,
            <code>ship_via</code>,
            <code>terms</code>,
            <code>payment_term</code>,
            <code>sub_total</code>,
            <code>tax</code>,
            <code>ship_to</code>,
            <code>shipping</code>,
            <code>discount</code>,
            <code>total</code>,
            <code>products.id</code>,
            <code>products.quantity</code>,
            <code>products.units</code>,
            <code>products.unit_price</code> and
            <code>products.amount</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Create PO Purchase Order</code> <small> </small><br>
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
    <span class="slctrl-attr">sys_gen</span>: <span class="slctrl-string">"{sys_gen}"</span>,
    <span class="slctrl-attr">po_number</span>: <span class="slctrl-string">"{po_number}"</span>,
    <span class="slctrl-attr">supplier_id</span>: <span class="slctrl-number">{supplier_id}</span>,
    <span class="slctrl-attr">notes</span>: <span class="slctrl-string">"{notes}"</span>,
    <span class="slctrl-attr">warehouse_id</span>: <span class="slctrl-number">{warehouse_id}</span>,
    <span class="slctrl-attr">cargo_ready_date</span>: <span class="slctrl-string">"{cargo_ready_date}"</span>,
    <span class="slctrl-attr">packing_method</span>: <span class="slctrl-string">"{packing_method}"</span>,
    <span class="slctrl-attr">ship_via</span>: <span class="slctrl-string">"{ship_via}"</span>,
    <span class="slctrl-attr">terms</span>: <span class="slctrl-string">"{terms}"</span>,
    <span class="slctrl-attr">payment_term</span>: <span class="slctrl-string">"{payment_term}"</span>,
    <span class="slctrl-attr">sub_total</span>: <span class="slctrl-string">"{sub_total}"</span>,
    <span class="slctrl-attr">tax</span>: <span class="slctrl-string">"{tax}"</span>,
    <span class="slctrl-attr">ship_to</span>: <span class="slctrl-string">"{ship_to}"</span>,
    <span class="slctrl-attr">shipping</span>: <span class="slctrl-string">"{shipping}"</span>,
    <span class="slctrl-attr">discount</span>: <span class="slctrl-string">"{discount}"</span>,
    <span class="slctrl-attr">total</span>: <span class="slctrl-string">"{total}"</span>,
    <span class="slctrl-attr">products.id</span>: <span class="slctrl-number">{products.id}</span>,
    <span class="slctrl-attr">products.quantity</span>: <span class="slctrl-string">"{products.quantity}"</span>,
    <span class="slctrl-attr">products.units</span>: <span class="slctrl-string">"{products.units}"</span>,
    <span class="slctrl-attr">products.unit_price</span>: <span class="slctrl-string">"{products.unit_price}"</span>,
    <span class="slctrl-attr">products.amount</span>: <span class="slctrl-string">"{products.amount}"</span>
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
                    <td>sys_gen<span class="badge-danger">required</span></td>
                    <td><i>String</i> sys_gen of PO purchase order</td>
                </tr>
                <tr>
                    <td>po_number<span class="badge-danger">required</span></td>
                    <td><i>String</i> PO number of PO purchase order</td>
                </tr>
                <tr>
                    <td>supplier_id<span class="badge-danger">required</span></td>
                    <td><i>Integer</i> Supplier foreign key of PO purchase order</td>
                </tr>
                <tr>
                    <td>notes<span class="badge-danger">required</span></td>
                    <td><i>String</i> Notes of PO purchase order</td>
                </tr>
                <tr>
                    <td>warehouse_id<span class="badge-danger">required</span></td>
                    <td><i>Integer</i> Warehouse foreign key of PO purchase order</td>
                </tr>
                <tr>
                    <td>cargo_ready_date<span class="badge-danger">required</span></td>
                    <td><i>String</i> Cargo ready date of PO purchase order</td>
                </tr>
                <tr>
                    <td>packing_method<span class="badge-danger">required</span></td>
                    <td><i>String</i> Packing method of PO purchase order</td>
                </tr>
                <tr>
                    <td>ship_via<span class="badge-danger">required</span></td>
                    <td><i>String</i> Shipping via {category} of PO purchase order</td>
                </tr>
                <tr>
                    <td>terms<span class="badge-danger">required</span></td>
                    <td><i>String</i> Terms of PO purchase order</td>
                </tr>
                <tr>
                    <td>payment_term<span class="badge-danger">required</span></td>
                    <td><i>String</i> Payment term of PO purchase order</td>
                </tr>
                <tr>
                    <td>sub_total<span class="badge-danger">required</span></td>
                    <td><i>String</i> Sub total of PO purchase order</td>
                </tr>
                <tr>
                    <td>tax<span class="badge-danger">required</span></td>
                    <td><i>String</i> Tax of PO purchase order</td>
                </tr>
                <tr>
                    <td>ship_to<span class="badge-danger">required</span></td>
                    <td><i>String</i> Shipping to of PO purchase order</td>
                </tr>
                <tr>
                    <td>shipping<span class="badge-danger">required</span></td>
                    <td><i>String</i> Shipping of PO purchase order</td>
                </tr>
                <tr>
                    <td>discount<span class="badge-danger">required</span></td>
                    <td><i>String</i> Discount of PO purchase order</td>
                </tr>
                <tr>
                    <td>total<span class="badge-danger">required</span></td>
                    <td><i>String</i> Total of PO purchase order</td>
                </tr>
                <tr>
                    <td>products.id<span class="badge-danger">required</span></td>
                    <td><i>Integer</i> Product id of PO purchase order</td>
                </tr>
                <tr>
                    <td>products.quantity<span class="badge-danger">required</span></td>
                    <td><i>String</i> Product quantity of PO purchase order</td>
                </tr>
                <tr>
                    <td>products.units<span class="badge-danger">required</span></td>
                    <td><i>String</i> Product units of PO purchase order</td>
                </tr>
                <tr>
                    <td>products.unit_price<span class="badge-danger">required</span></td>
                    <td><i>String</i> Product unit_price of PO purchase order</td>
                </tr>
                <tr>
                    <td>products.amount<span class="badge-danger">required</span></td>
                    <td><i>String</i> Product amount of PO purchase order</td>
                </tr>
                </tbody>
            </table>
        </div>




        <h4>Response
            <span
                    data-toggle="collapse"
                    data-target="#create-po-purchase-order-response"
                    aria-expanded="false"
                    aria-controls="create-po-purchase-order-response"
                    style="cursor: pointer; top: 2px !important;"
                    class="glyphicon glyphicon-eye-open">
            </span>
        </h4>

        <pre style="font-weight:bold" class="collapse" id="create-po-purchase-order-response">
 {
    <span class="slctrl-attr">"message"</span>: <span class="slctrl-string">"Purchase Order has been created."</span>,
    <span class="slctrl-attr">"data"</span>: {
        <span class="slctrl-attr">"po_number"</span>: <span class="slctrl-string">"762121"</span>,
        <span class="slctrl-attr">"supplier_id"</span>: <span class="slctrl-string">"1"</span>,
        <span class="slctrl-attr">"warehouse_id"</span>: <span class="slctrl-string">"64"</span>,
        <span class="slctrl-attr">"notes"</span>: <span class="slctrl-string">"qui"</span>,
        <span class="slctrl-attr">"created_by"</span>: <span class="slctrl-number">2</span>,
        <span class="slctrl-attr">"customer_id"</span>: <span class="slctrl-number">25</span>,
        <span class="slctrl-attr">"is_system_generated"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"ship_via"</span>: <span class="slctrl-string">"nisi"</span>,
        <span class="slctrl-attr">"packing_method"</span>: <span class="slctrl-string">"recusandae"</span>,
        <span class="slctrl-attr">"terms"</span>: <span class="slctrl-string">"ipsum"</span>,
        <span class="slctrl-attr">"payment_term"</span>: <span class="slctrl-string">"ea"</span>,
        <span class="slctrl-attr">"cargo_ready_date"</span>: <span class="slctrl-string">"2022-04-26"</span>,
        <span class="slctrl-attr">"sub_total"</span>: <span class="slctrl-string">"369"</span>,
        <span class="slctrl-attr">"tax"</span>: <span class="slctrl-string">"0"</span>,
        <span class="slctrl-attr">"ship_to"</span>: <span class="slctrl-string">"libero"</span>,
        <span class="slctrl-attr">"shipping"</span>: <span class="slctrl-string">"0"</span>,
        <span class="slctrl-attr">"discount"</span>: <span class="slctrl-string">"0"</span>,
        <span class="slctrl-attr">"total"</span>: <span class="slctrl-string">"450"</span>,
        <span class="slctrl-attr">"status"</span>: <span class="slctrl-string">"pending"</span>,
        <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2022-05-24T11:12:35.000000Z"</span>,
        <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2022-05-24T11:12:35.000000Z"</span>,
        <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">316</span>
    },
    <span class="slctrl-attr">"product_details"</span>: [
        {
            <span class="slctrl-attr">"product_id"</span>: <span class="slctrl-string">"1"</span>,
            <span class="slctrl-attr">"purchase_order_id"</span>: <span class="slctrl-number">316</span>,
            <span class="slctrl-attr">"customer_id"</span>: <span class="slctrl-number">25</span>,
            <span class="slctrl-attr">"quantity"</span>: <span class="slctrl-string">"1"</span>,
            <span class="slctrl-attr">"units"</span>: <span class="slctrl-string">"29"</span>,
            <span class="slctrl-attr">"unit_price"</span>: <span class="slctrl-string">"29"</span>,
            <span class="slctrl-attr">"amount"</span>: <span class="slctrl-string">"29"</span>,
            <span class="slctrl-attr">"classification_code"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"duty_rate"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"units_per_carton"</span>: <span class="slctrl-number">12</span>,
            <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2022-05-24T11:12:35.000000Z"</span>,
            <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2022-05-24T11:12:35.000000Z"</span>,
            <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">357</span>,
            <span class="slctrl-attr">"unship_cartons"</span>:<span class="slctrl-number">1</span>,
            <span class="slctrl-attr">"product"</span>: {
                <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">1</span>,
                <span class="slctrl-attr">"sku"</span>: <span class="slctrl-string">"10001"</span>,
                <span class="slctrl-attr">"name"</span>: <span class="slctrl-string">"Test"</span>,
                <span class="slctrl-attr">"category_id"</span>: <span class="slctrl-number">8</span>,
                <span class="slctrl-attr">"description"</span>: <span class="slctrl-string">"Test"</span>,
                <span class="slctrl-attr">"units_per_carton"</span>: <span class="slctrl-number">12</span>,
                <span class="slctrl-attr">"image"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2021-06-29T18:27:58.000000Z"</span>,
                <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2021-07-01T21:59:51.000000Z"</span>,
                <span class="slctrl-attr">"customer_id"</span>: <span class="slctrl-number">42</span>,
                <span class="slctrl-attr">"created_by"</span>: <span class="slctrl-number">42</span>,
                <span class="slctrl-attr">"deleted_at"</span>: <span class="slctrl-string">"2021-07-01T21:59:51.000000Z"</span>,
                <span class="slctrl-attr">"is_system_generated"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"customer_admins"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"unit_price"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"classification_code"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"duty_rate"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"carton_dimensions"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"is_for_classification_code"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"upc_number"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"country_from"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"country_to"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"product_classification_description"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"unit_weight"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"unit_dimensions"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"carton_upc"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"additional_classification_code"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"category_sku"</span>: <span class="slctrl-string">"8-10001"</span>,
                <span class="slctrl-attr">"inbound_associated"</span>: <span class="slctrl-literal">false</span>
            }
        }
    ]
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
                        <td>message</td>
                        <td> Message response of the created data </td>
                    </tr>
                    <tr>
                        <td
                                data-toggle="collapse"
                                data-target="#create-po-purchase-order-data"
                                aria-expanded="false"
                                aria-controls="create-po-purchase-order-data"
                                style="cursor: pointer;  "
                                class="glyphicon1 create-po-purchase-order-data"
                                rel="create-po-purchase-order-data"
                        >
                            <span class="create-po-purchase-order-data">
                               data <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                            </span>
                        </td>
                        <td>object</td>
                    </tr>
                    <tr  class="collapse" id="create-po-purchase-order-data">
                        <td colspan="2">
                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                <table class="table-bordered">
                                    <thead></thead>
                                    <tbody>
                                    <tr>
                                        <td>po_number</td>
                                        <td><i>String</i> Default: <code>NULL</code>, </td>
                                    </tr>
                                    <tr>
                                        <td>supplier_id</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>warehouse_id</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>created_by</td>
                                        <td><i>String</i></td>
                                    </tr>
                                    <tr>
                                        <td>customer_id</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>is_system_generated</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>ship_via</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>packing_method</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>terms</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>payment_term</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>cargo_ready_date</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>sub_total</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>tax</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>ship_to</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>shipping</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>discount</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>total</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>status</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>updated_at</td>
                                        <td><i>Timestamp</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>created_at</td>
                                        <td><i>Timestamp</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>id</td>
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
                                data-target="#create-po-purchase-order-product_details"
                                aria-expanded="false"
                                aria-controls="create-po-purchase-order-product_details"
                                style="cursor: pointer;  "
                                class="glyphicon1 create-po-purchase-order-product_details"
                                rel="create-po-purchase-order-product_details"
                        >
                            <span class="create-po-purchase-order-product_details">
                               product_details <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                            </span>
                        </td>
                        <td>object</td>
                    </tr>

                    <tr  class="collapse" id="create-po-purchase-order-product_details">
                        <td colspan="2">
                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                <table class="table-bordered">
                                    <thead></thead>
                                    <tbody>
                                    <tr>
                                        <td>product_id</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>, </td>
                                    </tr>
                                    <tr>
                                        <td>purchase_order_id</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>, </td>
                                    </tr>
                                    <tr>
                                        <td>customer_id</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>, </td>
                                    </tr>
                                    <tr>
                                        <td>units</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>, </td>
                                    </tr>
                                    <tr>
                                        <td>unit_price</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>, </td>
                                    </tr>
                                    <tr>
                                        <td>amount</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>, </td>
                                    </tr>
                                    <tr>
                                        <td>classification_code</td>
                                        <td><i>String</i> Default: <code>NULL</code>, </td>
                                    </tr>
                                    <tr>
                                        <td>duty_rate</td>
                                        <td><i>String</i> Default: <code>NULL</code>, </td>
                                    </tr>
                                    <tr>
                                        <td>units_per_carton</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>, </td>
                                    </tr>
                                    <tr>
                                        <td>updated_at</td>
                                        <td><i>Timestamp</i> Default: <code>NULL</code>, </td>
                                    </tr>
                                    <tr>
                                        <td>created_at</td>
                                        <td><i>Timestamp</i> Default: <code>NULL</code>, </td>
                                    </tr>
                                    <tr>
                                        <td>id</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>, </td>
                                    </tr>
                                    <tr>
                                        <td>unship_cartons</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>, </td>
                                    </tr>




                                    <tr>
                                        <td
                                                data-toggle="collapse"
                                                data-target="#create-po-purchase-order-product"
                                                aria-expanded="false"
                                                aria-controls="create-po-purchase-order-product"
                                                style="cursor: pointer;  "
                                                class="glyphicon1 i-create-po-purchase-order-product"
                                                rel="i-create-po-purchase-order-product"
                                        >
                                            <span class="i-create-po-purchase-order-product">
                                               product <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                            </span>
                                        </td>
                                        <td>object</td>
                                    </tr>




                                    <tr  class="collapse" id="create-po-purchase-order-product">
                                        <td colspan="2">
                                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                <table class="table-bordered">
                                                    <thead></thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>id</td>
                                                        <td><i>Integer</i> Default: <code>NULL</code>, </td>
                                                    </tr>
                                                    <tr>
                                                        <td>sku</td>
                                                        <td><i>String</i> Default: <code>NULL</code>, </td>
                                                    </tr>
                                                    <tr>
                                                        <td>name</td>
                                                        <td><i>String</i> Default: <code>NULL</code>, </td>
                                                    </tr>
                                                    <tr>
                                                        <td>category_id</td>
                                                        <td><i>Integer</i> Default: <code>NULL</code>, </td>
                                                    </tr>
                                                    <tr>
                                                        <td>description</td>
                                                        <td><i>String</i> Default: <code>NULL</code>, </td>
                                                    </tr>
                                                    <tr>
                                                        <td>units_per_carton</td>
                                                        <td><i>Integer</i> Default: <code>NULL</code>, </td>
                                                    </tr>
                                                    <tr>
                                                        <td>image</td>
                                                        <td><i>String</i> Default: <code>NULL</code>, </td>
                                                    </tr>
                                                    <tr>
                                                        <td>created_at</td>
                                                        <td><i>Timestamp</i> Default: <code>NULL</code>, </td>
                                                    </tr>
                                                    <tr>
                                                        <td>updated_at</td>
                                                        <td><i>Timestamp</i> Default: <code>NULL</code>, </td>
                                                    </tr>
                                                    <tr>
                                                        <td>customer_id</td>
                                                        <td><i>Integer</i> Default: <code>NULL</code>, </td>
                                                    </tr>
                                                    <tr>
                                                        <td>created_by</td>
                                                        <td><i>Integer</i> Default: <code>NULL</code>, </td>
                                                    </tr>
                                                    <tr>
                                                        <td>deleted_at</td>
                                                        <td><i>Timestamp</i> Default: <code>NULL</code>, </td>
                                                    </tr>
                                                    <tr>
                                                        <td>is_system_generated</td>
                                                        <td><i>String</i> Default: <code>NULL</code>, </td>
                                                    </tr>
                                                    <tr>
                                                        <td>customer_admins</td>
                                                        <td><i>Integer</i> Default: <code>NULL</code>, </td>
                                                    </tr>
                                                    <tr>
                                                        <td>unit_price</td>
                                                        <td><i>String</i> Default: <code>NULL</code>, </td>
                                                    </tr>
                                                    <tr>
                                                        <td>classification_code</td>
                                                        <td><i>String</i> Default: <code>NULL</code>, </td>
                                                    </tr>
                                                    <tr>
                                                        <td>duty_rate</td>
                                                        <td><i>String</i> Default: <code>NULL</code>, </td>
                                                    </tr>
                                                    <tr>
                                                        <td>carton_dimensions</td>
                                                        <td><i>String</i> Default: <code>NULL</code>, </td>
                                                    </tr>
                                                    <tr>
                                                        <td>is_for_classification_code</td>
                                                        <td><i>String</i> Default: <code>NULL</code>, </td>
                                                    </tr>
                                                    <tr>
                                                        <td>upc_number</td>
                                                        <td><i>Integer</i> Default: <code>NULL</code>, </td>
                                                    </tr>
                                                    <tr>
                                                        <td>country_from</td>
                                                        <td><i>String</i> Default: <code>NULL</code>, </td>
                                                    </tr>
                                                    <tr>
                                                        <td>country_to</td>
                                                        <td><i>String</i> Default: <code>NULL</code>, </td>
                                                    </tr>
                                                    <tr>
                                                        <td>unit_weight</td>
                                                        <td><i>String</i> Default: <code>NULL</code>, </td>
                                                    </tr>

                                                    <tr>
                                                        <td>unit_dimensions</td>
                                                        <td><i>String</i> Default: <code>NULL</code>, </td>
                                                    </tr>

                                                    <tr>
                                                        <td>carton_upc</td>
                                                        <td><i>String</i> Default: <code>NULL</code>, </td>
                                                    </tr>

                                                    <tr>
                                                        <td>additional_classification_code</td>
                                                        <td><i>String</i> Default: <code>NULL</code>, </td>
                                                    </tr>
                                                    <tr>
                                                        <td>category_sku</td>
                                                        <td><i>String</i> Default: <code>NULL</code>, </td>
                                                    </tr>
                                                    <tr>
                                                        <td>inbound_associated</td>
                                                        <td><i>Boolean</i> Default: <code>NULL</code>, </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
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
    </div><!-- End create resource -->





















</div> <!-- End Purchase Order -->

