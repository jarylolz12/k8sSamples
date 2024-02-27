<div class="col-sm-12" id="email-report-schedule" > <!-- start Email Report Schedule -->

    <div class="col-sm-12" id="get-email-reporting-by-user-id"><!-- Email Report Schedule specified resource -->
        <h3>{{ EndPointHelper::get('Email Reporting By User ID') }}</h3>
        <p>
            {{ EndPointHelper::getDescriptionArray('Email Reporting By User ID', array('user_id', 'email_report_schedule_id')) }};
        </p>
        <br>
        <div class="" style="position:relative;padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
                <b>Route: </b>
                <span class="glyphicon1" rel="i-email-reporting-by-user-id" aria-hidden="true">
                    <span
                            data-toggle="collapse"
                            data-target="#route-email-reporting-by-user-id"
                            aria-expanded="false"
                            aria-controls="route-email-reporting-by-user-id"
                            style="cursor: pointer"
                            class="i-email-reporting-by-user-id"
                    > <code>api/email-report-schedule/{user_id}</code>
                        <span class="glyphicon glyphicon-menu-right " aria-hidden="true">
                        </span>
                    </span>
                </span>
                <div class="collapse api-route" id="route-email-reporting-by-user-id">
                    <div class="badge-success email-reporting-by-user-id hidden" style="border-radius: 6px !important; ">
                        <span >Copied</span>
                    </div>
                    <div class="flex-port">


                        <p>SHIFL CENTRAL API</p>
                        <div  class="copy-api" rel="email-reporting-by-user-id">Copy</div>
                    </div>

                    <div class="api_ noselect" id="email-reporting-by-user-id">
                        {{ config('app.url')}}/api/email-report-schedule/{user_id}
                    </div>
                </div>  <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>user_id</code>, <code>email_report_schedule_id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Email Report Schedule (Specific Email Report Schedule) </code> <small> </small><br>
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
    <span class="slctrl-attr">"user_id"</span> : <span class="slctrl-number">{user_id}</span>,
    <span class="slctrl-attr">"email_report_schedule_id"</span> : <span class="slctrl-number">{email_report_schedule_id}</span>,
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
                    <td>user_id<span class="badge-danger">required</span></td>
                    <td><i>Integer</i> The unique user_id for the user to be retrieved</td>
                </tr>
                <tr>
                    <td>email_report_schedule_id<span class="badge-danger">required</span></td>
                    <td><i>Integer</i> The unique email_report_schedule_id for the Email Report Schedule to be retrieved</td>
                </tr>
                </tbody>
            </table>
        </div>

<h4>Response

    <span
            data-toggle="collapse"
            data-target="#email-reporting-by-user-id-response"
            aria-expanded="false"
            aria-controls="email-reporting-by-user-id-response"
            style="cursor: pointer; top: 2px !important;"
            class="glyphicon glyphicon-eye-open">
            </span>

</h4>
<pre  style="font-weight:bold" class="collapse" id="email-reporting-by-user-id-response">
{
    <span class="slctrl-attr">"success"</span>: <span class="slctrl-literal">true</span>,
    <span class="slctrl-attr">"data"</span>: {
        <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"customer_admin_id"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"frequency"</span>: <span class="slctrl-string">"WEEKLYON"</span>,
        <span class="slctrl-attr">"day"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"time"</span>: <span class="slctrl-string">"08:00:00"</span>,
        <span class="slctrl-attr">"active"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2021-02-05T07:26:34.000000Z"</span>,
        <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2021-02-05T07:26:34.000000Z"</span>,
        <span class="slctrl-attr">"month_day"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"report_type"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"name"</span>: <span class="slctrl-string">"Roomwerx"</span>,
        <span class="slctrl-attr">"report_recipients"</span>: [
            <span class="slctrl-string">"johndoe@roomwerx.com"</span>
        ]
    },
    <span class="slctrl-attr">"message"</span>: <span class="slctrl-string">"Data retrieved successfully"</span>
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
                        <td>success</td>
                        <td><i>String</i> Default: <code>NULL</code>,</td>
                    </tr>
                    <tr>
                        <td
                                data-toggle="collapse"
                                data-target="#email-reporting-by-user-id-data"
                                aria-expanded="false"
                                aria-controls="email-reporting-by-user-id-data"
                                style="cursor: pointer;  "

                                class="glyphicon1 icn-email-reporting-by-user-id-data"
                                rel="icn-email-reporting-by-user-id"
                        >
                                <span class="icn-email-reporting-by-user-id-data">
                                   data <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                </span>
                        </td>
                        <td>object</td>
                    </tr>

                    <tr  class="collapse" id="email-reporting-by-user-id-data">
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
                                        <td>customer_admin_id</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>frequency</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>day</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>time</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>active</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>created_at</td>
                                        <td><i>Timestamp</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>updated_at</td>
                                        <td><i>Timestamp</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>month_day</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>report_type</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>name</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>report_recipients</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
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













    <div class="col-sm-12" id="get-all-email-reporting-default-values"><!-- Email Reporting Default Values -->
        <h3>{{ EndPointHelper::getAll('Email Reporting Default Values') }}</h3>
        <p>
            {{ EndPointHelper::getAllDescription('Email Reporting Default Values') }};
        </p>
        <br>
        <div class="" style="position:relative;padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b>
            <span class="glyphicon1" rel="i-all-email-reporting-default-values" aria-hidden="true">
                    <span
                            data-toggle="collapse"
                            data-target="#route-all-email-reporting-default-values"
                            aria-expanded="false"
                            aria-controls="route-all-email-reporting-default-values"
                            style="cursor: pointer"
                            class="i-all-email-reporting-default-values"
                    > <code>api/email-report-schedule-default-values</code>
                        <span class="glyphicon glyphicon-menu-right " aria-hidden="true">
                        </span>
                    </span>
                </span>
            <div class="collapse api-route" id="route-all-email-reporting-default-values">
                <div class="badge-success email-reporting-by-user-id hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="all-email-reporting-default-values">Copy</div>
                </div>

                <div class="api_ noselect" id="all-email-reporting-default-values">
                    {{ config('app.url')}}/api/email-report-schedule-default-values
                </div>
            </div>  <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>URL Form Data required: &nbsp;</b> <code>NONE</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Display Email Report Schedule Default Value</code> <small> </small><br>
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
    <span class="slctrl-attr">NONE</span>
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
                    <td>NONE</td>
                    <td>No attribute available</td>
                </tr>
                </tbody>
            </table>
        </div>

        <h4>Response
            <span
                data-toggle="collapse"
                data-target="#all-email-reporting-default-values-response"
                aria-expanded="false"
                aria-controls="all-email-reporting-default-values-response"
                style="cursor: pointer; top: 2px !important;"
                class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre  style="font-weight:bold" class="collapse" id="all-email-reporting-default-values-response">
{
    <span class="slctrl-attr">"data"</span>: {
        <span class="slctrl-attr">"frequency"</span>: {
            <span class="slctrl-attr">"DAILYAT"</span>: <span class="slctrl-string">"Daily"</span>,
            <span class="slctrl-attr">"WEEKLYON"</span>: <span class="slctrl-string">"Weekly"</span>,
            <span class="slctrl-attr">"MONTHLYON"</span>: <span class="slctrl-string">"Monthly"</span>,
            <span class="slctrl-attr">"YEARLYON"</span>: <span class="slctrl-string">"Yearly"</span>
        },
        <span class="slctrl-attr">"days_of_the_week"</span>: {
            <span class="slctrl-attr">"1"</span>: <span class="slctrl-string">"Monday"</span>,
            <span class="slctrl-attr">"2"</span>: <span class="slctrl-string">"Tuesday"</span>,
            <span class="slctrl-attr">"3"</span>: <span class="slctrl-string">"Wednesday"</span>,
            <span class="slctrl-attr">"4"</span>: <span class="slctrl-string">"Thursday"</span>,
            <span class="slctrl-attr">"5"</span>: <span class="slctrl-string">"Friday"</span>,
            <span class="slctrl-attr">"6"</span>: <span class="slctrl-string">"Saturday"</span>,
            <span class="slctrl-attr">"7"</span>: <span class="slctrl-string">"Sunday"</span>
        },
        <span class="slctrl-attr">"report_by_option"</span>: {
            <span class="slctrl-attr">"BYREFERENCE"</span>: <span class="slctrl-string">"By Reference"</span>,
            <span class="slctrl-attr">"BYCONTAINER"</span>: <span class="slctrl-string">"By Container"</span>
        },
        <span class="slctrl-attr">"days_in_month"</span>: {
            <span class="slctrl-attr">"1"</span>:  <span class="slctrl-string">"Day 1"</span>,
            <span class="slctrl-attr">"2"</span>:  <span class="slctrl-string">"Day 2"</span>,
            <span class="slctrl-attr">"3"</span>:  <span class="slctrl-string">"Day 3"</span>,
            <span class="slctrl-attr">"4"</span>:  <span class="slctrl-string">"Day 4"</span>,
            <span class="slctrl-attr">"5"</span>:  <span class="slctrl-string">"Day 5"</span>,
            <span class="slctrl-attr">"6"</span>:  <span class="slctrl-string">"Day 6"</span>,
            <span class="slctrl-attr">"7"</span>:  <span class="slctrl-string">"Day 7"</span>,
            <span class="slctrl-attr">"8"</span>:  <span class="slctrl-string">"Day 8"</span>,
            <span class="slctrl-attr">"9"</span>:  <span class="slctrl-string">"Day 9"</span>,
            <span class="slctrl-attr">"10"</span>: <span class="slctrl-string">"Day 10"</span>,
            <span class="slctrl-attr">"11"</span>: <span class="slctrl-string">"Day 11"</span>,
            <span class="slctrl-attr">"12"</span>: <span class="slctrl-string">"Day 12"</span>,
            <span class="slctrl-attr">"13"</span>: <span class="slctrl-string">"Day 13"</span>,
            <span class="slctrl-attr">"14"</span>: <span class="slctrl-string">"Day 14"</span>,
            <span class="slctrl-attr">"15"</span>: <span class="slctrl-string">"Day 15"</span>,
            <span class="slctrl-attr">"16"</span>: <span class="slctrl-string">"Day 16"</span>,
            <span class="slctrl-attr">"17"</span>: <span class="slctrl-string">"Day 17"</span>,
            <span class="slctrl-attr">"18"</span>: <span class="slctrl-string">"Day 18"</span>,
            <span class="slctrl-attr">"19"</span>: <span class="slctrl-string">"Day 19"</span>,
            <span class="slctrl-attr">"20"</span>: <span class="slctrl-string">"Day 20"</span>,
            <span class="slctrl-attr">"21"</span>: <span class="slctrl-string">"Day 21"</span>,
            <span class="slctrl-attr">"22"</span>: <span class="slctrl-string">"Day 22"</span>,
            <span class="slctrl-attr">"23"</span>: <span class="slctrl-string">"Day 23"</span>,
            <span class="slctrl-attr">"24"</span>: <span class="slctrl-string">"Day 24"</span>,
            <span class="slctrl-attr">"25"</span>: <span class="slctrl-string">"Day 25"</span>,
            <span class="slctrl-attr">"26"</span>: <span class="slctrl-string">"Day 26"</span>,
            <span class="slctrl-attr">"27"</span>: <span class="slctrl-string">"Day 27"</span>,
            <span class="slctrl-attr">"28"</span>: <span class="slctrl-string">"Day 28"</span>,
            <span class="slctrl-attr">"29"</span>: <span class="slctrl-string">"Day 29"</span>,
            <span class="slctrl-attr">"30"</span>: <span class="slctrl-string">"Day 30"</span>,
            <span class="slctrl-attr">"31"</span>: <span class="slctrl-string">"Day 31"</span>
        }
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
                                data-target="#all-email-reporting-default-values-data"
                                aria-expanded="false"
                                aria-controls="all-email-reporting-default-values-data"
                                style="cursor: pointer;  "

                                class="glyphicon1 icn-all-email-reporting-default-values-data"
                                rel="icn-all-email-reporting-default-values"
                        >
                                <span class="icn-all-email-reporting-default-values-data">
                                   data <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                </span>
                        </td>
                        <td>object</td>
                    </tr>

                    <tr  class="collapse" id="all-email-reporting-default-values-data">
                        <td colspan="2">
                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                <table class="table-bordered">
                                    <thead></thead>
                                    <tbody>
                                    <tr>
                                        <td
                                                data-toggle="collapse"
                                                data-target="#all-email-reporting-default-values-frequency"
                                                aria-expanded="false"
                                                aria-controls="all-email-reporting-default-values-frequency"
                                                style="cursor: pointer;  "

                                                class="glyphicon1 icn-all-email-reporting-default-values-frequency"
                                                rel="icn-all-email-reporting-default-values-frequency"
                                        >
                                <span class="icn-all-email-reporting-default-values-frequency">
                                   frequency <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                </span>
                                            </td>
                                        <td><i>Object</i> Default: <code>NULL</code>,</td>
                                    </tr>


                                    <tr  class="collapse" id="all-email-reporting-default-values-frequency">
                                        <td colspan="2">
                                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                <table class="table-bordered">
                                                    <thead></thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>DAILYAT</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>

                                                    <tr>
                                                        <td>WEEKLYON</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>

                                                    <tr>
                                                        <td>MONTHLYON</td>
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
                                                data-target="#all-email-reporting-default-values-days_of_the_week"
                                                aria-expanded="false"
                                                aria-controls="all-email-reporting-default-values-days_of_the_week"
                                                style="cursor: pointer;  "

                                                class="glyphicon1 icn-all-email-reporting-default-values-days_of_the_week"
                                                rel="icn-all-email-reporting-default-values-days_of_the_week"
                                        >
                                <span class="icn-all-email-reporting-default-values-days_of_the_week">
                                   days_of_the_week <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                </span>
                                        </td>
                                        <td><i>Object</i> Default: <code>NULL</code>,</td>
                                    </tr>


                                    <tr  class="collapse" id="all-email-reporting-default-values-days_of_the_week">
                                        <td colspan="2">
                                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                <table class="table-bordered">
                                                    <thead></thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>Monday</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>

                                                    <tr>
                                                        <td>Tuesday</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>

                                                    <tr>
                                                        <td>Wednesday</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Thursday</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Friday</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Saturday</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Sunday</td>
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
                                            data-target="#all-email-reporting-default-values-report_by_option"
                                            aria-expanded="false"
                                            aria-controls="all-email-reporting-default-values-report_by_option"
                                            style="cursor: pointer;  "

                                            class="glyphicon1 icn-all-email-reporting-default-values-report_by_option"
                                            rel="icn-all-email-reporting-default-values-report_by_option"
                                        >
                                            <span class="icn-all-email-reporting-default-values-report_by_option">
                                               report_by_option <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                            </span>
                                        </td>
                                        <td><i>Object</i> Default: <code>NULL</code>,</td>
                                    </tr>

                                    <tr  class="collapse" id="all-email-reporting-default-values-report_by_option">
                                        <td colspan="2">
                                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                <table class="table-bordered">
                                                    <thead></thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>BYREFERENCE</td>
                                                            <td><i>String</i> Default: <code>NULL</code>,</td>
                                                        </tr>
                                                        <tr>
                                                            <td>BYCONTAINER</td>
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
                                                data-target="#all-email-reporting-default-values-days_in_month"
                                                aria-expanded="false"
                                                aria-controls="all-email-reporting-default-values-days_in_month"
                                                style="cursor: pointer;  "

                                                class="glyphicon1 icn-all-email-reporting-default-values-days_in_month"
                                                rel="icn-all-email-reporting-default-values-days_in_month"
                                        >
                                            <span class="icn-all-email-reporting-default-values-days_in_month">
                                               days_in_month <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                            </span>
                                        </td>
                                        <td><i>Object</i> Default: <code>NULL</code>,</td>
                                    </tr>

                                    <tr  class="collapse" id="all-email-reporting-default-values-days_in_month">
                                        <td colspan="2">
                                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                                <table class="table-bordered">
                                                    <thead></thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>Day 1</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Day 2</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>

                                                    <tr>
                                                        <td>Day 3</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>

                                                    <tr>
                                                        <td>Day 4</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>

                                                    <tr>
                                                        <td>Day 5</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>

                                                    <tr>
                                                        <td>Day 6</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>

                                                    <tr>
                                                        <td>Day 7</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>

                                                    <tr>
                                                        <td>Day 8</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>

                                                    <tr>
                                                        <td>Day 9</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>

                                                    <tr>
                                                        <td>Day 10</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>

                                                    <tr>
                                                        <td>Day 11</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Day 12</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Day 13</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Day 14</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Day 15</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Day 16</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Day 17</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Day 18</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Day 19</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Day 20</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Day 21</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Day 22</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Day 23</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Day 24</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Day 25</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Day 26</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Day 27</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Day 28</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Day 29</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Day 30</td>
                                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Day 31</td>
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
        </div>
        <p>
            {{ EndPointHelper::userGuide() }}
        </p>
    </div><!-- End Email Reporting Default Values -->








    <div class="col-sm-12" id="create-email-reporting-new"><!-- Email Report Schedule New -->
        <h3>{{ EndPointHelper::create('Email Reporting') }}</h3>
        <p>
            {{ EndPointHelper::createDescription('Email Reporting', array(
            'customer_admin_id',
            'active',
            'frequency',
            'day',
            'time',
            'month_day',
            'report_type'
            )) }};

        </p>
        <br>
        <div class="" style="position:relative;padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b>
            <span class="glyphicon1" rel="i-email-reporting-new" aria-hidden="true">
                    <span
                            data-toggle="collapse"
                            data-target="#route-email-reporting-new"
                            aria-expanded="false"
                            aria-controls="route-email-reporting-new"
                            style="cursor: pointer"
                            class="i-email-reporting-new"
                    > <code>api/email-report-schedule/new</code>
                        <span class="glyphicon glyphicon-menu-right " aria-hidden="true">
                        </span>
                    </span>
                </span>
            <div class="collapse api-route" id="route-email-reporting-new">
                <div class="badge-success email-reporting-new hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="email-reporting-new">Copy</div>
                </div>

                <div class="api_ noselect" id="email-reporting-new">
                    {{ config('app.url')}}/api/email-report-schedule/new
                </div>
            </div>  <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>customer_admin_id</code>,
            <code>active</code>,
            <code>frequency</code>,
            <code>day</code>,
            <code>time</code>,
            <code>month_day</code>,
            <code>report_type</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Create New Email Report Schedule </code> <small> </small><br>
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
    <span class="slctrl-attr">"customer_admin_id"</span> : <span class="slctrl-number">{customer_admin_id}</span>,
    <span class="slctrl-attr">"active"</span> : <span class="slctrl-string">"{active}"</span>,
    <span class="slctrl-attr">"frequency"</span> : <span class="slctrl-string">"{frequency}"</span>,
    <span class="slctrl-attr">"day"</span> : <span class="slctrl-string">"{day}"</span>,
    <span class="slctrl-attr">"time"</span> : <span class="slctrl-string">"{time}"</span>,
    <span class="slctrl-attr">"month_day"</span> : <span class="slctrl-string">"{month_day}"</span>,
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
                    <td>customer_admin_id<span class="badge-danger">required</span></td>
                    <td><i>Integer</i> Customer Admin ID</td>
                </tr>
                <tr>
                    <td>active<span class="badge-danger">required</span></td>
                    <td><i>Boolean</i> 0 or 1</td>
                </tr>
                <tr>
                    <td>frequency<span class="badge-danger">required</span></td>
                    <td><i>String</i> </td>
                </tr>
                <tr>
                    <td>day<span class="badge-danger">required</span></td>
                    <td><i>Integer</i>Day 1 to day 31</td>
                </tr>
                <tr>
                    <td>time<span class="badge-danger">required</span></td>
                    <td><i>Time</i> </td>
                </tr>
                <tr>
                    <td>month_day<span class="badge-danger">required</span></td>
                    <td><i>String</i> </td>
                </tr>
                </tbody>
            </table>
        </div>

        <h4>Response

            <span
                    data-toggle="collapse"
                    data-target="#email-reporting-new-response"
                    aria-expanded="false"
                    aria-controls="email-reporting-new-response"
                    style="cursor: pointer; top: 2px !important;"
                    class="glyphicon glyphicon-eye-open">
            </span>

        </h4>
        <pre  style="font-weight:bold" class="collapse" id="email-reporting-new-response">
{
    <span class="slctrl-attr">"success"</span>: <span class="slctrl-literal">true</span>,
    <span class="slctrl-attr">"data"</span>: {
        <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">137</span>,
            <span class="slctrl-attr">"customer_admin_id"</span>: <span class="slctrl-number">44</span>,
            <span class="slctrl-attr">"frequency"</span>: <span class="slctrl-string">"WEEKLYON"</span>,
            <span class="slctrl-attr">"day"</span>: <span class="slctrl-number">1</span>,
            <span class="slctrl-attr">"time"</span>: <span class="slctrl-string">"08:00:00"</span>,
            <span class="slctrl-attr">"active"</span>: <span class="slctrl-number">1</span>,
            <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2021-02-05T07:26:34.000000Z"</span>,
            <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2021-02-05T07:26:34.000000Z"</span>,
            <span class="slctrl-attr">"month_day"</span>: <span class="slctrl-string">"November 30"</span>,
            <span class="slctrl-attr">"report_type"</span>: <span class="slctrl-string">"quis"</span>,
    },
    <span class="slctrl-attr">"message"</span>: <span class="slctrl-string">"Successfully created email report"</span>
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
                        <td>success</td>
                        <td><i>String</i> Default: <code>NULL</code>,</td>
                    </tr>
                    <tr>
                        <td
                                data-toggle="collapse"
                                data-target="#email-reporting-new-data"
                                aria-expanded="false"
                                aria-controls="email-reporting-new-data"
                                style="cursor: pointer;  "

                                class="glyphicon1 icn-email-reporting-new-data"
                                rel="icn-email-reporting-new"
                        >
                                <span class="icn-email-reporting-new-data">
                                   data <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                </span>
                        </td>
                        <td>object</td>
                    </tr>

                    <tr  class="collapse" id="email-reporting-new-data">
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
                                        <td>customer_admin_id</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>frequency</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>day</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>time</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>active</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>created_at</td>
                                        <td><i>Timestamp</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>updated_at</td>
                                        <td><i>Timestamp</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>month_day</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>report_type</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
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
    </div><!-- End  Email Report Schedule New -->

















    <div class="col-sm-12" id="u-email-reporting"><!--Update Email Report Schedule -->
        <h3>{{ EndPointHelper::update('Email Reporting') }}</h3>
        <p>
            {{ EndPointHelper::updateDescription('Email Reporting', array(
            'id',
            'customer_admin_id',
            'active',
            'frequency',
            'day',
            'time',
            'month_day',
            'report_type'
            )) }};

        </p>
        <br>
        <div class="" style="position:relative;padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b>
            <span class="glyphicon1" rel="i-update-email-reporting" aria-hidden="true">
                    <span
                            data-toggle="collapse"
                            data-target="#route-update-email-reporting"
                            aria-expanded="false"
                            aria-controls="route-update-email-reporting"
                            style="cursor: pointer"
                            class="i-update-email-reporting"
                    > <code>api/email-report-schedule/update</code>
                        <span class="glyphicon glyphicon-menu-right " aria-hidden="true">
                        </span>
                    </span>
                </span>
            <div class="collapse api-route" id="route-update-email-reporting">
                <div class="badge-success update-email-reporting hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="update-email-reporting">Copy</div>
                </div>

                <div class="api_ noselect" id="update-email-reporting">
                    {{ config('app.url')}}/api/email-report-schedule/update
                </div>
            </div>  <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>id</code>, <code>customer_admin_id</code>,
            <code>active</code>,
            <code>frequency</code>,
            <code>day</code>,
            <code>time</code>,
            <code>month_day</code>,
            <code>report_type</code><br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Update Email Report Schedule </code> <small> </small><br>
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
    <span class="slctrl-attr">"id"</span> : <span class="slctrl-number">{id}</span>,
    <span class="slctrl-attr">"customer_admin_id"</span> : <span class="slctrl-number">{customer_admin_id}</span>,
    <span class="slctrl-attr">"active"</span> : <span class="slctrl-string">"{active}"</span>,
    <span class="slctrl-attr">"frequency"</span> : <span class="slctrl-string">"{frequency}"</span>,
    <span class="slctrl-attr">"day"</span> : <span class="slctrl-string">"{day}"</span>,
    <span class="slctrl-attr">"time"</span> : <span class="slctrl-string">"{time}"</span>,
    <span class="slctrl-attr">"month_day"</span> : <span class="slctrl-string">"{month_day}"</span>,
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
                    <td><i>Integer</i> The unique id to update the data</td>
                </tr>
                <tr>
                    <td>customer_admin_id<span class="badge-danger">required</span></td>
                    <td><i>Integer</i> Customer Admin ID</td>
                </tr>
                <tr>
                    <td>active<span class="badge-danger">required</span></td>
                    <td><i>Boolean</i> 0 or 1</td>
                </tr>
                <tr>
                    <td>frequency<span class="badge-danger">required</span></td>
                    <td><i>String</i> </td>
                </tr>
                <tr>
                    <td>day<span class="badge-danger">required</span></td>
                    <td><i>Integer</i>Day 1 to day 31</td>
                </tr>
                <tr>
                    <td>time<span class="badge-danger">required</span></td>
                    <td><i>Time</i> </td>
                </tr>
                <tr>
                    <td>month_day<span class="badge-danger">required</span></td>
                    <td><i>String</i> </td>
                </tr>
                </tbody>
            </table>
        </div>

        <h4>Response

            <span
                    data-toggle="collapse"
                    data-target="#update-email-reporting-response"
                    aria-expanded="false"
                    aria-controls="update-email-reporting-response"
                    style="cursor: pointer; top: 2px !important;"
                    class="glyphicon glyphicon-eye-open">
            </span>

        </h4>
        <pre  style="font-weight:bold" class="collapse" id="update-email-reporting-response">
{
    <span class="slctrl-attr">"success"</span>: <span class="slctrl-literal">true</span>,
    <span class="slctrl-attr">"data"</span>: {
        <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">137</span>,
        <span class="slctrl-attr">"customer_admin_id"</span>: <span class="slctrl-number">44</span>,
        <span class="slctrl-attr">"frequency"</span>: <span class="slctrl-string">"WEEKLYON"</span>,
        <span class="slctrl-attr">"day"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"time"</span>: <span class="slctrl-string">"08:00:00"</span>,
        <span class="slctrl-attr">"active"</span>: <span class="slctrl-number">1</span>,
        <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2021-02-05T07:26:34.000000Z"</span>,
        <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2021-02-05T07:26:34.000000Z"</span>,
        <span class="slctrl-attr">"month_day"</span>: <span class="slctrl-string">"November 30"</span>,
        <span class="slctrl-attr">"report_type"</span>: <span class="slctrl-string">"quis"</span>,
    },
    <span class="slctrl-attr">"message"</span>: <span class="slctrl-string">"Successfully updated email report"</span>
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
                        <td>success</td>
                        <td><i>String</i> Default: <code>NULL</code>,</td>
                    </tr>
                    <tr>
                        <td
                                data-toggle="collapse"
                                data-target="#update-email-reporting-data"
                                aria-expanded="false"
                                aria-controls="update-email-reporting-data"
                                style="cursor: pointer;  "

                                class="glyphicon1 icn-update-email-reporting-data"
                                rel="icn-update-email-reporting"
                        >
                                <span class="icn-update-email-reporting-data">
                                   data <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                                </span>
                        </td>
                        <td>object</td>
                    </tr>

                    <tr  class="collapse" id="update-email-reporting-data">
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
                                        <td>customer_admin_id</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>frequency</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>day</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>time</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>active</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>created_at</td>
                                        <td><i>Timestamp</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>updated_at</td>
                                        <td><i>Timestamp</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>month_day</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>report_type</td>
                                        <td><i>String</i> Default: <code>NULL</code>,</td>
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
    </div><!-- End Update Email Report Schedule -->












    <div class="col-sm-12" id="d-email-reporting-by-id"><!-- Delete Email Report Schedule -->
        <h3>{{ EndPointHelper::delete('Email Reporting') }}</h3>
        <p>
            {{ EndPointHelper::deleteDescription('Email Reporting', array(
                'id',
                'email_report_schedule_id'
            )) }};
        </p>
        <br>
        <div class="" style="position:relative;padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b>
            <span class="glyphicon1" rel="i-delete-email-reporting-by-id" aria-hidden="true">
                    <span
                            data-toggle="collapse"
                            data-target="#route-delete-email-reporting-by-id"
                            aria-expanded="false"
                            aria-controls="route-delete-email-reporting-by-id"
                            style="cursor: pointer"
                            class="i-delete-email-reporting-by-id"
                    > <code>api/email-report-schedule/delete/{email_report_schedule_id}</code>
                        <span class="glyphicon glyphicon-menu-right " aria-hidden="true">
                        </span>
                    </span>
                </span>
            <div class="collapse api-route" id="route-delete-email-reporting-by-id">
                <div class="badge-success delete-email-reporting-by-id hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="delete-email-reporting-by-id">Copy</div>
                </div>

                <div class="api_ noselect" id="delete-email-reporting-by-id">
                    {{ config('app.url')}}/api/email-report-schedule/delete/{email_report_schedule_id}
                </div>
            </div>  <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>id</code>, <code>email_report_schedule_id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Delete Email Report Schedule </code> <small> </small><br>
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
    <span class="slctrl-attr">"id"</span> : <span class="slctrl-number">{id}</span>,
    <span class="slctrl-attr">"email_report_schedule_id"</span> : <span class="slctrl-number">{email_report_schedule_id}</span>,
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
                    <td><i>Integer</i> The unique id to delete the data</td>
                </tr>
                <tr>
                    <td>email_report_schedule_id<span class="badge-danger">required</span></td>
                    <td><i>Integer</i> Email Report Schedule ID</td>
                </tr>
                </tbody>
            </table>
        </div>

        <h4>Response

            <span
                    data-toggle="collapse"
                    data-target="#delete-email-reporting-by-id-response"
                    aria-expanded="false"
                    aria-controls="delete-email-reporting-by-id-response"
                    style="cursor: pointer; top: 2px !important;"
                    class="glyphicon glyphicon-eye-open">
            </span>

        </h4>
        <pre  style="font-weight:bold" class="collapse" id="delete-email-reporting-by-id-response">
{
    <span class="slctrl-attr">"status"</span>: <span class="slctrl-string">"ok"</span>,
    <span class="slctrl-attr">"message"</span>: <span class="slctrl-string">"Email Report Schedule Deleted Successfully"</span>
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
                        <td>status</td>
                        <td><i>String</i> Default: <code>NULL</code>,</td>
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
    </div><!-- End Delete Email Report Schedule -->






</div> <!-- start Email Report Schedule -->

