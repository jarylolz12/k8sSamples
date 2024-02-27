<div class="col-sm-12" id="amazon">

    <h3 class="page-header">Amazon</h3>

    <!-- Create Store -->
    <div class="col-sm-12" id="amazon-create" >

        <h3>Create Amazon</h3>
        <p>
            Create resource for Amazon endpoint.
            To create the Amazon we have to input the ff. data: sellingPartnerId, code and customer_id.
            And the endpoint will return json value once created.
        </p>
        <br>

        <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

            <b>Route: </b> &nbsp;<code>api/amazon/store</code> <br>
            <b>Request Type: &nbsp;</b> <code>POST</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>sellingPartnerId</code>, <code>code</code> , <code>customer_id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>Create Store</code> <small> </small><br>

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
    "sellingPartnerId" : "{sellingPartnerId}",
    "code" : "{code}",
    "customer_id" : "{customer_id}"
}
                                                        </pre>

        <h4>Response</h4>
        <pre>
 PENDING
                                                        </pre>


        <p>

            Everytime users want to access other api endpoints they have to provide validate token on header with every request.
            Otherwise The request will be considered as unauthorized attempt.

        </p>


    </div>
    <!-- End Create Store-->



</div> <!-- end Amazon Store-->
