<!-- Central PO Product -->
<div class="col-sm-12" id="central-po-product">
    <h3 class="page-header">{{ EndPointHelper::endPoint('PO Products') }}</h3>
    <!-- List resource -->
    <div class="col-sm-12" id="central-get-all-po-product-get-by-customer">
        <h3>{{ EndPointHelper::getAll('Products by Customer') }}</h3>
        <p>
            {{ EndPointHelper::getAllDescription('Products by Customer') }}
        </p>
        <br>
        <div class="" style="position:relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-central-get-all-po-product-get-by-customer" aria-hidden="true">
                <span
                        data-toggle="collapse"
                        data-target="#route-central-get-all-po-product-get-by-customer"
                        aria-expanded="false"
                        aria-controls="route-central-get-all-po-product-get-by-customer"
                        style="cursor: pointer"
                        class="i-central-get-all-po-product-get-by-customer"
                > <code>api/products</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                </span>
            </span>
            <div class="collapse api-route" id="route-central-get-all-po-product-get-by-customer">
                <div class="badge-success central-get-all-po-product-get-by-customer hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="central-get-all-po-product-get-by-customer">Copy</div>
                </div>

                <div class="api_ noselect" id="central-get-all-po-product-get-by-customer">
                    {{ getenv('PO_URL')}}/api/products
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>per_page</code>, <code>page</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>List of Products by Customer</code> <small> </small><br>
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
                    data-target="#central-get-all-po-product-get-by-customer-response"
                    aria-expanded="false"
                    aria-controls="central-get-all-po-product-get-by-customer-response"
                    style="cursor: pointer; top: 2px !important;"
                    class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre style="font-weight:bold" class="collapse" id="central-get-all-po-product-get-by-customer-response">
{
    <span class="slctrl-attr">"data"</span>: [
        {
            <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">78</span>,
            <span class="slctrl-attr">"sku"</span>: <span class="slctrl-string">"424575"</span>,
            <span class="slctrl-attr">"name"</span>: <span class="slctrl-string">"Test Product 1"</span>,
            <span class="slctrl-attr">"category_id"</span>: <span class="slctrl-number">55</span>,
            <span class="slctrl-attr">"description"</span>: <span class="slctrl-string">"test description"</span>,
            <span class="slctrl-attr">"units_per_carton"</span>: <span class="slctrl-number">24</span>,
            <span class="slctrl-attr">"image"</span>: <span class="slctrl-attr">null</span>,
            <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2021-07-18T20:49:21.000000Z"</span>,
            <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2021-07-19T22:54:06.000000Z"</span>,
            <span class="slctrl-attr">"customer_id"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"created_by"</span>: <span class="slctrl-number">190</span>,
            <span class="slctrl-attr">"deleted_at"</span>: <span class="slctrl-literal">null</span>,
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
            <span class="slctrl-attr">"category_sku"</span>: <span class="slctrl-string">"55-424575"</span>,
            <span class="slctrl-attr">"inbound_associated"</span>: <span class="slctrl-literal">false</span>
        },{...},...,
    ],
    <span class="slctrl-attr">"links"</span>: {
        <span class="slctrl-attr">"first"</span>: <span class="slctrl-string">"/?page=1"</span>,
        <span class="slctrl-attr">"last"</span>: <span class="slctrl-string">"/?page=1"</span>,
        <span class="slctrl-attr">"prev"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"next"</span>: <span class="slctrl-literal">null</span>
    },
    <span class="slctrl-attr">"meta"</span>: {
        <span class="slctrl-attr">"current_page"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"from"</span>: <span class="slctrl-attr">1</span>,
        <span class="slctrl-attr">"last_page"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"links"</span>: [
            {
                <span class="slctrl-attr">"url"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"label"</span>: <span class="slctrl-string">"Previous"</span>,
                <span class="slctrl-attr">"active"</span>: <span class="slctrl-literal">false</span>
            },
            {
                <span class="slctrl-attr">"url"</span>: <span class="slctrl-string">"/?page=1"</span>,
                <span class="slctrl-attr">"label"</span>: <span class="slctrl-string">"1"</span>,
                <span class="slctrl-attr">"active"</span>: <span class="slctrl-literal">true</span>
            },
            {
                <span class="slctrl-attr">"url"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"label"</span>: <span class="slctrl-string">"Next &raquo;"</span>,
                <span class="slctrl-attr">"active"</span>: <span class="slctrl-literal">false</span>
            }
        ],
        <span class="slctrl-attr">"path"</span>: <span class="slctrl-string">"/"</span>,
        <span class="slctrl-attr">"per_page"</span>: <span class="slctrl-string">"5"</span>,
        <span class="slctrl-attr">"to"</span>: <span class="slctrl-number">2</span>,
        <span class="slctrl-attr">"total"</span>: <span class="slctrl-number">2</span>
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
                                data-target="#get-all-central-po-product-by-customer-data"
                                aria-expanded="false"
                                aria-controls="get-all-central-po-product-by-customer-data"
                                style="cursor: pointer;  "
                                class="glyphicon1 all-central-po-product-by-customer-data"
                                rel="all-purchase-order-data"
                        >
                                <span class="all-central-po-product-by-customer-data">
                                   data <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                </span>
                        </td>
                        <td>object</td>
                    </tr>
                    <tr  class="collapse" id="get-all-central-po-product-by-customer-data">
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
                    <tr>
                        <td
                                data-toggle="collapse"
                                data-target="#get-all-central-po-product-by-customer-links"
                                aria-expanded="false"
                                aria-controls="get-all-central-po-product-by-customer-links"
                                style="cursor: pointer;  "
                                class="glyphicon1 all-central-po-product-by-customer-links"
                                rel="all-purchase-order-links"
                        >
                                <span class="all-central-po-product-by-customer-links">
                                   links <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                </span>
                        </td>
                        <td>object</td>
                    </tr>
                    <tr  class="collapse" id="get-all-central-po-product-by-customer-links">
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
                                data-target="#get-all-central-po-product-by-customer-meta"
                                aria-expanded="false"
                                aria-controls="get-all-central-po-product-by-customer-meta"
                                style="cursor: pointer;  "

                                class="glyphicon1 all-central-po-product-by-customer-meta"
                                rel="all-c-meta">
                                    <span class="all-central-po-product-by-customer-meta">
                                       meta <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                    </span>
                        </td>
                        <td>object</td>
                    </tr>
                    <tr  class="collapse" id="get-all-central-po-product-by-customer-meta">
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
                                        <td>links</td>
                                        <td><i>String</i> Default: <code>NULL</code>, array links of meta field</td>
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
    <div class="col-sm-12" id="central-get-po-product-by-customer">
        <h3>{{ EndPointHelper::get('Products by Customer') }}</h3>
        <p>
            {{ EndPointHelper::getDescription('Products by Customer', 'id') }}
        </p>
        <br>
        <div class="" style="position:relative;padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-central-get-po-product-by-customer" aria-hidden="true">
                <span
                        data-toggle="collapse"
                        data-target="#route-central-get-po-product-by-customer"
                        aria-expanded="false"
                        aria-controls="route-central-get-po-product-by-customer"
                        style="cursor: pointer"
                        class="i-central-get-po-product-by-customer"
                > <code>api/products/{id}</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                </span>
            </span>

            <div class="collapse api-route" id="route-central-get-po-product-by-customer">
                <div class="badge-success central-get-po-product-by-customer hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="central-get-po-product-by-customer">Copy</div>
                </div>
                <div class="api_ noselect" id="central-get-po-product-by-customer">
                    {{ getenv('PO_URL')}}products/{id}
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Specific Products by Customer</code> <small> </small><br>
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
                    <td><i>Integer</i> The unique id for the Products by Customer to be retrieved</td>
                </tr>
                </tbody>
            </table>
        </div>
        <h4>Response
            <span
                    data-toggle="collapse"
                    data-target="#central-get-po-product-by-customer-response"
                    aria-expanded="false"
                    aria-controls="central-get-po-product-by-customer-response"
                    style="cursor: pointer; top: 2px !important;"
                    class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre style="font-weight:bold" class="collapse" id="central-get-po-product-by-customer-response">
  {
    <span class="slctrl-attr">"data"</span>: {
        <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">78</span>,
        <span class="slctrl-attr">"sku"</span>: <span class="slctrl-string">"424575"</span>,
        <span class="slctrl-attr">"name"</span>: <span class="slctrl-string">"Test Product 1"</span>,
        <span class="slctrl-attr">"category_id"</span>: <span class="slctrl-number">55</span>,
        <span class="slctrl-attr">"description"</span>: <span class="slctrl-string">"test description"</span>,
        <span class="slctrl-attr">"units_per_carton"</span>: <span class="slctrl-number">24</span>,
        <span class="slctrl-attr">"image"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2021-07-18T20:49:21.000000Z"</span>,
        <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2021-07-19T22:54:06.000000Z"</span>,
        <span class="slctrl-attr">"customer_id"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"created_by"</span>: <span class="slctrl-number">190</span>,
        <span class="slctrl-attr">"deleted_at"</span>: <span class="slctrl-literal">null</span>,
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
        <span class="slctrl-attr">"category_sku"</span>: <span class="slctrl-string">"55-424575"</span>,
        <span class="slctrl-attr">"inbound_associated"</span>: <span class="slctrl-literal">false</span>
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
                                data-target="#central-get-po-product-by-customer-data"
                                aria-expanded="false"
                                aria-controls="central-get-po-product-by-customer-data"
                                style="cursor: pointer;  "
                                class="glyphicon1 central-po-product-by-customer-data"
                                rel="purchase-order-results"
                        >
                            <span class="central-po-product-by-customer-data">
                               data <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                            </span>
                        </td>
                        <td>object</td>
                    </tr>
                    <tr  class="collapse" id="central-get-po-product-by-customer-data">
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
        </div>
        <p>
            {{ EndPointHelper::userGuide() }}
        </p>
    </div><!-- End specified resource -->




    <!-- Create resource -->
    <div class="col-sm-12" id="create-central-po-product-by-customer">
        <h3>{{ EndPointHelper::create('Products by Customer') }}</h3>
        <p>
            {{ EndPointHelper::createDescription('Products by Customer', array('sku', 'name', 'category_id', 'description', 'units_per_carton', 'is_system_generated', 'classifiation_code', 'duty_rate')) }}
        </p>
        <br>
        <div class="" style="position:relative;padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-create-central-po-product-by-customer" aria-hidden="true">
                <span
                        data-toggle="collapse"
                        data-target="#route-create-central-po-product-by-customer"
                        aria-expanded="false"
                        aria-controls="route-create-central-po-product-by-customer"
                        style="cursor: pointer"
                        class="i-create-central-po-product-by-customer"
                > <code>api/products/create</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                </span>
            </span>

            <div class="collapse api-route" id="route-create-central-po-product-by-customer">
                <div class="badge-success create-central-po-product-by-customer hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="create-central-po-product-by-customer">Copy</div>
                </div>
                <div class="api_ noselect" id="create-central-po-product-by-customer">
                    {{ getenv('PO_URL')}}api/products/create
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>sku</code>,
            <code>name</code>,
            <code>category_id</code>,
            <code>description</code>,
            <code>units_per_carton</code>,
            <code>is_system_generated</code>,
            <code>classifiation_code</code>,
            <code>duty_rate'
            </code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Create Products by Customer</code> <small> </small><br>
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
    <span class="slctrl-attr">"sku"</span>: <span class="slctrl-string">"{sku}"</span>,
    <span class="slctrl-attr">"name"</span>: <span class="slctrl-string">"{name}"</span>,
    <span class="slctrl-attr">"category_id"</span>: <span class="slctrl-number">{category_id}</span>,
    <span class="slctrl-attr">"description"</span>: <span class="slctrl-string">"{description}"</span>,
    <span class="slctrl-attr">"units_per_carton"</span>: <span class="slctrl-number">{units_per_carton}</span>,
    <span class="slctrl-attr">"is_system_generated"</span>: <span class="slctrl-string">"{is_system_generated}"</span>,
    <span class="slctrl-attr">"classifiation_code"</span>: <span class="slctrl-string">"{classifiation_code}"</span>,
    <span class="slctrl-attr">"duty_rate"</span>: <span class="slctrl-string">"{duty_rate}"</span>,
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
                    <td><i>Integer</i> The unique id for the Products by Customer to be retrieved</td>
                </tr>
                </tbody>
            </table>
        </div>
        <h4>Response
            <span
                    data-toggle="collapse"
                    data-target="#create-central-po-product-by-customer-response"
                    aria-expanded="false"
                    aria-controls="create-central-po-product-by-customer-response"
                    style="cursor: pointer; top: 2px !important;"
                    class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre style="font-weight:bold" class="collapse" id="create-central-po-product-by-customer-response">
{
    <span class="slctrl-attr">"message"</span>: <span class="slctrl-string">"Product has been created."</span>,
    <span class="slctrl-attr">"data"</span>: {
        <span class="slctrl-attr">"sku"</span>: <span class="slctrl-string">"voluptas"</span>,
        <span class="slctrl-attr">"name"</span>: <span class="slctrl-string">"minus"</span>,
        <span class="slctrl-attr">"category_id"</span>: <span class="slctrl-number">12</span>,
        <span class="slctrl-attr">"description"</span>: <span class="slctrl-string">"sit"</span>,
        <span class="slctrl-attr">"units_per_carton"</span>: <span class="slctrl-string">"14"</span>,
        <span class="slctrl-attr">"unit_price"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"classification_code"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"duty_rate"</span>: <span class="slctrl-string">"18"</span>,
        <span class="slctrl-attr">"carton_dimensions"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"is_for_classification_code"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"upc_number"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"carton_upc"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"country_from"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"country_to"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"product_classification_description"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"unit_weight"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"unit_dimensions"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"additional_classification_code"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"image"</span>: <span class="slctrl-string">"products/images/4Gl0CLJ1KYYSgwKYYZycqXPqDxesczCO88X4PSwM.jpg"</span>,
        <span class="slctrl-attr">"created_by"</span>: <span class="slctrl-number">2</span>,
        <span class="slctrl-attr">"is_system_generated"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"customer_id"</span>: <span class="slctrl-number">25</span>,
        <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2022-05-17T16:21:11.000000Z"</span>,
        <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2022-05-17T16:21:11.000000Z"</span>,
        <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">873</span>,
        <span class="slctrl-attr">"category_sku"</span>: <span class="slctrl-string">"12-voluptas"</span>,
        <span class="slctrl-attr">"inbound_associated"</span>: <span class="slctrl-literal">false</span>
    }
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
                        <td>message</td>
                        <td>  Message response of the created data </td>
                    </tr>
                    <tr>
                        <td
                                data-toggle="collapse"
                                data-target="#create-central-po-product-by-customer-data"
                                aria-expanded="false"
                                aria-controls="create-central-po-product-by-customer-data"
                                style="cursor: pointer;  "
                                class="glyphicon1 create-central-po-product-by-customer-data"
                                rel="purchase-order-results"
                        >
                            <span class="create-central-po-product-by-customer-data">
                               data <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                            </span>
                        </td>
                        <td>object</td>
                    </tr>
                    <tr  class="collapse" id="create-central-po-product-by-customer-data">
                        <td colspan="2">
                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                <table class="table-bordered">
                                    <thead></thead>
                                    <tbody>
                                    <tr>
                                        <td>sku</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>, </td>
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
                                        <td><i>String</i></td>
                                    </tr>
                                    <tr>
                                        <td>units_per_carton</td>
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
                                        <td>carton_upc</td>
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
                                        <td>additional_classification_code</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>image</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>created_by</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>is_system_generated</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>customer_id</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
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
        </div>
        <p>
            {{ EndPointHelper::userGuide() }}
        </p>
    </div><!-- End create resource -->














    <!-- Update resource -->
    <div class="col-sm-12" id="update-central-po-product-by-customer">
        <h3>{{ EndPointHelper::update('Products by Customer') }}</h3>
        <p>
            {{ EndPointHelper::updateDescription('Products by Customer', array('id','sku', 'name', 'category_id', 'description', 'units_per_carton', 'is_system_generated', 'classifiation_code', 'duty_rate')) }}
        </p>
        <br>
        <div class="" style="position:relative;padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-update-central-po-product-by-customer" aria-hidden="true">
                <span
                        data-toggle="collapse"
                        data-target="#route-update-central-po-product-by-customer"
                        aria-expanded="false"
                        aria-controls="route-update-central-po-product-by-customer"
                        style="cursor: pointer"
                        class="i-update-central-po-product-by-customer"
                > <code>api/products/update/{id}</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                </span>
            </span>

            <div class="collapse api-route" id="route-update-central-po-product-by-customer">
                <div class="badge-success update-central-po-product-by-customer hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="update-central-po-product-by-customer">Copy</div>
                </div>
                <div class="api_ noselect" id="update-central-po-product-by-customer">
                    {{ getenv('PO_URL')}}api/products/update/{id}
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>id</code>,<code>sku</code>,
            <code>name</code>,
            <code>category_id</code>,
            <code>description</code>,
            <code>units_per_carton</code>,
            <code>is_system_generated</code>,
            <code>classifiation_code</code>,
            <code>duty_rate
            </code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Update Products by Customer</code> <small> </small><br>
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
    <span class="slctrl-attr">id</span>: <span class="slctrl-number">{id}</span>,
    <span class="slctrl-attr">"sku"</span>: <span class="slctrl-string">"{sku}"</span>,
    <span class="slctrl-attr">"name"</span>: <span class="slctrl-string">"{name}"</span>,
    <span class="slctrl-attr">"category_id"</span>: <span class="slctrl-number">{category_id}</span>,
    <span class="slctrl-attr">"description"</span>: <span class="slctrl-string">"{description}"</span>,
    <span class="slctrl-attr">"units_per_carton"</span>: <span class="slctrl-number">{units_per_carton}</span>,
    <span class="slctrl-attr">"image"</span>: <span class="slctrl-number">{image}</span>,
    <span class="slctrl-attr">"is_system_generated"</span>: <span class="slctrl-string">"{is_system_generated}"</span>,
    <span class="slctrl-attr">"classifiation_code"</span>: <span class="slctrl-string">"{classifiation_code}"</span>,
    <span class="slctrl-attr">"duty_rate"</span>: <span class="slctrl-string">"{duty_rate}"</span>,
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
                    <td><i>Integer</i> The unique id for the Products by Customer to be retrieved</td>
                </tr>
                </tbody>
            </table>
        </div>
        <h4>Response
            <span
                    data-toggle="collapse"
                    data-target="#update-central-po-product-by-customer-response"
                    aria-expanded="false"
                    aria-controls="update-central-po-product-by-customer-response"
                    style="cursor: pointer; top: 2px !important;"
                    class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre style="font-weight:bold" class="collapse" id="update-central-po-product-by-customer-response">
{
    <span class="slctrl-attr">"message"</span>: <span class="slctrl-string">"Product has been updated."</span>,
    <span class="slctrl-attr">"data"</span>: {
        <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">873</span>,
        <span class="slctrl-attr">"sku"</span>: <span class="slctrl-string">"voluptas"</span>,
        <span class="slctrl-attr">"name"</span>: <span class="slctrl-string">"minus"</span>,
        <span class="slctrl-attr">"category_id"</span>: <span class="slctrl-number">12</span>,
        <span class="slctrl-attr">"description"</span>: <span class="slctrl-string">"sit"</span>,
        <span class="slctrl-attr">"units_per_carton"</span>: <span class="slctrl-string">"14"</span>,
        <span class="slctrl-attr">"unit_price"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"classification_code"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"duty_rate"</span>: <span class="slctrl-string">"18"</span>,
        <span class="slctrl-attr">"carton_dimensions"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"is_for_classification_code"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"upc_number"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"carton_upc"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"country_from"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"country_to"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"product_classification_description"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"unit_weight"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"unit_dimensions"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"additional_classification_code"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"image"</span>: <span class="slctrl-string">"products/images/4Gl0CLJ1KYYSgwKYYZycqXPqDxesczCO88X4PSwM.jpg"</span>,
        <span class="slctrl-attr">"created_by"</span>: <span class="slctrl-number">2</span>,
        <span class="slctrl-attr">"is_system_generated"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"customer_id"</span>: <span class="slctrl-number">25</span>,
        <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2022-05-17T16:21:11.000000Z"</span>,
        <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2022-05-17T16:21:11.000000Z"</span>,
        <span class="slctrl-attr">"category_sku"</span>: <span class="slctrl-string">"12-voluptas"</span>,
        <span class="slctrl-attr">"inbound_associated"</span>: <span class="slctrl-literal">false</span>
    }
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
                        <td>message</td>
                        <td><i>String</i> Default: <code>NULL</code>, </td>
                    </tr>
                    <tr>
                        <td
                                data-toggle="collapse"
                                data-target="#update-central-po-product-by-customer-data"
                                aria-expanded="false"
                                aria-controls="update-central-po-product-by-customer-data"
                                style="cursor: pointer;  "
                                class="glyphicon1 create-central-po-product-by-customer-data"
                                rel="purchase-order-results"
                        >
                            <span class="update-central-po-product-by-customer-data">
                               data <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                            </span>
                        </td>
                        <td>object</td>
                    </tr>
                    <tr  class="collapse" id="update-central-po-product-by-customer-data">
                        <td colspan="2">
                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                <table class="table-bordered">
                                    <thead></thead>
                                    <tbody>
                                    <tr>
                                        <td>sku</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>, </td>
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
                                        <td><i>String</i></td>
                                    </tr>
                                    <tr>
                                        <td>units_per_carton</td>
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
                                        <td>carton_upc</td>
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
                                        <td>additional_classification_code</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>image</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>created_by</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>is_system_generated</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>customer_id</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
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
        </div>
        <p>
            {{ EndPointHelper::userGuide() }}
        </p>
    </div><!-- End update resource -->


    <!-- Delete resource -->
    <div class="col-sm-12" id="delete-central-po-product-by-customer">
        <h3>{{ EndPointHelper::delete('Products by Customer') }}</h3>
        <p>
            {{ EndPointHelper::deleteDescription('Products by Customer', array('id')) }}
        </p>
        <br>
        <div class="" style="position:relative;padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-delete-central-po-product-by-customer" aria-hidden="true">
                <span
                        data-toggle="collapse"
                        data-target="#route-delete-central-po-product-by-customer"
                        aria-expanded="false"
                        aria-controls="route-delete-central-po-product-by-customer"
                        style="cursor: pointer"
                        class="i-delete-central-po-product-by-customer"
                > <code>api/products/delete/{id}</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                </span>
            </span>

            <div class="collapse api-route" id="route-delete-central-po-product-by-customer">
                <div class="badge-success delete-central-po-product-by-customer hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="delete-central-po-product-by-customer">Copy</div>
                </div>
                <div class="api_ noselect" id="delete-central-po-product-by-customer">
                    {{ getenv('PO_URL')}}products/delete/{id}
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Specific Products by Customer</code> <small> </small><br>
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
                    <td><i>Integer</i> The unique id for the Products by Customer to be retrieved</td>
                </tr>
                </tbody>
            </table>
        </div>
        <h4>Response
            <span
                    data-toggle="collapse"
                    data-target="#delete-central-po-product-by-customer-response"
                    aria-expanded="false"
                    aria-controls="delete-central-po-product-by-customer-response"
                    style="cursor: pointer; top: 2px !important;"
                    class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre style="font-weight:bold" class="collapse" id="delete-central-po-product-by-customer-response">
 {
    <span class="slctrl-attr">"message"</span>: <span class="slctrl-string">"Product has been deleted."</span>
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
                        <td>
                            message
                        </td>
                        <td>Message result for the deleted data</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <p>
            {{ EndPointHelper::userGuide() }}
        </p>
    </div><!-- End delete resource -->
















</div> <!-- End Purchase Order -->

