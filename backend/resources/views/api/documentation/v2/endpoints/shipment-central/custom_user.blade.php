




{{-- /////////////////////////////// --}}

<div class="col-sm-12 hidden" id="custom-user"> <!-- start Custom User -->
    <h3 class="page-header">Custom User</h3>
    <!--Customer details -->
    <h3 id="custom-user-customer-details">Customer details</h3>
    <p>
        Details resource for Custom User endpoint.
    </p>
    <br>


    <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

        <b>Route: </b> &nbsp;<code>api/custom/details</code> <br>
        <b>Request Type: &nbsp;</b> <code>POST</code> <br>
        <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
        <b>Body Form Data required: &nbsp;</b> <code>NONE</code> <br>
        <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
        <b>Will Return: &nbsp;</b> <code>Details for Custom User </code> <small> </small><br>

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

    <h4>Response</h4>
    <pre>
{
    "success": {
        "id": 1,
        "name": "John Doe",
        "email": "johndoe@shifl.com",
        "created_at": "2020-01-07T12:24:11.000000Z",
        "updated_at": "2022-07-28T12:03:23.000000Z",
        "forgot_password_verification_code": "",
        "forgot_password_verification_code_created_at": null,
        "is_forgot_password_requested": 0,
        "report_recipients": null,
        "phone": null,
        "shifl_office_id": null,
        "has_access_to_central_app": 1,
        "has_access_to_trucking_app": 1,
        "default_customer_id": null,
        "roles": [
            {
                "id": 2,
                "name": "John Doe",
                "guard_name": "web",
                "created_at": "2020-01-07T11:51:49.000000Z",
                "updated_at": "2020-01-07T11:51:49.000000Z",
                "pivot": {
                    "model_id": 3,
                    "role_id": 2,
                    "model_type": "App\\User"
                }
            }
        ],
        "customer_admins": [],
        "customers_api": []
    }
}
                                                        </pre>
    <p>
        Everytime users want to access other api endpoints they have to provide validate token on header with every request.
        Otherwise The request will be considered as unauthorized attempt.
    </p>

</div><!-- End Customer details -->

</div><!-- end of Custom User -->