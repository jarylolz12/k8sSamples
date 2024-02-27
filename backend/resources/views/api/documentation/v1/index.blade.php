<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>Shifl - Client API Documentation</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta content="A fully featured Client api documentation for Shifl Customers" name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- Google-Fonts -->
        <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:100,300,400,600,700,900,400italic' rel='stylesheet'>

        <!-- Bootstrap Core CSS -->
        <link href="{!! asset('api/documentation') !!}/css/bootstrap.min.css" rel="stylesheet">

        <link rel="stylesheet" type="text/css" href="{!! asset('api/documentation') !!}/css/prettify.css" />

        <!-- Custom CSS -->
        <link href="{!! asset('api/documentation') !!}/css/styles.css" rel="stylesheet">

        <link rel="shortcut icon" type="image/x-icon" href="http://www.shifl.com/images/shifl_Fav-01.png">

        <style media="screen">
            h3 {
                font-size: 22.5px;
                margin-bottom: 20px;
            }

            a {
                font-weight: bold;
            }
        </style>

    </head>

    <body>

        <nav class="navbar navbar-default navbar-fixed-top" style="background:#fff">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header ">
                    <a class="navbar-brand " href="#" style="color:#333 !important"> Shifl Client API Documentation</a>
                </div>

                <!--div><a href="#menu-toggle" class="btn btn-default" id="menu-toggle">Toggle Menu</a></div-->
            </div><!-- /.container-fluid -->
        </nav>

        <div id="wrapper">

            <!-- Sidebar -->
            <div id="sidebar-wrapper">
                <ul class="sidebar-nav">
                    <li>
                        <a href="#intro">Introduction</a>
                    </li>
                    <li>
                        <a href="#structure">Structure</a>
                    </li>
                    <li>
                        <a href="#endpoints_and_request">Enpoints And Requests</a>
                    </li>

                    <li>
                        <a href="#how_to_create_client_credentials">How to create Client Credentials?</a>
                    </li>

                    <li>
                        <a href="#support">Support</a>
                    </li>
                    <li>
                      <a href="{{ route('api.services.dashboard') }}">{{ __('Dahsboard') }}</a>
                    </li>
                    <li>
                        <a href="?version=v1" style="font-weight: bolder;;color:{{ $version == 'v1' ? 'blue' : '' }};">v1</a>
                    </li>

                    <!-- <li>
                        <a href="#plugins">Plugin's Usage</a>
                    </li>
                    <li>
                        <a href="#credits">Credits</a>
                    </li>
                    <li>
                        <a href="#support">Support</a>
                    </li> -->
                    <!-- <li><a href="#changelog">Change Log</a></li> -->
                </ul>
            </div>
            <!-- /#sidebar-wrapper -->

            <!-- Page Content -->
            <div id="page-content-wrapper">
                <div class="container-fluid" id="intro">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="right-bar">

                                <div class="com-container">


                                    <h3 class="page-header">Shifl</h3>

                                    <p>
                                        Propels global shipping into the cloud age, disrupting the status quo that kept shippers in the dark, and the industry in the dark ages. Shipping made the world smaller. shifl makes it the size of your screen.
                                    </p>
                                    <p>
                                        We really care for our Clients and so in case if you have any question or feedback, please feel free to get back to us. <a href="http://shifl.com" style="color:blue !important"><u>shifl</u> </a>
                                    </p>

                                    <h3 class="page-header">Introduction (API)</h3>

                                    <p>
                                        The Shifl API is organized around REST. Our API has predictable resource-oriented URLs, accepts form-encoded request bodies, returns JSON-encoded responses, and uses standard HTTP response codes,
                                        authentication, and verbs.
                                    </p>




                                    <div id="structure" class="com-container">
                                        <h3 class="page-header">Api Structure</h3>
                                        <p>
                                            The Shifl Api Structure Based on RESTful software architecture. </p>
                                        <h3 class="page-header">Authentication Structure</h3>
                                        <p>
                                            The Shifl Api's Authentication Based on Token. To access the routes of api's client have to be validated.Without a valid token every request will be considered as an unauthorized Request.
                                        </p>

                                        <div class="row p-t-10">
                                            <div class="col-sm-12" id="endpoints_and_request">
                                                <h3 class="page-header">Endpoints &amp; Requests </h3>

                                                <div class="col-sm-12">
                                                    <h4>Available Api Endpoints </h4>

                                                    <ul>

                                                        <a href="#get_access_token">
                                                            <li>Get Access Token</li>
                                                        </a>
                                                        <a href="#get_shipment_by_po">
                                                            <li>Get Shipments by PO</li>
                                                        </a>
                                                        <a href="#get_shipment_status">
                                                            <li>Get Shipment Status</li>
                                                        </a>
                                                        <a href="#download-shipment-documents">
                                                            <li>Download Shipment Documents</li>
                                                        </a>
                                                        <ul>
                                                            <a href="#hbl-copy">
                                                                <li>HBL Copy</li>
                                                            </a>
                                                            <a href="#packing-list">
                                                                <li>Packing List</li>
                                                            </a>
                                                            <a href="#commercial-documents">
                                                                <li>Commercial Documents</li>
                                                            </a>
                                                            <a href="#commercial-invoice">
                                                                <li>Commercial Invoice</li>
                                                            </a>
                                                            <a href="#customs-documents">
                                                                <li>Customs Documents</li>
                                                            </a>
                                                        </ul>
                                                        <!-- <a href="#">
                                                            <li>Shipment List</li>
                                                        </a>
                                                        <a href="#">
                                                            <li>Single Shipment Detail</li>
                                                        </a>
                                                        <a href="#">
                                                            <li>Customer List</li>
                                                        </a>
                                                        <a href="#">
                                                            <li>Single Shipment Detail</li>
                                                        </a> -->

                                                    </ul>
                                                    <small>
                                                        More will be added Soon ....
                                                    </small>

                                                </div>


                                            </div>
                                            <div class="col-sm-12 p-t-30 " id="get_access_token">

                                                <h3>Get Access Token</h3>
                                                <p>
                                                    As mentioned above, Shifl Client Api is a token based api. For Every api request client has to provide validate Access token. Client will get the token by oauth token endpoint.
                                                </p>
                                                <br>

                                                <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

                                                    <b>Route: </b> &nbsp;<code>https://beta.shifl.com/oauth/token</code> <br>
                                                    <b>Request Type: &nbsp;</b> <code>POST</code> <br>
                                                    <b>Header Required: &nbsp;</b> <code>NONE</code> <br>
                                                    <b>Body Form Data required: &nbsp;</b> <code>grant_type</code> , <code>client_id</code> , <code>client_secret</code> ,<code>scope</code> <br>
                                                    <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
                                                    <b>Will Return: &nbsp;</b> <code>Authentication Token </code> <small> (Bearer) </small><br>

                                                </div>
                                                <h4>Header</h4> <br>


                                                <pre style="font-weight:bold">{
    "Accept" : "application/json"
}</pre>
                                                <h4 style="margin-top: 25px">Form Data </h4> <br>


                                                <pre style="font-weight:bold">
{
    "grant_type" : "client_credentials",
    "client_id" : "{client-id}",
    "client_secret" : "{client-secret}",
    "scope" : "{scope}",
}</pre>
<small> Here scope is by default "*" </small>

                                                <h4 style="margin-top: 25px">Response</h4> <br>


                                                <pre style="font-weight:bold">
{
    "token_type": "Bearer",
    "expires_in": 300,
    "access_token": "{access_token}"
}</pre>
                                                <p class="m-t-20">
                                                    Every time users want to access other api endpoints they have to provide validate token on header with every request. Otherwise The request will be considered as unauthorized attempt.
                                                </p>

                                            </div>




                                            <div class="col-sm-12 p-t-30 " id="get_shipment_by_po">

                                                <h3>Get Shipments By PO</h3>

                                                <p>
                                                    By this endpoint client can access their shipment's details. To access their shipment's details , they have to provide po. And the endpoint will return all their shipments regarding the given po.
                                                </p>
                                                <br>

                                                <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

                                                    <b>Route: </b> &nbsp;<code>https://beta.shifl.com/api/client/v1/shipment-by-po/{po_num}</code> <br>
                                                    <b>Request Type: &nbsp;</b> <code>GET</code> <br>
                                                    <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
                                                    <b>Body Form Data required: &nbsp;</b> <code>NONE</code> <br>
                                                    <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
                                                    <b>Will Return: &nbsp;</b> <code>Shipments Detail </code> <small> </small><br>

                                                </div>
                                                <h4>Header</h4> <br>


                                                <pre style="font-weight:bold">
{
    "Accept" : "application/json",
    "Authorization" : "Bearer {access_token}",
}												</pre>
                                                <h4 style="margin-top: 25px">Form Data </h4> <br>


                                                <pre style="font-weight:bold">
NONE</pre>

                                                <h4 style="margin-top: 25px">Response</h4> <br>


                                                <pre style="font-weight:bold">
{
    "data": [
        {
            "id": "{id}",
            "shifl_ref": "{shifl_ref}",
            "customer": "{customer_name}",
            "mbl_num": "{mbl_number}",
            "is_tracking_shipment": "{true/false}",
            "vessel": "{vessel_name}",
            "booking_confirmed": "{true/false}",
            "booking_confirmed_at": "{booking_confirmed_date}",
            "schedules": {
                "etd": "{estimated_time_of_departure}",
                "eta": "{estimated_time_of_arrival}",
                "location_from": "{location_from_name}",
                "location_to": "{location_to_name}",
                "mode": "{Ocean/Air/Rail/Truck}",
                "carrier": {carrier_name},
                "type": "{FCL/LCL/Air}",
                "legs": [
                    {
                        "mode": "{Ocean/Air/Rail/Truck}",
                        "eta": "{estimated_time_of_arrival}",
                        "location_to": "{location_to_name}"
                    },
                    {...}, ...
                ]
            },
            "suppliers": [
                {
                    "supplier_name": "{supplier_name}",
                    "cargo_ready_date": "{date}",
                    "po_num_old": "{po_number}",
                    "selected_po": [
                        {
                            "po_number": "{po_number}",
                            "products": [
                                {
                                    "sku": "{product_sku}",
                                    "name": "{product_name}",
                                    "total_cartons": "{total_carton_in_the_PO}",
                                    "to_ship_cartons": "{to_ship_cartons}",
                                    "units_per_carton": "{units_per_carton}"
                                },
                                {...}, ...
                            ]
                        },
                        {...}, ...
                    ],
                    "weight": "{number_of_kg}",
                    "volume": "{number_of_cbm}",
                    "total_cartons": "{total_cartons}",
                    "ams": "{ams}",
                    "hbl": "{hbl}",
                    "marks": "{marks}",
                    "product_description": "{product_description}",
                    "terms": "{terms}",
                    "bl_status": "{Pending/Telex Released/Original Received}",
                    "hbl_copy": "{file_path}",
                    "packing_list": "{file_path}",
                    "commercial_documents": "{file_path}",
                    "commercial_invoice": "{file_path}"
                },
                {...}, ...
            ],
            "containers": [
                {
                    "container_num": "{container_number}",
                    "size": "{container_size}",
                    "seal_num": "{seal_number}",
                    "weight": "{number_of_kg}",
                    "volume": "{number_of_cbm}",
                    "carton_count": "{carton_count}"
                },
                {...}, ...
            ],
            "terminal": {
                "name": "{terminal_name}",
                "firms_code": "{terminal_firms_code}"
            },
            "customs_documents": [
                 {
                     "supplier_name": "{supplier_name}",
                     "files": [
                         {
                             "name": "{file_name}",
                             "path": "{file_path}"
                         },
                         {...}, ...
                     ]
                 },
                 {...}, ...
             ]
         },
         {...}, ...
    ]
}</pre>
                                                <p class="m-t-20">
                                                    Every time users want to access other api endpoints they have to provide validate token on header with every request. Otherwise The request will be considered as unauthorized attempt.
                                                </p>

                                                <div class="col-sm-12 p-t-30 " id="get_shipment_status">

                                                    <h3>Get Shipment Status</h3>
                                                    <p>
                                                        As mentioned above, Shifl Client Api is a token based api. For Every api request client has to provide validate Access token. Client will get the token by oauth token endpoint.
                                                    </p>
                                                    <br>

                                                    <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

                                                        <b>Route: </b> &nbsp;<code>https://beta.shifl.com/api/client/v1/shipment/status/{shipment_id}</code> <br>
                                                        <b>Request Type: &nbsp;</b> <code>GET</code> <br>
                                                        <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
                                                        <b>Body Form Data required: &nbsp;</b> <code>NONE</code> <br>
                                                        <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
                                                        <b>Will Return: &nbsp;</b> <code>Shipments Status (per Container) </code> <small> </small><br>

                                                    </div>
                                                    <h4>Header</h4> <br>


                                                <pre style="font-weight:bold">
{
    "Accept" : "application/json",
    "Authorization" : "Bearer {access_token}",
}												</pre>
                                                    <h4 style="margin-top: 25px">Form Data </h4> <br>


                                                    <pre style="font-weight:bold">
NONE</pre>

                                                    <h4 style="margin-top: 25px">Response</h4> <br>


                                                    <pre style="font-weight:bold">
{
    "data": [
        {
            "container_num": "{container_number}",
            "last_free_day": "{last_free_day}",
            "released_at_terminal": "{true/false}",
            "holds": [
                {
                   "name": "{customs/freight/USDA/TMF/other}",
                   "status": "{pending/hold}",
                   "description": "{description}"
                },
                {...}, ...
            ],
            "fees": [
                {
                   "type": "{demurrage/exam/total/other}",
                   "amount": "{amount_in_usd}"
                },
                {...}, ...
            ],
            "transport_events": [
                {
                    "event": "{transport_event_name}",
                    "created_at": "{datetime}",
                    "voyage_number": {voyage_number},
                    "timestamp": "{timestamp}",
                    "location_locode": "{lcode}",
                    "timezone": "{timezone}"
                },
                {...}, ...
            ]
        },
        {...}, ...
    ]
}</pre>
                                                    <p class="m-t-20">
                                                        Every time users want to access other api endpoints they have to provide validate token on header with every request. Otherwise The request will be considered as unauthorized attempt.
                                                    </p>

                                                </div>

                                                <div class="col-sm-12 " style="margin-bottom: 25px" id="download-shipment-documents">



                                                    <h3>Download Shipment Documents</h3>

                                                    <p>
                                                        By this endpoint client can download their shipment's documents(hbl_copy,packing_list etc.).
                                                    </p>
                                                    <br>

                                                    <div id="download-shipment-document-header" class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

                                                        <b>Route: </b> &nbsp;<code>https://beta.shifl.com/api/client/v1/shipment/documents/download</code> <br>
                                                        <b>Request Type: &nbsp;</b> <code>GET</code> <br>
                                                        <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
                                                        <b>Body Form Data required: &nbsp;</b> <code>document_type</code> , <code>document_path</code> , <code>shipment_id</code> <br>
                                                        <b>Return Type: &nbsp;</b> <code>FILE</code> <br>
                                                        <b>Will Return: &nbsp;</b> <code>Shipment Document </code> <small> </small><br>

                                                    </div>
                                                    <h4 id="download-shipment-document-header">Header</h4> <br>

                                                    <pre style="font-weight:bold">
{
    "Accept" : "application/json",
    "Authorization" : "Bearer {access_token}",
}</pre>
                                                    <p class="">
                                                        This Header will be same for all documents (hbl copy , packing list , commercial documents and commercial invoice) download api request only form data will change
                                                    </p>




                                                    <div class="col-sm-12" id="hbl-copy">
                                                        <h3>Hbl Copy</h3>
                                                        <p class="m-t-20">
                                                            For Hbl copy the header will be same <a href="#download-shipment-document-header">As mentioned avobe</a> , only form data will change.
                                                        </p>


                                                        <h4 style="margin-top: 25px">Form Data </h4> <br>


                                                        <pre style="font-weight:bold">
{
    "document_type" : "hbl_copy",
    "document_path" : "{suppliers > hbl_copy}",
    "shipment_id" : "{shipment_id}"
}</pre>

                                                        <h4 style="margin-top: 25px">Response</h4> <br>


                                                        <pre style="font-weight:bold">
Downloadable File</pre>
                                                    </div>

                                                    <div class="col-sm-12" id="packing-list">
                                                        <h3>Packing List</h3>
                                                        <p class="m-t-20">
                                                            For Packing list the header will be same <a href="#download-shipment-document-header">As mentioned avobe</a> , only form data will change.
                                                        </p>


                                                        <h4 style="margin-top: 25px">Form Data </h4> <br>


                                                        <pre style="font-weight:bold">
{
    "document_type" : "packing_list",
    "document_path" : "{suppliers > packing_list}",
    "shipment_id" : "{shipment_id}"
}</pre>

                                                        <h4 style="margin-top: 25px">Response</h4> <br>


                                                        <pre style="font-weight:bold">
Downloadable File</pre>
                                                    </div>
                                                    <div class="col-sm-12" id="commercial-documents">
                                                        <h3>Commercial Documents</h3>
                                                        <p class="m-t-20">
                                                            For Commercial Documents the header will be same <a href="#download-shipment-document-header">As mentioned avobe</a> , only form data will change.
                                                        </p>


                                                        <h4 style="margin-top: 25px">Form Data </h4> <br>


                                                        <pre style="font-weight:bold">
{
    "document_type" : "commercial_documents",
    "document_path" : "{suppliers > commercial_documents}",
    "shipment_id" : "{shipment_id}"
}</pre>

                                                        <h4 style="margin-top: 25px">Response</h4> <br>


                                                        <pre style="font-weight:bold">
Downloadable File</pre>
                                                    </div>
                                                    <div class="col-sm-12" id="commercial-invoice">
                                                        <h3>Commercial Invoice</h3>
                                                        <p class="m-t-20">
                                                            For Commercial Invoice the header will be same <a href="#download-shipment-document-header">As mentioned avobe</a> , only form data will change.
                                                        </p>


                                                        <h4 style="margin-top: 25px">Form Data </h4> <br>


                                                        <pre style="font-weight:bold">
{
    "document_type" : "commercial_invoice",
    "document_path" : "{suppliers > commercial_invoice}",
    "shipment_id" : "{shipment_id}"
}</pre>

                                                        <h4 style="margin-top: 25px">Response</h4> <br>


                                                        <pre style="font-weight:bold">
Downloadable File</pre>
                                                    </div>
                                                    <div class="col-sm-12" id="customs-documents">
                                                        <h3>Customs Documents</h3>
                                                        <p class="m-t-20">
                                                            For Customs Documents the header will be same <a href="#download-shipment-document-header">As mentioned avobe</a> , only form data will change.
                                                        </p>


                                                        <h4 style="margin-top: 25px">Form Data </h4> <br>


                                                        <pre style="font-weight:bold">
{
    "document_type" : "customs_documents",
    "document_path" : "{customs_documents > files > path}",
    "shipment_id" : "{shipment_id}"
}</pre>

                                                        <h4 style="margin-top: 25px">Response</h4> <br>


                                                        <pre style="font-weight:bold">
Downloadable File</pre>
                                                    </div>






                                                </div>



                                                <p>
                                                    Every time users want to access other api endpoints they have to provide validate token on header with every request. Otherwise The request will be considered as unauthorized attempt.
                                                </p>

                                                {{-- /////////////////////////////// --}}
                                                <div class="col-sm-12" id="how_to_create_client_credentials">
                                                    <h3 class="page-header"> How to create Client Credentials </h3>

                                                    <div class="col-sm-12">
                                                      <pre style="font-weight:bold">
STEPS:

1. visit <span style="color: #ff726f">https://beta.shifl.com/api-services/developers</span> or click on <a href="{{ route('api.services.dashboard') }}" style="color: red;"><u>Dashboard</u></a>

2. U will be redirect to login page if you're not logged in yet. Use your Credentials (same Credentials you use in app.shifl.com) to login.

3. Now click on "Create New Client"

   <img src="{{ asset("/images/documentation/client-api/passport_initial.png") }}" width="1000px">

4. Now a fill up the form and click "Create". (You can use your own website url for "Redirect Url")

   <img src="{{ asset("/images/documentation/client-api/passport_create_client_form.png") }}" width="1000px">

5. Now you can use this generated Client id as (client_id) and Client Secret as (client_secret) in the api (https://beta.shifl.com/oauth/token) to get access token.

   <img src="{{ asset("/images/documentation/client-api/passport_client_id_secret.png") }}" width="1000px">


 </pre>

                                                    </div>


                                                </div>
                                                <div style="clear: both;"></div>
                                                <div>
                                                    <div id="support" class="com-container">
                                                        <h3 class="page-header"> Support </h3>
                                                        <p>For any kind of Support regarding Shifl Client Api, feel free to reach out us.</p>
                                                        <p style="font-weight:bold"> Website : <a href="http://www.shifl.com" target="_blank">shifl.com</a> </p>
                                                        <p style="font-weight:bold"> Email: <a href="mailto:hello@shifl.com">{{ __('hello@shifl.com') }}</a></p>
                                                        <p style="font-weight:bold"> Call/Text/Whatsapp: <a href="https://api.whatsapp.com/send?phone=18884474435" target="_blank">888-HI-SHIFL (888-447-4435)</a></p>
                                                        <p style="font-weight:bold"> Headquarters : 386 RT 59 Suite 300J Monsey, NY 10952</p>
                                                    </div>
                                                </div>


                                            </div>










                                        </div>




                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /#page-content-wrapper -->

                    <div class="text-center">
                        <p class="copy" style="font-weight:bold;cursor:pointer" onclick="window.location.href='http://www.shifl.com'">© {{ \Carbon\Carbon::now()->format('Y') }} Shifl</p>
                    </div>
                </div>
                <!-- /#wrapper -->
                <!-- jQuery -->
                <script src="{!! asset('api/documentation') !!}/js/jquery.js"></script>

                <!-- Bootstrap Core JavaScript -->
                <script src="{!! asset('api/documentation') !!}/js/bootstrap.min.js"></script>

                <script src="{!! asset('api/documentation') !!}/js/jquery.easing.min.js"></script>

                <script type="{!! asset('api/documentation') !!}/text/javascript" src="js/prettify.js"></script>

                <!-- Menu Toggle Script -->
                <script>
                    //jQuery for page scrolling feature - requires jQuery Easing plugin
                    $(function() {
                        $('.sidebar-nav a').bind('click', function(event) {
                            var $anchor = $(this);
                            $('html, body').stop().animate({
                                scrollTop: $($anchor.attr('href')).offset().top - 100
                            }, 1500, 'easeInOutExpo');
                            event.preventDefault();
                        });
                    });
                </script>



    </body>

</html>
