<div class="col-sm-12" id="milestone"> <!-- start Milestone -->
    <h3 class="page-header">{{ EndPointHelper::endPoint('Milestone') }}</h3>
    <div class="col-sm-12" id="milestone-specified"><!-- Milestone specified resource -->
        <h3>{{ EndPointHelper::get('Milestone') }}</h3>
        <p>
            {{ EndPointHelper::getDescription('Milestone', 'mbl_num') }}
        </p>
        <br>
        <div class="" style="position:relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-milestones-mbl_num" aria-hidden="true">
                <span
                        data-toggle="collapse"
                        data-target="#route-milestones-mbl_num"
                        aria-expanded="false"
                        aria-controls="route-milestones-mbl_num"
                        style="cursor: pointer"
                        class="i-milestones-mbl_num"
                > <code>api/milestones/{mbl_num}</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                </span>
            </span>
            <div class="collapse api-route" id="route-milestones-mbl_num">
                <div class="badge-success milestones-mbl_num hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="milestones-mbl_num">Copy</div>
                </div>

                <div class="api_ noselect" id="milestones-mbl_num">
                    {{ config('app.url')}}/api/milestones/{mbl_num}
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>mbl_num</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Milestone (Specific Milestone) </code> <small> </small><br>
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
    <span class="slctrl-attr">"mbl_num"</span>: <span class="slctrl-string">"{mbl_num}"</span>,
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
                    <td>mbl_num<span class="badge-danger">required</span></td>
                    <td><i>Integer</i> The unique mbl_num for the Milestone to be retrieved</td>
                </tr>
                </tbody>
            </table>
        </div>
        <h4>Response
            <span
                data-toggle="collapse"
                data-target="#milestones-mbl_num-response"
                aria-expanded="false"
                aria-controls="milestones-mbl_num-response"
                style="cursor: pointer; top: 2px !important;"
                class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre style="font-weight:bold" class="collapse" id="milestones-mbl_num-response">
{
    <span class="slctrl-attr">"data"</span>: {
        <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"mbl_num"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"tr_id"</span>: <span class="slctrl-string">"41259d1a-3c15-4614-ae51-417f1faa7faf"</span>,
        <span class="slctrl-attr">"ts_id"</span>: <span class="slctrl-string">"147e127b-f114-484a-8337-58bd767af346"</span>,
        <span class="slctrl-attr">"etd"</span>: <span class="slctrl-string">"2021-03-04"</span>,
        <span class="slctrl-attr">"eta"</span>: <span class="slctrl-string">"2021-04-10"</span>,
        <span class="slctrl-attr">"attributes"</span>: <span class="slctrl-string">""</span>,
        <span class="slctrl-attr">"relationships"</span>: <span class="slctrl-string">""</span>,
        <span class="slctrl-attr">"containers"</span>: <span class="slctrl-string">""</span>,
        <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2021-04-07T00:05:30.000000Z"</span>,
        <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2021-11-03T20:43:57.000000Z"</span>,
        <span class="slctrl-attr">"terminals"</span>: <span class="slctrl-string">""</span>,
        <span class="slctrl-attr">"transport_events"</span>: <span class="slctrl-string">""</span>,
        <span class="slctrl-attr">"ignore_demurrage"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"lfd_daily_review_status"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"lfd_notes"</span>: <span class="slctrl-literal">null</span>
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
                        <th colspan="2"><span class="badge-success">200 collection of Carrier</span></th>
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
                                data-target="#get-milestone-data"
                                aria-expanded="false"
                                aria-controls="get-milestone-data"
                                style="cursor: pointer;  "

                                class="glyphicon1 icon-get-milestone-data"
                                rel="icon-get-milestone-data"
                            >
                                <span class="icon-get-milestone-data">
                                   data <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                </span>
                            </td>
                            <td>object</td>
                        </tr>
                        <tr  class="collapse" id="get-milestone-data">
                            <td colspan="2">
                                <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                    <table class="table-bordered">
                                        <thead></thead>
                                        <tbody>
                                            <tr>
                                                <td>id</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>

                                            <tr>
                                                <td>mbl_num</td>
                                                <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                            </tr>

                                            <tr>
                                                <td>tr_id</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>

                                            <tr>
                                                <td>ts_id</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>

                                            <tr>
                                                <td>etd</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>

                                            <tr>
                                                <td>eta</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>

                                            <tr>
                                                <td>attributes</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>

                                            <tr>
                                                <td>relationships</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>

                                            <tr>
                                                <td>containers</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>

                                            <tr>
                                                <td>created_at</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>updated_at</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>

                                            <tr>
                                                <td>terminals</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>

                                            <tr>
                                                <td>transport_events</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>

                                            <tr>
                                                <td>ignore_demurrage</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>

                                            <tr>
                                                <td>lfd_daily_review_status</td>
                                                <td><i>String</i> Default: <code>NULL</code>,</td>
                                            </tr>
                                            <tr>
                                                <td>lfd_notes</td>
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
        </div>
        <p>
            {{ EndPointHelper::userGuide() }}
        </p>
    </div><!-- End Milestone specified resource -->
</div> <!-- end of Milestone -->



