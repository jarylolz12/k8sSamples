<div class="col-sm-12" id="get-purchase-order" > <!-- start Get Purchase Order -->

    <div class="col-sm-12" ><!-- Get Purchase Order -->
        <h3>{{ EndPointHelper::get('Purchase Order') }}</h3>
        <p>
            {{ EndPointHelper::getAllDescription('Purchase Order') }};
        </p>
        <br>
        <div class="" style="position:relative;padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b>
            <span class="glyphicon1" rel="i-get-purchase-order" aria-hidden="true">
                    <span
                            data-toggle="collapse"
                            data-target="#route-get-purchase-order"
                            aria-expanded="false"
                            aria-controls="route-get-purchase-order"
                            style="cursor: pointer"
                            class="i-get-purchase-order"
                    > <code>/api/purchaseorders</code>
                        <span class="glyphicon glyphicon-menu-right " aria-hidden="true">
                        </span>
                    </span>
                </span>
            <div class="collapse api-route" id="route-get-purchase-order">
                <div class="badge-success c-shipment-by-container-number hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">


                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="c-get-purchase-order">Copy</div>
                </div>

                <div class="api_ noselect" id="c-get-purchase-order">
                    {{ config('app.url')}}/api/purchaseorders
                </div>
            </div>  <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>NONE</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Get Purchase Order </code> <small> </small><br>
        </div>

        <h4>Header</h4>

        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"Authorization"</span> : <span class="slctrl-string">"Bearer {access_token}"</span>
    <span class="slctrl-attr">"Content-Type"</span> : <span class="slctrl-string"> "application/json" </span>
    <span class="slctrl-attr">"Accept"</span> : <span class="slctrl-string"> "application/json" </span>
} </pre>


        <h4> Form Data </h4>
        <pre>
{
    <span class="slctrl-attr"> NONE </span>
}</pre>
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
                    <td> NONE </td>
                </tr>
                </tbody>
            </table>
        </div>

        <h4>Response
            <span
                    data-toggle="collapse"
                    data-target="#get-purchase-order-response"
                    aria-expanded="false"
                    aria-controls="get-purchase-order-response"
                    style="cursor: pointer; top: 2px !important;"
                    class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre  style="font-weight:bold" class="collapse" id="get-purchase-order-response">
{
    <span class="slctrl-attr">"results"</span>: {
        <span class="slctrl-attr">"pending"</span>: {
            <span class="slctrl-attr">"current_page"</span>: <span class="slctrl-number">1</span>,
            <span class="slctrl-attr">"data"</span>: [],
            <span class="slctrl-attr">"first_page_url"</span>: <span class="slctrl-string">"{pathUrl}/api/purchaseorders?page=1"</span>,
            <span class="slctrl-attr">"from"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"last_page"</span>: <span class="slctrl-number">1</span>,
            <span class="slctrl-attr">"last_page_url"</span>: <span class="slctrl-string">"{pathUrl}/api/purchaseorders?page=1"</span>,
            <span class="slctrl-attr">"links"</span>: [
                {
                    <span class="slctrl-attr">"url"</span>: <span class="slctrl-literal">"null</span>,
                    <span class="slctrl-attr">"label"</span>: <span class="slctrl-string">""&laquo; Previous"</span>,
                    <span class="slctrl-attr">"active"</span>: <span class="slctrl-literal">"false</span>
                },{...},...
            ],
            <span class="slctrl-attr">"next_page_url"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"path"</span>: <span class="slctrl-string">"{pathUrl}/api/purchaseorders"</span>,
            <span class="slctrl-attr">"per_page"</span>: <span class="slctrl-number">35</span>,
            <span class="slctrl-attr">"prev_page_url"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"to"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"total"</span>: <span class="slctrl-number">0</span>
        },
        <span class="slctrl-attr">"shipped"</span>: {
            <span class="slctrl-attr">"current_page"</span>: <span class="slctrl-number">1</span>,
            <span class="slctrl-attr">"data"</span>: [
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
                    <span class="slctrl-attr">"status"</span>: <span class="slctrl-string">"shipped"</span>,
                    <span class="slctrl-attr">"state"</span>: <span class="slctrl-literal">null</span>,
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
                    <span class="slctrl-attr">"buyer_id"</span>: <span class="slctrl-literal">null</span>,
                    <span class="slctrl-attr">"order_type"</span>: <span class="slctrl-string">"PO"</span>,
                    <span class="slctrl-attr">"total_products"</span>: <span class="slctrl-number">1</span>,
                    <span class="slctrl-attr">"total_quantity"</span>: <span class="slctrl-number">10</span>,
                    <span class="slctrl-attr">"total_units"</span>: <span class="slctrl-number">48</span>,
                    <span class="slctrl-attr">"total_amount"</span>: <span class="slctrl-number">720</span>,
                    <span class="slctrl-attr">"change_log"</span>: [],
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
                            <span class="slctrl-attr">"change_log"</span>: [],
                            <span class="slctrl-attr">"product"</span>: {
                                <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">262</span>,
                                <span class="slctrl-attr">"sku"</span>: <span class="slctrl-string">"727714"</span>,
                                <span class="slctrl-attr">"name"</span>: <span class="slctrl-string">"Phone test"</span>,
                                <span class="slctrl-attr">"category_id"</span>: <span class="slctrl-number">122</span>,
                                <span class="slctrl-attr">"description"</span>: <span class="slctrl-string">"Testing this one"</span>,
                                <span class="slctrl-attr">"units_per_carton"</span>: <span class="slctrl-number">5</span>,
                                <span class="slctrl-attr">"image"</span>: <span class="slctrl-string">"{pathUrl}/storage/products/images/20QY2SxlboZka1bVW41A7CDayXgVzNYIbsiHORMQ.jpg"</span>,
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
                                <span class="slctrl-attr">"preferred_unit_quantity"</span>: <span class="slctrl-number">0</span>,
                                <span class="slctrl-attr">"warehouse_customer_id"</span>: <span class="slctrl-literal">null</span>,
                                <span class="slctrl-attr">"tags"</span>: <span class="slctrl-literal">null</span>,
                                <span class="slctrl-attr">"sub_category_id"</span>: <span class="slctrl-literal">null</span>,
                                <span class="slctrl-attr">"category_sku"</span>: <span class="slctrl-string">"122-727714"</span>,
                                <span class="slctrl-attr">"inbound_associated"</span>: <span class="slctrl-literal">false</span>
                            }
                        }
                    ]
                }
            ],
            <span class="slctrl-attr">"first_page_url"</span>: <span class="slctrl-string">"{pathUrl}/api/purchaseorders?page=1"</span>,
            <span class="slctrl-attr">"from"</span>: <span class="slctrl-number">1</span>,
            <span class="slctrl-attr">"last_page"</span>: <span class="slctrl-number">1</span>,
            <span class="slctrl-attr">"last_page_url"</span>: <span class="slctrl-string">"{pathUrl}/api/purchaseorders?page=1"</span>,
            <span class="slctrl-attr">"links"</span>: [
                {
                    <span class="slctrl-attr">"url"</span>: <span class="slctrl-literal">null</span>,
                    <span class="slctrl-attr">"label"</span>: <span class="slctrl-string">"&laquo; Previous"</span>,
                    <span class="slctrl-attr">"active"</span>: <span class="slctrl-literal">false</span>
                },{...},...
            ],
            <span class="slctrl-attr">"next_page_url"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"path"</span>: <span class="slctrl-string">"{pathUrl}/api/purchaseorders"</span>,
            <span class="slctrl-attr">"per_page"</span>: <span class="slctrl-number">35</span>,
            <span class="slctrl-attr">"prev_page_url"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"to"</span>: <span class="slctrl-number">1</span>,
            <span class="slctrl-attr">"total"</span>: <span class="slctrl-number">1</span>
        }
    }
} </pre>

        <div style="margin-bottom: 10px">
            <div>
                <h4 style="display: inline-block">Responses</h4>
            </div>
            <div>
                <table class="table-bordered" style="width: 50%">
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
                            data-target="#get-purchase-order-results"
                            aria-expanded="false"
                            aria-controls="#get-purchase-order-results"
                            style="cursor: pointer;  "

                            class="glyphicon1 icn-get-purchase-order-results"
                            rel="icn-get-purchase-order-results"
                        >
                            <span class="icn-get-purchase-order-results">
                               results <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                            </span>
                        </td>
                        <td>Object</td>
                    </tr>
                    <tr  class="collapse" id="get-purchase-order-results">
                        <td colspan="2">
                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                <table class="table-bordered" style="width: 100%">
                                    <thead></thead>
                                    <tbody>
                                        <tr>
                                            <td
                                                data-toggle="collapse"
                                                data-target="#get-purchase-order-pending"
                                                aria-expanded="false"
                                                aria-controls="#get-purchase-order-pending"
                                                style="cursor: pointer;  "
                                                class="glyphicon1 icn-get-purchase-order-pending"
                                                rel="icn-get-purchase-order-pending"
                                            >
                                                <span class="icn-get-purchase-order-pending">
                                                   pending <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                                </span>
                                            </td>
                                            <td>Object</td>
                                        </tr>
                                        <tr  class="collapse" id="get-purchase-order-pending">
                                            <td colspan="2">
                                                <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                    <table class="table-bordered" style="width: 100%">
                                                        <thead></thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>current_page</td>
                                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                            </tr>
                                                            <tr>
                                                                <td>data</td>
                                                                <td><i>Array</i> Default: <code>NULL</code>,</td>
                                                            </tr>
                                                            <tr>
                                                                <td>first_page_url</td>
                                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                                            </tr>
                                                            <tr>
                                                                <td>from</td>
                                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                                            </tr>
                                                            <tr>
                                                                <td>last_page</td>
                                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                            </tr>
                                                            <tr>
                                                                <td>last_page_url</td>
                                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                                            </tr>
                                                            <tr>
                                                                <td
                                                                    data-toggle="collapse"
                                                                    data-target="#get-purchase-order-links"
                                                                    aria-expanded="false"
                                                                    aria-controls="#get-purchase-order-links"
                                                                    style="cursor: pointer;  "
                                                                    class="glyphicon1 icn-get-purchase-order-links"
                                                                    rel="icn-get-purchase-order-links"
                                                                >
                                                                    <span class="icn-get-purchase-order-links">
                                                                       links <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                                                    </span>
                                                                </td>
                                                                <td>Object</td>
                                                            </tr>
                                                            <tr  class="collapse" id="get-purchase-order-links">
                                                                <td colspan="2">
                                                                    <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                                        <table class="table-bordered" style="width: 100%">
                                                                            <thead></thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td>url</td>
                                                                                    <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>label</td>
                                                                                    <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>active</td>
                                                                                    <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>next_page_url</td>
                                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                                            </tr>
                                                            <tr>
                                                                <td>path</td>
                                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                                            </tr>
                                                            <tr>
                                                                <td>per_page</td>
                                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                            </tr>
                                                            <tr>
                                                                <td>prev_page_url</td>
                                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                                            </tr>
                                                            <tr>
                                                                <td>to</td>
                                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                                            </tr>
                                                            <tr>
                                                                <td>total</td>
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
                                                    data-target="#get-purchase-order-shipped"
                                                    aria-expanded="false"
                                                    aria-controls="#get-purchase-order-shipped"
                                                    style="cursor: pointer;  "
                                                    class="glyphicon1 icn-get-purchase-order-shipped"
                                                    rel="icn-get-purchase-order-shipped"
                                            >
                                                <span class="icn-get-purchase-order-shipped">
                                                   shipped <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                                </span>
                                            </td>
                                            <td>Object</td>
                                        </tr>

                                        <tr  class="collapse" id="get-purchase-order-shipped">
                                            <td colspan="2">
                                                <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                    <table class="table-bordered" style="width: 100%">
                                                        <thead></thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>current_page</td>
                                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                            </tr>
                                                            <tr>
                                                                <td
                                                                    data-toggle="collapse"
                                                                    data-target="#get-purchase-order-pending-data"
                                                                    aria-expanded="false"
                                                                    aria-controls="#get-purchase-order-pending-data"
                                                                    style="cursor: pointer;  "
                                                                    class="glyphicon1 icn-get-purchase-order-pending-data"
                                                                    rel="icn-get-purchase-order-pending-data"
                                                                >
                                                                    <span class="icn-get-purchase-order-pending-data">
                                                                       data <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                                                    </span>
                                                                </td>
                                                                <td>Object</td>
                                                            </tr>
                                                            <tr  class="collapse" id="get-purchase-order-pending-data">
                                                                <td colspan="2">
                                                                    <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                                        <table class="table-bordered" style="width: 100%">
                                                                            <thead></thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td>id</td>
                                                                                    <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                                </tr>

                                                                                <tr>
                                                                                    <td>po_number</td>
                                                                                    <td><i>String</i> Default: <code>NULL</code>,</td>
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
                                                                                    <td><i>String</i> Default: <code>NULL</code>,</td>
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
                                                                                    <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>warehouse_id</td>
                                                                                    <td><i>Integer</i> Default: <code>NULL</code>,</td>
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
                                                                                    <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                                </tr>

                                                                                <tr>
                                                                                    <td>ship_via</td>
                                                                                    <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                                </tr>

                                                                                <tr>
                                                                                    <td>status</td>
                                                                                    <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                                </tr>

                                                                                <tr>
                                                                                    <td>state</td>
                                                                                    <td><i>String</i> Default: <code>NULL</code>,</td>
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
                                                                                    <td><i>Date</i> Default: <code>NULL</code>,</td>
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
                                                                                    <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>buyer_id</td>
                                                                                    <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>order_type</td>
                                                                                    <td><i>String</i> Default: <code>NULL</code>,</td>
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
                                                                                    <td>total_units</td>
                                                                                    <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>total_amount</td>
                                                                                    <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>change_log</td>
                                                                                    <td><i>Object</i> Default: <code>NULL</code>,</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td
                                                                                        data-toggle="collapse"
                                                                                        data-target="#get-purchase-order-products"
                                                                                        aria-expanded="false"
                                                                                        aria-controls="#get-purchase-order-products"
                                                                                        style="cursor: pointer;  "
                                                                                        class="glyphicon1 icn-get-purchase-order-products"
                                                                                        rel="icn-get-purchase-order-products"
                                                                                    >
                                                                                        <span class="icn-get-purchase-order-products">
                                                                                           purchase_order_products <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                                                                        </span>
                                                                                    </td>
                                                                                    <td>Object</td>
                                                                                </tr>
                                                                                <tr  class="collapse" id="get-purchase-order-products">
                                                                                    <td colspan="2">
                                                                                        <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                                                            <table class="table-bordered" style="width: 100%">
                                                                                                <thead></thead>
                                                                                                <tbody>
                                                                                                <tr>
                                                                                                    <td>id</td>
                                                                                                    <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td>purchase_order_id</td>
                                                                                                    <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td>product_id</td>
                                                                                                    <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td>customer_id</td>
                                                                                                    <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td>quantity</td>
                                                                                                    <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td>unit_price</td>
                                                                                                    <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td>amount</td>
                                                                                                    <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td>deleted_at</td>
                                                                                                    <td><i>Date</i> Default: <code>NULL</code>,</td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td>created_at</td>
                                                                                                    <td><i>Date</i> Default: <code>NULL</code>,</td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td>updated_at</td>
                                                                                                    <td><i>Date</i> Default: <code>NULL</code>,</td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td>sku</td>
                                                                                                    <td><i>String</i> Default: <code>NULL</code>,</td>
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
                                                                                                    <td>units_per_carton</td>
                                                                                                    <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td>units</td>
                                                                                                    <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td>unship_cartons</td>
                                                                                                    <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td>change_log</td>
                                                                                                    <td><i>Object</i> Default: <code>NULL</code>,</td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td
                                                                                                        data-toggle="collapse"
                                                                                                        data-target="#get-purchase-order-product"
                                                                                                        aria-expanded="false"
                                                                                                        aria-controls="#get-purchase-order-product"
                                                                                                        style="cursor: pointer;  "
                                                                                                        class="glyphicon1 icn-get-purchase-order-product"
                                                                                                        rel="icn-get-purchase-order-product"
                                                                                                    >
                                                                                                        <span class="icn-get-purchase-order-product">
                                                                                                           product <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                                                                                        </span>
                                                                                                    </td>
                                                                                                    <td>Object</td>
                                                                                                </tr>
                                                                                                <tr  class="collapse" id="get-purchase-order-product">
                                                                                                    <td colspan="2">
                                                                                                        <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                                                                            <table class="table-bordered" style="width: 100%">
                                                                                                                <thead></thead>
                                                                                                                <tbody>
                                                                                                                    <tr>
                                                                                                                        <td>id</td>
                                                                                                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td>sku</td>
                                                                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td>name</td>
                                                                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td>category_id</td>
                                                                                                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td>description</td>
                                                                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
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
                                                                                                                        <td><i>Date</i> Default: <code>NULL</code>,</td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td>updated_at</td>
                                                                                                                        <td><i>Date</i> Default: <code>NULL</code>,</td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td>customer_id</td>
                                                                                                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td>created_by</td>
                                                                                                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td>deleted_at</td>
                                                                                                                        <td><i>Date</i> Default: <code>NULL</code>,</td>
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
                                                                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td>is_for_classification_code</td>
                                                                                                                        <td><i>Boolean</i> Default: <code>NULL</code>,</td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td>upc_number</td>
                                                                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
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
                                                                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td>unit_dimensions</td>
                                                                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td>carton_upc</td>
                                                                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td>additional_classification_code</td>
                                                                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td>preferred_unit_quantity</td>
                                                                                                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td>warehouse_customer_id</td>
                                                                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td>tags</td>
                                                                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td>sub_category_id</td>
                                                                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td>category_sku</td>
                                                                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                                                                    </tr>
                                                                                                                    <tr>
                                                                                                                        <td>inbound_associated</td>
                                                                                                                        <td><i>Boolean</i> Default: <code>NULL</code>,</td>
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

                                                            <tr>
                                                                <td>first_page_url</td>
                                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                                            </tr>
                                                            <tr>
                                                                <td>from</td>
                                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                            </tr>
                                                            <tr>
                                                                <td>last_page</td>
                                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                            </tr>
                                                            <tr>
                                                                <td>last_page_url</td>
                                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                                            </tr>
                                                            <tr>
                                                                <td
                                                                    data-toggle="collapse"
                                                                    data-target="#get-purchase-order-shipped-links"
                                                                    aria-expanded="false"
                                                                    aria-controls="#get-purchase-order-shipped-links"
                                                                    style="cursor: pointer;  "
                                                                    class="glyphicon1 icn-get-purchase-order-shipped-links"
                                                                    rel="icn-get-purchase-order-shipped-links"
                                                                >
                                                                    <span class="icn-get-purchase-order-shipped-links">
                                                                       links <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                                                    </span>
                                                                </td>
                                                                <td>Object</td>
                                                            </tr>

                                                            <tr  class="collapse" id="get-purchase-order-shipped-links">
                                                                <td colspan="2">
                                                                    <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                                        <table class="table-bordered" style="width: 100%">
                                                                            <thead></thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td>url</td>
                                                                                    <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>label</td>
                                                                                    <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>active</td>
                                                                                    <td><i>Boolean</i> Default: <code>NULL</code>,</td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>next_page_url</td>
                                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                                            </tr>
                                                            <tr>
                                                                <td>path</td>
                                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                                            </tr>

                                                            <tr>
                                                                <td>per_page</td>
                                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                                            </tr>
                                                            <tr>
                                                                <td>prev_page_url</td>
                                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                            </tr>

                                                            <tr>
                                                                <td>to</td>
                                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                            </tr>
                                                            <tr>
                                                                <td>total</td>
                                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
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
    </div><!-- End Email Report Schedule specified resource -->


</div> <!-- start Email Report Schedule -->

