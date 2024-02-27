<div class="col-sm-12 hidden" id="custom-admin"> <!-- start Custom Admin -->
    <h3 class="page-header">{{ EndPointHelper::endPoint('Custom Admin') }}</h3>
    <!-- Custom Admin list resource -->
    <div class="col-sm-12" id="custom-admin-list">

        <h3>{{ EndPointHelper::getAll('Custom Admin') }}</h3>
        <p>
            {{ EndPointHelper::getAllDescription('Custom Admin') }}
        </p>
        <br>

        <div class="" style="position:relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp; 

            <span
                    data-toggle="collapse"
                    data-target="#route-custom-customer-admin-suppliers"
                    aria-expanded="false"
                    aria-controls="route-custom-customer-admin-suppliers"
                    style="cursor: pointer"
            > <code>api/custom/customer-admin/suppliers</code> > </span>

            <div class="collapse api-route" id="route-custom-customer-admin-suppliers">
                <div class="badge-success custom-customer-admin-suppliers hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>

                    <div  class="copy-api" rel="custom-customer-admin-suppliers">Copy</div>
                </div>

                <div class="api_ noselect" id="custom-customer-admin-suppliers">
                    {{ config('app.url')}}/api/custom/customer-admin/suppliers
                </div>
            </div>

            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>NONE</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>List of Custom Admin</code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
       </pre>
        <h4> Form Data </h4>
        <pre>
NONE
        </pre>

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

        <h4>Response</h4>
        <pre>
{
    "results": [
        {
            "id": 1,
            "company_name": "Testing Company",
            "address": "Testing Address",
            "phone": "18259127189",
            "admin_user_id": null,
            "created_at": "2020-02-28T13:57:05.000000Z",
            "updated_at": "2020-02-28T13:57:05.000000Z",
            "emails": null,
            "pivot": {
                "customer_id": 25,
                "supplier_id": 8
            }
        },{...},...
    ]
}
        </pre>


        <div style="margin-bottom: 10px">
            <div>
                <h4 style="display: inline-block">Responses</h4>
            </div>
            <div>
                <table class="table-bordered">
                    <thead>
                    <th colspan="2"><span class="badge-success">200 collection of Custom Admin</span></th>
                    </thead>
                    <thead>
                    <th>RESPONSE SCHEMA: </th>
                    <th>application/json</th>
                    </thead>
                    <tbody>
                    <tr>
                        <td>results</td>
                        <td>object</td>
                    </tr>
                    <tr>
                        <td>id</td>
                        <td><i>Integer</i> Unique identifier for the Custom Admin, autoincrement</td>
                    </tr>
                    <tr>
                        <td>company_name</td>
                        <td><i>String</i> Company name of Customer, string or null</td>
                    </tr>
                    <tr>
                        <td>address</td>
                        <td><i>String</i> Address of Customer, string or null</td>
                    </tr>
                    <tr>
                        <td>phone</td>
                        <td><i>String</i> Phone number of Customer, internation number, string or null</td>
                    </tr>
                    <tr>
                        <td>admin_user_id</td>
                        <td><i>Integer</i> Admin User ID, int or null </td>
                    </tr>
                    <tr>
                        <td>emails</td>
                        <td><i>String</i>, Email of Customer, uniq, string or null</td>
                    </tr>
                    <tr>
                        <td>Pivot</td>
                        <td><i>Object</i></td>
                    </tr>
                    <tr>
                        <td>customer_id</td>
                        <td><i>integer</i>Foreign key, Customer ID</td>
                    </tr>
                    <tr>
                        <td>supplier_id</td>
                        <td><i>integer</i>Foreign key,  Supplier ID</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <p>
            {{ EndPointHelper::userGuide() }}
        </p>

    </div><!-- End Custom Admin list resource -->
</div>  <!-- end of Custom Admin -->