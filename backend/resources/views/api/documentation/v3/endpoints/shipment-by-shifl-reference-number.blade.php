<div class="col-sm-12" id="shipment-by-shifl-reference-number" > <!-- start Email Report Schedule -->

    <div class="col-sm-12" ><!-- Email Report Schedule specified resource -->
        <h3>{{ EndPointHelper::get('Shipment by Shifl Reference Number') }}</h3>     <p>
            For Shipment By Shifl Reference Number the headers, form data and api route will be same as mentioned above shipment by container number, only the form data type variable and response will change.
        </p>





        <h4> Form Data </h4>
        <pre>
{
    <span class="slctrl-attr">"userId"</span> : <span class="slctrl-number">{userId}</span>,
    <span class="slctrl-attr">"type"</span> : <span class="slctrl-string">{type > BYREFERENCE}</span>,
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
                    data-target="#shipment-by-shifl-reference-number-response"
                    aria-expanded="false"
                    aria-controls="shipment-by-shifl-reference-number-response"
                    style="cursor: pointer; top: 2px !important;"
                    class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre  style="font-weight:bold" class="collapse" id="shipment-by-shifl-reference-number-response">
{   <span class="slctrl-attr">"success"</span>: <span class="slctrl-literal">true</span>,
    <span class="slctrl-attr">"data"</span>: {
        <span class="slctrl-attr">"BYREFERENCE"</span>: {
            <span class="slctrl-attr">"Active"</span>: [
                {
                    <span class="slctrl-attr">"Shifl Ref#"</span>: <span class="slctrl-string">"718236"</span>,
                    <span class="slctrl-attr">"MBL#"</span>: <span class="slctrl-string">"ZIMUSHH30940855"</span>,
                    <span class="slctrl-attr">"PO#"</span>: [
                        <span class="slctrl-string">"NONE"</span>,
                        <span class="slctrl-string">"NONE"</span>
                    ],
                    <span class="slctrl-attr">"Consignee"</span>: <span class="slctrl-string">"Townley Inc."</span>,
                    <span class="slctrl-attr">"Shipper"</span>: [
                        <span class="slctrl-string">"Shenzhen Lantern Science Co.,Ltd"</span>,
                        <span class="slctrl-string">"FoShan Shine Fair Cosmetic Co., LTD"</span>
                    ],
                    <span class="slctrl-attr">"Telex Release"</span>: [
                        <span class="slctrl-string">"Pending"</span>,
                        <span class="slctrl-string">"Pending"</span>
                    ],
                    <span class="slctrl-attr">"Booked Date"</span>: <span class="slctrl-string">" "</span>,
                    <span class="slctrl-attr">"ETD"</span>: <span class="slctrl-string">""</span>,
                    <span class="slctrl-attr">"ETA"</span>: <span class="slctrl-string">""</span>,
                    <span class="slctrl-attr">"POL"</span>: <span class="slctrl-string">""</span>,
                    <span class="slctrl-attr">"POD"</span>: <span class="slctrl-string">""</span>,
                    <span class="slctrl-attr">"Terminal"</span>: <span class="slctrl-string">""</span>,
                    <span class="slctrl-attr">"Number of Containers"</span>: <span class="slctrl-number">1</span>,
                    <span class="slctrl-attr">"Container#"</span>: [
                        <span class="slctrl-string">"ZIMU3157712"</span>
                    ],
                    <span class="slctrl-attr">"Container Sizes"</span>: [
                        <span class="slctrl-string">"20'GP"</span>
                    ],
                    <span class="slctrl-attr">"Discharge Date"</span>: [
                        <span class="slctrl-string">""</span>
                    ],
                    <span class="slctrl-attr">"Freight Release"</span>: <span class="slctrl-string">"No"</span>,
                    <span class="slctrl-attr">"Customs Release"</span>: <span class="slctrl-string">"No"</span>,
                    <span class="slctrl-attr">"Customs Submitted/Release Date"</span>: <span class="slctrl-string">" "</span>,
                    <span class="slctrl-attr">"LFD"</span>: [
                        <span class="slctrl-string">""</span>
                    ],
                    <span class="slctrl-attr">"Status"</span>: <span class="slctrl-string">"Pending Approval"</span>,
                    <span class="slctrl-attr">"Full Gated Out"</span>: [
                        <span class="slctrl-string">""</span>
                    ],
                    <span class="slctrl-attr">"Empty Gated In"</span>: [
                        <span class="slctrl-string">""</span>
                    ],
                    <span class="slctrl-attr">"Tracking Status"</span>: <span class="slctrl-string">"Pending Approval"</span>
                },{...},...
            ],
            <span class="slctrl-attr">"Completed"</span>: [
                {
                    <span class="slctrl-attr">"Shifl Ref#"</span>: <span class="slctrl-string">"714613"</span>,
                    <span class="slctrl-attr">"MBL#"</span>: <span class="slctrl-string">"OOLU2131850510"</span>,
                    <span class="slctrl-attr">"PO#"</span>: [
                        <span class="slctrl-string">"NONE"</span>,
                        <span class="slctrl-string">"55988"</span>,
                        <span class="slctrl-string">"NONE"</span>
                    ],
                    <span class="slctrl-attr">"Consignee"</span>: <span class="slctrl-string">"Townley Inc."</span>,
                    <span class="slctrl-attr">"Shipper"</span>: [
                        <span class="slctrl-string">"JINHUA AOSHIYA COSMETICS CO.,LTD"</span>,
                        <span class="slctrl-string">"Jinhua TR Technology Co.,Ltd."</span>,
                        <span class="slctrl-string">"TIP TOP IMPORT & EXPORT CO.,LTD"</span>
                    ],
                    <span class="slctrl-attr">"Telex Release"</span>: [
                        <span class="slctrl-string">"Telex Released"</span>,
                        <span class="slctrl-string">"Telex Released"</span>,
                        <span class="slctrl-string">"Telex Released"</span>
                    ],
                    <span class="slctrl-attr">"Booked Date"</span>: <span class="slctrl-string">"03-30-2022"</span>,
                    <span class="slctrl-attr">"ETD"</span>: <span class="slctrl-string">"04-13-2022"</span>,
                    <span class="slctrl-attr">"ETA"</span>: <span class="slctrl-string">"05-06-2022"</span>,
                    <span class="slctrl-attr">"POL"</span>: <span class="slctrl-string">"Ningbo"</span>,
                    <span class="slctrl-attr">"POD"</span>: <span class="slctrl-string">"Los Angeles"</span>,
                    <span class="slctrl-attr">"Terminal"</span>: <span class="slctrl-string">"Long Beach Container Terminal (LBCT- PIER E) WAC8"</span>,
                    <span class="slctrl-attr">"Number of Containers"</span>: <span class="slctrl-number">1</span>,
                    <span class="slctrl-attr">"Container#"</span>: [
                        <span class="slctrl-string">"OOCU6503472"</span>
                    ],
                    <span class="slctrl-attr">"Container Sizes"</span>: [
                        <span class="slctrl-string">"40'HQ"</span>
                    ],
                    <span class="slctrl-attr">"Discharge Date"</span>: [
                        <span class="slctrl-string">"05-08-2022"</span>
                    ],
                    <span class="slctrl-attr">"Freight Release"</span>: <span class="slctrl-string">"Yes"</span>,
                    <span class="slctrl-attr">"Customs Release"</span>: <span class="slctrl-string">"Yes"</span>,
                    <span class="slctrl-attr">"Customs Submitted/Release Date"</span>: <span class="slctrl-string">"04-25-2022"</span>,
                    <span class="slctrl-attr">"LFD"</span>: [
                        <span class="slctrl-string">"05-12-2022"</span>
                    ],
                <span class="slctrl-attr">"Status"</span>: <span class="slctrl-string">"Completed"</span>,
                    <span class="slctrl-attr">"Full Gated Out"</span>: [
                        <span class="slctrl-string">"05-12-2022"</span>
                    ],
                    <span class="slctrl-attr">"Empty Gated In"</span>: [
                        <span class="slctrl-string">""</span>
                    ],
                    <span class="slctrl-attr">"Tracking Status"</span>: <span class="slctrl-string">"Completed"</span>
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
                <table class="table-bordered" style="width:50%;">
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
                                data-target="#shipment-by-shifl-reference-number-data"
                                aria-expanded="false"
                                aria-controls="shipment-by-shifl-reference-number-data"
                                style="cursor: pointer;  "

                                class="glyphicon1 icn-shipment-by-shifl-reference-number-data"
                                rel="icn-shipment-by-shifl-reference-number-data"
                        >
                                <span class="icn-shipment-by-shifl-reference-number-data">
                                   data <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                </span>
                        </td>
                        <td>object</td>
                    </tr>

                    <tr  class="collapse" id="shipment-by-shifl-reference-number-data">
                        <td colspan="2">
                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                <table class="table-bordered"  style="width:100%;">
                                    <thead></thead>
                                    <tbody>
                                        <tr>
                                            <td
                                                    data-toggle="collapse"
                                                    data-target="#shipment-by-shifl-reference-number-byreference"
                                                    aria-expanded="false"
                                                    aria-controls="shipment-by-shifl-reference-number-byreference"
                                                    style="cursor: pointer;  "

                                                    class="glyphicon1 icn-shipment-by-shifl-reference-number-byreference"
                                                    rel="icn-shipment-by-shifl-reference-number-byreference"
                                            >
                                                <span class="icn-shipment-by-shifl-reference-number-byreference">
                                                   BYREFERENCE <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                                </span>
                                            </td>
                                            <td>Object</td>
                                        </tr>
                                        <tr  class="collapse" id="shipment-by-shifl-reference-number-byreference">
                                            <td colspan="2">
                                                <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                    <table class="table-bordered" style="width: 100%;">
                                                        <thead></thead>
                                                        <tbody>
                                                        <tr>
                                                            <td
                                                                data-toggle="collapse"
                                                                data-target="#shipment-by-shifl-reference-number-active"
                                                                aria-expanded="false"
                                                                aria-controls="shipment-by-shifl-reference-number-active"
                                                                style="cursor: pointer;  "

                                                                class="glyphicon1 icn-shipment-by-shifl-reference-number-active"
                                                                rel="icn-shipment-by-shifl-reference-number-active"
                                                            >
                                                            <span class="icn-shipment-by-shifl-reference-number-active">
                                                               Active <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                                            </span>
                                                            </td>
                                                            <td>Object</td>
                                                        </tr>
                                                        <tr  class="collapse" id="shipment-by-shifl-reference-number-active">
                                                            <td colspan="2">
                                                                <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                                    <table class="table-bordered">
                                                                        <thead></thead>
                                                                        <tbody>
                                                                        <tr>
                                                                            <td>Shifl Ref#</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>MBL#</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>PO#</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Consignee</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Shipper</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Telex Release</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Booked Date</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>ETD</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>ETA</td>
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
                                                                            <td>Terminal</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Number of Containers</td>
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
                                                                            <td>Discharge Date</td>
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
                                                                            <td>Customs Submitted/Release Date</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>LFD</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Status</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Full Gated Out</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Empty Gated In</td>
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
                                                                    data-target="#shipment-by-shifl-reference-number-completed"
                                                                    aria-expanded="false"
                                                                    aria-controls="shipment-by-shifl-reference-number-completed"
                                                                    style="cursor: pointer;  "

                                                                    class="glyphicon1 icn-shipment-by-shifl-reference-number-completed"
                                                                    rel="icn-shipment-by-shifl-reference-number-completed"
                                                            >
                                                            <span class="icn-shipment-by-shifl-reference-number-completed">
                                                               Completed <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                                            </span>
                                                            </td>
                                                            <td>Object</td>
                                                        </tr>


                                                        <tr  class="collapse" id="shipment-by-shifl-reference-number-completed">
                                                            <td colspan="2">
                                                                <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                                    <table class="table-bordered">
                                                                        <thead></thead>
                                                                        <tbody>
                                                                        <tr>
                                                                            <td>Shifl Ref#</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>MBL#</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>PO#</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Consignee</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Shipper</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Telex Release</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Booked Date</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>ETD</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>ETA</td>
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
                                                                            <td>Terminal</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Number of Containers</td>
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
                                                                            <td>Discharge Date</td>
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
                                                                            <td>Customs Submitted/Release Date</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>LFD</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Status</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Full Gated Out</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Empty Gated In</td>
                                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Tracking Status</td>
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
        <p>
            {{ EndPointHelper::userGuide() }}
        </p>
    </div><!-- End Email Report Schedule specified resource -->


</div> <!-- start Email Report Schedule -->

