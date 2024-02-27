<div class="col-sm-12" id="card">
    <h2 class="page-header">{{ EndPointHelper::endPoint('Card') }} </h2>
    <!-- Card list resource -->
    <div class="col-sm-12" id="card-list" >
        <h3>{{ EndPointHelper::getAll('Card') }}</h3>
        <p>
            {{ EndPointHelper::getAllDescriptionV2('Card', 'default_customer_id') }}
        </p>
        <br>
        <div class="" style="position:relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-poynt-cards-default-customer-id" aria-hidden="true">
                <span
                        data-toggle="collapse"
                        data-target="#route-poynt-cards-default-customer-id"
                        aria-expanded="false"
                        aria-controls="route-poynt-cards-default-customer-id"
                        style="cursor: pointer"
                        class="i-poynt-cards-default-customer-id"
                > <code>api/poynt/cards/{default_customer_id}</code>
                    <span class="glyphicon glyphicon-menu-right " aria-hidden="true">
                    </span>
                </span>
            </span>
            <div class="collapse api-route" id="route-poynt-cards-default-customer-id">
                <div class="badge-success poynt-cards-default-customer-id hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                
                
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="poynt-cards-default-customer-id">Copy</div>
                </div>

                <div class="api_ noselect" id="poynt-cards-default-customer-id">
                    {{ config('app.url')}}/api/poynt/cards/{default_customer_id}
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>default_customer_id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>List of Card </code> <small> </small><br>
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
    <span class="slctrl-attr">"default_customer_id"</span>: <span class="slctrl-number">{default_customer_id}</span>
} </pre>
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
                    <td>default_customer_id <span class="badge-danger">required</span></td>
                    <td><i>Integer</i> The unique default_customer_id for the Card Shipment Central to be retrieved</td>
                </tr>
                </tbody>
            </table>
        </div>
        <h4>Response
            <span
                data-toggle="collapse"
                data-target="#poynt-cards-default-customer-id-response"
                aria-expanded="false"
                aria-controls="poynt-cards-default-customer-id-response"

                style="cursor: pointer; top: 2px !important;"
                class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre class="collapse" id="poynt-cards-default-customer-id-response">
{
    <span class="slctrl-attr">"data"</span>: {
        <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">{id}</span>,
        <span class="slctrl-attr">"type"</span>: <span class="slctrl-string">"{type}"</span>,
        <span class="slctrl-attr">"source"</span>: <span class="slctrl-string">"{source}"</span>,
        <span class="slctrl-attr">"expiration_month"</span>: <span class="slctrl-number">{expiration_month}</span>,
        <span class="slctrl-attr">"expiration_year"</span>: <span class="slctrl-number">{expiration_year}</span>,
        <span class="slctrl-attr">"card_id"</span>: <span class="slctrl-string">{card_id}</span>,
        <span class="slctrl-attr">"number_masked"</span>: <span class="slctrl-string">"{number_masked}"</span>,
        <span class="slctrl-attr">"first_name"</span>: <span class="slctrl-string">"{first_name}"</span>",
        <span class="slctrl-attr">"last_name"</span>: <span class="slctrl-string">"{last_name}"</span>,
        <span class="slctrl-attr">"card_card_id"</span>: <span class="slctrl-string">"{card_card_id}"</span>,
        <span class="slctrl-attr">"payment_token"</span>: <span class="slctrl-string">"{payment_token}"</span>,
        <span class="slctrl-attr">"customer_admin_id"</span>: <span class="slctrl-number">{customer_admin_id}</span>,
        <span class="slctrl-attr">"is_default"</span>: <span class="slctrl-number">1</span>
    }
} </pre>

        <div style="margin-bottom: 10px">
            <div>
                <h4 style="display: inline-block">Responses</h4>
            </div>
            <div>
                <table class="table-bordered">
                    <thead>
                    <thead>
                        <th colspan="2"><span class="badge-success">200 collection of Card</span></th>
                    </thead>
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
                        <td><i>Integer</i> Unique identifier for the Card, autoincrement</td>
                    </tr>
                    <tr>
                        <td>type</td>
                        <td><i>String</i> Default: <code>NULL</code></td>
                    </tr>
                    <tr>
                        <td>source</td>
                        <td><i>String</i> Default: <code>NULL</code>, source of the card. Example: Direct</td>
                    </tr>
                    <tr>
                        <td>expiration_month</td>
                        <td><i>Integer</i> Default: <code>NULL</code>, Use to estemate when the month of expiration of the card</td>
                    </tr>
                    <tr>
                        <td>expiration_year</td>
                        <td><i>Integer</i> Default: <code>NULL</code>, Use to estemate when the year of expiration of the card</td>
                    </tr>
                    <tr>
                        <td>card_id</td>
                        <td><i>Integer</i> Default: <code>NULL</code></td>
                    </tr>
                    <tr>
                        <td>number_masked</td>
                        <td><i>String</i> Default: <code>NULL</code>, Thenumber masked of the Card owner</td>
                    </tr>
                    <tr>
                        <td>first_name</td>
                        <td><i>String</i> Default: <code>NULL</code>, The first name of the Card owner</td>
                    </tr>
                    <tr>
                        <td>last_name</td>
                        <td><i>String</i> Default: <code>NULL</code>, The last name of the Card owner</td>
                    </tr>
                    <tr>
                        <td>card_card_id</td>
                        <td><i>String</i> Default: <code>NULL</code></td>
                    </tr>
                    <tr>
                        <td>payment_token</td>
                        <td><i>String</i> Default: <code>NULL</code></td>
                    </tr>
                    <tr>
                        <td>customer_admin_id</td>
                        <td><i>Integer</i> Default: <code>NULL</code></td>
                    </tr>
                    <tr>
                        <td>is_default</td>
                        <td><i>Boolean</i> Default: <code>0</code></td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
        <p>
            {{ EndPointHelper::userGuide() }}
        </p>
    </div>
    <!-- End Card list resource -->

    <!-- End Specified resource -->
    <div class="col-sm-12"  id="card-specific" >

        <h3>{{ EndPointHelper::get('Card') }}</h3>
        <p>
            {{ EndPointHelper::getDescription('Card', 'id') }}
        </p>
        <br>
        <div class="" style="position:relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp
            <span class="glyphicon1" rel="i-poynt-card-id" aria-hidden="true">
                <span
                        data-toggle="collapse"
                        data-target="#route-poynt-card-id"
                        aria-expanded="false"
                        aria-controls="route-poynt-card-id"
                        style="cursor: pointer"
                        class="i-poynt-card-id"
                > <code>api/poynt/card/{id}</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true">
                    </span>
                </span>
            </span>
            <div class="collapse api-route" id="route-poynt-card-id">
                <div class="badge-success poynt-card-id hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>

                    <div  class="copy-api" rel="poynt-card-id">Copy</div>
                </div>

                <div class="api_ noselect" id="poynt-card-id">
                    {{ config('app.url')}}/api/poynt/card/{id}
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Card (Specific Card) </code> <small> </small><br>
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
    <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">{id}</span>
} </pre>
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
            <td><i>Integer</i> The unique id for the Card Shipment Central to be retrieved</td>
        </tr>
        </tbody>
    </table>
</div>
    <h4>Response
        <span
            data-toggle="collapse"
            data-target="#poynt-card-id-response"
            aria-expanded="false"
            aria-controls="poynt-card-id-response"

            style="cursor: pointer; top: 2px !important;"
            class="glyphicon glyphicon-eye-open">
        </span>
    </h4>
   <pre class="collapse" id="poynt-card-id-response">
{
    <span class="slctrl-attr">"data"</span>: {
       <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">{id}</span>,
        <span class="slctrl-attr">"type"</span>: <span class="slctrl-string">"{type}"</span>,
        <span class="slctrl-attr">"source"</span>: <span class="slctrl-string">"{source}"</span>,
        <span class="slctrl-attr">"expiration_month"</span>: <span class="slctrl-number">{expiration_month}</span>,
        <span class="slctrl-attr">"expiration_year"</span>: <span class="slctrl-number">{expiration_year}</span>,
        <span class="slctrl-attr">"card_id"</span>: <span class="slctrl-number">{card_id}</span>,
        <span class="slctrl-attr">"number_masked"</span>: <span class="slctrl-number">{number_masked}"</span>",
        <span class="slctrl-attr">"first_name"</span>: <span class="slctrl-string">"{first_name}"</span>,
        <span class="slctrl-attr">"last_name"</span>: <span class="slctrl-string">"{last_name}"</span>,
        <span class="slctrl-attr">"card_card_id"</span>: <span class="slctrl-number">{card_card_id}</span>,
        <span class="slctrl-attr">"payment_token"</span>: <span class="slctrl-string">"{payment_token}"</span>,
        <span class="slctrl-attr">"customer_admin_id"</span>: <span class="slctrl-number">{customer_admin_id}</span>,
        <span class="slctrl-attr">"is_default"</span>: <span class="slctrl-number">1</span>
    }
} </pre>
        <div style="margin-bottom: 10px">
            <div>
                <h4 style="display: inline-block">Responses</h4>
            </div>
            <div>
                <table class="table-bordered">
                    <thead>
                        <th colspan="2"><span class="badge-success">200 collection of Card</span></th>
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
                        <td><i>Integer</i> Unique identifier for the Card, autoincrement</td>
                    </tr>
                    <tr>
                        <td>type</td>
                        <td><i>String</i> Default: <code>NULL</code></td>
                    </tr>
                    <tr>
                        <td>source</td>
                        <td><i>String</i> Default: <code>NULL</code>, source of the card. Example: Direct</td>
                    </tr>
                    <tr>
                        <td>expiration_month</td>
                        <td><i>Integer</i> Default: <code>NULL</code>, Use to estemate when the month of expiration of the card</td>
                    </tr>
                    <tr>
                        <td>expiration_year</td>
                        <td><i>Integer</i> Default: <code>NULL</code>, Use to estemate when the year of expiration of the card</td>
                    </tr>
                    <tr>
                        <td>card_id</td>
                        <td><i>Integer</i> Default: <code>NULL</code></td>
                    </tr>
                    <tr>
                        <td>number_masked</td>
                        <td><i>String</i> Default: <code>NULL</code>, Thenumber masked of the Card owner</td>
                    </tr>
                    <tr>
                        <td>first_name</td>
                        <td><i>String</i> Default: <code>NULL</code>, The first name of the Card owner</td>
                    </tr>
                    <tr>
                        <td>last_name</td>
                        <td><i>String</i> Default: <code>NULL</code>, The last name of the Card owner</td>
                    </tr>
                    <tr>
                        <td>card_card_id</td>
                        <td><i>String</i> Default: <code>NULL</code></td>
                    </tr>
                    <tr>
                        <td>payment_token</td>
                        <td><i>String</i> Default: <code>NULL</code></td>
                    </tr>
                    <tr>
                        <td>customer_admin_id</td>
                        <td><i>Integer</i> Default: <code>NULL</code></td>
                    </tr>
                    <tr>
                        <td>is_default</td>
                        <td><i>Boolean</i> Default: <code>0</code></td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
        <p>
            {{ EndPointHelper::userGuide() }}
        </p>
    </div> <!-- End Specified resource -->

    <!-- Card update -->
    <div class="col-sm-12"  id="card-update">
        <h3>{{ EndPointHelper::update('Card') }}</h3>
        <p>
            {{ EndPointHelper::updateDescription('Card', array('card_id', 'first_name','last_name', 'is_default')) }}
        </p>
        <br>
        <div class="" style="position:relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-payment-method-cc-update" aria-hidden="true">
                <span
                        data-toggle="collapse"
                        data-target="#route-payment-method-cc-update"
                        aria-expanded="false"
                        aria-controls="route-payment-method-cc-update"
                        style="cursor: pointer"
                        class="i-payment-method-cc-update"
                > <code>api/payment-method/cc/update</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true">
                    </span>
                </span>
              </span>
            <div class="collapse api-route" id="route-payment-method-cc-update">
                <div class="badge-success payment-method-cc-update hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="payment-method-cc-update">Copy</div>
                </div>

                <div class="api_ noselect" id="payment-method-cc-update">
                    {{ config('app.url')}}/api/payment-method/cc/update
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>PUT</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>card_id</code>,
            <code>first_name</code>,
            <code>last_name</code>,
            <code>is_default</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Card (Update Card) </code> <small> </small><br>
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
    <span class="slctrl-attr">"card_id"</span> : <span class="slctrl-string">"{card_id}"</span>,
    <span class="slctrl-attr">"first_name"</span> : <span class="slctrl-string">"{first_name}"</span>,
    <span class="slctrl-attr">"last_name"</span> : <span class="slctrl-string">"{last_name}"</span>,
    <span class="slctrl-attr">"is_default"</span> : <span class="slctrl-string">"{is_default}"</span>
} </pre>
        <div>
            <div>
                <h4 style="display: inline-block">REQUEST</h4>
            </div>
            <table class="table-bordered">
                <thead>
                    <th colspan="2">Body Parameters</th>
                </thead>
                <thead>
                    <th>Attribute</th>
                    <th>Description</th>
                </thead>
                <tbody>
                <tr>
                    <td>card_id<span class="badge-danger">required</span></td>
                    <td><i>Integer</i> The unique card_id for the Card Shipment Central to be retrieved</td>
                </tr>
                <tr>
                    <td>first_name<span class="badge-danger">required</span></td>
                    <td><i>String</i> First name of the owner of the Card</td>
                </tr>
                <tr>
                    <td>last_name<span class="badge-danger">required</span></td>
                    <td><i>String</i> Last name of the owner of the Card</td>
                </tr>
                <tr>
                    <td>is_default<span class="badge-danger">required</span></td>
                    <td><i>Boolean</i> The defualt value is 0</td>
                </tr>
                </tbody>
            </table>
        </div>
        <h4>Response
            <span
                data-toggle="collapse"
                data-target="#payment-method-cc-update-response"
                aria-expanded="false"
                aria-controls="payment-method-cc-update-response"

                style="cursor: pointer; top: 2px !important;"
                class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre class="collapse" id="payment-method-cc-update-response">
{
    <span class="slctrl-attr">"data"</span>: {
        <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">{id}</span>,
        <span class="slctrl-attr">"type"</span>: <span class="slctrl-string">"{type}"</span>,
        <span class="slctrl-attr">"source"</span>: <span class="slctrl-string">"{source}"</span>,
        <span class="slctrl-attr">"expiration_month"</span>: <span class="slctrl-string">"{expiration_month}"</span>,
        <span class="slctrl-attr">"expiration_year"</span>: <span class="slctrl-string">"{expiration_year}"</span>,
        <span class="slctrl-attr">"card_id"</span>: <span class="slctrl-string">{card_id}</span>,
        <span class="slctrl-attr">"number_masked"</span>: <span class="slctrl-string">"{number_masked}"</span>,
        <span class="slctrl-attr">"first_name"</span>": <span class="slctrl-string">"{first_name}"</span>,
        <span class="slctrl-attr">"last_name"</span>: <span class="slctrl-string">"{last_name}"</span>,
        <span class="slctrl-attr">"card_card_id"</span>: <span class="slctrl-string">"{card_card_id}"</span>,
        <span class="slctrl-attr">"payment_token"</span>: <span class="slctrl-string">"{payment_token}"</span>,
        <span class="slctrl-attr">"customer_admin_id"</span>: <span class="slctrl-string">{customer_admin_id}</span>,
        <span class="slctrl-attr">"is_default"</span>: <span class="slctrl-number">{1 or 0}</span>
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

                        <th colspan="2"><span class="badge-success">200 collection of Card</span></th>

                    </tr>
                    <tr>
                        <th>RESPONSE SCHEMA: </th>
                        <th>application/json</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>data</td>
                        <td>object</td>
                    </tr>
                    <tr>
                        <td>id</td>
                        <td><i>Integer</i> Unique identifier for the Card, autoincrement</td>
                    </tr>
                    <tr>
                        <td>type</td>
                        <td><i>String</i> Default: <code>NULL</code></td>
                    </tr>
                    <tr>
                        <td>source</td>
                        <td><i>String</i> Default: <code>NULL</code>, source of the card. Example: Direct</td>
                    </tr>
                    <tr>
                        <td>expiration_month</td>
                        <td><i>Integer</i> Default: <code>NULL</code>, Use to estemate when the month of expiration of the card</td>
                    </tr>
                    <tr>
                        <td>expiration_year</td>
                        <td><i>Integer</i> Default: <code>NULL</code>, Use to estemate when the year of expiration of the card</td>
                    </tr>
                    <tr>
                        <td>card_id</td>
                        <td><i>Integer</i> Default: <code>NULL</code></td>
                    </tr>
                    <tr>
                        <td>number_masked</td>
                        <td><i>String</i>Default: <code>NULL</code>, Thenumber masked of the Card owner</td>
                    </tr>
                    <tr>
                        <td>first_name</td>
                        <td><i>String</i> Default: <code>NULL</code>, The first name of the Card owner</td>
                    </tr>
                    <tr>
                        <td>last_name</td>
                        <td><i>String</i> Default: <code>NULL</code>, The last name of the Card owner</td>
                    </tr>
                    <tr>
                        <td>card_card_id</td>
                        <td><i>String</i> Default: <code>NULL</code></td>
                    </tr>
                    <tr>
                        <td>payment_token</td>
                        <td><i>String</i> Default: <code>NULL</code></td>
                    </tr>
                    <tr>
                        <td>customer_admin_id</td>
                        <td><i>Integer</i> Default: <code>NULL</code></td>
                    </tr>
                    <tr>
                        <td>is_default</td>
                        <td><i>Boolean</i> Default: <code>0</code></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <p>
            {{ EndPointHelper::userGuide() }}
        </p>
    </div><!-- end Card update -->

    <!-- Card Delete -->
    <div class="col-sm-12"  id="card-destroy" >
        <h3>{{ EndPointHelper::delete('Card') }}</h3>
        <p>
            {{ EndPointHelper::deleteDescription('Card', array('card_id', 'default_customer_id')) }}
        </p>
        <br>
        <div class="" style="position:relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-payment-method-cc-delete" aria-hidden="true">
                <span
                        data-toggle="collapse"
                        data-target="#route-payment-method-cc-delete"
                        aria-expanded="false"
                        aria-controls="route-payment-method-cc-delete"
                        style="cursor: pointer"
                        class="i-payment-method-cc-delete"
                > <code>api/payment-method/cc/delete</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true">
                    </span>
                </span>
            </span>
            <div class="collapse api-route" id="route-payment-method-cc-delete">
                <div class="badge-success payment-method-cc-delete hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port"> 
                    <p>SHIFL CENTRAL API</p> 
                    <div  class="copy-api" rel="payment-method-cc-delete">Copy</div>
                </div>

                <div class="api_ noselect" id="payment-method-cc-delete">
                    {{ config('app.url')}}/api/payment-method/cc/delete
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>DELETE</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>card_id</code>, <code>default_customer_id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Card (Delete Card) </code> <small> </small><br>
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
    <span class="slctrl-attr">"{card_id}"</span>: <span class="slctrl-number">{card_id}</span>,
    <span class="slctrl-attr">"{default_customer_id}"</span>: <span class="slctrl-number">{default_customer_id}</span>
} </pre>
        <div>
            <div>
                <h4 style="display: inline-block">REQUEST</h4>
            </div>
            <table class="table-bordered">
                <thead>
                    <th colspan="2">Body Parameters</th>
                </thead>
                <thead>
                    <th>Attribute</th>
                    <th>Description</th>
                </thead>
                <tbody>
                <tr>
                    <td>card_id<span class="badge-danger">required</span></td>
                    <td><i>Integer</i> The unique card_id for the Card Shipment Central to be retrieved to be deleted</td>
                </tr>
                <tr>
                    <td>default_customer_id<span class="badge-danger">required</span></td>
                    <td><i>String</i>The unique default_customer_id for the Card Shipment Central to be deleted</td>
                </tr>
                </tbody>
            </table>
        </div>
        <h4>Response
            <span
                data-toggle="collapse"
                data-target="#payment-method-cc-delete-response"
                aria-expanded="false"
                aria-controls="payment-method-cc-delete-response"

                style="cursor: pointer; top: 2px !important;"
                class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre class="collapse" id="payment-method-cc-delete-response">
{
    {
       <span class="slctrl-attr">"status"</span>: <span class="slctrl-string">"ok"</span>
    }
} </pre>

        <div style="margin-bottom: 10px">
            <div>
                <h4 style="display: inline-block">Responses</h4>
            </div>
            <div>
                <table class="table-bordered">
                    <thead>
                        <th colspan="2"><span class="badge-success">200 collection of Card</span></th>
                    </thead>
                    <thead>
                    <th>RESPONSE SCHEMA: </th>
                    <th>application/json</th>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Status</td>
                        <td><code>ok</code>, The return status of the endpoint is okay and we successfully deleted the data</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <p>
            {{ EndPointHelper::userGuide() }}
        </p>

    </div><!-- end Card Delete -->

</div> <!-- end card -->
