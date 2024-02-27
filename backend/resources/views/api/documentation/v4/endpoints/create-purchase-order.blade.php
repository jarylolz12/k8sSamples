<div class="col-sm-12" id="create-purchase-order" > <!-- start Email Report Schedule -->

    <div class="col-sm-12" ><!-- Email Report Schedule specified resource -->
        <h3>{{ EndPointHelper::create('Purchase Order') }}</h3>
        <p>
            {{ EndPointHelper::createDescription('Purchase Order',
            array(
                'order_type',
                'po_number',
                'supplier_id',
                'buyer_id',
                'notes',
                'warehouse_id',
                'cargo_ready_date',
                'packing_method',
                'ship_via',
                'terms',
                'payment_term',
                'sub_total',
                'tax',
                'ship_to',
                'shipping',
                'discount',
                'products.*.id',
                'products.*.quantity',
                'products.*.units',
                'products.*.unit_price',
                'products.*.amount',
                'products.*.units_per_carton'
            )) }};
        </p>
        <br>
        <div class="" style="position:relative;padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b>
            <span class="glyphicon1" rel="i-create-purchase-order" aria-hidden="true">
                    <span
                            data-toggle="collapse"
                            data-target="#route-create-purchase-order"
                            aria-expanded="false"
                            aria-controls="route-create-purchase-order"
                            style="cursor: pointer"
                            class="i-create-purchase-order"
                    > <code>/api/orders/create</code>
                        <span class="glyphicon glyphicon-menu-right " aria-hidden="true">
                        </span>
                    </span>
                </span>
            <div class="collapse api-route" id="route-create-purchase-order">
                <div class="badge-success c-create-purchase-order hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">


                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="c-create-purchase-order">Copy</div>
                </div>

                <div class="api_ noselect" id="c-create-purchase-order">
                    {{ config('app.url')}}/api/orders/create
                </div>
            </div>  <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b>
                <code>order_type</code>,
                <code>po_number</code>,
                <code>supplier_id</code>,
                <code>buyer_id</code>,
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
                <code>products.*.id</code>,
                <code>products.*.quantity</code>,
                <code>products.*.units</code>,
                <code>products.*.unit_price</code>,
                <code>products.*.amount</code>,
                <code>products.*.units_per_carton</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Shipment By Shifl Reference Numbebr </code> <small> </small><br>
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
    <span class="slctrl-attr">"order_type"</span> : <span class="slctrl-string">"{order_type}"</span>,
    <span class="slctrl-attr">"po_number"</span> : <span class="slctrl-number">{po_number}</span>,
    <span class="slctrl-attr">"supplier_id"</span> : <span class="slctrl-number">{supplier_id}</span>,
    <span class="slctrl-attr">"buyer_id"</span> : <span class="slctrl-number">{buyer_id}</span>,
    <span class="slctrl-attr">"notes"</span> : <span class="slctrl-string">"{notes}"</span>,
    <span class="slctrl-attr">"warehouse_id"</span> : <span class="slctrl-number">{warehouse_id}</span>,
    <span class="slctrl-attr">"cargo_ready_date"</span> : <span class="slctrl-string">"{cargo_ready_date}"</span>,
    <span class="slctrl-attr">"packing_method"</span> : <span class="slctrl-string">"{packing_method}"</span>,
    <span class="slctrl-attr">"ship_via"</span> : <span class="slctrl-string">"{ship_via}"</span>,
    <span class="slctrl-attr">"terms"</span> : <span class="slctrl-string">"{terms}"</span>,
    <span class="slctrl-attr">"payment_term"</span> : <span class="slctrl-number">{payment_term}</span>,
    <span class="slctrl-attr">"sub_total"</span> : <span class="slctrl-number">{sub_total}</span>,
    <span class="slctrl-attr">"tax"</span> : <span class="slctrl-number">{tax}</span>,
    <span class="slctrl-attr">"ship_to"</span> : <span class="slctrl-string">"{ship_to}"</span>,
    <span class="slctrl-attr">"shipping"</span> : <span class="slctrl-number">{shipping}</span>,
    <span class="slctrl-attr">"discount"</span> : <span class="slctrl-number">{discount}</span>,
    <span class="slctrl-attr">"products.*.id"</span> : <span class="slctrl-number">{products.*.id}</span>,
    <span class="slctrl-attr">"products.*.quantity"</span> : <span class="slctrl-number">{products.*.quantity}</span>,
    <span class="slctrl-attr">"products.*.units"</span> : <span class="slctrl-number">{products.*.units}</span>,
    <span class="slctrl-attr">"products.*.unit_price"</span> : <span class="slctrl-number">{products.*.unit_price}</span>,
    <span class="slctrl-attr">"products.*.amount"</span> : <span class="slctrl-number">{products.*.amount}</span>,
    <span class="slctrl-attr">"products.*.units_per_carton"</span> : <span class="slctrl-number">{products.*.units_per_carton}</span>
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
                        <td>order_type<span class="badge-danger">required</span></td>
                        <td><i>String</i> The order type PO/SO for the purchase order</td>
                    </tr>
                    <tr>
                        <td>po_number<span class="badge-danger">required</span></td>
                        <td><i>Integer</i> The unique po_number for the purchase order</td>
                    </tr>
                    <tr>
                        <td>supplier_id<span class="badge-danger">required</span></td>
                        <td><i>Integer</i> The supplier id foreign key for the purchase order </td>
                    </tr>
                    <tr>
                        <td>buyer_id<span class="badge-danger">required</span></td>
                        <td><i>Integer</i> The buyer id foreign key for the purchase order</td>
                    </tr>
                    <tr>
                        <td>notes<span class="badge-danger">required</span></td>
                        <td><i>String</i> The notes for the purchase order</td>
                    </tr>
                    <tr>
                        <td>warehouse_id<span class="badge-danger">required</span></td>
                        <td><i>Integer</i> The warehouse id foreign key for the purchase order</td>
                    </tr>
                    <tr>
                        <td>cargo_ready_date<span class="badge-danger">required</span></td>
                        <td><i>Date</i> The cargo ready date for the purchase order</td>
                    </tr>

                    <tr>
                        <td>packing_method<span class="badge-danger">required</span></td>
                        <td><i>String</i> The Packing Method for the purchase order</td>
                    </tr>
                    <tr>
                        <td>ship_via<span class="badge-danger">required</span></td>
                        <td><i>String</i> The ship_via for the purchase order</td>
                    </tr>
                    <tr>
                        <td>terms<span class="badge-danger">required</span></td>
                        <td><i>String</i> The Terms for the purchase order</td>
                    </tr>
                    <tr>
                        <td>payment_term<span class="badge-danger">required</span></td>
                        <td><i>Integer</i> The Payment Term for the purchase order</td>
                    </tr>
                    <tr>
                        <td>sub_total<span class="badge-danger">required</span></td>
                        <td><i>Integer</i> The Sub Total for the purchase order</td>
                    </tr>
                    <tr>
                        <td>tax<span class="badge-danger">required</span></td>
                        <td><i>Integer</i> The Tax for the purchase order</td>
                    </tr>
                    <tr>
                        <td>ship_to<span class="badge-danger">required</span></td>
                        <td><i>String</i> The ship_to for the purchase order</td>
                    </tr>
                    <tr>
                        <td>shipping<span class="badge-danger">required</span></td>
                        <td><i>Integer</i> The shipping for the purchase order</td>
                    </tr>
                    <tr>
                        <td>discount<span class="badge-danger">required</span></td>
                        <td><i>Integer</i> The discount for the purchase order</td>
                    </tr>
                    <tr>
                        <td>products.*.id<span class="badge-danger">required</span></td>
                        <td><i>Integer</i> The product id array for the purchase order</td>
                    </tr>

                    <tr>
                        <td>products.*.quantity<span class="badge-danger">required</span></td>
                        <td><i>Integer</i> The product quantity array for the products</td>
                    </tr>
                    <tr>
                        <td>products.*.units<span class="badge-danger">required</span></td>
                        <td><i>Integer</i> The product units array for the products</td>
                    </tr>
                    <tr>
                        <td>products.*.unit_price<span class="badge-danger">required</span></td>
                        <td><i>Integer</i> The product unit_price array for the products</td>
                    </tr>
                    <tr>
                        <td>products.*.amount<span class="badge-danger">required</span></td>
                        <td><i>Integer</i> The product amount array for the products</td>
                    </tr>
                    <tr>
                        <td>products.*.units_per_carton<span class="badge-danger">required</span></td>
                        <td><i>Integer</i> The product units_per_carton array for the products</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h4>Response
            <span
                data-toggle="collapse"
                data-target="#create-purchase-order-response"
                aria-expanded="false"
                aria-controls="create-purchase-order-response"
                style="cursor: pointer; top: 2px !important;"
                class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre  style="font-weight:bold" class="collapse" id="create-purchase-order-response">
{
    <span class="slctrl-attr">"message"</span>: <span class="slctrl-string">"Purchase Order has been created."</span>,
    <span class="slctrl-attr">"data"</span>: {
        <span class="slctrl-attr">"po_number"</span>: <span class="slctrl-string">"373515"</span>,
        <span class="slctrl-attr">"order_type"</span>: <span class="slctrl-string">"PO"</span>,
        <span class="slctrl-attr">"supplier_id"</span>: <span class="slctrl-string">"1"</span>,
        <span class="slctrl-attr">"buyer_id"</span>: <span class="slctrl-string">"3"</span>,
        <span class="slctrl-attr">"warehouse_id"</span>: <span class="slctrl-string">"64"</span>,
        <span class="slctrl-attr">"notes"</span>: <span class="slctrl-string">"voluptatum"</span>,
        <span class="slctrl-attr">"created_by"</span>: <span class="slctrl-number">2</span>,
        <span class="slctrl-attr">"customer_id"</span>: <span class="slctrl-number">25</span>,
        <span class="slctrl-attr">"is_system_generated"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"ship_via"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"packing_method"</span>: <span class="slctrl-string">"assumenda"</span>,
        <span class="slctrl-attr">"terms"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"payment_term"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"cargo_ready_date"</span>: <span class="slctrl-string">"2022-04-26"</span>,
        <span class="slctrl-attr">"sub_total"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"tax"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"ship_to"</span>: <span class="slctrl-string">"Warehouse Testing, 404 Powder House Road\nWest Palm Beach, FL 33401"</span>,
        <span class="slctrl-attr">"shipping"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"discount"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"total"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"status"</span>: <span class="slctrl-string">"pending"</span>,
        <span class="slctrl-attr">"state"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2022-11-15T14:19:22.000000Z"</span>,
        <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2022-11-15T14:19:22.000000Z"</span>,
        <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">301</span>,
        <span class="slctrl-attr">"change_log"</span>: []
    },
    <span class="slctrl-attr">"product_details"</span>: [
        {
            <span class="slctrl-attr">"product_id"</span>: <span class="slctrl-string">"1"</span>,
            <span class="slctrl-attr">"purchase_order_id"</span>: <span class="slctrl-number">301</span>,
            <span class="slctrl-attr">"customer_id"</span>: <span class="slctrl-number">25</span>,
            <span class="slctrl-attr">"quantity"</span>: <span class="slctrl-string">"1"</span>,
            <span class="slctrl-attr">"units"</span>: <span class="slctrl-string">"29"</span>,
            <span class="slctrl-attr">"unit_price"</span>: <span class="slctrl-string">"29"</span>,
            <span class="slctrl-attr">"amount"</span>: <span class="slctrl-string">"29"</span>,
            <span class="slctrl-attr">"classification_code"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"duty_rate"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"units_per_carton"</span>: <span class="slctrl-string">"12"</span>,
            <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2022-11-15T14:19:22.000000Z"</span>,
            <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2022-11-15T14:19:22.000000Z"</span>,
            <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">357</span>,
            <span class="slctrl-attr">"unship_cartons"</span>: <span class="slctrl-number">1</span>,
            <span class="slctrl-attr">"change_log"</span>: [],
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
                <span class="slctrl-attr">"preferred_unit_quantity"</span>: <span class="slctrl-number">0</span>,
                <span class="slctrl-attr">"warehouse_customer_id"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"tags"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"sub_category_id"</span>: <span class="slctrl-literal">null</span>,
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
                        <td>message</td>
                        <td><i>String</i> Default: <code>NULL</code>,</td>
                    </tr>
                    <tr>
                        <td
                            data-toggle="collapse"
                            data-target="#create-purchase-order-data"
                            aria-expanded="false"
                            aria-controls="#create-purchase-order-data"
                            style="cursor: pointer;  "

                            class="glyphicon1 icn-create-purchase-order-data"
                            rel="icn-create-purchase-order-data"
                        >
                            <span class="icn-create-purchase-order-data">
                               data <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                            </span>
                        </td>
                        <td>Object</td>
                    </tr>


                    <tr  class="collapse" id="create-purchase-order-data">
                        <td colspan="2">
                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                <table class="table-bordered" style="width: 100%">
                                    <thead></thead>
                                    <tbody>
                                    <tr>
                                        <td>po_number</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>order_type</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>supplier_id</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>buyer_id</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>

                                    <tr>
                                        <td>warehouse_id</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
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
                                        <td>customer_id</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>customer_id</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>is_system_generated</td>
                                        <td><i>Boolean</i> Default: <code>NULL</code>,</td>
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
                                        <td>customer_id</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
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
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>tax</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
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
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>total</td>
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
                                        <td>updated_at</td>
                                        <td><i>Date</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>created_at</td>
                                        <td><i>Date</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>id</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>change_log</td>
                                        <td><i>Object</i> Default: <code>NULL</code>,</td>
                                    </tr>


                                    <tr>
                                        <td
                                                data-toggle="collapse"
                                                data-target="#create-purchase-order-product-details"
                                                aria-expanded="false"
                                                aria-controls="#create-purchase-order-product-details"
                                                style="cursor: pointer;  "

                                                class="glyphicon1 icn-create-purchase-order-product-details"
                                                rel="icn-create-purchase-order-product-details"
                                        >
                                            <span class="icn-create-purchase-order-product-details">
                                               product_details <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                            </span>
                                        </td>
                                        <td>Object</td>
                                    </tr>
                                    <tr  class="collapse" id="create-purchase-order-product-details">
                                        <td colspan="2">
                                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                <table class="table-bordered" style="width: 100%">
                                                    <thead></thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>product_id</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>purchase_order_id</td>
                                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>customer_id</td>
                                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>quantity</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>units</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>unit_price</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>amount</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>classification_code</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>duty_rate</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>units_per_carton</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>updated_at</td>
                                                        <td><i>Date</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>created_at</td>
                                                        <td><i>Date</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>id</td>
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
                                                        <td>product</td>
                                                        <td><i>Object</i> Default: <code>NULL</code>,</td>
                                                    </tr>


                                                    <tr  class="collapse" id="create-purchase-order-product-details">
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
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>updated_at</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
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
                                                                        <td><i>Boolean</i> Default: <code>NULL</code>,</td>
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
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
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
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
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
                                                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
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
                                                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>preferred_unit_quantity</td>
                                                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>warehouse_customer_id</td>
                                                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>tags</td>
                                                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>sub_category_id</td>
                                                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
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

                    </tbody>
                </table>
            </div>
        </div>







        <p>
            {{ EndPointHelper::userGuide() }}
        </p>
    </div><!-- End Email Report Schedule specified resource -->


</div> <!-- start Email Report Schedule -->

