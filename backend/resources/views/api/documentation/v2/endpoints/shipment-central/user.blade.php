
{{-- /////////////////////////////// --}}
<div class="col-sm-12 hidden" id="user">
    <h3 class="page-header">User</h3>

    <!-- user login -->
    <div class="col-sm-12" id="user-login">

        <h3>Login</h3>
        <p class="m-t-20">
            In this endpoint, we can also get the token.
        </p>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/login</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>None</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>email_address</code>, <code>password</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Login credentials</code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>

        <h4> Form Data </h4>
        <pre style="font-weight:bold">
{
    "email_address" : "{email}",
    "password" : "{password}",
}
</pre>

        <h4>Response</h4>
        <pre style="font-weight:bold">
{
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxNiIsImp0aSI6ImMwY2VjZTJmNmM2MTBjYWQwYjMyYWRjMTI2MjliNmUxYWViNjVkNmE3OGMwY2M2NzdjYTZhYjA0YzUzNjZlNzNhMTk1YzcxZGY0ZmY2ZjEzIiwiaWF0IjoxNjU5MDg2NjExLCJuYmYiOjE2NTkwODY2MTEsImV4cCI6MTY5MDYyMjYxMCwic3ViIjoiMyIsInNjb3BlcyI6WyIqIl19.R_-Lm5kBFsrEoKD6vqXto6U9LRMc65H_jgcK8YFTo757z3JutkMy7giZ06YHWPYeU50s5AftK03F1VUgKNdGfXpwxCZHdQknEv9mbuo9naPBy49GaL_dzXJYhAMJXOxN1ydFZM0uCpZR7kkqgzQ9mQfpXQlkkVZPtQUTN0AvpFzn8t_U9nzuDh_RuebIW51TiGjzr7TPWkSU8S8PJOjO7JUIDLc89jY8XE41g_-VlebvWpA1hvf8D353D8Q7omydEZEGIyQOSeMPWMveZPph4qRvbnk5GJ6RAyHPulbadvAvm6k5J2LL0PegdnMV9pCxU9AAoof9v9D6Ag9PEjX_PoDEHF7PBPj1zPHLRZTKhyRElRGf7D-VXU96gmordVQytABdp8RrYmMKZ-a2EAKnFvLJI3gHJQOItx7D73SKkSF-y3qqanPr9tZz9HzxOGXH4DMOoAMHItNkOl_bX4G18TjEgF9Fpv001fhcsnRCpi7oXYUoxIa-kaqslr66hjXozjBgUpJZKZKTa0Ny9j-A942kGc46dmdZBncqPNtxKouJgRcmVBuY5gAEm25huTszMygA3hDn9AVmZq7h1fpALsvsp5cRqryQ1_aNm77FhjYf9RGxLSX01xtyJcXFjlX6AgkCCzjwUkQhlQmAQ7oXchV8RRMwQ1R1SW0YrQuY760",
    "refresh_token": "def50200b2bba4b89438c7bd44cb303f215f151e26d4725d315d75b89447688982d98c1c2603a8f3078cb1e7923d025a795f6cc56a62405a8d1020e697831bf7db7299fd2b35c73187a7534065abe992fdd8a45d32ac6eef34e7660083f85fd229e8d70c9dabc8ac940457cfba67cb1b093ffe3aea4df7018e946d0df033fbd76b0b1747a16c92fed9b10325ae67e8f393b4321136bf8c0e00bec4df4c0212385dbf89d02f06cb880c26830c3f820031b9c7ff84a142147969ee0598e973464f4b3b742616a4b294cfb6d1bc83c60f5d571253942c6535fa3c86fc4e4370798a8af3277bf3659240963dc985875b793e9f1775acd01c73ca46e1ca8a217a4b05850e70a1ffef1c5c9cdabec844a56a41a502fa51a886d99fee710ef52d240495a5ffede1804afc84e3d7c9bd734870690a64839479952928de3a7e0386f22716729a7bf8bab26787dfb2e955388b781b1cfc327413b7323ef343fc09864744660fba5e8467",
    "expiresIn": 31535999,
    "message": "You have been logged in"
}
                                                        </pre>
        <p>
            In this endpoints user can access api without providing valid token on header
        </p>
    </div>
    <!-- End user login -->

    <!-- refresh token -->
    <div class="col-sm-12" id="user-refresh-token" >

        <h3>Refresh Token</h3>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/refresh-token</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>None</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>NONE</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Refresh token</code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>


        <h4> Form Data </h4>
        <pre style="font-weight:bold">
NONE
</pre>

        <h4>Response</h4>
        <pre style="font-weight:bold">
{
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxNiIsImp0aSI6IjIwZWQzMWU2NTg3NzBkYmE0MGU1MGVlMzU4OTU3M2NmYjZkYjVlYTFiZTEzOWMzYjJkMDg3NDg5M2U2YzljOGVlMGMyMTBkYTEwMmUxYTZmIiwiaWF0IjoxNjU5MDkzMDc0LCJuYmYiOjE2NTkwOTMwNzQsImV4cCI6MTY5MDYyOTA3NCwic3ViIjoiMyIsInNjb3BlcyI6WyIqIl19.lo7OcaN5Zfmfh6fhN4O9-mWA_O9G7x9TGhPHxtsqJHBoEusFEpu_LCzVfn4XY76mcOdXaGOZrXT-lZc0so4667afcKYGE1KTkAn1dWvfYfbmJm1Kyh3t72B9KaEMnPtbMuXflJTUy1jTY5L7sUaepmjIvAjBGmhDCmKXIm7LlGn7lDk8TzGmPSr2Z6fjYUBttiPugcTC7K7wIp18fMV6dbgQD7lgnbaKOHnD0FxT7sl22nkbhC-elV6hnsMrLSLS6ZztnbVcs3QZjws1lCnTsKyaIUrxHwPb5YIXDF4g4vOj26TClT1ucv4J8YeSWwKe_CjrSX5YxOclBWqCtyoOOiH3QFBsyOlctV1iPIJmqzL-HUaYIQAGODdAvfYynbwDCqdwoD61mHrFetmnzgZmVnhIZRK9i00O462KGY3zDbMUYWo0XBTLDEMzsqO8NWPRu6t6SIDCHcER4eI8-eK0OVaJfLLwdwjwqj9yVnWa7qiUwkA3YZh4n8XvXh0YQN6ql_PQYwvgalwmIYs36WdHA1AQCoajUrkwzQskJUd5xj6ImZVL6JWVVNSAJCw83g_iokRy1Lf64jNg-DU8divreN0ZLQD5pgvuuMXsXBBBdZIoCXSvmDtCzBGqcbVpOUFvR3pRBJLeFS8L5n-xJg6uaEUOaIhpigyfGIHpbotFKdA",
    "expiresIn": 31536000,
    "refresh_token": "def502001c2b5b659c78d46c48c56e411a3838ac170e0368ea9dcdfb7eb82ce2b0bf948eccb9344120ff08fc7c99637829925efa6026487a6399a582bbac2ded7919343d295143d53461c2623dbca93362bb5a8cd392d73942677d19df9474a661301a9404e0097d13b579984df1f7ba54ef5d8d136f322d796c171df33b94e3f6222bedbaa278d82681a5b51099709eeeac643f1fa7f0d680055b4a84adcd597ff7c1f63fd059b43d7cdd944d3b36c2f83b864b16b760ea2d43e8faca8e98e40c7d02c614d602cdbdeef6127e7c5ecb882e67846a65aaf7189c31988ee9a23d577baf0c8e5d60d1d9872d2b2203b3cc644c6602d2aff6e25fc0e4b1612cca184982a590e513357913e1b9bb63ad5daedd51f35d4f38cdf7ea77fcd567ed8a0a135475c7d6ea8b36b058e1148aef7361aa4989b0e9c0bb849a1adff38e5cc9d512f5982ab102136da24b562fad30ad35b18cbb0f9005e49042ac11d045db7d9218f88125e2",
    "message": "Token has been refreshed."
}
                                                        </pre>
        <p>
            In this endpoints user can access api without providing valid token on header
        </p>
    </div>
    <!-- End refresh token -->

    <!-- Register token -->
    <div class="col-sm-12"  id="user-register">

        <h3>Register</h3>
        <div

                class=""
                style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px"
        >

            <b>Route: </b> &nbsp;<code>api/register</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>None</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>NONE</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Registration</code> <small> </small><br>

        </div>

        <h4>Header</h4>


        <pre style="font-weight:bold">
{
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>


        <h4> Form Data </h4>
        <pre style="font-weight:bold">
{
    "name" : "{name}",
    "email" : "{email}",
    "password" : "{password}",
    "confirm-password" : "{confirm-password}"
}
</pre>

        <h4>Response</h4>
        <pre style="font-weight:bold">
{
    "success": true,
    "user": {
        "id": 460,
        "name": "Temporary Name",
        "email": "michaelsuarez@shifl.com",
        "created_at": "2022-07-29T13:37:59.000000Z",
        "updated_at": "2022-07-29T13:37:59.000000Z",
        "forgot_password_verification_code": null,
        "forgot_password_verification_code_created_at": null,
        "is_forgot_password_requested": 0,
        "report_recipients": null,
        "phone": null,
        "shifl_office_id": null,
        "has_access_to_central_app": 1,
        "has_access_to_trucking_app": 0,
        "default_customer_id": null
    }
}
                                                        </pre>
        <p>
            In this endpoints user can access api without providing valid token on header
        </p>
    </div>
    <!-- End Register -->

    <!-- Logout token -->
    <div class="col-sm-12" id="user-logout">

        <h3>Logout</h3>
        <div

                class=""
                style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px"
        >

            <b>Route: </b> &nbsp;<code>api/logout</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>None</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>NONE</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Logout</code> <small> </small><br>

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
        <pre style="font-weight:bold">
NONE
</pre>

        <h4>Response</h4>
        <pre style="font-weight:bold">
{
   "message": "You have been successfully logged out"
}
                                                        </pre>
        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div>
    <!-- End logout -->

    <!-- Details -->
    <div class="col-sm-12" id="user-details" >

        <h3>Details</h3>
        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/details</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>NONE</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Details</code> <small> </small><br>

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
        <pre style="font-weight:bold">
NONE
</pre>

        <h4>Response</h4>
        <pre style="font-weight:bold">
{
    "success": {
        "id": 3,
        "name": "Anthony Marwyn Abadicio",
        "email": "anthony@shifl.com",
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
                "name": "Super Admin",
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
        "customers_api": []
    }
}
                                                        </pre>
        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>

    </div>
    <!-- End Details -->

    <!-- Update Customer Preference -->
    <div class="col-sm-12"  id="user-update-customer-preference">

        <h3>Update Customer Preference</h3>
        <p>
            Update Customer Preference for User endpoint.
            To update customer preference we have to input the valid customer_id.
            And the endpoint will return json value once updated.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;<code>api/v2/update-customer-preference</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>customer_id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Update Customer Preference</code> <small> </small><br>

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
{
    "customer_id" : "{customer_id}",
}
                                                        </pre>
        <h4>Response</h4>
        <pre>
{
    "status": "ok"
}
                                                        </pre>
        <p>
            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.
        </p>
    </div><!-- end Update Customer Preference -->

    <!-- Forgot Password -->
    <div class="col-sm-12"  id="user-forgot-password">

        <h3>Forgot Password</h3>
        <p>
            Forgot Password for User endpoint.
            To access forgot password, they have to provide email.
            And the endpoint will return json value.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;<code>api/forgot-password</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>NONE</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>email</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Forgot Password</code> <small> </small><br>

        </div>

        <h4>Header</h4>
        <pre style="font-weight:bold">
{
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>

        <h4> Form Data </h4>
        <pre>
{
    "email" : "{email}",
}
                                                        </pre>
        <h4>Response</h4>
        <pre>
{
    "status": "ok",
    "message": "We have sent you a change password link. Please check your email address",
    "error": null
}
                                                        </pre>

        <p>
            In this endpoints user can access api without providing valid token on header
        </p>
    </div><!-- end Forgot Password -->

    <!-- Check Forgot Password Code -->
    <div class="col-sm-12"  id="user-check-forgot-password-code">

        <h3>Check Forgot Password Code</h3>
        <p>
            Forgot Password for User endpoint.
            To access forgot password, they have to provide email.
            And the endpoint will return json value.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;<code>api/check-forgot-password-code</code> <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>NONE</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>forgot_password_verification_code</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Check Forgot Password Code</code> <small> </small><br>

        </div>

        <h4>Header</h4>
        <pre style="font-weight:bold">
{
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>

        <h4> Form Data </h4>
        <pre>
{
    "forgot_password_verification_code" : "{forgot_password_verification_code}",
}
                                                        </pre>
        <h4>Response</h4>
        <pre>
{
    "status": "ok"
}
                                                        </pre>
        <p>
            In this endpoints user can access api without providing valid token on header
        </p>
    </div><!-- end Check Forgot Password Code -->

    <!-- Change Password -->
    <div class="col-sm-12"  id="user-change-password">

        <h3>Change Password</h3>
        {{--<p>--}}
            {{--Forgot Password for User endpoint.--}}
            {{--To access forgot password, they have to provide email.--}}
            {{--And the endpoint will return json value.--}}
        {{--</p>--}}
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;<code>api/change-password</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>NONE</code> <br>
            <b>Body Form Data required: &nbsp;</b>
            <code>code</code>,
            <code>newPassword</code>,
            <code>renewPassword</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Change Password</code> <small> </small><br>

        </div>

        <h4>Header</h4>
        <pre style="font-weight:bold">
{
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>

        <h4> Form Data </h4>
        <pre>
{
    "code" : "{code}",
    "newPassword" : "{newPassword}",
    "renewPassword" : "{renewPassword}",
}
                                                        </pre>
        <h4>Response</h4>
        <pre>
{
    "status": "ok"
}
                                                        </pre>
        <p>
            In this endpoints user can access api without providing valid token on header
        </p>
    </div><!-- end Change Password -->

    <!-- update notification token -->
    <div class="col-sm-12"  id="user-update-notification-token">

        <h3>Update Notification Token</h3>
        {{--<p>--}}
        {{--Forgot Password for User endpoint.--}}
        {{--To access forgot password, they have to provide email.--}}
        {{--And the endpoint will return json value.--}}
        {{--</p>--}}
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;<code>api/v2/update-notification-token</code> <br>
            <b>Request Type: &nbsp;</b> <code>PUT</code> <br>
            <b>Header Required: &nbsp;</b> <code>NONE</code> <br>
            <b>Body Form Data required: &nbsp;</b>
            <code>notification_token  </code>
            <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Update notification token</code> <small> </small><br>

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
{
    "notification_token" : "{notification_token}",
}
                                                        </pre>
        <h4>Response</h4>
        <pre>
{
    "status": "ok"
}
                                                        </pre>
        <p>
            In this endpoints user can access api without providing valid token on header
        </p>
    </div><!-- end update notification token -->

</div>
<!-- End User -->