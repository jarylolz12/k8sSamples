<div class="col-sm-12 hidden" id="waiting-list">
    <h3 class="page-header">Waiting List</h3>

    <!-- Register User -->
    <div class="col-sm-12 " id="waiting-list-register" >

        <h3>Register User</h3>
        <p>
            Register User for Waiting List endpoint.
            To create the user for waiting list endpoint we have to input the ff. data:
            company_name, business_type, approximate_annual_shipments, phone_number, email and contact_person.
            And the endpoint will return json value once updated.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/join-waiting-list</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>company_name</code>,
            <code>business_type</code>,
            <code>approximate_annual_shipments</code>,
            <code>phone_number</code>,
            <code>email</code>,
            <code>contact_person</code>
            <br>
            <b>Return Type: &nbsp;</b> <code>STRING</code> <br>
            <b>Will Return: &nbsp;</b> <code>Register user for waiting list</code> <small> </small><br>

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
    "default_customer_id" : "{default_customer_id}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
Created
        </pre>

        <p>
            In this endpoints user can access api without providing valid token on header
        </p>
    </div>
    <!-- End Register User -->
</div> <!-- end Waiting -->
