<div class="col-sm-12" id="invoice"> <!-- start Invoice -->
    <h3 class="page-header">Invoice</h3>
    <!-- Invoice list resource -->
    <div class="col-sm-12" id="invoice-list">
        <h3>{{ EndPointHelper::getAll('Quickbook Invioces') }}</h3>
        <p>
            {{ EndPointHelper::getAllDescription("Quickbook Invioces")}}
        </p>
        <br>
        <div class="" style="position:relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-quickbooks-invoice-shipmentId" aria-hidden="true">
                <span
                    data-toggle="collapse"
                    data-target="#route-quickbooks-invoice-shipmentId"
                    aria-expanded="false"
                    aria-controls="route-quickbooks-invoice-shipmentId"
                    style="cursor: pointer"
                    class="i-quickbooks-invoice-shipmentId"
                > <code>api/quickbooks/invoices/{shipmentId?}</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                </span>
            </span>

            <div class="collapse api-route" id="route-quickbooks-invoice-shipmentId">
                <div class="badge-success quickbooks-invoice-shipmentId hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="quickbooks-invoice-shipmentId">Copy</div>
                </div>

                <div class="api_ noselect" id="quickbooks-invoice-shipmentId">
                    {{ config('app.url')}}/api/quickbooks/invoices/{shipmentId?}
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>shipmentId</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>List of Quickbook Invioce</code> <small> </small><br>
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
   <span class="slctrl-attr">"shipmentId"</span>: <span class="slctrl-number">{shipmentId}</span>,
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
                    <td>shipmentId<span class="badge-danger">required</span></td>
                    <td><i>Integer</i> The unique shipmentId for the Quickbook Invioces to be retrieved</td>
                </tr>
                </tbody>
            </table>
        </div>
        <h4>Response
            <span
                data-toggle="collapse"
                data-target="#quickbooks-invoice-shipmentId-response"
                aria-expanded="false"
                aria-controls="quickbooks-invoice-shipmentId-response"
                style="cursor: pointer; top: 2px !important;"
                class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre style="font-weight:bold" class="collapse" id="quickbooks-invoice-shipmentId-response">
{
    <span class="slctrl-attr">"data"</span>: [
        {
            <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">1</span>,
            <span class="slctrl-attr">"qbo_customer_id"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"qbo_customer_name"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"invoice_num"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"qbo_bill_to_email"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"qbo_bill_to_address"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"term"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"invoice_date"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"due_date"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"shifl_office_from_id"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"shifl_office_to_id"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"total_tax"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"total_amount"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"home_total_amount"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"balance"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"home_balance"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"currency_ref"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"home_currency_ref"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"exchange_rate"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"exchange_rate_info"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"qbo_id"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"qbo_term_id"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"qbo_term_name"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"qbo_term_days"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"qbo_customer_memo"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"qbo_country"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"qbo_company"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"qbo_company_realmid"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"qbo_tax_detail"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"global_tax_calculation"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"allow_credit_card_payment"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"allow_online_ach_payment"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"is_email_sent"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"email_sent_count"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"email_sent_at"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"qbo_customer_gstin"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"sync_token"</span>: <span class="slctrl-literal">null</span>
        }, {...}, ...
    ],
    <span class="slctrl-attr">"links"</span>: {
        <span class="slctrl-attr">"first"</span>: <span class="slctrl-string">"/?page=1"</span>,
       <span class="slctrl-attr">"last"</span>: <span class="slctrl-string">"/?page=1"</span>,
       <span class="slctrl-attr">"prev"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"next"</span>: <span class="slctrl-literal">null</span>
    },
    <span class="slctrl-attr">"meta"</span>: {
       <span class="slctrl-attr">"current_page"</span>: <span class="slctrl-number">1</span>,
       <span class="slctrl-attr">"from"</span>: <span class="slctrl-number">1</span>,
       <span class="slctrl-attr">"last_page"</span>: <span class="slctrl-attr">1</span>,
       <span class="slctrl-attr">"path"</span>: <span class="slctrl-attr">"/"</span>,
       <span class="slctrl-attr">"per_page"</span>: <span class="slctrl-string">"5"</span>,
       <span class="slctrl-attr">"to"</span>: <span class="slctrl-number">2</span>,
       <span class="slctrl-attr">"total"</span>:<span class="slctrl-number">2</span>
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
                            data-target="#get-qinvoice-data"
                            aria-expanded="false"
                            aria-controls="get-qinvoice-data"
                            style="cursor: pointer;  "
                            class="glyphicon1 qinvoice-data"
                            rel="qinvoice-data"
                        >
                            <span class="qinvoice-data">
                               data <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                            </span>
                        </td>
                        <td>object</td>
                    </tr>
                    <tr  class="collapse" id="get-qinvoice-data">
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
                                            <td>qbo_customer_id</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>qbo_customer_name</td>
                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                        </tr>
                                        <tr>
                                            <td>invoice_num</td>
                                            <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                        </tr>
                                        <tr>
                                            <td>qbo_bill_to_email</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>qbo_bill_to_address</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>term</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>invoice_date</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>due_date</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>shifl_office_from_id</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>shifl_office_to_id</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>total_tax</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>total_amount</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>home_total_amount</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>balance</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>currency_ref</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>home_currency_ref</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>exchange_rate</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>exchange_rate_info</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>qbo_id</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>qbo_term_id</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>qbo_term_name</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>qbo_term_days</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>qbo_customer_memo</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>qbo_country</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>qbo_company</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>qbo_company_realmid</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>qbo_tax_detail</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>global_tax_calculation</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>allow_credit_card_payment</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>allow_online_ach_payment</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>is_email_sent</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>email_sent_count</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>email_sent_at</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>qbo_customer_gstin</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>sync_token</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td
                            data-toggle="collapse"
                            data-target="#get-qinvoice-links"
                            aria-expanded="false"
                            aria-controls="get-qinvoice-links"
                            style="cursor: pointer;  "

                            class="glyphicon1 qinvoice-links"
                            rel="all-c-links"
                        >
                            <span class="qinvoice-links">
                               links <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                            </span>
                        </td>
                        <td>object</td>
                    </tr>
                    <tr  class="collapse" id="get-qinvoice-links">
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
                            data-target="#get-qinvoice-meta"
                            aria-expanded="false"
                            aria-controls="get-qinvoice-meta"
                            style="cursor: pointer;  "
                            class="glyphicon1 qinvoice-meta"
                            rel="all-c-meta"
                        >
                            <span class="qinvoice-meta">
                               meta <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                            </span>
                        </td>
                        <td>object</td>
                    </tr>
                    <tr  class="collapse" id="get-qinvoice-meta">
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
    </div><!-- End Invoice list resource -->

    <div class="col-sm-12" id="invoice-specified"><!-- Invoice specified resource -->
        <h3>{{ EndPointHelper::get('Quickbook Invoice') }}</h3>
        <p>
            {{ EndPointHelper::getDescription('Quickbook Invoice', 'id') }}
        </p>
        <br>
        <div class="" style="position:relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-quickbooks-invoice-id" aria-hidden="true">
                <span
                    data-toggle="collapse"
                    data-target="#route-quickbooks-invoice-id"
                    aria-expanded="false"
                    aria-controls="route-quickbooks-invoice-id"
                    style="cursor: pointer"
                    class="i-quickbooks-invoice-id"
                > <code>api/quickbooks/invoice/{id}</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                </span>
            </span>
            <div class="collapse api-route" id="route-quickbooks-invoice-id">
                <div class="badge-success quickbooks-invoice-id hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="quickbooks-invoice-id">Copy</div>
                </div>

                <div class="api_ noselect" id="quickbooks-invoice-id">
                    {{ config('app.url')}}/api/quickbooks/invoice/{id}
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Quickbook Invoice (Specific Invoice) </code> <small> </small><br>
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
    <span class="slctrl-attr">"invoice_id"</span>: <span class="slctrl-number">{id}</span>,
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
                    <td><i>Integer</i> The unique shipmentId for the Quickbook Invoice to be retrieved</td>
                </tr>
                </tbody>
            </table>
        </div>
        <h4>Response
            <span
                data-toggle="collapse"
                data-target="#quickbooks-invoice-id-response"
                aria-expanded="false"
                aria-controls="quickbooks-invoice-id-response"
                style="cursor: pointer; top: 2px !important;"
                class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre style="font-weight:bold" class="collapse" id="quickbooks-invoice-id-response">
{
    <span class="slctrl-attr">"shipment_reference_number"</span>: <span class="slctrl-attr">1</span>,
    <span class="slctrl-attr">"invoice_items"</span>: [
        {
            <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">1</span>,
            <span class="slctrl-attr">"invoice_id"</span>: <span class="slctrl-number">1</span>,
            <span class="slctrl-attr">"qbo_service_id"</span>: <span class="slctrl-number">1</span>,
            <span class="slctrl-attr">"description"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"quantity"</span>: <span class="slctrl-string">"1.00"</span>,
            <span class="slctrl-attr">"rate"</span>: <span class="slctrl-string">"3865.00"</span>,
            <span class="slctrl-attr">"amount"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"qbo_service_name"</span>: <span class="slctrl-string">"Ocean Freight"</span>,
            <span class="slctrl-attr">"qbo_tax_code"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2021-04-20T04:48:13.000000Z"</span>,
            <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2021-04-20T04:48:13.000000Z"</span>
        }
    ],
    <span class="slctrl-attr">"invoice_number"</span>: <span class="slctrl-number">1</span>,
    <span class="slctrl-attr">"balance"</span>: <span class="slctrl-string">"0.00"</span>,
    <span class="slctrl-attr">"total_amount"</span>: <span class="slctrl-literal">null</span>,
    <span class="slctrl-attr">"bill_to"</span>: <span class="slctrl-string">"SOUND AROUND INC"</span>,
    <span class="slctrl-attr">"bill_to_address"</span>: <span class="slctrl-string">"SOUND AROUND INC"</span>,
    <span class="slctrl-attr">"date"</span>: <span class="slctrl-string">"April 24, 2021"</span>,
    <span class="slctrl-attr">"due_date"</span>: <span class="slctrl-string">"April 19, 2021"</span>,
    <span class="slctrl-attr">"payment_method"</span>: <span class="slctrl-literal">"null</span>,
    <span class="slctrl-attr">"paid_on"</span>: <span class="slctrl-literal">null</span>,
    <span class="slctrl-attr">"mbl_number"</span>: <span class="slctrl-string">"SMLMSHFO0K993100"</span>,
    <span class="slctrl-attr">"from"</span>: <span class="slctrl-string">""SHANGHAI"</span>,
    <span class="slctrl-attr">"to</span>: <span class="slctrl-string">"LOS ANGELES"</span>,
    <span class="slctrl-attr">"etd"</span>: <span class="slctrl-string">"2021-03-29 07:28:00"</span>,
    <span class="slctrl-attr">"eta"</span>: <span class="slctrl-string">"2021-04-24 07:28:00"</span>,
    <span class="slctrl-attr">"suppliers"</span>: [
        {
            <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">1</span>,
            <span class="slctrl-attr">"hbl_copy"</span>: <span class="slctrl-string">"shipment/hbl_copy/FES2103156-提单H单.PDF_6644_1_hbl.pdf"</span>,
            <span class="slctrl-attr">"packing_list"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"commercial_documents"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"commercial_invoice"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"po_id"</span>: <span class="slctrl-string">""</span>,
            <span class="slctrl-attr">"po_num"</span>: <span class="slctrl-number">6809</span>,
            <span class="slctrl-attr">"volume"</span>: <span class="slctrl-string">""</span>,
            <span class="slctrl-attr">"supplier_id"</span>: <span class="slctrl-number">458</span>,
            <span class="slctrl-attr">"kg"</span>: <span class="slctrl-string">"8509.000"</span>,
            <span class="slctrl-attr">"cbm"</span>: <span class="slctrl-string">"68.000"</span>,
            <span class="slctrl-attr">"incoterm_id"</span>: <span class="slctrl-number">1</span>,
            <span class="slctrl-attr">"hbl_num"</span>: <span class="slctrl-string">"SQFN706644"</span>,
            <span class="slctrl-attr">"product_description"</span>: <span class="slctrl-string">"PORTABLE SAUNA"</span>,
            <span class="slctrl-attr">"total_cartons"</span>: <span class="slctrl-string">"670"</span>,
            <span class="slctrl-attr">"bl_status"</span>: <span class="slctrl-string">"Telex Released"</span>,
            <span class="slctrl-attr">"ams_num"</span>: <span class="slctrl-string">"SQFN706644"</span>,
            <span class="slctrl-attr">"containers"</span>: [
                {
                    <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">1</span>,
                    <span class="slctrl-attr">"shipment_suppliers_id"</span>: <span class="slctrl-number">1615520851194</span>,
                    <span class="slctrl-attr">"container_num"</span>: <span class="slctrl-string">""</span>,
                    <span class="slctrl-attr">"cartons"</span>: <span class="slctrl-string">"670"</span>,
                    <span class="slctrl-attr">"size"</span>: <span class="slctrl-string">"68.000"</span>,
                    <span class="slctrl-attr">"weight"</span>: <span class="slctrl-string">"8509.000"</span>,
                    <span class="slctrl-attr">"container_id"</span>: <span class="slctrl-string">1616571042200</span>
                }
            ],
            <span class="slctrl-attr">"cargo_ready_date"</span>: <span class="slctrl-string">""</span>,
            <span class="slctrl-attr">"marks"</span>: <span class="slctrl-string">"N/M"</span>,
            <span class="slctrl-attr">"bol_shipper_address"</span>: <span class="slctrl-string">"#3 BUILDING, SHUSHAN INTERNATIONAL ENTERPRISES PARK,  LIANDO U VALLEY, NO. 1499, ZHENXING ROAD, SHUSHAN  DISTRICT, HEFEI ,ANHUI,CHINA"</span>,
            <span class="slctrl-attr">"bol_consignee_address"</span>: <span class="slctrl-string">"1600 63RD ST  BROOKLYN NY"</span>,
            <span class="slctrl-attr">"bol_notify_address"</span>: <span class="slctrl-string">"1600 63RD ST  BROOKLYN NY"</span>,
            <span class="slctrl-attr">"bol_notify_party"</span>: <span class="slctrl-string">"SOUND AROUND "</span>,
            <span class="slctrl-attr">"name"</span>: <span class="slctrl-string">"HEFEI SMARTMAK CO .,LTD"</span>,
            <span class="slctrl-attr">"address"</span>: <span class="slctrl-literal">null</span>
        }
    ],
    <span class="slctrl-attr">"purchase_orders"</span>: [],
    <span class="slctrl-attr">"containers"</span>: [
        <span class="slctrl-string">"SMCU1122410"</span>
    ],
    <span class="slctrl-attr">"schedule"</span>: {
        <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"eta"</span>: <span class="slctrl-string">"2021-04-24 07:28:00"</span>,
        <span class="slctrl-attr">"etd"</span>: <span class="slctrl-string">"2021-03-29 07:28:00"</span>,
        <span class="slctrl-attr">"location_from"</span>: <span class="slctrl-attr">5</span>,
        <span class="slctrl-attr">"location_to"</span>: <span class="slctrl-number">2</span>,
        <span class="slctrl-attr">"mode"</span>: <span class="slctrl-string">"Ocean"</span>,
        <span class="slctrl-attr">"legs"</span>: [],
        <span class="slctrl-attr">"type"</span>: <span class="slctrl-string">"FCL"</span>,
        <span class="slctrl-attr">"carrier_name"</span>: {
            <span class="slctrl-attr">"name"</span>: <span class="slctrl-string">"SM LINE"</span>,
            <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">1</span>
        },
        <span class="slctrl-attr">"size_id"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"price"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"selling_size_id"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"selling_price"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"sell_rates"</span>: [],
        <span class="slctrl-attr">"buy_rates"</span>: [],
        <span class="slctrl-attr">"is_confirmed"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"etaError"</span>: {},
        <span class="slctrl-attr">"etdError"</span>: {},
        <span class="slctrl-attr">"size_name"</span>: <span class="slctrl-string">""</span>,
        <span class="slctrl-attr">"selling_size_name"</span>: <span class="slctrl-string">""</span>,
        <span class="slctrl-attr">"location_to_name"</span>: <span class="slctrl-string">"LOS ANGELES"</span>,
        <span class="slctrl-attr">"location_from_name"</span>: <span class="slctrl-string">SHANGHAI</span>
    },
    <span class="slctrl-attr">"payment_status"</span>: <span class="slctrl-string">"Paid"</span>
}  </pre>
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
                                shipment_reference_number
                            </td>
                            <td>String</td> Default: <code>NULL</code>,
                        </tr>
                        <tr>
                            <td
                                data-toggle="collapse"
                                data-target="#get-invoice-items"
                                aria-expanded="false"
                                aria-controls="get-invoice-items"
                                style="cursor: pointer;  "

                                class="glyphicon1 invoice-items"
                                rel="invoice-items"
                            >
                                <span class="invoice-items">
                                   invoice_items <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                </span>
                            </td>
                        <td>object</td>
                    </tr>
                    <tr  class="collapse" id="get-invoice-items">
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
                                            <td>invoice_id</td>
                                            <td><i>Integer</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>qbo_service_id</td>
                                            <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                        </tr>
                                        <tr>
                                            <td>description</td>
                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                        </tr>
                                        <tr>
                                            <td>quantity</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>rate</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>amount</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>qbo_service_name</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>qbo_tax_code</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>created_at</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>updated_at</td>
                                            <td><i>String</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>

                        <tr>
                            <td>invoice_number</td>
                            <td><i>Integer</i> Default: <code>NULL</code>,</td>
                        </tr>
                        <tr>
                            <td>balance</td>
                            <td><i>String</i> Default: <code>NULL</code>,</td>
                        </tr>
                        <tr>
                            <td>total_amount</td>
                            <td><i>String</i> Default: <code>NULL</code>,</td>
                        </tr>
                        <tr>
                            <td>bill_to</td>
                            <td><i>String</i> Default: <code>NULL</code>,</td>
                        </tr>
                        <tr>
                            <td>bill_to_address</td>
                            <td><i>String</i> Default: <code>NULL</code>,</td>
                        </tr>
                        <tr>
                            <td>date</td>
                            <td><i>Date</i> Default: <code>NULL</code>,</td>
                        </tr>
                        <tr>
                            <td>due_date</td>
                            <td><i>Date</i> Default: <code>NULL</code>,</td>
                        </tr>
                        <tr>
                            <td>payment_method</td>
                            <td><i>String</i> Default: <code>NULL</code>,</td>
                        </tr>
                        <tr>
                            <td>payment_method</td>
                            <td><i>String</i> Default: <code>NULL</code>,</td>
                        </tr>
                        <tr>
                            <td>paid_on</td>
                            <td><i>String</i> Default: <code>NULL</code>,</td>
                        </tr>
                        <tr>
                            <td>mbl_number</td>
                            <td><i>String</i> Default: <code>NULL</code>,</td>
                        </tr>
                        <tr>
                            <td>from</td>
                            <td><i>String</i> Default: <code>NULL</code>,</td>
                        </tr>
                        <tr>
                            <td>to</td>
                            <td><i>String</i> Default: <code>NULL</code>,</td>
                        </tr>
                        <tr>
                            <td>etd</td>
                            <td><i>String</i> Default: <code>NULL</code>,</td>
                        </tr>
                        <tr>
                            <td>eta</td>
                            <td><i>String</i> Default: <code>NULL</code>,</td>
                        </tr>

                        </tr>
                            <td
                                data-toggle="collapse"
                                data-target="#get-qi-suppliers"
                                aria-expanded="false"
                                aria-controls="get-qi-suppliers"
                                style="cursor: pointer;  "

                                class="glyphicon1 qi-suppliers"
                                rel="qi-suppliers"
                            >
                                <span class="qi-suppliers">
                                   suppliers <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                </span>
                            </td>
                            <td>object</td>
                        </tr>
                        <tr  class="collapse" id="get-qi-suppliers">
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
                                                <td>hbl_copy</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>packing_list</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>commercial_documents</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>commercial_invoice</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>po_id</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>po_num</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>volume</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>supplier_id</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>kg</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>cbm</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>incoterm_id</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>hbl_num</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>product_description</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>total_cartons</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>bl_status</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>ams_num</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td
                                                    data-toggle="collapse"
                                                    data-target="#get-qi-containers"
                                                    aria-expanded="false"
                                                    aria-controls="get-qi-containers"
                                                    style="cursor: pointer;  "

                                                    class="glyphicon1 qi-containers"
                                                    rel="qi-containers"
                                                >
                                                    <span class="qi-containers">
                                                       containers <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                                    </span>
                                                </td>

                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>


                                            <tr  class="collapse" id="get-qi-containers">
                                                <td colspan="2">
                                                    <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                        <table class="table-bordered">
                                                            <thead></thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>id</td>
                                                                    <td><i>Integer</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>shipment_suppliers_id</td>
                                                                    <td><i>String</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>container_num</td>
                                                                    <td><i>String</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>cartons</td>
                                                                    <td><i>String</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>size</td>
                                                                    <td><i>String</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>weight</td>
                                                                    <td><i>String</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>container_id</td>
                                                                    <td><i>String</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>cargo_ready_date</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>marks</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>bol_shipper_address</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>bol_consignee_address</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>bol_notify_address</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>bol_notify_party</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>name</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>address</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>purchase_orders</td>
                            <td>object</td>
                        </tr>
                        <tr>
                            <td>containers</td>
                            <td>object</td>
                        </tr>
                        </tr>
                            <td

                                    data-toggle="collapse"
                                    data-target="#get-qi-schedule"
                                    aria-expanded="false"
                                    aria-controls="get-qi-schedule"
                                    style="cursor: pointer;  "

                                    class="glyphicon1 qi-schedule"
                                    rel="qi-schedule"

                            >
                                        <span class="qi-schedule">
                                           schedule <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                        </span>
                            </td>
                            <td>object</td>
                        </tr>
                        <tr  class="collapse" id="get-qi-schedule">
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
                                                <td>eta</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>etd</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>location_from</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>location_to</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>mode</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>legs</td>
                                                <td>Object</td>
                                            </tr>
                                            <tr>
                                                <td>type</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td
                                                    data-toggle="collapse"
                                                    data-target="#get-qi-carrier_name"
                                                    aria-expanded="false"
                                                    aria-controls="get-qi-carrier_name"
                                                    style="cursor: pointer;  "

                                                    class="glyphicon1 qi-carrier_name"
                                                    rel="qi-carrier_name"
                                                >
                                                    <span class="qi-carrier_name">
                                                       carrier_name <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                                    </span>
                                                </td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr  class="collapse" id="get-qi-carrier_name">
                                                <td colspan="2">
                                                    <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                        <table class="table-bordered">
                                                            <thead></thead>
                                                            <tbody>
                                                            <tr>
                                                                <td>name</td>
                                                                <td><i>String</i> Default: <code>NULL</code></td>
                                                            </tr>
                                                            <tr>
                                                                <td>id</td>
                                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>size_id</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>price</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>selling_size_id</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>selling_price</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>sell_rates</td>
                                                <td>Object</td>
                                            </tr>
                                            <tr>
                                                <td>buy_rates</td>
                                                <td>Object</td>
                                            </tr>
                                            <tr>
                                                <td>is_confirmed</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>etaError</td>
                                                <td>Object</td>
                                            </tr>
                                            <tr>
                                                <td>etdError</td>
                                                <td>Object</td>
                                            </tr>
                                            <tr>
                                                <td>size_name</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>

                                            <tr>
                                                <td>selling_size_name</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>location_to_name</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>location_from_name</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>payment_status</td>
                            <td><i>String</i> Default: <code>NULL</code>,</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <p>
            {{ EndPointHelper::userGuide() }}
        </p>
    </div><!-- End Invoice specified resource -->

    <div class="col-sm-12" id="invoice-download"><!-- Download -->
        <h3>{{ EndPointHelper::get('Download') }}</h3>
        <p>By this endpoint they can download their Invoice</p>
        <br>
        <div class="" style="position:relative;padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-quickbooks-download-invoice" aria-hidden="true">
                <span
                        data-toggle="collapse"
                        data-target="#route-quickbooks-download-invoice"
                        aria-expanded="false"
                        aria-controls="route-quickbooks-download-invoice"
                        style="cursor: pointer"
                        class="i-quickbooks-download-invoice"
                > <code>api/quickbooks/download-invoice</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                </span>
            </span>
            <div class="collapse api-route" id="route-quickbooks-download-invoice">
                <div class="badge-success quickbooks-download-invoice hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="quickbooks-download-invoice">Copy</div>
                </div>
                <div class="api_ noselect" id="quickbooks-download-invoice">
                    {{ config('app.url')}}/api/quickbooks/download-invoice
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>id</code> <br>
            <b>Return Type: &nbsp;</b> <code>FILE</code> <br>
            <b>Will Return: &nbsp;</b> <code> Download Quickbook Invoice) </code> <small> </small><br>
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
                    <td>id<span class="badge-danger">required</span></td>
                    <td><i>Integer</i> The unique shipmentId for the Quickbook Invoice Download to be retrieved</td>
                </tr>
                </tbody>
            </table>
        </div>
        <h4>Response
            <span
                data-toggle="collapse"
                data-target="#quickbooks-download-invoice-response"
                aria-expanded="false"
                aria-controls="quickbooks-download-invoice-response"
                style="cursor: pointer; top: 2px !important;"
                class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
<pre style="font-weight:bold" class="collapse" id="quickbooks-download-invoice-response">
   <span class="slctrl-attr">Downloadable File</span></pre>
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
                            Downloadable File
                        </td>
                        <td>
                            Files can be downloaded to users
                        </td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </div>
        <p>
            {{ EndPointHelper::userGuide() }}
        </p>
    </div><!-- End Download -->

    <div class="col-sm-12" id="invoice-total-due"><!-- Total Due -->
        <h3>{{ EndPointHelper::get('Total Due') }}</h3>
        <br>
        <div class="" style="position:relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-quickbooks-download-invoice" aria-hidden="true">
                <span
                    data-toggle="collapse"
                    data-target="#route-quickbooks-invoice-total-due"
                    aria-expanded="false"
                    aria-controls="route-quickbooks-invoice-total-due"
                    style="cursor: pointer"
                    class="i-quickbooks-invoice-total-due"
                > <code>api/quickbooks/invoice-total-due</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                </span>
            </span>
            <div class="collapse api-route" id="route-quickbooks-invoice-total-due">
                <div class="badge-success quickbooks-invoice-total-due hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="quickbooks-invoice-total-due">Copy</div>
                </div>
                <div class="api_ noselect" id="quickbooks-invoice-total-due">
                    {{ config('app.url')}}/api/quickbooks/invoice-total-due
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>NONE</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Total Due Quickbook Invoice) </code> <small> </small><br>
        </div>
        <h4>Header</h4>
        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"Authorization"</span>: <span class="slctrl-string">"Bearer {access_token}"</span>
    <span class="slctrl-attr">"Content-Type"</span>: <span class="slctrl-string"> "application/json" </span>
    <span class="slctrl-attr">"Accept"</span>: <span class="slctrl-string"> "application/json" </span>
} </pre>
        <h4> Form Data </h4>
<pre><span class="slctrl-attr">NONE</span></pre>
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
                data-target="#quickbooks-invoice-total-due-response"
                aria-expanded="false"
                aria-controls="quickbooks-invoice-total-due-response"
                style="cursor: pointer; top: 2px !important;"
                class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre style="font-weight:bold" class="collapse" id="quickbooks-invoice-total-due-response">
 {
     <span class="slctrl-attr">"total_due"</span>: <span class="slctrl-number">{total_due}</span>
 }
</pre>
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
                            <td>total_due</td>
                            <td><i>Integer</i> Default: <code>NULL</code>, </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <p>
            {{ EndPointHelper::userGuide() }}
        </p>
    </div><!-- End Total Due  -->
</div> <!-- end of Invoice -->