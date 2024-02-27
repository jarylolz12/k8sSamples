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
        ul.endpoint_{
            list-style: none;
            padding: 0 10px;
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
                <a href="#endpoints_and_request"
                   data-toggle="collapse"
                   data-target="#enpoint-requests"
                   aria-expanded="false"
                   aria-controls="enpoint-requests"
                >
                    Enpoints And Requests
                </a>



                <div id="enpoint-requests">
                    <ul class="endpoint_"><!-- Available Api -->
                        <li>
                            <a href="#get_access_token" >
                                Get Access Token
                            </a>
                        </li>

                        <li>
                            <a href="#get_shipment_by_po" >
                                Get Shipment by PO
                            </a>
                        </li>
                        <li>
                            <a href="#get_shipment_status">
                                Get Shipment Status
                            </a>
                        </li>


                        <li>
                            <a href="#download-shipment-documents"
                               data-toggle="collapse"
                               data-target="#dl-shipment-document-endpoint"
                               aria-expanded="false"
                               aria-controls="dl-shipment-document-endpoint"
                            >
                                Download Shipment Documents
                            </a>

                            <ul class="endpoint_ collapse" id="dl-shipment-document-endpoint">
                                <li>
                                    <a href="#hbl-copy">HBL Copy</a>
                                </li>
                                <li>
                                    <a href="#packing-list">Packing List</a>
                                </li>
                                <li>
                                    <a href="#commercial-documents">Commercial Documents</a>
                                </li>
                                <li>
                                    <a href="#commercial-invoice">Commercial Invoice</a>
                                </li>
                                <li>
                                    <a href="#customs-documents">Customs Documents</a>
                                </li>
                            </ul>
                        </li>
                    </ul><!-- end Available Api -->



                    <!-- Purchase Order -->
                    <ul class="endpoint_">
                        <li>
                            <a href="#purchase-order"
                               data-toggle="collapse"
                               data-target="#purchase-order-endpoint"
                               aria-expanded="false"
                               aria-controls="purchase-order-endpoint"
                            >
                                Purchase Order
                            </a>
                            <ul class="endpoint_ collapse" id="purchase-order-endpoint">
                                <li>
                                    <a href="#purchase-order-list">List resource</a>
                                </li>
                                <li>
                                    <a href="#purchase-order-specified">Specified resource</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <!-- end Purchase Order -->





                    <ul class="endpoint_"><!-- user -->
                        <li>
                            <a href="#user"
                               data-toggle="collapse"
                               data-target="#user-endpoint"
                               aria-expanded="false"
                               aria-controls="user-endpoint">
                                User
                            </a>
                            <ul class="endpoint_ collapse" id="user-endpoint">
                                <li>
                                    <a href="#user-login">Login</a>
                                </li>
                                <li>
                                    <a href="#user-refresh-token">Refresh Token</a>
                                </li>
                                <li>
                                    <a href="#user-register">Register</a>
                                </li>

                                <li>
                                    <a href="#user-logout">Logout</a>
                                </li>

                                <li>
                                    <a href="#user-details">Details</a>
                                </li>
                            </ul>
                        </li>
                    </ul><!-- end user -->






                    <ul class="endpoint_">
                        <li>
                            <a href="#card" data-toggle="collapse" data-target="#card-endpoint" aria-expanded="false" aria-controls="card-endpoint">Card</a>
                            <ul class="endpoint_ collapse" id="card-endpoint">
                                <li>
                                    <a href="#card-list">List resource</a>
                                </li>
                                <li>
                                    <a href="#card-specific">Specified resource</a>
                                </li>
                                <li>
                                    <a href="#card-update">Update</a>
                                </li>
                                <li>
                                    <a href="#card-destroy">Destroy</a>
                                </li>
                            </ul>
                        </li>
                    </ul>


                    <ul class="endpoint_">
                        <li>
                            <a href="#carrier" data-toggle="collapse" data-target="#carrier-endpoint" aria-expanded="false" aria-controls="carrier-endpoint">Carrier</a>
                            <ul class="endpoint_ collapse" id="carrier-endpoint">
                                <li>
                                    <a href="#carrier-list">List resource</a>
                                </li>
                                <li>
                                    <a href="#carrier-specific">Specified resource</a>
                                </li>
                            </ul>
                        </li>
                    </ul>



                    <ul class="endpoint_">
                        <li>
                            <a href="#carrier-type" data-toggle="collapse" data-target="#carrier-type-endpoint" aria-expanded="false" aria-controls="carrier-type-endpoint">Carrier Type</a>
                            <ul class="endpoint_ collapse" id="carrier-type-endpoint">
                                <li>
                                    <a href="#carrier-type-list">List resource</a>
                                </li>
                                <li>
                                    <a href="#carrier-type-specified">Specified resource</a>
                                </li>
                            </ul>
                        </li>
                    </ul>




                    <ul class="endpoint_">
                        <li>
                            <a href="#client-shipment" data-toggle="collapse" data-target="#client-shipment-endpoint" aria-expanded="false" aria-controls="client-shipment-endpoint">Client Shipment</a>
                            <ul class="endpoint_ collapse" id="client-shipment-endpoint">
                                <li>
                                    <a href="#client-shipment-list">List resource</a>
                                </li>
                                <li>
                                    <a href="#client-shipment-status">Shipment Status</a>
                                </li>
                                <li>
                                    <a href="#client-shipment-download">Download</a>
                                </li>
                            </ul>
                        </li>
                    </ul>



                    <ul class="endpoint_">
                        <li>
                            <a href="#container" data-toggle="collapse" data-target="#container-endpoint" aria-expanded="false" aria-controls="container-endpoint">
                                Container
                            </a>
                            <ul class="endpoint_ collapse" id="container-endpoint">
                                <li>
                                    <a href="#container-list">List resource</a>
                                </li>
                                <li>
                                    <a href="#container-specified">Specified resource</a>
                                </li>
                            </ul>
                        </li>
                    </ul>


                    <ul class="endpoint_">
                        <li>
                            <a href="#container-size" data-toggle="collapse" data-target="#container-size-endpoint" aria-expanded="false" aria-controls="container-size-endpoint">
                                Container Size
                            </a>
                            <ul class="endpoint_ collapse" id="container-size-endpoint">
                                <li>
                                    <a href="#container-size-list">List resource</a>
                                </li>
                                <li>
                                    <a href="#container-size-specified">Specified resource</a>
                                </li>
                            </ul>
                        </li>
                    </ul>


                    <ul class="endpoint_">
                        <li>
                            <a href="#custom-admin" data-toggle="collapse" data-target="#custom-admin-endpoint" aria-expanded="false" aria-controls="custom-admin-endpoint">
                                Custom Admin
                            </a>
                            <ul class="endpoint_ collapse" id="custom-admin-endpoint">
                                <li>
                                    <a href="#custom-admin-list">List resource</a>
                                </li>
                            </ul>
                        </li>
                    </ul>


                    <ul class="endpoint_">
                        <li>
                            <a href="#custom-customer" data-toggle="collapse" data-target="#custom-customer-endpoint" aria-expanded="false" aria-controls="custom-customer-endpoint">
                                Custom Customer
                            </a>
                            <ul class="endpoint_ collapse" id="custom-customer-endpoint">
                                <li>
                                    <a href="#custom-customer-list">List resource</a>
                                </li>
                                <li>
                                    <a href="#custom-customer-specified">Specified resource</a>
                                </li>
                                <li>
                                    <a href="#custom-customer-supplier">Display customer supplier</a>
                                </li>
                                <li>
                                    <a href="#custom-customer-buyer">Display customer buyer</a>
                                </li>
                            </ul>
                        </li>
                    </ul>

                    <ul class="endpoint_">
                        <li>
                            <a href="#custom-supplier" data-toggle="collapse" data-target="#custom-supplier-endpoint" aria-expanded="false" aria-controls="custom-supplier-endpoint">
                                Custom Supplier
                            </a>
                            <ul class="endpoint_ collapse" id="custom-customer-endpoint">
                                <li>
                                    <a href="#custom-supplier-specified">Specified resource</a>
                                </li>
                            </ul>
                        </li>
                    </ul>




                    <ul class="endpoint_">
                        <li>
                            <a href="#custom-user"
                               data-toggle="collapse"
                               data-target="#custom-user-endpoint"
                               aria-expanded="false"
                               aria-controls="custom-user-endpoint"
                            >
                                Custom User
                            </a>
                            <ul class="endpoint_ collapse" id="custom-user-endpoint">
                                <li>
                                    <a href="#custom-user-getaccesstoken">Get access token</a>
                                </li>
                                <li>
                                    <a href="#custom-user-customer-details">Customer details</a>
                                </li>
                            </ul>
                        </li>
                    </ul>



                    <ul class="endpoint_">
                        <li>
                            <a href="#customer"
                               data-toggle="collapse"
                               data-target="#customer-endpoint"
                               aria-expanded="false"
                               aria-controls="customer-endpoint"
                            >
                                Customer
                            </a>
                            <ul class="endpoint_ collapse" id="customer-endpoint">
                                <li>
                                    <a href="#customer-specified ">Specified resource</a>
                                </li>
                                <li>
                                    <a href="#customer-list">List resource</a>
                                </li>
                            </ul>
                        </li>
                    </ul>




                    <ul class="endpoint_">
                        <li>
                            <a href="#customer-admin"
                               data-toggle="collapse"
                               data-target="#customer-admin-endpoint"
                               aria-expanded="false"
                               aria-controls="customer-admin-endpoint"
                            >
                                Customer Admin
                            </a>
                            <ul class="endpoint_ collapse" id="customer-admin-endpoint">
                                <li>
                                    <a href="#customer-admin-specified ">Specified resource</a>
                                </li>
                                <li>
                                    <a href="#customer-admin-destroy">Destroy</a>
                                </li>
                            </ul>
                        </li>
                    </ul>





                    <ul class="endpoint_">
                        <li>
                            <a href="#email-report-schedule"
                               data-toggle="collapse"
                               data-target="#email-report-schedule-endpoint"
                               aria-expanded="false"
                               aria-controls="email-report-schedule-endpoint"
                            >
                                Email Report Schedule
                            </a>
                            <ul class="endpoint_ collapse" id="email-report-schedule-endpoint">
                                <li>
                                    <a href="#email-report-schedule-update ">Update resource</a>
                                </li>
                                <li>
                                    <a href="#email-report-schedule-update ">Create resource</a>
                                </li>
                                <li>
                                    <a href="#email-report-schedule-update ">Display dropdown value</a>
                                </li>
                                <li>
                                    <a href="#email-report-schedule-update ">Specified resource</a>
                                </li>
                                <li>
                                    <a href="#email-report-schedule-update ">Destroy resource</a>
                                </li>
                                <li>
                                    <a href="#email-report-schedule-update ">Downloadable document</a>
                                </li>
                                <li>
                                    <a href="#email-report-schedule-update ">Version 2</a>
                                </li>
                                <li>
                                    <a href="#email-report-schedule-update ">Downloadable v2</a>
                                </li>
                            </ul>
                        </li>
                    </ul>




                    <!-- eta logs -->
                    <ul class="endpoint_">
                        <li>
                            <a href="#eta-log"
                               data-toggle="collapse"
                               data-target="#eta-log-endpoint"
                               aria-expanded="false"
                               aria-controls="eta-log-endpoint"
                            >
                                Eta Logs
                            </a>
                            <ul class="endpoint_ collapse" id="eta-log-endpoint">
                                <li>
                                    <a href="#eta-log-specified ">Specified resource</a>
                                </li>
                            </ul>
                        </li>
                    </ul>


                    <!-- end eta logs -->


                    <!-- incoterm -->
                    <ul class="endpoint_">
                        <li>
                            <a href="#incoterm"
                               data-toggle="collapse"
                               data-target="#incoterm-endpoint"
                               aria-expanded="false"
                               aria-controls="incoterm-endpoint"
                            >
                                Incoterm
                            </a>
                            <ul class="endpoint_ collapse" id="incoterm-endpoint">
                                <li>
                                    <a href="#incoterm-list ">List resource</a>
                                </li>
                                <li>
                                    <a href="#incoterm-specified ">Specified resource</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <!-- end incoterm -->




                    <!-- incoterm -->
                    <ul class="endpoint_">
                        <li>
                            <a href="#incoterm"
                               data-toggle="collapse"
                               data-target="#incoterm-endpoint"
                               aria-expanded="false"
                               aria-controls="incoterm-endpoint"
                            >
                                Incoterm
                            </a>
                            <ul class="endpoint_ collapse" id="incoterm-endpoint">
                                <li>
                                    <a href="#incoterm-list ">List resource</a>
                                </li>
                                <li>
                                    <a href="#incoterm-specified ">Specified resource</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <!-- end incoterm -->



                    <!-- invoice -->
                    <ul class="endpoint_">
                        <li>
                            <a href="#invoice"
                               data-toggle="collapse"
                               data-target="#invoice-endpoint"
                               aria-expanded="false"
                               aria-controls="invoice-endpoint"
                            >
                                Invoice
                            </a>
                            <ul class="endpoint_ collapse" id="invoice-endpoint">
                                <li>
                                    <a href="#invoice-list">List resource</a>
                                </li>
                                <li>
                                    <a href="#invoice-specified">Specified resource</a>
                                </li>
                                <li>
                                    <a href="#invoice-downloadable-file">Downloadable File</a>
                                </li>
                                <li>
                                    <a href="#invoice-total-due">Total due</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <!-- end invoice -->







                    <!-- Item -->
                    <ul class="endpoint_">
                        <li>
                            <a href="#item"
                               data-toggle="collapse"
                               data-target="#item-endpoint"
                               aria-expanded="false"
                               aria-controls="item-endpoint"
                            >
                                Item
                            </a>
                            <ul class="endpoint_ collapse" id="item-endpoint">
                                <li>
                                    <a href="#item-list">List resource</a>
                                </li>
                                <li>
                                    <a href="#item-specified">Specified resource</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <!-- end Item -->



                    <!-- Juliverdevshifl -->
                    <ul class="endpoint_">
                        <li>
                            <a href="#Juliverdevshifl"
                               data-toggle="collapse"
                               data-target="#Juliverdevshifl-endpoint"
                               aria-expanded="false"
                               aria-controls="Juliverdevshifl-endpoint"
                            >
                                Juliverdevshifl
                            </a>
                            <ul class="endpoint_ collapse" id="Juliverdevshifl-endpoint">
                                <li>
                                    <a href="#Juliverdevshifl-specified-terminal">Specified terminal resource</a>
                                </li>
                                <li>
                                    <a href="#Juliverdevshifl-specified-container">Specified container resource</a>
                                </li>
                                <li>
                                    <a href="#Juliverdevshifl-specified-terminal-index">Specified terminal index resource</a>
                                </li>
                                <li>
                                    <a href="#Juliverdevshifl-specified-graph-terminal">Specified graph terminal resource</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <!-- end Juliverdevshifl -->





                    <!-- Milestones -->
                    <ul class="endpoint_">
                        <li>
                            <a href="#milestone"
                               data-toggle="collapse"
                               data-target="#milestone-endpoint"
                               aria-expanded="false"
                               aria-controls="milestone-endpoint"
                            >
                                Milestones
                            </a>
                            <ul class="endpoint_ collapse" id="milestone-endpoint">
                                <li>
                                    <a href="#milestone-specified">Specified resource</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <!-- end Milestones -->




                    <!-- Profit Monitor -->
                    <ul class="endpoint_">
                        <li>
                            <a href="#profit-monitor"
                               data-toggle="collapse"
                               data-target="#profit-monitor-endpoint"
                               aria-expanded="false"
                               aria-controls="profit-monitor-endpoint"
                            >
                                Profit Monitor
                            </a>
                            <ul class="endpoint_ collapse" id="profit-monitor-endpoint">
                                <li>
                                    <a href="#profit-monitor-calculated-shipment-v2">Calculated shipment V2</a>
                                </li>
                                <li>
                                    <a href="#profit-monitor-calculated-shipment-v2">QBO Companies</a>
                                </li>
                                <li>
                                    <a href="#profit-monitor-calculated-shipment-v2">sale representatives</a>
                                </li>
                                <li>
                                    <a href="#profit-monitor-calculated-shipment-v2">Total profit by request</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <!-- end Profit Monitor -->





                    <!-- Search -->
                    <ul class="endpoint_">
                        <li>
                            <a href="#search"
                               data-toggle="collapse"
                               data-target="#search-endpoint"
                               aria-expanded="false"
                               aria-controls="search-endpoint"
                            >
                                Search
                            </a>
                            <ul class="endpoint_ collapse" id="purchase-order-endpoint">
                                <li>
                                    <a href="#search-list">List resource</a>
                                </li>
                                <li>
                                    <a href="#search-specified">Specified resource</a>
                                </li>
                                <li>
                                    <a href="#search-specified-customer">Specified customer</a>
                                </li>
                                <li>
                                    <a href="#search-specified-shipment">Specified shipment</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <!-- end Search -->



                    <!-- Shipment -->
                    <ul class="endpoint_">
                        <li>
                            <a href="#shipment"
                               data-toggle="collapse"
                               data-target="#shipment-endpoint"
                               aria-expanded="false"
                               aria-controls="shipment-endpoint"
                            >
                                Shipment
                            </a>
                            <ul class="endpoint_ collapse" id="shipment-endpoint">
                                <li>
                                    <a href="#shipment-snooze">Snooze shipment</a>
                                </li>
                                <li>
                                    <a href="#get-shipment-po">Get po shipment</a>
                                </li>
                                <li>
                                    <a href="#shipment-schedule-option">Schedule option</a>
                                </li>
                                <li>
                                    <a href="#shipment-unsnooze">Unsnooze shipment</a>
                                </li>
                                <li>
                                    <a href="#shipment-select-schedule-app">Select schedule app</a>
                                </li>
                                <li>
                                    <a href="#shipment-specified">Specified resource.</a>
                                </li>
                                <li>
                                    <a href="#shipment-terminal-49">Terminal 49 Shipment</a>
                                </li>

                                <li>
                                    <a href="#shipment-details">Display details</a>
                                </li>

                                <li>
                                    <a href="#shipment-transit">Transit</a>
                                </li>

                                <li>
                                    <a href="#shipment-dl-file-do">Downloadable File DO</a>
                                </li>
                                <li>
                                    <a href="#shipment-expired">Expired</a>
                                </li>

                                <li>
                                    <a href="#shipment-get-shipment">Get shipment</a>
                                </li>
                                <li>
                                    <a href="#shipment-pending">Pending</a>
                                </li>

                                <li>
                                    <a href="#shipment-completed">Completed</a>
                                </li>
                                <li>
                                    <a href="#shipment-search-customer-order">Search customer order</a>
                                </li>
                                <li>
                                    <a href="#shipment-display-details">Display detail</a>
                                </li>

                                <li>
                                    <a href="#shipment-po">Shipment po</a>
                                </li>

                                <li>
                                    <a href="#shipment-po">Pending quote shipments</a>
                                </li>

                                <li>
                                    <a href="#shipment-po">Snooze shipments</a>
                                </li>
                                <li>
                                    <a href="#shipment-transit-completed">Transit/Completed</a>
                                </li>

                            </ul>
                        </li>
                    </ul>
                    <!-- end Shipment -->



                    <!-- Search -->
                    <ul class="endpoint_">
                        <li>
                            <a href="#shipment-document"
                               data-toggle="collapse"
                               data-target="#shipment-document-endpoint"
                               aria-expanded="false"
                               aria-controls="shipment-document-endpoint"
                            >
                                Shipment Documents
                            </a>
                            <ul class="endpoint_ collapse" id="purchase-order-endpoint">
                                <li>
                                    <a href="#shipment-document-insert">Insert Documents</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <!-- end Search -->



                </div>

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

                                        <!--                                    <div class="col-sm-12">
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
                                                                                    </ul>
                                                                                    <small>
                                                                                        More will be added Soon ....
                                                                                    </small>

                                                                                </div> -->


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



                                        <div class="col-sm-12" id="user">
                                            <h3 class="page-header">User</h3>

                                            <!-- user login -->
                                            <div class="col-sm-12">

                                                <h3>Login</h3>
                                                <p class="m-t-20">
                                                    In this endpoint, we can also get the token.
                                                </p>

                                                <div id="user-login" class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

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

                                            </div>
                                            <!-- End user login -->




                                            <!-- refresh token -->
                                            <div class="col-sm-12">

                                                <h3>Refresh Token</h3>
                                                <div id="user-refresh-token" class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

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

                                            </div>
                                            <!-- End refresh token -->



                                            <!-- Register token -->
                                            <div class="col-sm-12">

                                                <h3>Register</h3>
                                                <div
                                                        id="user-register"
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

                                            </div>
                                            <!-- End Register -->







                                            <!-- Logout token -->
                                            <div class="col-sm-12">

                                                <h3>Logout</h3>
                                                <div
                                                        id="user-logout"
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

                                            </div>
                                            <!-- End logout -->





                                            <!-- Details -->
                                            <div class="col-sm-12">

                                                <h3>Details</h3>
                                                <div id="user-details" class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

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

                                            </div>
                                            <!-- End Details -->

                                        </div>

                                    {{-- /////////////////////////////// --}}





                                    <!-- Purchase Order -->
                                        <div class="col-sm-12" id="purchase-order">
                                            <h3 class="page-header">Purchase Order</h3>

                                            <!-- List resource -->
                                            <div class="col-sm-12" id="purchase-order-list">
                                                <h3>List resource</h3>
                                                <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

                                                    <b>Route: </b> &nbsp;<code>api/purchase-orders</code> <br>
                                                    <b>Request Type: &nbsp;</b> <code>GET</code> <br>
                                                    <b>Header Required: &nbsp;</b> <code>None</code> <br>
                                                    <b>Body Form Data required: &nbsp;</b> <code>per_page</code>, <code>page</code> <br>
                                                    <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
                                                    <b>Will Return: &nbsp;</b> <code>List of purchase order</code> <small> </small><br>

                                                </div>

                                                <h4>Header</h4>


                                                <pre style="font-weight:bold">
{
    "Authorization" : "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>


                                                <h4> Form Data </h4>
                                                <pre style="font-weight:bold">
{
    "per_page" : 5,
    "page" : 1,
}
</pre>

                                                <h4>Response</h4>
                                                <pre style="font-weight:bold">
   {
    "data": [
        {
            "id": 1,
            "customer_id": 48,
            "po_num": "1415909",
            "supplier_id": 11,
            "po_items": "[]",
            "created_at": "2022-01-14T15:04:33.000000Z",
            "updated_at": "2022-01-14T15:04:33.000000Z"
        },
        {
            "id": 4,
            "customer_id": 48,
            "po_num": "1415910",
            "supplier_id": 11,
            "po_items": "[]",
            "created_at": "2022-01-14T15:05:49.000000Z",
            "updated_at": "2022-01-14T15:05:49.000000Z"
        },
        {
            "id": 6,
            "customer_id": 48,
            "po_num": "1415949",
            "supplier_id": 11,
            "po_items": "[]",
            "created_at": "2022-01-14T16:09:41.000000Z",
            "updated_at": "2022-01-14T16:09:41.000000Z"
        },
        {
            "id": 7,
            "customer_id": 194,
            "po_num": "111000007",
            "supplier_id": 2429,
            "po_items": "[]",
            "created_at": "2022-03-10T00:19:51.000000Z",
            "updated_at": "2022-03-10T00:19:51.000000Z"
        },
        {
            "id": 9,
            "customer_id": 447,
            "po_num": "111000009",
            "supplier_id": 1836,
            "po_items": "[]",
            "created_at": "2022-04-08T21:51:18.000000Z",
            "updated_at": "2022-04-08T21:51:18.000000Z"
        }
    ],
    "links": {
        "first": "api/purchase-orders?page=1",
        "last": "api/purchase-orders?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "path": "api/purchase-orders",
        "per_page": 5,
        "to": 5,
        "total": 5
    }
}
                                                        </pre>

                                            </div><!-- End List resource -->



                                            <!-- Specified resource -->
                                            <div class="col-sm-12" id="purchase-order-specified">
                                                <h3>Specified resource</h3>
                                                <div class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

                                                    <b>Route: </b> &nbsp;<code>api/purchase-order/{id}</code> <br>
                                                    <b>Request Type: &nbsp;</b> <code>GET</code> <br>
                                                    <b>Header Required: &nbsp;</b> <code>None</code> <br>
                                                    <b>Body Form Data required: &nbsp;</b> <code>id</code> <br>
                                                    <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
                                                    <b>Will Return: &nbsp;</b> <code>Specific resources</code> <small> </small><br>

                                                </div>
                                                <h4>Header</h4>


                                                <pre style="font-weight:bold">
{
    "Authorization" : "Bearer {YOUR_AUTH_KEY}",
    "Content-Type" : "application/json",
    "Accept" : "application/json",
}
                                                        </pre>


                                                <h4> Form Data </h4>
                                                <pre style="font-weight:bold">
{
    "id" : 1,
}
</pre>

                                                <h4>Response</h4>
                                                <pre style="font-weight:bold">
{
    "data": {
        "id": 1,
        "customer_id": 48,
        "po_num": "1415909",
        "supplier_id": 11,
        "po_items": "[]",
        "created_at": "2022-01-14T15:04:33.000000Z",
        "updated_at": "2022-01-14T15:04:33.000000Z"
    }
}
                                                        </pre>

                                            </div><!-- End specified resource -->


                                        </div> <!-- End Purchase Order -->










                                        <div class="col-sm-12" id="card">
                                            <h3 class="page-header">Card</h3>

                                            <!-- Card list resource -->
                                            <div class="col-sm-12">

                                                <h3>List resource</h3>
                                                <div id="card-list" class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

                                                    <b>Route: </b> &nbsp;<code>api/poynt/cards/{default_customer_id}</code> <br>
                                                    <b>Request Type: &nbsp;</b> <code>GET</code> <br>
                                                    <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
                                                    <b>Body Form Data required: &nbsp;</b> <code>NONE</code> <br>
                                                    <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
                                                    <b>Will Return: &nbsp;</b> <code>List of Card </code> <small> </small><br>

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
    "id" : "{default_customer_id}"
}
                                                        </pre>

                                                <h4>Response</h4>
                                                <pre>
{
    "data": {
        "id": 81,
        "type": "VISA",
        "source": "DIRECT",
        "expiration_month": 8,
        "expiration_year": 2026,
        "card_id": 355314669,
        "number_masked": "415417******3090",
        "first_name": "Anthony",
        "last_name": "Abadicio",
        "card_card_id": "d9bf0135-95ae-4305-b4a8-99bdb65dfde1",
        "payment_token": "eyJhbGciOiJSUzI1NiJ9.eyJwb3ludC5lcHkiOjIwMjYsInBveW50LmF1ciI6InVybjphaWQ6MWY5YmQ2NDctZjkzMy00NDQ2LWFmNWYtNmE3OWMzNTM5YjUyIiwicG95bnQuY2lkIjoiZDliZjAxMzUtOTVhZS00MzA1LWI0YTgtOTliZGI2NWRmZGUxIiwicG95bnQuYml6IjoiNTdmYTlmNjMtNjQxOS00MjNiLTg5YjctYTQ1ODlmMzk0ZGFlIiwicG95bnQuZXBtIjo4LCJpc3MiOiJodHRwczpcL1wvc2VydmljZXMucG95bnQubmV0IiwicG95bnQucG10IjoiYjU3NmEwMTgtYmYyOC00NzMyLTk1YzctMDMyYjU3ODAzMDFlIiwicG95bnQua2lkIjo4ODM3MzA4MjkxMTcyNDMyMDkxLCJleHAiOjE3ODgxODMxNzYsImlhdCI6MTY0NjQwMDc3NiwianRpIjoiYjU3NmEwMTgtYmYyOC00NzMyLTk1YzctMDMyYjU3ODAzMDFlIiwicG95bnQucGxmIjoiMzA5MCJ9.SlUGja1iNff1lLvJUNywdfU66fnDru5QHjwJ3ePihzbThPMYysFhtGwmeLIxZheL5bl9vyQEVAs-wYrXK9YQJe7JR3Qigp7-9l8L_9CnvOrY46z9pz-fmvaCzzzo0wqERgU2yn26UPhCyDKXQoqPSnlNnHYPsXzaBO9_VkbmlyJcsl7K4K4B1n-vVUP9A8i3myuiIq6nwt4fgQfpzp5J_Szq7iaZjQZRJr6juhR4GOegebokzkCa-0fpC8dA_WzI6xyWv8G2Nfivex_cSfTviAn3rmVxf5x92iNIFKNcWNkketyMJX_ubpI2vU7K-2s-dOAqsTtVJBtu0Pi13HxqdQ",
        "customer_admin_id": 365,
        "is_default": 1
    }
}
                                                        </pre>

                                            </div>
                                            <!-- End Card list resource -->




                                            <!-- End Specified resource -->
                                            <div class="col-sm-12">

                                                <h3>Specified resource</h3>
                                                <div id="card-specific" class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

                                                    <b>Route: </b> &nbsp;<code>api/poynt/card/{id}</code> <br>
                                                    <b>Request Type: &nbsp;</b> <code>GET</code> <br>
                                                    <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
                                                    <b>Body Form Data required: &nbsp;</b> <code>NONE</code> <br>
                                                    <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
                                                    <b>Will Return: &nbsp;</b> <code>Card (Specific Card) </code> <small> </small><br>

                                                </div>

                                                <h4>Header</h4>


                                                <pre style="font-weight:bold">
{
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept" : "application/json",
}
                                                        </pre>


                                                <h4> Form Data </h4>
                                                <pre>
{
    "id" : "{default_customer_id}"
}
                                                        </pre>

                                                <h4>Response</h4>
                                                <pre>
{
    "data": {
        "id": 81,
        "type": "VISA",
        "source": "DIRECT",
        "expiration_month": 8,
        "expiration_year": 2026,
        "card_id": 355314669,
        "number_masked": "415417******3090",
        "first_name": "Anthony",
        "last_name": "Abadicio",
        "card_card_id": "d9bf0135-95ae-4305-b4a8-99bdb65dfde1",
        "payment_token": "eyJhbGciOiJSUzI1NiJ9.eyJwb3ludC5lcHkiOjIwMjYsInBveW50LmF1ciI6InVybjphaWQ6MWY5YmQ2NDctZjkzMy00NDQ2LWFmNWYtNmE3OWMzNTM5YjUyIiwicG95bnQuY2lkIjoiZDliZjAxMzUtOTVhZS00MzA1LWI0YTgtOTliZGI2NWRmZGUxIiwicG95bnQuYml6IjoiNTdmYTlmNjMtNjQxOS00MjNiLTg5YjctYTQ1ODlmMzk0ZGFlIiwicG95bnQuZXBtIjo4LCJpc3MiOiJodHRwczpcL1wvc2VydmljZXMucG95bnQubmV0IiwicG95bnQucG10IjoiYjU3NmEwMTgtYmYyOC00NzMyLTk1YzctMDMyYjU3ODAzMDFlIiwicG95bnQua2lkIjo4ODM3MzA4MjkxMTcyNDMyMDkxLCJleHAiOjE3ODgxODMxNzYsImlhdCI6MTY0NjQwMDc3NiwianRpIjoiYjU3NmEwMTgtYmYyOC00NzMyLTk1YzctMDMyYjU3ODAzMDFlIiwicG95bnQucGxmIjoiMzA5MCJ9.SlUGja1iNff1lLvJUNywdfU66fnDru5QHjwJ3ePihzbThPMYysFhtGwmeLIxZheL5bl9vyQEVAs-wYrXK9YQJe7JR3Qigp7-9l8L_9CnvOrY46z9pz-fmvaCzzzo0wqERgU2yn26UPhCyDKXQoqPSnlNnHYPsXzaBO9_VkbmlyJcsl7K4K4B1n-vVUP9A8i3myuiIq6nwt4fgQfpzp5J_Szq7iaZjQZRJr6juhR4GOegebokzkCa-0fpC8dA_WzI6xyWv8G2Nfivex_cSfTviAn3rmVxf5x92iNIFKNcWNkketyMJX_ubpI2vU7K-2s-dOAqsTtVJBtu0Pi13HxqdQ",
        "customer_admin_id": 365,
        "is_default": 1
    }
}
                                                        </pre>

                                            </div> <!-- End Specified resource -->








                                            <!-- Card update -->
                                            <div class="col-sm-12">

                                                <h3>Update</h3>
                                                <div id="card-update" class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

                                                    <b>Route: </b> &nbsp;<code>api/poynt/card</code> <br>
                                                    <b>Request Type: &nbsp;</b> <code>PUT</code> <br>
                                                    <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
                                                    <b>Body Form Data required: &nbsp;</b> <code>NONE</code> <br>
                                                    <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
                                                    <b>Will Return: &nbsp;</b> <code>Card (Update Card) </code> <small> </small><br>

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
    "card_id" : "{card_id}",
    "first_name" : "{first_name}",
    "last_name" : "{last_name}",
    "is_default" : "{is_default}",

}
                                                        </pre>

                                                <h4>Response</h4>
                                                <pre>
{
    "data": {
        "id": 81,
        "type": "VISA",
        "source": "DIRECT",
        "expiration_month": 8,
        "expiration_year": 2026,
        "card_id": 355314669,
        "number_masked": "415417******3090",
        "first_name": "Anthony",
        "last_name": "Abadicio",
        "card_card_id": "d9bf0135-95ae-4305-b4a8-99bdb65dfde1",
        "payment_token": "eyJhbGciOiJSUzI1NiJ9.eyJwb3ludC5lcHkiOjIwMjYsInBveW50LmF1ciI6InVybjphaWQ6MWY5YmQ2NDctZjkzMy00NDQ2LWFmNWYtNmE3OWMzNTM5YjUyIiwicG95bnQuY2lkIjoiZDliZjAxMzUtOTVhZS00MzA1LWI0YTgtOTliZGI2NWRmZGUxIiwicG95bnQuYml6IjoiNTdmYTlmNjMtNjQxOS00MjNiLTg5YjctYTQ1ODlmMzk0ZGFlIiwicG95bnQuZXBtIjo4LCJpc3MiOiJodHRwczpcL1wvc2VydmljZXMucG95bnQubmV0IiwicG95bnQucG10IjoiYjU3NmEwMTgtYmYyOC00NzMyLTk1YzctMDMyYjU3ODAzMDFlIiwicG95bnQua2lkIjo4ODM3MzA4MjkxMTcyNDMyMDkxLCJleHAiOjE3ODgxODMxNzYsImlhdCI6MTY0NjQwMDc3NiwianRpIjoiYjU3NmEwMTgtYmYyOC00NzMyLTk1YzctMDMyYjU3ODAzMDFlIiwicG95bnQucGxmIjoiMzA5MCJ9.SlUGja1iNff1lLvJUNywdfU66fnDru5QHjwJ3ePihzbThPMYysFhtGwmeLIxZheL5bl9vyQEVAs-wYrXK9YQJe7JR3Qigp7-9l8L_9CnvOrY46z9pz-fmvaCzzzo0wqERgU2yn26UPhCyDKXQoqPSnlNnHYPsXzaBO9_VkbmlyJcsl7K4K4B1n-vVUP9A8i3myuiIq6nwt4fgQfpzp5J_Szq7iaZjQZRJr6juhR4GOegebokzkCa-0fpC8dA_WzI6xyWv8G2Nfivex_cSfTviAn3rmVxf5x92iNIFKNcWNkketyMJX_ubpI2vU7K-2s-dOAqsTtVJBtu0Pi13HxqdQ",
        "customer_admin_id": 365,
        "is_default": 1
    }
}
                                                        </pre>

                                            </div><!-- end Card update -->









                                            <!-- Card Delete -->
                                            <div class="col-sm-12">

                                                <h3>Destroy</h3>
                                                <div id="card-destroy" class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

                                                    <b>Route: </b> &nbsp;<code>api/payment-method/cc/delete</code> <br>
                                                    <b>Request Type: &nbsp;</b> <code>DELETE</code> <br>
                                                    <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
                                                    <b>Body Form Data required: &nbsp;</b> <code>NONE</code> <br>
                                                    <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
                                                    <b>Will Return: &nbsp;</b> <code>Card (Delete Card) </code> <small> </small><br>

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
    "card_id" : "{card_id}",
}
                                                        </pre>

                                                <h4>Response</h4>
                                                <pre>
{
    {
        "status": "ok"
    }
}
                                                        </pre>

                                            </div><!-- end Card Delete -->





                                        </div> <!-- end card tag -->




                                        <!-- Carrier resource -->
                                        <div class="col-sm-12" id="carrier">
                                            <h3 class="page-header">Carrier</h3>
                                            <!-- Carrier list resource -->
                                            <div class="col-sm-12">

                                                <h3>List resource</h3>
                                                <div id="carrier-list" class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

                                                    <b>Route: </b> &nbsp;<code>api/carrier</code> <br>
                                                    <b>Request Type: &nbsp;</b> <code>GET</code> <br>
                                                    <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
                                                    <b>Body Form Data required: &nbsp;</b> <code>NONE</code> <br>
                                                    <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
                                                    <b>Will Return: &nbsp;</b> <code>List of Carrier </code> <small> </small><br>

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
NONE
</pre>

                                                <h4>Response</h4>
                                                <pre>
{
    "data": [
        {
            "id": 1,
            "name": "Cosco"
        },
        {
            "id": 1,
            "name": "Cosco"
        }
    ],
    "links": {
        "first": "/?page=1",
        "last": "/?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "path": "/",
        "per_page": "5",
        "to": 2,
        "total": 2
    }
}
                                                        </pre>
                                            </div><!-- End Carrier list resource -->


                                            <!-- Carrier Specified resource -->
                                            <div class="col-sm-12">

                                                <h3>Specified resource</h3>
                                                <div id="carrier-specific" class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

                                                    <b>Route: </b> &nbsp;<code>api/carrier/{id}</code> <br>
                                                    <b>Request Type: &nbsp;</b> <code>GET</code> <br>
                                                    <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
                                                    <b>Body Form Data required: &nbsp;</b> <code>NONE</code> <br>
                                                    <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
                                                    <b>Will Return: &nbsp;</b> <code> Carrier (Specific Carrier) </code> <small> </small><br>

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
NONE
</pre>

                                                <h4>Response</h4>
                                                <pre>
{
    "data": {
        "id": 1,
        "name": "Cosco"
    }
}
                                                        </pre>

                                            </div><!-- End Carrier Specified resource -->

                                        </div>



                                        <div class="col-sm-12" id="carrier-type">
                                            <h3 class="page-header">Carrier Type</h3>


                                            <!-- Carrier type list resource -->
                                            <div class="col-sm-12">

                                                <h3>List resource</h3>
                                                <div id="carrier-type-list" class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

                                                    <b>Route: </b> &nbsp;<code>api/carrier-types</code> <br>
                                                    <b>Request Type: &nbsp;</b> <code>GET</code> <br>
                                                    <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
                                                    <b>Body Form Data required: &nbsp;</b> <code>NONE</code> <br>
                                                    <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
                                                    <b>Will Return: &nbsp;</b> <code>List of Carrier Type </code> <small> </small><br>

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
NONE
</pre>

                                                <h4>Response</h4>
                                                <pre>
{
   "data": [
         {
             "id": 1,
             "name": "Air",
             "created_at": "2020-02-28T07:04:28.000000Z",
             "updated_at": "2020-02-28T07:04:28.000000Z"
         },
         {
             "id": 2,
             "name": "Ocean",
             "created_at": "2020-02-28T07:04:48.000000Z",
             "updated_at": "2020-02-28T07:04:48.000000Z"
          }
      ],
      "links": {
         "first": "/?page=1",
         "last": "/?page=1",
         "prev": null,
         "next": null
      },
      "meta": {
         "current_page": 1,
         "from": 1,
         "last_page": 1,
         "path": "/api/carrier-types",
         "per_page": 5,
         "to": 2,
         "total": 2
      }
  }
}
                                                        </pre>

                                            </div><!-- End Carrier type list resource -->






                                            <!-- Carrier type specified resource -->
                                            <div class="col-sm-12">

                                                <h3>Specified resource</h3>
                                                <div id="carrier-type-specified" class="" style="padding-left:20px;line-height:1.8;padding-bottom: 10px; padding-top:2px">

                                                    <b>Route: </b> &nbsp;<code>api/carrier-type/{id}</code> <br>
                                                    <b>Request Type: &nbsp;</b> <code>GET</code> <br>
                                                    <b>Header Required: &nbsp;</b> <code>Access Token</code> <br>
                                                    <b>Body Form Data required: &nbsp;</b> <code>NONE</code> <br>
                                                    <b>Return Type: &nbsp;</b> <code>JSON</code> <br>
                                                    <b>Will Return: &nbsp;</b> <code> Carrier Type (Specific Carrier Type) </code> <small> </small><br>

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
    "id" : "{id}",
}
</pre>

                                                <h4>Response</h4>
                                                <pre>
{
    "data": {
        "id": 1,
        "name": "Air",
        "created_at": "2020-02-28T07:04:28.000000Z",
        "updated_at": "2020-02-28T07:04:28.000000Z"
    }
}
                                                        </pre>

                                            </div><!-- End Carrier type specified resource -->





                                        </div>







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
