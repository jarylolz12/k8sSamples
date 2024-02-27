<!-- Central PO Purchase Order by ID-->
<div class="col-sm-12" id="central-po-purchase-order-main">
    <h3 class="page-header">{{ EndPointHelper::endPoint('PO Purchase Order Main') }}</h3>

    <!-- Specified resource -->
    <div class="col-sm-12" id="gcp-purchase-order-main-by-customer-id">
        <h3>{{ EndPointHelper::get('Purchase Order by Customer ID') }}</h3>
        <p>
            {{ EndPointHelper::getDescriptionArray('Purchase Order by Customer ID', array('customerId', 'searchText', 'supplierId')) }}
        </p>
        <br>
        <div class="" style="position:relative;padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-gcp-purchase-order-main-by-customer-id" aria-hidden="true">
                <span
                        data-toggle="collapse"
                        data-target="#route-gcp-purchase-order-main-by-customer-id"
                        aria-expanded="false"
                        aria-controls="route-gcp-purchase-order-main-by-customer-id"
                        style="cursor: pointer"
                        class="i-gcp-purchase-order-main-by-customer-id"
                > <code>api/po/customers/{customerId}/purchase-orders</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                </span>
            </span>

            <div class="collapse api-route" id="route-gcp-purchase-order-main-by-customer-id">
                <div class="badge-success gcp-purchase-order-main-by-customer-id hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="gcp-purchase-order-main-by-customer-id">Copy</div>
                </div>
                <div class="api_ noselect" id="gcp-purchase-order-main-by-customer-id">
                    {{ getenv('PO_URL')}}/api/po/customers/{customerId}/purchase-orders
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>customerId</code>, <code>searchText</code>, <code>supplierId</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Specific Purchase Order by Customer ID</code> <small> </small><br>
        </div>
        <h4>Header</h4>
        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"Content-Type"</span>: <span class="slctrl-string"> "application/json" </span>
    <span class="slctrl-attr">"Accept"</span>: <span class="slctrl-string"> "application/json" </span>
} </pre>
        <h4> Form Data </h4>
        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"customerId"</span>: <span class="slctrl-number">{customerId}</span>,
    <span class="slctrl-attr">"searchText"</span>: <span class="slctrl-string">"{searchText}"</span>,
    <span class="slctrl-attr">"supplierId"</span>: <span class="slctrl-number">{supplierId}</span>,
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
                    <td>customerId<span class="badge-danger">required</span></td>
                    <td><i>Integer</i> The foreign key customer id for the Purchase Order by Customer ID to be retrieved</td>
                </tr>
                <tr>
                    <td>searchText<span class="badge-danger">required</span></td>
                    <td><i>String</i> The PO number for the Purchase Order by Customer ID to be retrieved</td>
                </tr>
                <tr>
                    <td>supplierId<span class="badge-danger">required</span></td>
                    <td><i>Integer</i> The foreign key supplier id for the Purchase Order by Customer ID to be retrieved</td>
                </tr>
                </tbody>
            </table>
        </div>
        <h4>Response
            <span
                    data-toggle="collapse"
                    data-target="#gcp-purchase-order-main-by-customer-id-response"
                    aria-expanded="false"
                    aria-controls="gcp-purchase-order-main-by-customer-id-response"
                    style="cursor: pointer; top: 2px !important;"
                    class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre style="font-weight:bold" class="collapse" id="gcp-purchase-order-main-by-customer-id-response">
[
    {
        <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">2</span>,
        <span class="slctrl-attr">"po_number"</span>: <span class="slctrl-string">"932506"</span>,
        <span class="slctrl-attr">"supplier_id"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"customer_id"</span>: <span class="slctrl-number">5</span>,
        <span class="slctrl-attr">"notes"</span>: <span class="slctrl-string">"gdzzz"</span>,
        <span class="slctrl-attr">"po_items"</span>: <span class="slctrl-string">"[{\"product\":100,\"quantity\":10,\"unitPrice\":500,\"total\":5000},{\"product\":99,\"quantity\":15,\"unitPrice\":450,\"total\":6750}]"</span>,
        <span class="slctrl-attr">"created_by"</span>: <span class="slctrl-number">190</span>,
        <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2021-08-04 20:26:17"</span>,
        <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2021-08-04 20:26:17"</span>,
        <span class="slctrl-attr">"warehouse_id"</span>: <span class="slctrl-number">15</span>,
        <span class="slctrl-attr">"deleted_at"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"is_system_generated"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"customer_admins"</span>: <span class="slctrl-string">"[190]"</span>,
        <span class="slctrl-attr">"terms"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"due_by"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"ship_via"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"status"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"sub_total"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"tax"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"shipping"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"discount"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"total"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"cargo_ready_date"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"packing_method"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"payment_term"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"pdf"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"ship_to"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"warehouse"</span>: {
            <span class="slctrl-attr">"name"</span>: <span class="slctrl-string">"N/A"</span>
        }
    }
] </pre>
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
                            <td><i>Integer</i> Default: <code>NULL</code>,</td>
                        </tr>
                        <tr>
                            <td>po_items</td>
                            <td><i>Integer</i> Default: <code>NULL</code>,</td>
                        </tr>
                        <tr>
                            <td>created_by</td>
                            <td><i>Integer</i> Default: <code>NULL</code>,</td>
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
                            <td>warehouse_id</td>
                            <td><i>Integer</i> Default: <code>NULL</code>,</td>
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
                            <td><i>String</i> Default: <code>NULL</code>,</td>
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
                            <td>warehouse</td>
                            <td><i>Integer</i> Default: <code>NULL</code>,</td>
                        </tr>
                        <td
                            data-toggle="collapse"
                            data-target="#gcp-purchase-order-main-by-customer-id-data"
                            aria-expanded="false"
                            aria-controls="gcp-purchase-order-main-by-customer-id-data"
                            style="cursor: pointer;  "
                            class="glyphicon1 icn-gcp-purchase-order-main-by-customer-id-data"
                            rel="icn-gcp-purchase-order-main-by-customer-id-data"
                        >
                            <span class="icn-gcp-purchase-order-main-by-customer-id-data">
                               data <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                            </span>
                        </td>
                        <td>object</td>
                        </tr>
                        <tr  class="collapse" id="gcp-purchase-order-main-by-customer-id-data">
                            <td colspan="2">
                                <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                    <table class="table-bordered">
                                        <thead></thead>
                                        <tbody>
                                        <tr>
                                            <td>name</td>
                                            <td><i>String</i> Default: <code>Nullt</code>,</td>
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





    <!-- Product order main product -->
    <div class="col-sm-12" id="gcp-purchase-order-main-product">
        <h3>{{ EndPointHelper::get('Purchase Order by Product') }}</h3>
        <p>
            {{ EndPointHelper::getDescription('Purchase Order by Product', 'purchaseOrderId') }}
        </p>
        <br>
        <div class="" style="position:relative;padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-gcp-purchase-order-main-product" aria-hidden="true">
                <span
                        data-toggle="collapse"
                        data-target="#route-gcp-purchase-order-main-product"
                        aria-expanded="false"
                        aria-controls="route-gcp-purchase-order-main-product"
                        style="cursor: pointer"
                        class="i-gcp-purchase-order-main-product"
                > <code>api/po/purchase-orders/{purchaseOrderId}/products</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                </span>
            </span>

            <div class="collapse api-route" id="route-gcp-purchase-order-main-product">
                <div class="badge-success gcp-purchase-order-main-product hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="gcp-purchase-order-main-product">Copy</div>
                </div>
                <div class="api_ noselect" id="gcp-purchase-order-main-product">
                    {{ getenv('PO_URL')}}/api/po/purchase-orders/{purchaseOrderId}/products
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>purchaseOrderId</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Specific Purchase Order by Product</code> <small> </small><br>
        </div>
        <h4>Header</h4>
        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"Content-Type"</span>: <span class="slctrl-string"> "application/json" </span>
    <span class="slctrl-attr">"Accept"</span>: <span class="slctrl-string"> "application/json" </span>
} </pre>
        <h4> Form Data </h4>
        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"purchaseOrderId"</span>: <span class="slctrl-string">"{purchaseOrderId}"</span>
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
                    <td>purchaseOrderId<span class="badge-danger">required</span></td>
                    <td><i>Integer</i> The foreign key Purchase Order Id for the Purchase Order main by product to be retrieved</td>
                </tr>
                </tbody>
            </table>
        </div>
        <h4>Response
            <span
                    data-toggle="collapse"
                    data-target="#gcp-purchase-order-main-product-response"
                    aria-expanded="false"
                    aria-controls="gcp-purchase-order-main-product-response"
                    style="cursor: pointer; top: 2px !important;"
                    class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre style="font-weight:bold" class="collapse" id="gcp-purchase-order-main-product-response">
{
      [
          {
              <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">12</span>,
              <span class="slctrl-attr">"purchase_order_id"</span>: <span class="slctrl-number">11</span>,
              <span class="slctrl-attr">"product_id"</span>: <span class="slctrl-number">108</span>,
              <span class="slctrl-attr">"customer_id"</span>: <span class="slctrl-number">447</span>,
              <span class="slctrl-attr">"quantity"</span>: <span class="slctrl-number">15</span>,
              <span class="slctrl-attr">"unit_price"</span>: <span class="slctrl-number">15</span>,
              <span class="slctrl-attr">"amount"</span>: <span class="slctrl-number">225</span>,
              <span class="slctrl-attr">"deleted_at"</span>: <span class="slctrl-literal">null</span>,
              <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2021-08-20T12:51:43.000000Z"</span>,
              <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2021-08-20T12:51:43.000000Z"</span>,
              <span class="slctrl-attr">"sku"</span>: <span class="slctrl-string">""</span>,
              <span class="slctrl-attr">"classification_code"</span>: <span class="slctrl-literal">null</span>,
              <span class="slctrl-attr">"duty_rate"</span>: <span class="slctrl-literal">null</span>,
              <span class="slctrl-attr">"units_per_carton"</span>: <span class="slctrl-literal">null</span>,
              <span class="slctrl-attr">"units"</span>: <span class="slctrl-literal">null</span>,
              <span class="slctrl-attr">"unship_cartons"</span>: <span class="slctrl-number">15</span>,
              <span class="slctrl-attr">"product"</span>: {
                  <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">108</span>,
                  <span class="slctrl-attr">"sku"</span>: <span class="slctrl-string">"275012"</span>,
                  <span class="slctrl-attr">"name"</span>: <span class="slctrl-string">"Test Product Shoes"</span>,
                  <span class="slctrl-attr">"category_id"</span>: <span class="slctrl-number">73</span>,
                  <span class="slctrl-attr">"description"</span>: <span class="slctrl-string">"Man's shoes sizes 10 -13"</span>,
                  <span class="slctrl-attr">"units_per_carton"</span>: <span class="slctrl-number">24</span>,
                  <span class="slctrl-attr">"image"</span>: <span class="slctrl-string">"/storage/products/images/agOpKSgt0uT9AlsMkHBlO5sMi72byGCCE1UFtVHk.jpg"</span>,
                  <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2021-08-20T01:01:31.000000Z"</span>,
                  <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2021-09-08T23:06:38.000000Z"</span>,
                  <span class="slctrl-attr">"customer_id"</span>: <span class="slctrl-number">447</span>,
                  <span class="slctrl-attr">"created_by"</span>: <span class="slctrl-number">190</span>,
                  <span class="slctrl-attr">"deleted_at"</span>: <span class="slctrl-string">"2021-09-08T23:06:38.000000Z"</span>,
                  <span class="slctrl-attr">"is_system_generated"</span>: <span class="slctrl-number">1</span>,
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
                  <span class="slctrl-attr">"category_sku"</span>: <span class="slctrl-string">"73-275012"</span>,
                  <span class="slctrl-attr">"inbound_associated"</span>: <span class="slctrl-literal">false</span>
              },
              <span class="slctrl-attr">"shipment_distribution"</span>: []
           },
      ]

}</pre>
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
                        <td>id</td>
                        <td><i>Integer</i> Default: <code>Auto Increment</code>,</td>
                    </tr>
                    <tr>
                        <td>purchase_order_id</td>
                        <td><i>Integer</i> Default: <code>NULL</code>, </td>
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
                        <td><i>Timestamp</i> Default: <code>NULL</code>,</td>
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
                        <td>sku</td>
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
                        <td>units</td>
                        <td><i>String</i> Default: <code>NULL</code>,</td>
                    </tr>
                    <tr>
                        <td>product</td>
                        <td><i>Object</i> Default: <code>NULL</code>,</td>
                    </tr>
                    <td
                        data-toggle="collapse"
                        data-target="#gcp-pomp-product"
                        aria-expanded="false"
                        aria-controls="gcp-pomp-product"
                        style="cursor: pointer;  "
                        class="glyphicon1 icn-gcp-pomp-product"
                        rel="icn-gcp-pomp-product"
                    >
                            <span class="icn-gcp-pomp-product">
                               data <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                            </span>
                    </td>
                    <td>object</td>
                    </tr>
                    <tr  class="collapse" id="gcp-pomp-product">
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
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
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
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
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
                                        <td>carton_dimensions</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
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
                    <tr>
                        <td>shipment_distribution</td>
                        <td><i>Object</i></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <p>
            {{ EndPointHelper::userGuide() }}
        </p>
    </div><!-- End Product order main product -->


    <!-- Purchase Order by Shipment ID -->
    <div class="col-sm-12" id="get-purchase-order-by-shipment-id">
        <h3>{{ EndPointHelper::get('Purchase Order by Shipment ID') }}</h3>
        <p>
            {{ EndPointHelper::getDescription('Purchase Order by Shipment ID', 'purchaseOrderId') }}
        </p>
        <br>
        <div class="" style="position:relative;padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-get-purchase-order-by-shipment-id" aria-hidden="true">
                <span
                        data-toggle="collapse"
                        data-target="#route-get-purchase-order-by-shipment-id"
                        aria-expanded="false"
                        aria-controls="route-get-purchase-order-by-shipment-id"
                        style="cursor: pointer"
                        class="i-get-purchase-order-by-shipment-id"
                > <code>api/po/shipments/{shipment_id}/purchase-orders</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                </span>
            </span>

            <div class="collapse api-route" id="route-get-purchase-order-by-shipment-id">
                <div class="badge-success gcp-purchase-order-by-shipment-id hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="gcp-purchase-order-by-shipment-id">Copy</div>
                </div>
                <div class="api_ noselect" id="gcp-purchase-order-by-shipment-id">
                    {{ getenv('PO_URL')}}/api/po/shipments/{shipment_id}/purchase-orders
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>shipment_id</code>, <code>supplierId</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Specific Purchase Order by Shipment ID</code> <small> </small><br>
        </div>
        <h4>Header</h4>
        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"Content-Type"</span>: <span class="slctrl-string"> "application/json" </span>
    <span class="slctrl-attr">"Accept"</span>: <span class="slctrl-string"> "application/json" </span>
} </pre>
        <h4> Form Data </h4>
        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"purchaseOrderId"</span>: <span class="slctrl-string">"{purchaseOrderId}"</span>
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
                    <td>purchaseOrderId<span class="badge-danger">required</span></td>
                    <td><i>Integer</i> The foreign key Purchase Order Id for the Purchase Order main by product to be retrieved</td>
                </tr>
                </tbody>
            </table>
        </div>
        <h4>Response
            <span
                    data-toggle="collapse"
                    data-target="#gcp-purchase-order-by-shipment-id-response"
                    aria-expanded="false"
                    aria-controls="gcp-purchase-order-by-shipment-id-response"
                    style="cursor: pointer; top: 2px !important;"
                    class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre style="font-weight:bold" class="collapse" id="gcp-purchase-order-by-shipment-id-response">
[
    {
        <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">236</span>,
        <span class="slctrl-attr">"po_number"</span>: <span class="slctrl-string">"474193"</span>,
        <span class="slctrl-attr">"supplier_id"</span>: <span class="slctrl-number">1834</span>,
        <span class="slctrl-attr">"customer_id"</span>: <span class="slctrl-number">447</span>,
        <span class="slctrl-attr">"notes"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"created_by"</span>: <span class="slctrl-number">190</span>,
        <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2022-02-17T23:17:09.000000Z"</span>,
        <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2022-03-24T11:04:38.000000Z"</span>,
        <span class="slctrl-attr">"warehouse_id"</span>: <span class="slctrl-number">217</span>,
        <span class="slctrl-attr">"is_system_generated"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"terms"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"due_by"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"ship_via"</span>: <span class="slctrl-string">"Air"</span>,
        <span class="slctrl-attr">"status"</span>: <span class="slctrl-string">"partial_shipped"</span>,
        <span class="slctrl-attr">"sub_total"</span>: <span class="slctrl-number">13188</span>,
        <span class="slctrl-attr">"tax"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"shipping"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"discount"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"total"</span>: <span class="slctrl-number">13188</span>,
        <span class="slctrl-attr">"cargo_ready_date"</span>: <span class="slctrl-string">"2022-02-17"</span>,
        <span class="slctrl-attr">"packing_method"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"payment_term"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"pdf"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"ship_to"</span>: <span class="slctrl-string">"Testing DC Warehouse, Blk 13 Lot Testing New Address Here, SK 80990"</span>,
        <span class="slctrl-attr">"purchase_order_products"</span>: [
            {
                <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">278</span>,
                <span class="slctrl-attr">"purchase_order_id"</span>: <span class="slctrl-number">236</span>,
                <span class="slctrl-attr">"product_id"</span>: <span class="slctrl-number">394</span>,
                <span class="slctrl-attr">"customer_id"</span>: <span class="slctrl-number">447</span>,
                <span class="slctrl-attr">"quantity"</span>: <span class="slctrl-number">15</span>,
                <span class="slctrl-attr">"unit_price"</span>: <span class="slctrl-number">25.12</span>,
                <span class="slctrl-attr">"amount"</span>: <span class="slctrl-number">13188</span>,
                <span class="slctrl-attr">"deleted_at"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2022-02-17T23:17:09.000000Z"</span>,
                <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2022-02-17T23:17:09.000000Z"</span>,
                <span class="slctrl-attr">"sku"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"classification_code"</span>: <span class="slctrl-string">"TESTING123456"</span>,
                <span class="slctrl-attr">"duty_rate"</span>: <span class="slctrl-number">0</span>,
                <span class="slctrl-attr">"units_per_carton"</span>: <span class="slctrl-number">35</span>,
                <span class="slctrl-attr">"units"</span>: <span class="slctrl-number">525</span>,
                <span class="slctrl-attr">"shipment_distribution"</span>: [
                    {
                        <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">10</span>,
                        <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2022-03-24T11:04:38.000000Z"</span>,
                        <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2022-03-24T11:04:38.000000Z"</span>,
                        <span class="slctrl-attr">"purchase_order_product_id"</span>: <span class="slctrl-number">278</span>,
                        <span class="slctrl-attr">"shipment_id"</span>: <span class="slctrl-number">14610</span>,
                        <span class="slctrl-attr">"ship_cartons"</span>: <span class="slctrl-number">10</span>,
                        <span class="slctrl-attr">"is_shipment"</span>: <span class="slctrl-number">1</span>,
                        <span class="slctrl-attr">"supplier_id"</span>: <span class="slctrl-number">1834</span>
                    }
                ],
                <span class="slctrl-attr">"unship_cartons"</span>: <span class="slctrl-number">-10</span>,
                <span class="slctrl-attr">"product"</span>: {
                    <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">394</span>,
                    <span class="slctrl-attr">"sku"</span>: <span class="slctrl-string">"952468"</span>,
                    <span class="slctrl-attr">"name"</span>: <span class="slctrl-string">"Test Product 1"</span>,
                    <span class="slctrl-attr">"category_id"</span>: <span class="slctrl-number">187</span>,
                    <span class="slctrl-attr">"description"</span>: <span class="slctrl-string">"Please provide more details of the item, like the material and use"</span>,
                    <span class="slctrl-attr">"units_per_carton"</span>: <span class="slctrl-number">35</span>,
                    <span class="slctrl-attr">"image"</span>: <span class="slctrl-string">"products/images/y777NjfllxEor3W9kqDkluRuHUHisww9TdCTwBRX.jpg"</span>,
                    <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2021-11-18T23:46:29.000000Z"</span>,
                    <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2022-03-24T03:31:33.000000Z"</span>,
                    <span class="slctrl-attr">"customer_id"</span>: <span class="slctrl-number">447</span>,
                    <span class="slctrl-attr">"created_by"</span>: <span class="slctrl-number">240</span>,
                    <span class="slctrl-attr">"deleted_at"</span>: <span class="slctrl-literal">null</span>,
                    <span class="slctrl-attr">"is_system_generated"</span>: <span class="slctrl-number">1</span>,
                    <span class="slctrl-attr">"customer_admins"</span>: <span class="slctrl-literal">null</span>,
                    <span class="slctrl-attr">"unit_price"</span>: <span class="slctrl-number">25.12</span>,
                    <span class="slctrl-attr">"classification_code"</span>: <span class="slctrl-literal">null</span>,
                    <span class="slctrl-attr">"duty_rate"</span>: <span class="slctrl-number">100</span>,
                    <span class="slctrl-attr">"carton_dimensions"</span>: <span class="slctrl-string">"\"{}\""</span>,
                    <span class="slctrl-attr">"is_for_classification_code"</span>: <span class="slctrl-number">2</span>,
                    <span class="slctrl-attr">"upc_number"</span>   : <span class="slctrl-string">"12002155"</span>,
                    <span class="slctrl-attr">"country_from"</span>: <span class="slctrl-string">"China"</span>,
                    <span class="slctrl-attr">"country_to"</span>: <span class="slctrl-string">"United States"</span>,
                    <span class="slctrl-attr">"product_classification_description"</span>: <span class="slctrl-string">"https://examplelink.com\r\n\r\nthis is an example only"</span>,
                    <span class="slctrl-attr">"unit_weight"</span>: <span class="slctrl-string">"\"{}\""</span>,
                    <span class="slctrl-attr">"unit_dimensions"</span>: <span class="slctrl-string">"\"{}\""</span>,
                    <span class="slctrl-attr">"carton_upc"</span>: <span class="slctrl-literal">null</span>,
                    <span class="slctrl-attr">"additional_classification_code"</span>: <span class="slctrl-literal">null</span>,
                    <span class="slctrl-attr">"shipped_units"</span>: <span class="slctrl-number">350</span>,
                    <span class="slctrl-attr">"unshipped_units"</span>: <span class="slctrl-number">175</span>,
                    <span class="slctrl-attr">"category_sku"</span>: <span class="slctrl-string">"187-952468"</span>,
                    <span class="slctrl-attr">"inbound_associated"</span>: <span class="slctrl-literal">true</span>
                }
            }
        ]
    }
] </pre>


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
                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                    </tr>
                    <tr>
                        <td>created_by</td>
                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                    </tr>
                    <tr>
                        <td>created_at</td>
                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
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
                        <td><i>Timestamp</i> Default: <code>NULL</code>,</td>
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
                        <td>sub_total</td>
                        <td><i>String</i> Default: <code>NULL</code>,</td>
                    </tr>
                    <tr>
                        <td>tax</td>
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
                        <td>cargo_ready_date</td>
                        <td><i>String</i> Default: <code>NULL</code>,</td>
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
                    <td
                            data-toggle="collapse"
                            data-target="#gcp-purchase-order-by-shipment-id-purchase_order_products"
                            aria-expanded="false"
                            aria-controls="gcp-purchase-order-by-shipment-id-purchase_order_products"
                            style="cursor: pointer;  "
                            class="glyphicon1 icn-gcp-purchase-order-by-shipment-id-purchase_order_products"
                            rel="icn-gcp-purchase-order-by-shipment-id-purchase_order_products"
                    >
                            <span class="icn-gcp-purchase-order-by-shipment-id-purchase_order_products">
                               purchase_order_products <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                            </span>
                    </td>
                    <td>object</td>
                    </tr>
                    <tr  class="collapse" id="gcp-purchase-order-by-shipment-id-purchase_order_products">
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
                                        <td>purchase_order_id</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>product_id</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
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
                                        <td>unit_price</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>amount</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>deleted_at</td>
                                        <td><i>Timestamp</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>created_at</td>
                                        <td><i>Timestamp</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>updated_at</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>sku</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>classification_code</td>
                                        <td><i>Timestamp</i> Default: <code>NULL</code>,</td>
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
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td
                                            data-toggle="collapse"
                                            data-target="#gcp-purchase-order-by-shipment-id-shipment_distribution"
                                            aria-expanded="false"
                                            aria-controls="gcp-purchase-order-by-shipment-id-shipment_distribution"
                                            style="cursor: pointer;  "
                                            class="glyphicon1 icn-gcp-purchase-order-by-shipment-id-shipment_distribution"
                                            rel="icn-gcp-purchase-order-by-shipment-id-shipment_distribution"
                                        >
                                            <span class="icn-gcp-purchase-order-by-shipment-id-shipment_distribution">
                                               shipment_distribution <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                            </span>
                                        </td>
                                        <td>object</td>
                                    </tr>



                                    <tr  class="collapse" id="gcp-purchase-order-by-shipment-id-shipment_distribution">
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
                                                        <td>created_at</td>
                                                        <td><i>Timestamp</i> Default: <code>Null</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>updated_at</td>
                                                        <td><i>Timestamp</i> Default: <code>Null</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>purchase_order_product_id</td>
                                                        <td><i>Integer</i> Default: <code>Null</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>shipment_id</td>
                                                        <td><i>Integer</i> Default: <code>Null</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>ship_cartons</td>
                                                        <td><i>Integer</i> Default: <code>Null</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>is_shipment</td>
                                                        <td><i>Integer</i> Default: <code>Null</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>supplier_id</td>
                                                        <td><i>Integer</i> Default: <code>Null</code>,</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>


                                    <tr>
                                        <td>unship_cartons</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>

                                    <tr>
                                        <td
                                                data-toggle="collapse"
                                                data-target="#gcp-purchase-order-by-shipment-id-product"
                                                aria-expanded="false"
                                                aria-controls="gcp-purchase-order-by-shipment-id-product"
                                                style="cursor: pointer;  "
                                                class="glyphicon1 icn-gcp-purchase-order-by-shipment-id-product"
                                                rel="icn-gcp-purchase-order-by-shipment-id-product"
                                        >
                                            <span class="icn-gcp-purchase-order-by-shipment-id-product">
                                               Product <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                            </span>
                                        </td>
                                        <td>object</td>
                                    </tr>
                                    <tr  class="collapse" id="gcp-purchase-order-by-shipment-id-product">
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
                                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>deleted_at</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>is_system_generated</td>
                                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>customer_admins</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
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
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
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
                                                        <td>shipped_units</td>
                                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>unshipped_units</td>
                                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
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
        </div>
        <p>
            {{ EndPointHelper::userGuide() }}
        </p>
    </div><!-- End urchase Order by Shipment ID -->

    <!-- Shipment Distribution by PO Number-->
    <div class="col-sm-12" id="gcp-shipment-distribution-by-po-number">
        <h3>{{ EndPointHelper::get('Shipment Distribution by PO Number') }}</h3>
        <p>
            {{ EndPointHelper::getDescriptionArray('Shipment Distribution by PO Number', array('po_num ', 'customer_ids') ) }}
        </p>
        <br>
        <div class="" style="position:relative;padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-get-shipment-distribution-by-po-number" aria-hidden="true">
                <span
                        data-toggle="collapse"
                        data-target="#route-get-shipment-distribution-by-po-number"
                        aria-expanded="false"
                        aria-controls="route-get-shipment-distribution-by-po-number"
                        style="cursor: pointer"
                        class="i-get-shipment-distribution-by-po-number"
                > <code>api/po/shipments/{shipment_id}/purchase-orders</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                </span>
            </span>

            <div class="collapse api-route" id="route-get-shipment-distribution-by-po-number">
                <div class="badge-success c-shipment-distribution-by-po-number hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="c-shipment-distribution-by-po-number">Copy</div>
                </div>
                <div class="api_ noselect" id="c-shipment-distribution-by-po-number">
                    {{ getenv('PO_URL')}}/api/po/shipments/{shipment_id}/purchase-orders
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>shipment_id</code>, <code>supplierId</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Specific Purchase Order by Shipment ID</code> <small> </small><br>
        </div>
        <h4>Header</h4>
        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"Content-Type"</span>: <span class="slctrl-string"> "application/json" </span>
    <span class="slctrl-attr">"Accept"</span>: <span class="slctrl-string"> "application/json" </span>
} </pre>
        <h4> Form Data </h4>
        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"po_num"</span>: <span class="slctrl-string">"{po_num}"</span>
    <span class="slctrl-attr">"customer_ids"</span>: <span class="slctrl-string">"{customer_ids}"</span>
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
                    <td>po_num<span class="badge-danger">required</span></td>
                    <td><i>Integer</i> The PO Number for the Shipment Distribution by PO Number to be retrieved</td>
                </tr>
                <tr>
                    <td>customer_ids<span class="badge-danger">required</span></td>
                    <td><i>Integer</i> The foreign key Customer Id array for the Shipment Distribution to be retrieved</td>
                </tr>
                </tbody>
            </table>
        </div>
        <h4>Response
            <span
                    data-toggle="collapse"
                    data-target="#get-shipment-distribution-by-po-number-response"
                    aria-expanded="false"
                    aria-controls="get-shipment-distribution-by-po-number-response"
                    style="cursor: pointer; top: 2px !important;"
                    class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre style="font-weight:bold" class="collapse" id="get-shipment-distribution-by-po-number-response">
{
    <span class="slctrl-attr">"data"<span>: <span>{
        <span class="slctrl-attr">"id"<span>: <span class="slctrl-number">2</span>,
        <span class="slctrl-attr">"po_number"<span>: <span class="slctrl-attr">"932506"</span>,
        <span class="slctrl-attr">"supplier_id"<span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"customer_id"<span>: <span class="slctrl-number">190</span>,
        <span class="slctrl-attr">"notes"<span>: <span class="slctrl-attr">"gdzzz"</span>,
        <span class="slctrl-attr">"created_by"<span>: <span class="slctrl-number">190</span>,
        <span class="slctrl-attr">"created_at"<span>: <span class="slctrl-string">"2021-08-04T20:26:17.000000Z"</span>,
        <span class="slctrl-attr">"updated_at"<span>: <span class="slctrl-string">"2021-08-04T20:26:17.000000Z"</span>,
        <span class="slctrl-attr">"warehouse_id"<span>: <span class="slctrl-number">15</span>,
        <span class="slctrl-attr">"is_system_generated"</span>:<span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"terms"<span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"due_by"<span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"ship_via"<span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"status"<span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"sub_total"<span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"tax"<span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"shipping"<span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"discount"<span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"total"<span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"cargo_ready_date"<span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"packing_method"<span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"payment_term"<span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"pdf"<span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"ship_to"<span>: <span class="slctrl-string">"Mega Warehouse 1, 2464 Royal Ln. Mesa, New Jersey 45463"</span>
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
                                data-target="#get-shipment-distribution-by-po-number-data"
                                aria-expanded="false"
                                aria-controls="get-shipment-distribution-by-po-number-data"
                                style="cursor: pointer;  "

                                class="glyphicon1 icn-shipment-distribution-by-po-number-data"
                                rel="icn-shipment-distribution-by-po-number-data"
                        >
                            <span class="icn-shipment-distribution-by-po-number-data">
                               data <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                            </span>
                        </td>
                        <td>object</td>
                    </tr>
                    <tr  class="collapse" id="get-shipment-distribution-by-po-number-data">
                        <td colspan="2">
                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                <table class="table-bordered">
                                    <thead></thead>
                                    <tbody>
                                    <tr>
                                        <td>id</td>
                                        <td><i>Integer</i> Default: <code>Auto Increment</code></td>
                                    </tr>
                                    <tr>
                                        <td>supplier_id</td>
                                        <td><i>String</i> Default: <code>Null</code></td>
                                    </tr>
                                    <tr>
                                        <td>notes</td>
                                        <td><i>String</i> Default: <code>Null</code></td>
                                    </tr>
                                    <tr>
                                        <td>created_by</td>
                                        <td><i>Integer</i> Default: <code>Null</code></td>
                                    </tr>
                                    <tr>
                                        <td>created_at</td>
                                        <td><i>Timestamp</i> Default: <code>Null</code></td>
                                    </tr>
                                    <tr>
                                        <td>updated_at</td>
                                        <td><i>Timestamp</i> Default: <code>Null</code></td>
                                    </tr>
                                    <tr>
                                        <td>warehouse_id</td>
                                        <td><i>Integer</i> Default: <code>Null</code></td>
                                    </tr>
                                    <tr>
                                        <td>is_system_generated</td>
                                        <td><i>String</i> Default: <code>Null</code></td>
                                    </tr>
                                    <tr>
                                        <td>terms</td>
                                        <td><i>String</i> Default: <code>Null</code></td>
                                    </tr>
                                    <tr>
                                        <td>due_by</td>
                                        <td><i>String</i> Default: <code>Null</code></td>
                                    </tr>
                                    <tr>
                                        <td>ship_via</td>
                                        <td><i>String</i> Default: <code>Null</code></td>
                                    </tr>
                                    <tr>
                                        <td>status</td>
                                        <td><i>String</i> Default: <code>Null</code></td>
                                    </tr>

                                    <tr>
                                        <td>sub_total</td>
                                        <td><i>String</i> Default: <code>Null</code></td>
                                    </tr>
                                    <tr>
                                        <td>tax</td>
                                        <td><i>String</i> Default: <code>Null</code></td>
                                    </tr>
                                    <tr>
                                        <td>shipping</td>
                                        <td><i>String</i> Default: <code>Null</code></td>
                                    </tr>
                                    <tr>
                                        <td>discount</td>
                                        <td><i>Integer</i> Default: <code>Null</code></td>
                                    </tr>
                                    <tr>
                                        <td>total</td>
                                        <td><i>String</i> Default: <code>Null</code></td>
                                    </tr>
                                    <tr>
                                        <td>cargo_ready_date</td>
                                        <td><i>String</i> Default: <code>Null</code></td>
                                    </tr>
                                    <tr>
                                        <td>packing_method</td>
                                        <td><i>String</i> Default: <code>Null</code></td>
                                    </tr>
                                    <tr>
                                        <td>payment_term</td>
                                        <td><i>String</i> Default: <code>Null</code></td>
                                    </tr>
                                    <tr>
                                        <td>pdf</td>
                                        <td><i>String</i> Default: <code>Null</code></td>
                                    </tr>
                                    <tr>
                                        <td>ship_to</td>
                                        <td><i>String</i> Default: <code>Null</code></td>
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
    </div><!-- End Shipment Distribution by PO Number -->






</div> <!-- End Purchase Order -->

