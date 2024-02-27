<div class="col-sm-12" id="custom-customer"> <!-- start Custom Customer -->
    <h3 class="page-header">{{ EndPointHelper::endPoint('Custom Customer') }}</h3>
    <!-- Custom Customer list resource -->
    <div class="col-sm-12" id="custom-customer-list">
        <h3>{{ EndPointHelper::getAll('Custom Customer') }}</h3>
        <p>
            {{ EndPointHelper::getAllDescription("Custom Customer")}}
        </p>
        <br>
        <div class="" style="position:relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-custom-customers" aria-hidden="true">
                <span
                        data-toggle="collapse"
                        data-target="#route-custom-customers"
                        aria-expanded="false"
                        aria-controls="route-custom-customers"
                        style="cursor: pointer"
                        class="i-custom-customers"
                > <code>api/custom/customers</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                </span>
            </span>
            <div class="collapse api-route" id="route-custom-customers">
                <div class="badge-success custom-customers hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="custom-customers">Copy</div>
                </div>

                <div class="api_ noselect" id="custom-customers">
                    {{ config('app.url')}}/api/custom/customers
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>NONE</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>List of Custom Customer</code> <small> </small><br>
        </div>
        <h4>Header</h4>
        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"Authorization"</span> : <span class="slctrl-string">"Bearer {access_token}"</span>
    <span class="slctrl-attr">"Content-Type"</span> : <span class="slctrl-string"> "application/json" </span>
    <span class="slctrl-attr">"Accept"</span> : <span class="slctrl-string"> "application/json" </span>
} </pre>

        <h4> Form Data </h4>
    <pre><span class="slctrl-attr">NONE</span> </pre>
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
        <h4>Response
            <span
                data-toggle="collapse"
                data-target="#custom-customers-response"
                aria-expanded="false"
                aria-controls="custom-customers-response"

                style="cursor: pointer; top: 2px !important;"
                class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre class="collapse" id="custom-customers-response">
{
    <span class="slctrl-attr">"results"</span>: {
        <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"company_name"</span>: <span class="slctrl-string">"Goldner-Kunze"</span>,
        <span class="slctrl-attr">"address"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"phone"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"admin_user_id"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"created_at"</span>": <span class="slctrl-number">"2020-01-07T22:54:10.000000Z"</span>,
        <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-number">"2021-09-08T22:24:34.000000Z"</span>,
        <span class="slctrl-attr">"managers"</span>: <span class="slctrl-string">119</span>,
        <span class="slctrl-attr">"sale_persons"</span>": <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"emails"</span>: [
            {
                <span class="slctrl-attr">"email"</span>: <span class="slctrl-string">"johndoe@yokigroup.com"</span>
            },
            {
                <span class="slctrl-attr">"email"</span>: <span class="slctrl-string">"johndoe@yokigroup.com"</span>
            },
            {
                <span class="slctrl-attr">"email"</span>: <span class="slctrl-string">"johndoe@yokigroup.com"</span>
            }
        ],
        <span class="slctrl-attr">"requirements"</span>: <span class="slctrl-attr">"NO Trucking NO Customs"</span>,
        <span class="slctrl-attr">"credit_term_freight"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"credit_term_duty"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"ein"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"booking_email_recipients"</span>: <span class="slctrl-string">"[]"</span>,
        <span class="slctrl-attr">"loi"</span>: <span class="slctrl-string">"customers/81179f359120099142e62e20adbd0f48.pdf"</span>,
        <span class="slctrl-attr">"offices_managers"</span>: <span class="slctrl-string">""</span>,
        <span class="slctrl-attr">"qbo_customer"</span>: <span class="slctrl-string">""</span>,
        <span class="slctrl-attr">"poa"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"default_duty_layout"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"created_by"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"last_updated_by"</span>: <span class="slctrl-literal">"null</span>,
        <span class="slctrl-attr">"last_updated"</span>: <span class="slctrl-literal">"null</span>,
        <span class="slctrl-attr">"handling_freight"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"handling_trucking"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"handling_customs"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"default_requirements_section"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"confirmed_default_requirements"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"customer_admins"</span>: [
            <span class="slctrl-number">46</span>
        ],
        <span class="slctrl-attr">"customer_admins_api"</span>: [
            {
                <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">46</span>,
                <span class="slctrl-attr">"name"</span>: <span class="slctrl-string">"Goldner-Kunze"</span>,
                <span class="slctrl-attr">"email"</span>: <span class="slctrl-string">"elie@yokigroup.com"</span>,
                <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2020-07-07T00:20:04.000000Z"</span>,
                <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2020-07-07T00:22:31.000000Z"</span>,
                <span class="slctrl-attr">"forgot_password_verification_code"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"forgot_password_verification_code_created_at"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"is_forgot_password_requested"</span>: <span class="slctrl-number">0</span>,
                <span class="slctrl-attr">"report_recipients"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"phone"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"shifl_office_id"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"has_access_to_central_app"</span>: <span class="slctrl-number">1</span>,
                <span class="slctrl-attr">"has_access_to_trucking_app"</span>: <span class="slctrl-number">0</span>,
                <span class="slctrl-attr">"default_customer_id"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"pivot"</span>: {
                    <span class="slctrl-attr">"customer_id"</span>: <span class="slctrl-number">1</span>,
                    <span class="slctrl-attr">"user_id"</span>: <span class="slctrl-number">46</span>
            }
        ]
    },
    {...}, ...
}</pre>
        <table class="table-bordered">
            <thead>
            <tr>
                <th colspan="2"><span class="badge-success">200 collection of Shipments By PO</span></th>
            </tr>
            <tr>
                <th>RESPONSE SCHEMA: </th>
                <th>application/json</th>
            </tr>
            </thead>
            <tbody>
                <tr data-toggle="collapse"
                    data-target="#custom-customers_data"
                    aria-expanded="false"
                    aria-controls="custom-customers_data"
                    style="cursor: pointer;  "
                    class="glyphicon1 d-custom-customers"

                    rel="d-custom-customers"
                >
                    <td>
                        <span class="d-custom-customers">
                                    results
                             <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                        </span>
                    </td>
                    <td>object</td>
                </tr>
                <tr class="endpoint_ collapse" id="custom-customers_data">
                    <td colspan="2">
                        <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                            <table class="table-bordered">
                                <thead></thead>
                                <tbody>
                                    <tr>
                                        <td>id</td>
                                        <td><i>Integer</i> Unique identifier for the Custom Customer, autoincrement</td>
                                    </tr>
                                    <tr>
                                        <td>company_name</td>
                                        <td><i>String</i>Default: <code>NULL</code>, Name of the company</td>
                                    </tr>
                                    <tr>
                                        <td>address</td>
                                        <td><i>String</i>Default: <code>NULL</code>, Address of the Custom Customer</td>
                                    </tr>
                                    <tr>
                                        <td>phone</td>
                                        <td><i>String</i>Default: <code>NULL</code>, Phone number</td>
                                    </tr>
                                    <tr>
                                        <td>admin_user_id</td>
                                        <td><i>Integer</i>Default: <code>NULL</code>, Identification for admin user</td>
                                    </tr>
                                    <tr>
                                        <td>created_at</td>
                                        <td><i>Timestamp</i> Custom Customer created date</td>
                                    </tr>
                                    <tr>
                                        <td>updated_at</td>
                                        <td><i>Timestamp</i> Custom Customer updated date</td>
                                    </tr>
                                    <tr>
                                        <td>managers</td>
                                        <td><i>String</i> Default: <code>NULL</code>, Managers identification number</td>
                                    </tr>
                                    <tr>
                                        <td>sale_persons</td>
                                        <td><i>String</i> Default: <code>NULL</code>, Sales Person of Custom Customer </td>
                                    </tr>
                                    <tr>
                                        <td>emails</td>
                                        <td><i>String</i> Default: <code>NULL</code>, Multiple emails</td>
                                    </tr>
                                    <tr>
                                        <td>requirements</td>
                                        <td><i>String</i> Default: <code>NULL</code>, requirements of Custom Customer, e.g: NO Trucking NO Customs</td>
                                    </tr>
                                    <tr>
                                        <td>credit_term_freight</td>
                                        <td><i>String</i> Default: <code>NULL</code>, Credit Term Freight</td>
                                    </tr>
                                    <tr>
                                        <td>credit_term_duty</td>
                                        <td><i>String</i> Default: <code>NULL</code>, Credit Term Duty</td>

                                    </tr>
                                    <tr>
                                        <td>ein</td>
                                        <td><i>String</i> Default: <code>NULL</code></td>
                                    </tr>
                                    <tr>
                                        <td>booking_email_recipients</td>
                                        <td><i>String</i> Default: <code>NULL</code>, Booking email recipients</td>

                                    </tr>
                                    <tr>
                                        <td>loi</td>
                                        <td><i>String</i> Default: <code>NULL</code></td>
                                    </tr>
                                    <tr>
                                        <td>offices_managers</td>
                                        <td><i>String</i> Default: <code>NULL</code>, Officers  managers</td>
                                    </tr>
                                    <tr>
                                        <td>qbo_customer</td>
                                        <td><i>String</i> Default: <code>NULL</code>, qbo for customer</td>
                                    </tr>
                                    <tr>
                                        <td>poa</td>
                                        <td><i>String</i> Default: <code>NULL</code>, poa custom customer</td>
                                    </tr>
                                    <tr>
                                        <td>default_duty_layout</td>
                                        <td><i>String</i> Default: <code>NULL</code>, default duty layout</td>
                                    </tr>
                                    <tr>
                                        <td>created_by</td>
                                        <td><i>String</i> Default: <code>NULL</code>, created by <code>custom customer</code></td>
                                    </tr>
                                    <tr>
                                        <td>last_updated_by</td>
                                        <td><i>String</i> Default: <code>NULL</code>, last updated by <code>custom customer</code></td>
                                    </tr>
                                    <tr>
                                        <td>last_updated</td>
                                        <td><i>String</i> Default: <code>NULL</code>, last updated data from <code>custom customer</code></td>
                                    </tr>
                                    <tr>
                                        <td>handling_freight</td>
                                        <td><i>String</i> Default: <code>NULL</code>, handling freight</td>
                                    </tr>
                                    <tr>
                                        <td>handling_trucking</td>
                                        <td><i>String</i> Default: <code>NULL</code></td>
                                    </tr>
                                    <tr>
                                        <td>handling_customs</td>
                                        <td><i>String</i> Default: <code>NULL</code></td>
                                    </tr>
                                    <tr>
                                        <td>default_requirements_section</td>
                                        <td><i>String</i> Default: <code>NULL</code>, default requirements section for <code>custom customer</code></td>
                                    </tr>
                                    <tr>
                                        <td>confirmed_default_requirements</td>
                                        <td><i>String</i> Default: <code>NULL</code>, confirmed default requirements for <code>custom customer</code></td>
                                    </tr>
                                    <tr>
                                        <td>customer_admins</td>
                                        <td><i>String</i> Default: <code>NULL</code>, foreign key for customer admins</td>
                                    </tr>
                                    <tr
                                            data-toggle="collapse"
                                            data-target="#customer_admins_data"
                                            aria-expanded="false"
                                            aria-controls="customer_admins_data"
                                            style="cursor: pointer;  "

                                            class="glyphicon1 ca-custom-customers"
                                            rel="ca-custom-customers"

                                    >
                                        <td>
                                            <span class="ca-custom-customers">
                                                        customer_admins_api
                                                 <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                            </span>
                                        </td>
                                        <td><i>String</i> Default: <code>NULL</code></td>
                                    </tr>

                                    <tr class="endpoint_ collapse" id="customer_admins_data">
                                        <td colspan="2">
                                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                <table class="table-bordered">
                                                    <thead></thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>id</td>
                                                            <td><i>Integer</i> Unique identifier for the Custom Customer, autoincrement</td>
                                                        </tr>
                                                        <tr>
                                                            <td>name</td>
                                                            <td><i>Sting</i> Default: <code>NULL</code></td>
                                                        </tr>
                                                        <tr>
                                                            <td>email</td>
                                                            <td><i>Sting</i> Default: <code>NULL</code></td>
                                                        </tr>
                                                        <tr>
                                                            <td>created_at</td>
                                                            <td><i>Timestamp</i> Default: <code>NULL</code></td>
                                                        </tr>
                                                        <tr>
                                                            <td>updated_at</td>
                                                            <td><i>Timestamp</i> Default: <code>NULL</code></td>
                                                        </tr>
                                                        <tr>
                                                            <td>forgot_password_verification_code</td>
                                                            <td><i>String</i> Default: <code>NULL</code></td>
                                                        </tr>
                                                        <tr>
                                                            <td>forgot_password_verification_code_created_at</td>
                                                            <td><i>String</i> Default: <code>NULL</code></td>
                                                        </tr>
                                                        <tr>
                                                            <td>is_forgot_password_requested</td>
                                                            <td><i>String</i> Default: <code>NULL</code></td>
                                                        </tr>
                                                        <tr>
                                                            <td>report_recipients</td>
                                                            <td><i>String</i> Default: <code>NULL</code></td>
                                                        </tr>
                                                        <tr>
                                                            <td>phone</td>
                                                            <td><i>String</i> Default: <code>NULL</code></td>
                                                        </tr>
                                                        <tr>
                                                            <td>shifl_office_id</td>
                                                            <td><i>String</i> Default: <code>NULL</code></td>
                                                        </tr>
                                                        <tr>
                                                            <td>has_access_to_central_app</td>
                                                            <td><i>String</i> Default: <code>NULL</code></td>
                                                        </tr>
                                                        <tr>
                                                            <td>has_access_to_trucking_app</td>
                                                            <td><i>String</i> Default: <code>NULL</code></td>
                                                        </tr>
                                                        <tr>
                                                            <td>default_customer_id</td>
                                                            <td><i>String</i> Default: <code>NULL</code></td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                data-toggle="collapse"
                                                                data-target="#pivot_customer_admins_data"
                                                                aria-expanded="false"
                                                                aria-controls="pivot_customer_admins_data"
                                                                style="cursor: pointer;  "

                                                                class="glyphicon1 ca-pivot-custom-customers"
                                                                rel="ca-pivot-custom-customers"
                                                            >

                                                                    <span class="ca-pivot-custom-customers">
                                                                        pivot
                                                                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                                                    </span>
                                                                </td>
                                                            <td><i>Object</i></td>
                                                        <tr class="endpoint_ collapse" id="pivot_customer_admins_data">
                                                            <td colspan="2">
                                                                <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                                    <table class="table-bordered">
                                                                        <thead></thead>
                                                                        <tbody>

                                                                        <tr>
                                                                            <td>customer_id</td>
                                                                            <td><i>Integer</i> Unique identifier for the Customer</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>user_id</td>
                                                                            <td><i>Integer</i> Unique identifier for the User</td>
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
            <p>
            {{ EndPointHelper::userGuide() }}
        </p>
    </div><!-- End Custom Customer list resource -->
    <div class="col-sm-12" id="custom-customer-specified"><!-- custom customer specified resource -->
        <h3>{{ EndPointHelper::get('Custom Customer') }}</h3>
        <p>
            {{ EndPointHelper::getDescription('Custom Customer', 'id') }}
        </p>
        <br>
        <div id="carrier-type-specified" class="" style="position:relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-custom-customers-id" aria-hidden="true">
                <span
                        data-toggle="collapse"
                        data-target="#route-custom-customers-id"
                        aria-expanded="false"
                        aria-controls="route-custom-customers-id"
                        style="cursor: pointer"
                        class="i-custom-customers-id"
                > <code>api/custom/customers/{id}</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true">
                    </span>
                </span>
            </span>
            <div class="collapse api-route" id="route-custom-customers-id">
                <div class="badge-success custom-customers-id hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="custom-customers-id">Copy</div>
                </div>
                <div class="api_ noselect" id="custom-customers-id">
                    {{ config('app.url')}}/api/custom/customers/{id}
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Custom Customer (Specific Custom Customer) </code> <small> </small><br>

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
    <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">id</span>,
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
                    <td><i>Integer</i> The unique id for the Custom Customer to be retrieved</td>
                </tr>
                </tbody>
            </table>
        </div>
        <h4>Response
            <span
                data-toggle="collapse"
                data-target="#custom-customers-id-response"
                aria-expanded="false"
                aria-controls="custom-customers-id-response"

                style="cursor: pointer; top: 2px !important;"
                class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre class="collapse" id="custom-customers-id-response">
{
    <span class="slctrl-attr">"results"</span>: {
        <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"company_name"</span>: <span class="slctrl-string">"Goldner-Kunze"</span>,
        <span class="slctrl-attr">"address"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"phone"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"admin_user_id"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2020-01-07T22:54:10.000000Z"</span>,
        <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2021-09-08T22:24:34.000000Z"</span>,
        <span class="slctrl-attr">"managers"</span>: <span class="slctrl-number">119</span>,
        <span class="slctrl-attr">"sale_persons"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"emails"</span>: [
            {
                <span class="slctrl-attr">"email"</span>: <span class="slctrl-string">"johndoe@yokigroup.com"</span>
            },
            {
                <span class="slctrl-attr">"email"</span>: <span class="slctrl-string">"johndoe@yokigroup.com"</span>
            },
            {
                <span class="slctrl-attr">"email"</span>: <span class="slctrl-string">"johndoe@yokigroup.com"</span>
            }
        ],
        <span class="slctrl-attr">"requirements"</span>: <span class="slctrl-string">"NO Trucking NO Customs"</span>,
        <span class="slctrl-attr">"credit_term_freight"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"credit_term_duty"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"ein"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"booking_email_recipients"</span>: <span class="slctrl-string">"[]"</span>,
        <span class="slctrl-attr">"loi"</span>: <span class="slctrl-string">"customers/81179f359120099142e62e20adbd0f48.pdf"</span>,
        <span class="slctrl-attr">"offices_managers"</span>: <span class="slctrl-string">""</span>,
        <span class="slctrl-attr">"qbo_customer"</span>: <span class="slctrl-string">""</span>,
        <span class="slctrl-attr">"poa"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"default_duty_layout"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"created_by"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"last_updated_by"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"last_updated"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"handling_freight"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"handling_trucking"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"handling_customs"</span>: <span class="slctrl-literal">"null</span>,
        <span class="slctrl-attr">"default_requirements_section"</span>: <span class="slctrl-literal">"null</span>,
        <span class="slctrl-attr">"confirmed_default_requirements"</span>: <span class="slctrl-number">0</span>,
        <span class="slctrl-attr">"customer_admins"</span>: [
            <span class="slctrl-number">46</span>,
        ],
        <span class="slctrl-attr">"customer_admins_api"</span>: [
            {
                <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">46</span>,
                <span class="slctrl-attr">"name"</span>: <span class="slctrl-string">""Goldner-Kunze"</span>,
                <span class="slctrl-attr">"email"</span>: <span class="slctrl-string">"elie@yokigroup.com"</span>,
                <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2020-07-07T00:20:04.000000Z"</span>,
                <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2020-07-07T00:22:31.000000Z"</span>,
                <span class="slctrl-attr">"forgot_password_verification_code"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"forgot_password_verification_code_created_at"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"is_forgot_password_requested"</span>: <span class="slctrl-number">0</span>,
                <span class="slctrl-attr">"report_recipients"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"phone"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"shifl_office_id"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"has_access_to_central_app"</span>: <span class="slctrl-number">1</span>,
                <span class="slctrl-attr">"has_access_to_trucking_app"</span>: <span class="slctrl-number">0</span>,
                <span class="slctrl-attr">"default_customer_id"</span>: <span class="slctrl-literal">null</span>,
                <span class="slctrl-attr">"pivot"</span>: {
                    <span class="slctrl-attr">"customer_id"</span>: <span class="slctrl-number">1</span>,
                    <span class="slctrl-attr">"user_id"</span>: <span class="slctrl-number">46</span>
                }
            }
        ]
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
                            <td
                                data-toggle="collapse"
                                data-target="#get-cc-results"
                                aria-expanded="false"
                                aria-controls="get-cc-results"
                                style="cursor: pointer;  "
                                class="glyphicon1 cc-results"
                                rel="cc-results"
                            >
                                <span class="cc-results">
                                    results
                                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                </span>

                            </td>
                            <td>object</td>
                        </tr>

                        <tr  class="collapse" id="get-cc-results">
                            <td colspan="2">
                                <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                    <table class="table-bordered">
                                        <tbody>
                                            <tr>
                                                <td>id</td>
                                                <td><i>Integer</i> Unique identifier for the Custom Customer, autoincrement</td>
                                            </tr>
                                            <tr>
                                                <td>company_name</td>
                                                <td><i>String</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>address</td>
                                                <td><i>String</i> Default: <code>NULL</code>, source of the card. Example: Direct</td>
                                            </tr>
                                            <tr>
                                                <td>phone</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>, Use to estemate when the month of expiration of the card</td>
                                            </tr>
                                            <tr>
                                                <td>admin_user_id</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>, Use to estemate when the year of expiration of the card</td>
                                            </tr>
                                            <tr>
                                                <td>created_at</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>updated_at</td>
                                                <td><i>String</i>Default: <code>NULL</code>, Thenumber masked of the Card owner</td>
                                            </tr>
                                            <tr>
                                                <td>managers</td>
                                                <td><i>String</i> Default: <code>NULL</code>, The first name of the Card owner</td>
                                            </tr>
                                            <tr>
                                                <td>sale_persons</td>
                                                <td><i>String</i> Default: <code>NULL</code>, The last name of the Card owner</td>
                                            </tr>
                                            <tr>
                                                <td>emails</td>
                                                <td><i>Array</i> Default: <code>NULL</code>, Array, Multiple email</td>
                                            </tr>
                                            <tr>
                                                <td>requirements</td>
                                                <td><i>String</i> Default: <code>NULL</code>, Token for payment</td>
                                            </tr>
                                            <tr>
                                                <td>credit_term_freight</td>
                                                <td><i>String</i> Default: <code>NULL</code>, Customer admin id</td>
                                            </tr>
                                            <tr>
                                                <td>credit_term_duty</td>
                                                <td><i>String</i> Default: <code>Null</code></td>
                                            </tr>
                                            <tr>
                                                <td>ein</td>
                                                <td><i>String</i> Default: <code>Null</code></td>
                                            </tr>
                                            <tr>
                                                <td>booking_email_recipients</td>
                                                <td><i>String</i> Default: <code>Null</code></td>
                                            </tr>
                                            <tr>
                                                <td>loi</td>
                                                <td><i>String</i> Default: <code>Null</code></td>
                                            </tr>
                                            <tr>
                                                <td>offices_managers</td>
                                                <td><i>String</i> Default: <code>Null</code></td>
                                            </tr>
                                            <tr>
                                                <td>qbo_customer</td>
                                                <td><i>String</i> Default: <code>Null</code></td>
                                            </tr>
                                            <tr>
                                                <td>poa</td>
                                                <td><i>String</i> Default: <code>Null</code></td>
                                            </tr>
                                            <tr>
                                                <td>default_duty_layout</td>
                                                <td><i>String</i> Default: <code>Null</code></td>
                                            </tr>
                                            <tr>
                                                <td>created_by</td>
                                                <td><i>Timestamp</i> Default: <code>Null</code></td>
                                            </tr>
                                            <tr>
                                                <td>last_updated_by</td>
                                                <td><i>Timestamp</i> Default: <code>Null</code></td>
                                            </tr>
                                            <tr>
                                                <td>handling_freight</td>
                                                <td><i>String</i> Default: <code>Null</code></td>
                                            </tr>
                                            <tr>
                                                <td>handling_trucking</td>
                                                <td><i>String</i> Default: <code>Null</code></td>
                                            </tr>
                                            <tr>
                                                <td>handling_customs</td>
                                                <td><i>String</i> Default: <code>Null</code></td>
                                            </tr>
                                            <tr>
                                                <td>default_requirements_section</td>
                                                <td><i>String</i> Default: <code>Null</code></td>
                                            </tr>
                                            <tr>
                                                <td>confirmed_default_requirements</td>
                                                <td><i>String</i> Default: <code>Null</code></td>
                                            </tr>
                                            <tr>
                                                <td>customer_admins</td>
                                                <td><i>String</i> Default: <code>Null</code>, Array</td>
                                            </tr>

                                            <tr>
                                                <td
                                                    data-toggle="collapse"
                                                    data-target="#get-cc-customer-admins-api"
                                                    aria-expanded="false"
                                                    aria-controls="get-cc-customer-admins-api"
                                                    style="cursor: pointer;  "

                                                    class="glyphicon1 cc-customer-admins-api"
                                                    rel="cc-customer-admins-api"
                                                >
                                                    <span class="cc-customer-admins-api">
                                                       customer_admins_api <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                                    </span>
                                                </td>
                                                <td><i>Boolean</i> Default: <code>0</code></td>
                                            </tr>
                                            <tr  class="collapse" id="get-cc-customer-admins-api">
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
                                                                    <td>name</td>
                                                                    <td><i>String</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>email</td>
                                                                    <td><i>String</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>created_at</td>
                                                                    <td><i>Timestamp</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>updated_at</td>
                                                                    <td><i>Timestamp</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>forgot_password_verification_code</td>
                                                                    <td><i>String</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>forgot_password_verification_code_created_at</td>
                                                                    <td><i>String</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>is_forgot_password_requested</td>
                                                                    <td><i>String</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>report_recipients</td>
                                                                    <td><i>String</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>phone</td>
                                                                    <td><i>String</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>shifl_office_id</td>
                                                                    <td><i>String</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>has_access_to_central_app</td>
                                                                    <td><i>String</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>has_access_to_trucking_app</td>
                                                                    <td><i>String</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>default_customer_id</td>
                                                                    <td><i>Integer</i> Default: <code>NULL</code></td>
                                                                </tr>
                                                                <tr>
                                                                    <td
                                                                            data-toggle="collapse"
                                                                            data-target="#get-cc-pivot"
                                                                            aria-expanded="false"
                                                                            aria-controls="get-cc-pivot"
                                                                            style="cursor: pointer;  "

                                                                            class="glyphicon1 cc-pivot"
                                                                            rel="cc-pivot"
                                                                    >
                                                                        <span class="cc-pivot">
                                                                           pivot <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                                                        </span>

                                                                    </td>
                                                                    <td><i>String</i> Default: <code>NULL</code> Location to from legs</td>
                                                                </tr>
                                                                <tr  class="collapse" id="get-cc-pivot">
                                                                    <td colspan="2">
                                                                        <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                                            <table class="table-bordered">
                                                                                <thead></thead>
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td>customer_id</td>
                                                                                        <td><i>Integer</i> Default: <code>NULL</code>, Foreign key Customer id</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>user_id</td>
                                                                                        <td><i>Integer</i> Default: <code>NULL</code>, Foreign key User id</td>
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
    </div><!-- End Custom Customer specified resource -->

    <div class="col-sm-12" id="custom-customer-supplier"><!--  Customer Supplier resource -->
        <h3>{{ EndPointHelper::get('Customer Supplier') }}</h3>
        <p>
            {{ EndPointHelper::getDescription('Customer Supplier', 'id') }}
        </p>
        <br>
        <div id="carrier-type-specified" class="" style="position:relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-custom-customers-id-id-suplliers" aria-hidden="true">
                <span
                        data-toggle="collapse"
                        data-target="#route-custom-customers-id-id-suplliers"
                        aria-expanded="false"
                        aria-controls="route-custom-customers-id-id-suplliers"
                        style="cursor: pointer"
                        class="i-custom-customers-id-id-suplliers"
                > <code>api/custom/customers/{id_id}/suppliers</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                </span>
            </span>
            <div class="collapse api-route" id="route-custom-customers-id-id-suplliers">
                <div class="badge-success custom-customers-id-id-suplliers hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="custom-customers-id-id-suplliers">Copy</div>
                </div>

                <div class="api_ noselect" id="custom-customers-id-id-suplliers">
                    {{ config('app.url')}}/api/custom/customers/{id_id}/suppliers
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>List of specified Custom Customer Supplier</code> <small> </small><br>
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
    <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">{id_id}</span>,
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
                    <td>id_id<span class="badge-danger">required</span></td>
                    <td><i>Integer</i> The unique id_id for the Custom Supplier to be retrieved</td>
                </tr>
                </tbody>
            </table>
        </div>
        <h4>Response
            &nbsp;
            <span
                data-toggle="collapse"
                data-target="#custom-customers-id-id-suplliers-response"
                aria-expanded="false"
                aria-controls="custom-customers-id-id-suplliers-response"
                style="cursor: pointer; top: 2px !important;"
                class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre class="collapse" id="custom-customers-id-id-suplliers-response">
{
    <span class="slctrl-attr">"results"</span>: [
        {
            <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">1</span>,
            <span class="slctrl-attr">"company_name"</span>: <span class="slctrl-string">"Goldner-Kunze"</span>,
            <span class="slctrl-attr">"address"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"phone"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"admin_user_id"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2020-03-16T12:04:39.000000Z"</span>,
            <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2020-03-16T12:04:39.000000Z"</span>"",
            <span class="slctrl-attr">"emails"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"pivot"</span>: {
                <span class="slctrl-attr">"customer_id"</span>: <span class="slctrl-number">1</span>,
                <span class="slctrl-attr">"supplier_id"</span>: <span class="slctrl-number">1</span>
            }
        },
        {...}, ...
    ]
} </pre>
        <div style="margin-bottom: 10px">
            <div>
                <h4 style="display: inline-block">Responses</h4>
            </div>
            <div>
                <table class="table-bordered">
                    <tr>
                        <th colspan="2"><span class="badge-success">200 collection of Carrier</span></th>
                    </tr>
                    <tr>
                        <th>RESPONSE SCHEMA: </th>
                        <th>application/json</th>
                    </tr>
                    <tbody>
                        <tr>
                            <td
                                data-toggle="collapse"
                                data-target="#get-cs-results"
                                aria-expanded="false"
                                aria-controls="get-cs-results"
                                style="cursor: pointer;  "

                                class="glyphicon1 cs-results"
                                rel="cs-results"
                            >
                                <span class="cs-results">
                                   results <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                </span>
                            </td>
                            <td>object</td>
                        </tr>
                        <tr  class="collapse" id="get-cs-results">
                            <td colspan="2">
                                <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                    <table class="table-bordered">
                                        <thead></thead>
                                        <tbody>
                                            <tr>
                                                <td>id</td>
                                                <td><i>Integer</i> Unique identifier for the Customer Supplier, autoincrement</td>
                                            </tr>
                                            <tr>
                                                <td>company_name</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>address</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>phone</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>admin_user_id</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>created_at</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>updated_at</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td>emails</td>
                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>
                                            <tr>
                                                <td

                                                        data-toggle="collapse"
                                                        data-target="#get-cs-pivot"
                                                        aria-expanded="false"
                                                        aria-controls="get-cs-pivot"
                                                        style="cursor: pointer;  "

                                                        class="glyphicon1 cs-pivot"
                                                        rel="cs-pivot"

                                                >
                                                    <span class="cs-pivot">
                                                       pivot <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                                    </span>
                                                </td>

                                                <td><i>Integer</i> Default: <code>NULL</code></td>
                                            </tr>

                                            <tr  class="collapse" id="get-cs-pivot">
                                                <td colspan="2">
                                                    <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                        <table class="table-bordered">
                                                            <thead></thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>customer_id</td>
                                                                    <td><i>Integer</i> Default: <code>NULL</code>, Foreign key Customer id</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>user_id</td>
                                                                    <td><i>Integer</i> Default: <code>NULL</code>, Foreign key User id</td>
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
    </div><!-- End  Customer Supplier  resource -->


    <div class="col-sm-12" id="custom-customer-buyer"><!--  Customer buyer resource -->
        <h3>{{ EndPointHelper::get('Customer Buyer') }}</h3>
        <p>
            {{ EndPointHelper::getDescription('Customer Buyer', 'id') }}
        </p>
        <br>
        <div id="carrier-type-specified" class="" style="position:relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-custom-customers-id-id-buyers" aria-hidden="true">
                <span
                        data-toggle="collapse"
                        data-target="#route-custom-customers-id-id-buyers"
                        aria-expanded="false"
                        aria-controls="route-custom-customers-id-id-buyers"
                        style="cursor: pointer"
                        class="i-custom-customers-id-id-buyers"
                > <code>api/custom/customers/{id_id}/buyers</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                </span>
            </span>
            <div class="collapse api-route" id="route-custom-customers-id-id-buyers">
                <div class="badge-success custom-customers-id-id-buyers hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="custom-customers-id-id-buyers">Copy</div>
                </div>
                <div class="api_ noselect" id="custom-customers-id-id-buyers">
                    {{ config('app.url')}}/api/custom/customers/{id_id}/buyers
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>List of specified Custom Customer buyer</code> <small> </small><br>
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
    <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">{id_id}</span>,
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
                    <td><i>Integer</i> The unique id_id for the Custom Buyer to be retrieved</td>
                </tr>
                </tbody>
            </table>
        </div>
        <h4>Response
            <span
                data-toggle="collapse"
                data-target="#custom-customers-id-id-buyers-response"
                aria-expanded="false"
                aria-controls="custom-customers-id-id-buyers-response"
                style="cursor: pointer; top: 2px !important;"
                class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre class="collapse" id="custom-customers-id-id-buyers-response">
{
    <span class="slctrl-attr">"message"</span>: <span class="slctrl-string">"No query results"</span>
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
                        <td>No query results</td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </div>
        <p>
            {{ EndPointHelper::userGuide() }}
        </p>
    </div><!-- End  Customer buyer  resource -->
</div>  <!-- end of Custom Customer -->