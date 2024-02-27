<div class="col-sm-12" id="shipment-by-container-number" > <!-- start Email Report Schedule -->

    <div class="col-sm-12" ><!-- Email Report Schedule specified resource -->
        <h3>{{ EndPointHelper::get('Shipment by Container Number') }}</h3>
        <p>
            {{ EndPointHelper::getDescriptionArray('Shipment by Container Number', array('userId', 'type')) }};
        </p>
        <br>
        <div class="" style="position:relative;padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b>
            <span class="glyphicon1" rel="i-shipment-by-container-number" aria-hidden="true">
                    <span
                            data-toggle="collapse"
                            data-target="#route-shipment-by-container-number"
                            aria-expanded="false"
                            aria-controls="route-shipment-by-container-number"
                            style="cursor: pointer"
                            class="i-shipment-by-container-number"
                    > <code>/api/shipment-report/user/{userId}/type/{type}</code>
                        <span class="glyphicon glyphicon-menu-right " aria-hidden="true">
                        </span>
                    </span>
                </span>
            <div class="collapse api-route" id="route-shipment-by-container-number">
                <div class="badge-success c-shipment-by-container-number hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">


                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="c-shipment-by-container-number">Copy</div>
                </div>

                <div class="api_ noselect" id="c-shipment-by-container-number">
                    {{ config('app.url')}}/api/shipment-report/user/{userId}/type/{type}
                </div>
            </div>  <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>userId</code>, <code>type</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Shipment By Shifl Reference Number </code> <small> </small><br>
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
    <span class="slctrl-attr">"userId"</span> : <span class="slctrl-number">{userId}</span>,
    <span class="slctrl-attr">"type"</span> : <span class="slctrl-string">{type > BYCONTAINER}</span>,
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
                    <td>userId<span class="badge-danger">required</span></td>
                    <td><i>Integer</i> The unique userId for the user</td>
                </tr>
                <tr>
                    <td>type<span class="badge-danger">required</span></td>
                    <td><i>String</i> We have three types of the api: BYREFERENCE, BYREFERENCEADV and BYCONTAINER</td>
                </tr>
                </tbody>
            </table>
        </div>

        <h4>Response
            <span
                    data-toggle="collapse"
                    data-target="#shipment-by-container-number-response"
                    aria-expanded="false"
                    aria-controls="shipment-by-container-number-response"
                    style="cursor: pointer; top: 2px !important;"
                    class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre  style="font-weight:bold" class="collapse" id="shipment-by-container-number-response">
{
    <span class="slctrl-attr">"success"</span>: <span class="slctrl-literal">true</span>,
    <span class="slctrl-attr">"data"</span>: {
        <span class="slctrl-attr">"BYCONTAINER"</span>: {
            <span class="slctrl-attr">"Active"</span>: [
                {
                    <span class="slctrl-attr">"Shifl Ref#"</span>: <span class="slctrl-string">"718236"</span>,
                    <span class="slctrl-attr">"PO#"</span>: [
                        <span class="slctrl-string">"NONE"</span>,
                        <span class="slctrl-string">"NONE"</span>
                    ],
                    <span class="slctrl-attr">"Shipper"</span>: [
                        <span class="slctrl-string">"Shenzhen Lantern Science Co.,Ltd"</span>,
                        <span class="slctrl-string">"FoShan Shine Fair Cosmetic Co., LTD"</span>
                    ],
                    <span class="slctrl-attr">"Factory Cargo Ready Date"</span>: [
                        <span class="slctrl-string">"09-09-2022"</span>,
                        <span class="slctrl-string">"09-09-2022"</span>
                    ],
                    <span class="slctrl-attr">"Supplier Cartons"</span>: <span class="slctrl-number">398</span>,
                    <span class="slctrl-attr">"Booked Date"</span>: <span class="slctrl-string">" "</span>,
                    <span class="slctrl-attr">"POL"</span>: <span class="slctrl-string">""</span>,
                    <span class="slctrl-attr">"POD"</span>: <span class="slctrl-string">""</span>,
                    <span class="slctrl-attr">"Delivery Loc (WRHS)"</span>: <span class="slctrl-string">""</span>,
                    <span class="slctrl-attr">"Consignee"</span>: <span class="slctrl-string">"Townley Inc."</span>,
                    <span class="slctrl-attr">"Type"</span>: <span class="slctrl-string">""</span>,
                    <span class="slctrl-attr">"Mode"</span>: <span class="slctrl-string">""</span>,
                    <span class="slctrl-attr">"Carrier"</span>: <span class="slctrl-string">""</span>,
                    <span class="slctrl-attr">"MBL#"</span>: <span class="slctrl-string">"ZIMUSHH30940855"</span>,
                    <span class="slctrl-attr">"Vessel Name"</span>: <span class="slctrl-string">"SYNERGY OAKLAND"</span>,
                    <span class="slctrl-attr">"Voyage#"</span>: <span class="slctrl-string">"V.6E"</span>,
                    <span class="slctrl-attr">"Total Cartons"</span>: <span class="slctrl-string">398</span>,
                    <span class="slctrl-attr">"Container#"</span>: <span class="slctrl-string">"ZIMU3157712"</span>,
                    <span class="slctrl-attr">"Container Sizes"</span>: <span class="slctrl-string">"20'GP"</span>,
                    <span class="slctrl-attr">"Container Weight (kg)"</span>: <span class="slctrl-string">"4245.89"</span>,
                    <span class="slctrl-attr">"Container Cartons"</span>: <span class="slctrl-string">"398"</span>,
                    <span class="slctrl-attr">"Container Volume"</span>: <span class="slctrl-string">"27.480"</span>,
                    <span class="slctrl-attr">"Container Seal#"</span>: <span class="slctrl-string">"A4221536456"</span>,
                    <span class="slctrl-attr">"Freight Rate Services"</span>: [],
                    <span class="slctrl-attr">"Status"</span>: <span class="slctrl-string">"Pending Approval"</span>,
                    <span class="slctrl-attr">"HBL#"</span>: [
                        <span class="slctrl-string">"SQFN718236A"</span>,
                        <span class="slctrl-string">"SQFN718236B"</span>
                    ],
                    <span class="slctrl-attr">"Telex Release"</span>: [
                        <span class="slctrl-string">"Pending"</span>,
                        <span class="slctrl-string">"Pending"</span>
                    ],
                    <span class="slctrl-attr">"Current ETD"</span>: <span class="slctrl-string">""</span>,
                    <span class="slctrl-attr">"Current ETA"</span>: <span class="slctrl-string">""</span>,
                    <span class="slctrl-attr">"Original ETA"</span>: <span class="slctrl-string">""</span>,
                    <span class="slctrl-attr">"Empty Out To FTY"</span>: <span class="slctrl-string">"09-22-2022"</span>,
                    <span class="slctrl-attr">"Gated In POL"</span>: <span class="slctrl-string">"09-23-2022"</span>,
                    <span class="slctrl-attr">"Arrival Notice Sent"</span>: <span class="slctrl-string">""</span>,
                    <span class="slctrl-attr">"DO Sent"</span>: <span class="slctrl-string">""</span>,
                    <span class="slctrl-attr">"Freight Release"</span>: <span class="slctrl-string">"No"</span>,
                    <span class="slctrl-attr">"Customs Release"</span>: <span class="slctrl-string">" "</span>,
                    <span class="slctrl-attr">"Discharge"</span>: <span class="slctrl-string">""</span>,
                    <span class="slctrl-attr">"Latest LFD"</span>: <span class="slctrl-string">""</span>,
                    <span class="slctrl-attr">"Container Picked Up"</span>: <span class="slctrl-string">""</span>,
                    <span class="slctrl-attr">"Empty container returned"</span>: <span class="slctrl-string">"",</span>
                    <span class="slctrl-attr">"Dmrg Rate Per Day"</span>: [],
                    <span class="slctrl-attr">"Demurrage Days"</span>: [],
                    <span class="slctrl-attr">"Demurrage Total"</span>: <span class="slctrl-string">""</span>,
                    <span class="slctrl-attr">"Per Diem Rate Day"</span>: [],
                    <span class="slctrl-attr">"Per Diem Days"</span>: [],
                    <span class="slctrl-attr">"Total Per Diem"</span>: <span class="slctrl-string">""</span>,
                    <span class="slctrl-attr">"Pier Pass"</span>: <span class="slctrl-string">""</span>,
                    <span class="slctrl-attr">"Customs Entry Cost"</span>: [],
                    <span class="slctrl-attr">"Other Charges"</span>: [],
                    <span class="slctrl-attr">"Other Services Total"</span>: [],
                    <span class="slctrl-attr">"Tracking Status"</span>: <span class="slctrl-string">"Auto Tracking"</span>,
                    <span class="slctrl-attr">"Booking#"</span>: <span class="slctrl-string">"SHH30940855"</span>
                },{...},...
            ],
            <span class="slctrl-attr">"Completed"</span>: [
                {
                    <span class="slctrl-attr">"Shifl Ref#"</span>: <span class="slctrl-string">"714613"</span>,
                    <span class="slctrl-attr">"PO#"</span>: [
                        <span class="slctrl-string">"NONE"</span>,
                        <span class="slctrl-string">"55988"</span>,
                        <span class="slctrl-string">"NONE"</span>
                    ],
                    <span class="slctrl-attr">"Shipper"</span>: [
                        <span class="slctrl-string">"JINHUA AOSHIYA COSMETICS CO.,LTD"</span>,
                        <span class="slctrl-string">"Jinhua TR Technology Co.,Ltd."</span>,
                        <span class="slctrl-string">"TIP TOP IMPORT & EXPORT CO.,LTD"</span>
                    ],
                    <span class="slctrl-attr">"Factory Cargo Ready Date"</span>: [
                        <span class="slctrl-string">"04-06-2022"</span>,
                        <span class="slctrl-string">"04-07-2022"</span>,
                        <span class="slctrl-string">"04-05-2022"</span>
                    ],
                    <span class="slctrl-attr">"Supplier Cartons"</span>: <span class="slctrl-number">1569</span>,
                    <span class="slctrl-attr">"Booked Date"</span>: <span class="slctrl-string">"03-30-2022"</span>,
                    <span class="slctrl-attr">"POL"</span>: <span class="slctrl-string">"Ningbo"</span>,
                    <span class="slctrl-attr">"POD"</span>: <span class="slctrl-string">"Los Angeles"</span>,
                    <span class="slctrl-attr">"Delivery Loc (WRHS)"</span>: <span class="slctrl-string">"ADDRESS: Kenko\n13355 Cambridge Street Santa Fe Springs, CA 90670\nTel: 562.663.8888 gyuen@kenkogroup.com"</span>,
                    <span class="slctrl-attr">"Consignee"</span>: <span class="slctrl-string">"Townley Inc."</span>,
                    <span class="slctrl-attr">"Type"</span>: <span class="slctrl-string">"FCL"</span>,
                    <span class="slctrl-attr">"Mode"</span>: <span class="slctrl-string">"Ocean"</span>,
                    <span class="slctrl-attr">"Carrier"</span>: <span class="slctrl-string">"OOCL"</span>,
                    <span class="slctrl-attr">"MBL#"</span>: <span class="slctrl-string">"OOLU2131850510"</span>,
                    <span class="slctrl-attr">"Vessel Name"</span>: <span class="slctrl-string">"OOCL TOKYO V.119E"</span>,
                    <span class="slctrl-attr">"Voyage#"</span>: <span class="slctrl-string">"119E"</span>,
                    <span class="slctrl-attr">"Total Cartons"</span>: <span class="slctrl-number">1569</span>,
                    <span class="slctrl-attr">"Container#"</span>: <span class="slctrl-string">"OOCU6503472"</span>,
                    <span class="slctrl-attr">"Container Sizes"</span>: <span class="slctrl-string">"40'HQ"</span>,
                    <span class="slctrl-attr">"Container Weight (kg)"</span>: <span class="slctrl-string">"10886.49"</span>,
                    <span class="slctrl-attr">"Container Cartons"</span>: <span class="slctrl-string">"1569"</span>,
                    <span class="slctrl-attr">"Container Volume"</span>: <span class="slctrl-string">"64.400"</span>,
                    <span class="slctrl-attr">"Container Seal#"</span>: <span class="slctrl-string">"OOLGFK8262"</span>,
                    <span class="slctrl-attr">"Freight Rate Services"</span>: [
                        <span class="slctrl-string">"9900"</span>
                    ],
                    <span class="slctrl-attr">"Status"</span>: <span class="slctrl-string">"Completed"</span>,
                    <span class="slctrl-attr">"HBL#"</span>: [
                        <span class="slctrl-string">"SQFN714613A"</span>,
                        <span class="slctrl-string">"SQFN714613B"</span>,
                        <span class="slctrl-string">"SQFN714613C"</span>
                    ],
                    <span class="slctrl-attr">"Telex Release"</span>: [
                        <span class="slctrl-string">"Telex Released"</span>,
                        <span class="slctrl-string">"Telex Released"</span>,
                        <span class="slctrl-string">"Telex Released"</span>
                    ],
                    <span class="slctrl-attr">"Current ETD"</span>: <span class="slctrl-string">"04-13-2022"</span>,
                    <span class="slctrl-attr">"Current ETA"</span>: <span class="slctrl-string">"05-06-2022"</span>,
                    <span class="slctrl-attr">"Original ETA"</span>: <span class="slctrl-string">"04-26-2022"</span>,
                    <span class="slctrl-attr">"Empty Out To FTY"</span>: <span class="slctrl-string">"04-08-2022"</span>,
                    <span class="slctrl-attr">"Gated In POL"</span>: <span class="slctrl-string">"04-10-2022"</span>,
                    <span class="slctrl-attr">"Arrival Notice Sent"</span>: <span class="slctrl-string">"04/21/22  07:18 pm"</span>,
                    <span class="slctrl-attr">"DO Sent"</span>: <span class="slctrl-string">"04/21/22  07:19 pm"</span>,
                    <span class="slctrl-attr">"Freight Release"</span>: <span class="slctrl-string">"Yes"</span>,
                    <span class="slctrl-attr">"Customs Release"</span>: <span class="slctrl-string">"04-25-2022"</span>,
                    <span class="slctrl-attr">"Discharge"</span>: <span class="slctrl-string">"05-08-2022"</span>,
                    <span class="slctrl-attr">"Latest LFD"</span>: <span class="slctrl-string">"05-12-2022"</span>,
                    <span class="slctrl-attr">"Container Picked Up"</span>: <span class="slctrl-string">"05-12-2022"</span>,
                    <span class="slctrl-attr">"Empty container returned"</span>: <span class="slctrl-string">""</span>,
                    <span class="slctrl-attr">"Dmrg Rate Per Day"</span>: [],
                    <span class="slctrl-attr">"Demurrage Days"</span>: [],
                    <span class="slctrl-attr">"Demurrage Total"</span>: <span class="slctrl-string">""</span>,
                    <span class="slctrl-attr">"Per Diem Rate Day"</span>: [],
                    <span class="slctrl-attr">"Per Diem Days"</span>: [],
                    <span class="slctrl-attr">"Total Per Diem"</span>: <span class="slctrl-string">""</span>,
                    <span class="slctrl-attr">"Pier Pass"</span>: <span class="slctrl-string">133.42</span>,
                    <span class="slctrl-attr">"Customs Entry Cost"</span>: [
                        <span class="slctrl-attr">"150"</span>
                    ],
                    <span class="slctrl-attr">"Other Charges"</span>: {
                        <span class="slctrl-attr">"Ocean Freight"</span>: <span class="slctrl-string">9900</span>,
                        <span class="slctrl-attr">"Duty"</span>: <span class="slctrl-string">204.75</span>,
                        <span class="slctrl-attr">"Trucking"</span>: <span class="slctrl-string">700</span>,
                        <span class="slctrl-attr">"Chassis"</span>: <span class="slctrl-string">100</span>
                    },
                    <span class="slctrl-attr">"Other Services Total"</span>: [],
                    <span class="slctrl-attr">"Tracking Status"</span>: <span class="slctrl-string">"Auto Tracked"</span>,
                    <span class="slctrl-attr">"Booking#"</span>: <span class="slctrl-string">""</span>
                },{...},...
            ]
        }
    },
    <span class="slctrl-attr">"message"</span>: <span class="slctrl-string">"Data shipment successfully fetch"</span>
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
                        <td>success</td>
                        <td><i>String</i> Default: <code>NULL</code>,</td>
                    </tr>
                    <tr>
                        <td
                            data-toggle="collapse"
                            data-target="#shipment-by-container-number-data"
                            aria-expanded="false"
                            aria-controls="#shipment-by-container-number-data"
                            style="cursor: pointer;  "

                            class="glyphicon1 icn-shipment-by-container-number-data"
                            rel="icn-shipment-by-container-number-data"
                        >
                                <span class="icn-shipment-by-container-number-data">
                                   data <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                </span>
                        </td>
                        <td>Object</td>
                    </tr>



                    <tr  class="collapse" id="shipment-by-container-number-data">
                        <td colspan="2">
                            <div style="background: rgb(245, 247, 250);border-radius: 8px;">
                                <table class="table-bordered" style="width:100%"    >
                                    <thead></thead>
                                    <tbody>
                                    <tr>
                                        <td
                                            data-toggle="collapse"
                                            data-target="#shipment-by-container-number-bycontainer"
                                            aria-expanded="false"
                                            aria-controls="#shipment-by-container-number-bycontainer"
                                            style="cursor: pointer;  "

                                            class="glyphicon1 icn-shipment-by-container-number-bycontainer"
                                            rel="icn-shipment-by-container-number-bycontainer"
                                        >
                                            <span class="icn-shipment-by-container-number-bycontainer">
                                               BYCONTAINER <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                            </span>
                                        </td>
                                        <td>Object</td>
                                    </tr>
                                    <tr  class="collapse" id="shipment-by-container-number-bycontainer">
                                        <td colspan="2">
                                            <div style="background: rgb(245, 247, 250);border-radius: 8px;">
                                                <table class="table-bordered" style="width:100%">
                                                    <thead></thead>
                                                    <tbody>



                                                    <tr>
                                                        <td
                                                                data-toggle="collapse"
                                                                data-target="#shipment-by-container-number-active"
                                                                aria-expanded="false"
                                                                aria-controls="shipment-by-container-number-active"
                                                                style="cursor: pointer;  "

                                                                class="glyphicon1 icn-shipment-by-container-number-active"
                                                                rel="icn-shipment-by-container-number-active"
                                                        >
                                                            <span class="icn-shipment-by-container-number-active">
                                                               Active <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                                            </span>
                                                        </td>
                                                        <td>object</td>
                                                    </tr>

                                                    <tr  class="collapse" id="shipment-by-container-number-active">
                                                        <td colspan="2">
                                                            <div style="background: rgb(245, 247, 250);border-radius: 8px;">
                                                                <table class="table-bordered">
                                                                    <thead></thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>Shifl Ref#</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>PO#</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Shipper</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Factory Cargo Ready Date</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Supplier Cartons</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Booked Date</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>POL</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>POD</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Delivery Loc (WRHS)</td>
                                                                            <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Consignee</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Telex Release</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Type</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Mode</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Carrier</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>MBL#</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Vessel Name</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Voyage#</td>
                                                                            <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Total Cartons</td>
                                                                            <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Container#</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Container Sizes</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Container Weight (kg)</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Container Cartons</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Container Volume</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Container Seal#</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Freight Rate Services</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Status</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>HBL#</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Telex Release</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Current ETD</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Current ETA</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Original ETA</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Empty Out To FTY</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Gated In POL</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Arrival Notice Sent</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>DO Sent</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Freight Release</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Customs Release</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Latest LFD</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Container Picked Up</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Empty container returned</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Dmrg Rate Per Day</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Demurrage Days</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Demurrage Total</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Per Diem Rate Day</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Per Diem Days</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Total Per Diem</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Pier Pass</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Customs Entry Cost</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Other Charges</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Other Services Total</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Tracking Status</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Booking#</td>
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
                                                                data-target="#shipment-by-container-number-completed"
                                                                aria-expanded="false"
                                                                aria-controls="shipment-by-container-number-completed"
                                                                style="cursor: pointer;  "

                                                                class="glyphicon1 icn-shipment-by-container-number-completed"
                                                                rel="icn-shipment-by-container-number-completed"
                                                        >
                                                            <span class="icn-shipment-by-container-number-completed">
                                                               Completed <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                                            </span>
                                                        </td>
                                                        <td>Object</td>
                                                    </tr>


                                                    <tr  class="collapse" id="shipment-by-container-number-completed">
                                                        <td colspan="2">
                                                            <div style="background: rgb(245, 247, 250);border-radius: 8px;">
                                                                <table class="table-bordered" style="width: 100%">
                                                                    <thead></thead>
                                                                    <tbody>
                                                                    <tr>
                                                                        <td>Shifl Ref#</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>PO#</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Shipper</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Factory Cargo Ready Date</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Supplier Cartons</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Booked Date</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>POL</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>POD</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Delivery Loc (WRHS)</td>
                                                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Consignee</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Type</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Mode</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Carrier</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>MBL#</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Vessel Name</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Voyage#</td>
                                                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Total Cartons</td>
                                                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Container#</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Container Sizes</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Container Weight (kg)</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Container Cartons</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Container Volume</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Container Seal#</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Freight Rate Services</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Status</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>HBL#</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Telex Release</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Current ETD</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Current ETA</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Original ETA</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Empty Out To FTY</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Gated In POL</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Arrival Notice Sent</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>DO Sent</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Freight Release</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Customs Release</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Discharge</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Latest LFD</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Container Picked Up</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Empty container returned</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Dmrg Rate Per Day</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Demurrage Days</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Demurrage Total</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Per Diem Rate Day</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Per Diem Days</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Total Per Diem</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Pier Pass</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Customs Entry Cost</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>

                                                                        <td
                                                                            data-toggle="collapse"
                                                                            data-target="#shipment-by-container-number-other-charges"
                                                                            aria-expanded="false"
                                                                            aria-controls="shipment-by-container-number-other-charges"
                                                                            style="cursor: pointer;  "

                                                                            class="glyphicon1 icn-shipment-by-container-number-other-charges"
                                                                            rel="icn-shipment-by-container-number-other-charges"
                                                                        >
                                                                            <span class="icn-shipment-by-container-number-other-charges">
                                                                               Other Charges <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                                                            </span>
                                                                        </td>

                                                                        <td>Object</td>
                                                                    </tr>

                                                                    <tr  class="collapse" id="shipment-by-container-number-other-charges">
                                                                        <td colspan="2">
                                                                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                                                <table class="table-bordered" style="width: 100%">
                                                                                    <thead></thead>
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td>Ocean Freight</td>
                                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>Duty</td>
                                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>Trucking</td>
                                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>Chassis</td>
                                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Other Services Total</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Tracking Status</td>
                                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Booking#</td>
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
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>

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

