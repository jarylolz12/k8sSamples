<div class="col-sm-12" id="delete-purchase-order" > <!-- start Email Report Schedule -->

    <div class="col-sm-12" ><!-- Email Report Schedule specified resource -->
        <h3>{{ EndPointHelper::delete('Purchase Order') }}</h3>
        <p>
            {{ EndPointHelper::deleteDescription('Purchase Order', array('id')) }};
        </p>
        <br>
        <div class="" style="position:relative;padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b>
            <span class="glyphicon1" rel="i-delete-purchase-order" aria-hidden="true">
                    <span
                            data-toggle="collapse"
                            data-target="#route-delete-purchase-order"
                            aria-expanded="false"
                            aria-controls="route-delete-purchase-order"
                            style="cursor: pointer"
                            class="i-delete-purchase-order"
                    > <code>/api/orders/delete/{id}}</code>
                        <span class="glyphicon glyphicon-menu-right " aria-hidden="true">
                        </span>
                    </span>
                </span>
            <div class="collapse api-route" id="route-delete-purchase-order">
                <div class="badge-success c-delete-purchase-order hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">


                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="c-delete-purchase-order">Copy</div>
                </div>

                <div class="api_ noselect" id="c-delete-purchase-order">
                    {{ config('app.url')}}/api/orders/delete/{id}
                </div>
            </div>  <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>userId</code>, <code>type</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Shipment By Shifl Reference Numbebr </code> <small> </small><br>
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
    <span class="slctrl-attr">"id"</span> : <span class="slctrl-number">{purchase-order-id}</span>,
}</pre>



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
                    <td><i>Integer</i> The unique id for the purchase order</td>
                </tr>
                </tbody>
            </table>
        </div>

        <h4>Response
            <span
                    data-toggle="collapse"
                    data-target="#delete-purchase-order-response"
                    aria-expanded="false"
                    aria-controls="delete-purchase-order-response"
                    style="cursor: pointer; top: 2px !important;"
                    class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre  style="font-weight:bold" class="collapse" id="delete-purchase-order-response">
{
     <span class="slctrl-attr">"message"</span>: <span class="slctrl-string">"Purchase Order has been deleted."</span>
}</pre>

        <div style="margin-bottom: 10px">
            <div>
                <h4 style="display: inline-block">Responses</h4>
            </div>
            <div>
                <table class="table-bordered" style="width: 50%">
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
                        <td><i>String</i> Default: <code>NULL</code>,</td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
        {{--<p>--}}
        {{--{{ EndPointHelper::userGuide() }}--}}
        {{--</p>--}}
    </div><!-- End Email Report Schedule specified resource -->


</div> <!-- start Email Report Schedule -->

