<div class="col-sm-12" id="incoterm"> <!-- start incoterm -->
    <h3 class="page-header">{{ EndPointHelper::endPoint('Incoterm') }}</h3>
    <!-- Container Size list resource -->
    <div class="col-sm-12" id="incoterm-list">
        <h3>{{ EndPointHelper::getAll('Incoterm') }} </h3>
        <p>
            {{ EndPointHelper::getAllDescription("Incoterm")}}
        </p>
        <br>
        <div class="" style="position:relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-incoterms" aria-hidden="true">
                <span
                    data-toggle="collapse"
                    data-target="#route-incoterms"
                    aria-expanded="false"
                    aria-controls="route-incoterms"
                    style="cursor: pointer"
                    class="i-incoterms"
                > <code>api/incoterms</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                </span>
            </span>
            <div class="collapse api-route" id="route-incoterms">
                <div class="badge-success incoterms hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="incoterms">Copy</div>
                </div>
                <div class="api_ noselect" id="incoterms">
                    {{ config('app.url')}}/api/incoterms
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>NONE</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code>List of Incoterm</code> <small> </small><br>
        </div>
        <h4>Header</h4>
        <pre style="font-weight:bold">
{
    <span class="slctrl-attr">"Authorization"</span>: <span class="slctrl-string">"Bearer {access_token}"</span>
    <span class="slctrl-attr">"Content-Type"</span>: <span class="slctrl-string"> "application/json" </span>
    <span class="slctrl-attr">"Accept"</span>: <span class="slctrl-string"> "application/json" </span>
} </pre>
        <h4> Form Data </h4>
        <pre><span class="slctrl-attr">NONE</span></pre>
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
                data-target="#incoterms-response"
                aria-expanded="false"
                aria-controls="incoterms-response"
                style="cursor: pointer; top: 2px !important;"
                class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre class="collapse" id="incoterms-response">
{
     <span class="slctrl-attr">"data"</span>: [
        {
             <span class="slctrl-attr">"id"</span>: <span class="slctrl-string">1</span>,
             <span class="slctrl-attr">"name"</span>: <span class="slctrl-string">"John Doe"</span>,
             <span class="slctrl-attr">"description"</span>: <span class="slctrl-literal">null</span>,
             <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2020-02-28T07:03:39.000000Z"</span>,
             <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2020-02-28T07:03:39.000000Z"</span>
        },
        {...}, ...
    ],
     <span class="slctrl-attr">"links"</span>: {
         <span class="slctrl-attr">"first"</span>: <span class="slctrl-string">"/api/incoterms?page=1"</span>,
         <span class="slctrl-attr">"last"</span>: <span class="slctrl-string">"/api/incoterms?page=1"</span>,
         <span class="slctrl-attr">"prev"</span>: <span class="slctrl-literal">null</span>,
         <span class="slctrl-attr">"next"</span>: <span class="slctrl-literal">null</span>
    },
     <span class="slctrl-attr">"meta"</span>: {
         <span class="slctrl-attr">"current_page"</span>: <span class="slctrl-number">1</span>,
         <span class="slctrl-attr">"from"</span>: <span class="slctrl-number">1</span>,
         <span class="slctrl-attr">"last_page"</span>: <span class="slctrl-number">1</span>,
         <span class="slctrl-attr">"path"</span>: <span class="slctrl-string">"/api/incoterms"</span>,
         <span class="slctrl-attr">"per_page"</span>: <span class="slctrl-number">5</span>,
         <span class="slctrl-attr">"to"</span>: <span class="slctrl-number">4</span>,
         <span class="slctrl-attr">"total"</span>: <span class="slctrl-number">4</span>
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
                            data-target="#get-all-ictrm-data"
                            aria-expanded="false"
                            aria-controls="get-all-ictrm-data"
                            style="cursor: pointer;  "
                            class="glyphicon1 all-ictrm-data"
                            rel="all-ictrm-data"
                        >
                            <span class="all-ictrm-data">
                               data <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                            </span>
                        </td>
                        <td>object</td>
                    </tr>
                    <tr  class="collapse" id="get-all-ictrm-data">
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
                                        <td>name</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>, </td>
                                    </tr>
                                    <tr>
                                        <td>description</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>created_at</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                    </tr>
                                    <tr>
                                        <td>updated_at</td>
                                        <td><i>Integer</i> Default: <code>NULL</code>, </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td
                            data-toggle="collapse"
                            data-target="#get-all-ictrm-links"
                            aria-expanded="false"
                            aria-controls="get-all-ictrm-links"
                            style="cursor: pointer;  "

                            class="glyphicon1 all-ictrm-links"
                            rel="all-ictrm-links"
                        >
                            <span class="all-ictrm-links">
                               links <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                            </span>
                        </td>
                        <td>object</td>
                    </tr>
                    <tr  class="collapse" id="get-all-ictrm-links">
                        <td colspan="2">
                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                <table class="table-bordered">
                                    <thead></thead>
                                    <tbody>
                                    <tr>
                                        <td>first</td>
                                        <td><i>String</i> Default: <code>NULL</code>, link to the first page</td>
                                    </tr>
                                    <tr>
                                        <td>last</td>
                                        <td><i>String</i> Default: <code>NULL</code>, link to the last page</td>
                                    </tr>
                                    <tr>
                                        <td>prev</td>
                                        <td><i>String</i> Default: <code>NULL</code>, link to the previous page</td>
                                    </tr>
                                    <tr>
                                        <td>next</td>
                                        <td><i>String</i> Default: <code>NULL</code>, link to the next page</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td
                            data-toggle="collapse"
                            data-target="#get-all-ictrm-meta"
                            aria-expanded="false"
                            aria-controls="get-all-ictrm-meta"
                            style="cursor: pointer;  "

                            class="glyphicon1 all-ictrm-meta"
                            rel="all-ictrm-meta"
                        >
                            <span class="all-ictrm-meta">
                               meta <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                            </span>
                        </td>
                        <td>object</td>
                    </tr>
                    <tr  class="collapse" id="get-all-ictrm-meta">
                        <td colspan="2">
                            <div style="margin-left: 1em;margin-bottom: 1em;background: rgb(245, 247, 250);border-radius: 8px;">
                                <table class="table-bordered">
                                    <thead></thead>
                                    <tbody>
                                    <tr>
                                        <td>current_page</td>
                                        <td><i>String</i> Default: <code>NULL</code></td>
                                    </tr>
                                    <tr>
                                        <td>from</td>
                                        <td><i>String</i> Default: <code>NULL</code></td>
                                    </tr>
                                    <tr>
                                        <td>last_page</td>
                                        <td><i>String</i> Default: <code>NULL</code></td>
                                    </tr>
                                    <tr>
                                        <td>path</td>
                                        <td><i>String</i> Default: <code>NULL</code></td>
                                    </tr>
                                    <tr>
                                        <td>per_page</td>
                                        <td><i>String</i> Default: <code>NULL</code></td>
                                    </tr>
                                    <tr>
                                        <td>to</td>
                                        <td><i>String</i> Default: <code>NULL</code></td>
                                    </tr>
                                    <tr>
                                        <td>total</td>
                                        <td><i>String</i> Default: <code>NULL</code></td>
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
    </div><!-- End Incoterm list resource -->

    <div class="col-sm-12" id="incoterm-specified"><!-- Incoterm specified resource -->
        <h3>{{ EndPointHelper::get('Incoterm') }}</h3>
        <p>
            {{ EndPointHelper::getDescription('Incoterm', 'id') }}
        </p>
        <br>
        <div class="" style="position:relative; padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">
            <b>Route: </b> &nbsp;
            <span class="glyphicon1" rel="i-incoterm-id" aria-hidden="true">
                <span
                    data-toggle="collapse"
                    data-target="#route-incoterm-id"
                    aria-expanded="false"
                    aria-controls="route-incoterm-id"
                    style="cursor: pointer"
                    class="i-incoterm-id"
                > <code>api/incoterm/{id}</code>
                    <span class="glyphicon glyphicon-menu-right" aria-hidden="true">
                    </span>
                </span>
            </span>
            <div class="collapse api-route" id="route-incoterm-id">
                <div class="badge-success incoterm-id hidden" style="border-radius: 6px !important; ">
                    <span >Copied</span>
                </div>
                <div class="flex-port">
                    <p>SHIFL CENTRAL API</p>
                    <div  class="copy-api" rel="incoterm-id">Copy</div>
                </div>

                <div class="api_ noselect" id="incoterm-id">
                    {{ config('app.url')}}/api/incoterm/{id}
                </div>
            </div>
            <br>
            <b>Request Type: &nbsp;</b> <code>GET</code> <br>
            <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
            <b>Body Form Data required: &nbsp;</b> <code>id</code> <br>
            <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
            <b>Will Return: &nbsp;</b> <code> Incoterm (Specific Incoterm) </code> <small> </small><br>
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
    <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">{id}</span>,
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
                    <td><i>Integer</i> The unique id for the Incoterm to be retrieved</td>
                </tr>
                </tbody>
            </table>
        </div>
        <h4>Response
            <span
                data-toggle="collapse"
                data-target="#incoterm-id-response"
                aria-expanded="false"
                aria-controls="incoterm-id-response"
                style="cursor: pointer; top: 2px !important;"
                class="glyphicon glyphicon-eye-open">
            </span>
        </h4>
        <pre class="collapse" id="incoterm-id-response">
{
    <span class="slctrl-attr">"data"</span>: {
        <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">2</span>,
        <span class="slctrl-attr">"name"</span>: <span class="slctrl-string">"John Doe"</span>,
        <span class="slctrl-attr">"description"</span>: <span class="slctrl-literal">null</span>,
        <span class="slctrl-attr">"created_at"</span>: <span class="slctrl-string">"2020-02-28T07:03:43.000000Z"</span>,
        <span class="slctrl-attr">"updated_at"</span>: <span class="slctrl-string">"2020-02-28T07:03:43.000000Z"</span>
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
                            data-target="#get-ictrm-data"
                            aria-expanded="false"
                            aria-controls="get-ictrm-data"
                            style="cursor: pointer;  "

                            class="glyphicon1 ictrm-data"
                            rel="ictrm-data"
                        >
                            <span class="ictrm-data">
                               data <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                            </span>
                        </td>
                        <td>object</td>
                    </tr>
                    <tr  class="collapse" id="get-ictrm-data">
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
                                            <td>name</td>
                                            <td><i>Integer</i> Default: <code>NULL</code>, </td>
                                        </tr>
                                        <tr>
                                            <td>description</td>
                                            <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                        </tr>
                                        <tr>
                                            <td>created_at</td>
                                            <td><i>Integer</i> Default: <code>NULL</code>,</td>
                                        </tr>
                                        <tr>
                                            <td>updated_at</td>
                                            <td><i>Integer</i> Default: <code>NULL</code>, </td>
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
    </div><!-- End Incoterm specified resource -->
</div> <!-- end of Incoterm -->
