<div id="sidebar-wrapper" >

<ul class="sidebar-nav" style="width:100%">
    <li class="hidden">
        <div style="padding-top: 20px;">
            <form action="#" method="GET">
                <div class="row my-5" style="display: flex; justify-content: center;">
                    <div class="col-md-11">
                        <div class="input-group" style="display: flex;flex-direction: row;justify-content: space-evenly;align-items: center;margin: 0px 10px;">
                            <input type="text" class="form-control" placeholder="Search" id="txtSearch"/>

                                <button class="btn btn-primary" type="submit">
                                    <span class="glyphicon glyphicon-search"></span>
                                </button>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </li>
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


            <ul class="endpoint_" style="padding-right: 0px"><!-- Available Api -->
                <li>
                    <a  href="#shipment_central_request"
                        data-toggle="collapse"
                        data-target="#shipment-central-request-endpoint"
                        aria-expanded="false"
                        aria-controls="shipment-central-request-endpoint"
                    >Shipment Central</a>

                    <div id="shipment-central-request-endpoint" class="collapse" >
                        <ul class="endpoint_" style="padding: 0px;margin-left: 10px;">
                            <li>
                                <a href="#available-client"
                                   data-toggle="collapse"
                                   data-target="#available-client-endpoint"
                                   aria-expanded="false"
                                   aria-controls="available-client-endpoint"
                                   class="active"
                                >
                                    {{ EndPointHelper::endPoint('Available Client Api') }}
                                </a>

                                <ul class="endpoint_ collapse" id="available-client-endpoint" style="padding: 0px;margin-left: 10px; "><!-- Available Api for client -->

                                    <li>
                                        <a href="#get_access_token" >
                                            <span class="badge-primary">POST</span> {{ EndPointHelper::get('Access Token') }}
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#get_shipment_by_po" >
                                            <span class="badge-success">GET</span> {{ EndPointHelper::get('Shipment by PO') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#get_shipment_status">
                                            <span class="badge-success">GET</span> {{ EndPointHelper::get('Shipment Status') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#get-all-customer-shipment-info">
                                            <span class="badge-success">GET</span> {{ EndPointHelper::getAll('Customer Shipment Info') }}
                                        </a>
                                    </li>


                                    <li>
                                        <a href="#download-shipment-documents">
                                            <span class="badge-success">GET</span>  {{ EndPointHelper::endPoint('Download Shipment Documents') }}
                                        </a>
                                    </li>


                                    <!-- card -->
                                    <ul class="endpoint_" style="padding: 0px;margin-left: 10px;">
                                        <li>
                                            <a href="#card" data-toggle="collapse" data-target="#card-endpoint" aria-expanded="false" aria-controls="card-endpoint">
                                                {{ EndPointHelper::endPoint('Card') }}
                                            </a>
                                            <ul class="endpoint_ collapse" id="card-endpoint" style="padding: 0px;margin-left: 10px;">
                                                <li>
                                                    <a href="#card-list"><span class="badge-success">GET</span> {{ EndPointHelper::getAll('Card') }} </a>
                                                </li>
                                                <li>
                                                    <a href="#card-specific"><span class="badge-success">GET</span> {{ EndPointHelper::get('Card') }}</a>
                                                </li>
                                                <li>
                                                    <a href="#card-update"><span class="badge-info">PUT</span> {{ EndPointHelper::update('Card') }}</a>
                                                </li>
                                                <li>
                                                    <a href="#card-destroy"><span class="badge-danger">DELETE</span> {{ EndPointHelper::delete('Card') }}</a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                    <!-- end card -->


                                    <ul class="endpoint_" style="padding: 0px;margin-left: 10px;">
                                        <li>
                                            <a href="#custom-customer" data-toggle="collapse" data-target="#custom-customer-endpoint" aria-expanded="false" aria-controls="custom-customer-endpoint">
                                                {{ EndPointHelper::endPoint('Custom Customer') }}
                                            </a>
                                            <ul class="endpoint_ collapse" id="custom-customer-endpoint" style="padding: 0px;margin-left: 10px;">
                                                <li>
                                                    <a href="#custom-customer-list">
                                                        <span class="badge-success">GET</span> {{ EndPointHelper::getAll('Custom Customer') }}
                                                    </a>
                                                </li>
                                                <li>

                                                    <a href="#custom-customer-specified">
                                                        <span class="badge-success">GET</span> {{ EndPointHelper::get('Custom Customer') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#custom-customer-supplier">
                                                        <span class="badge-success">GET</span> {{ EndPointHelper::get('Customer Supplier') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#custom-customer-buyer">
                                                        <span class="badge-success">GET</span> {{ EndPointHelper::get('Customer Buyer') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>



                                    <ul class="endpoint_ hidden" style="padding: 0px;margin-left: 10px;">
                                        <li>
                                            <a href="#customer"
                                               data-toggle="collapse"
                                               data-target="#customer-endpoint"
                                               aria-expanded="false"
                                               aria-controls="customer-endpoint"
                                            >
                                                {{ EndPointHelper::endPoint('Customer') }}
                                            </a>
                                            <ul class="endpoint_ collapse" id="customer-endpoint" style="padding: 0px;margin-left: 10px;">
                                                <li>
                                                    <a href="#customer-list">
                                                        <span class="badge-success">GET</span> {{ EndPointHelper::getAll('Customer') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#customer-specified ">
                                                        <span class="badge-success">GET</span> {{ EndPointHelper::get('Customer') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>



                                    <!-- incoterm -->
                                    <ul class="endpoint_" style="padding: 0px;margin-left: 10px;">
                                        <li>
                                            <a href="#incoterm"
                                               data-toggle="collapse"
                                               data-target="#incoterm-endpoint"
                                               aria-expanded="false"
                                               aria-controls="incoterm-endpoint"
                                            >
                                                {{ EndPointHelper::endPoint('Incoterm') }}
                                            </a>
                                            <ul class="endpoint_ collapse" id="incoterm-endpoint">
                                                <li>
                                                    <a href="#incoterm-list ">
                                                        <span class="badge-success">GET</span> {{ EndPointHelper::getAll('Incoterm') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#incoterm-specified ">
                                                        <span class="badge-success">GET</span> {{ EndPointHelper::get('Incoterm') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                    <!-- end incoterm -->


                                    <!-- invoice -->
                                    <ul class="endpoint_" style="padding: 0px;margin-left: 10px;">
                                        <li>
                                            <a href="#invoice"
                                               data-toggle="collapse"
                                               data-target="#invoice-endpoint"
                                               aria-expanded="false"
                                               aria-controls="invoice-endpoint"
                                            >
                                                {{ EndPointHelper::endPoint('Invoice') }}
                                            </a>
                                            <ul class="endpoint_ collapse" id="invoice-endpoint" style="padding: 0px;margin-left: 10px;">

                                                <li>
                                                    <a href="#invoice-list">
                                                        <span class="badge-success">GET</span> {{ EndPointHelper::get('Quickbook Invoices') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#invoice-specified">
                                                        <span class="badge-success">GET</span> {{ EndPointHelper::get('Quickbook Invoice') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#invoice-download">
                                                        <span class="badge-success">GET</span> {{ EndPointHelper::get('Download') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#invoice-total-due">
                                                        <span class="badge-success">GET</span> {{ EndPointHelper::getAll('Total Due') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                    <!-- end invoice -->

                                    <!-- Milestones -->
                                    <ul class="endpoint_" style="padding: 0px;margin-left: 10px;">
                                        <li>
                                            <a href="#milestone"
                                               data-toggle="collapse"
                                               data-target="#milestone-endpoint"
                                               aria-expanded="false"
                                               aria-controls="milestone-endpoint"
                                            >
                                                {{ EndPointHelper::endPoint('Milestones') }}
                                            </a>
                                            <ul class="endpoint_ collapse" id="milestone-endpoint" style="padding: 0px;margin-left: 10px;">
                                                <li>
                                                    <a href="#milestone-specified">
                                                        <span class="badge-success">GET</span> {{ EndPointHelper::get('Milestones') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                    <!-- end Milestones -->

                                    <!-- PO Product -->
                                    <ul class="endpoint_" style="padding: 0px; margin-left: 10px;">
                                        <li>
                                            <a href="#central-po-product"
                                               data-toggle="collapse"
                                               data-target="#central-po-product-endpoint"
                                               aria-expanded="false"
                                               aria-controls="central-po-product-endpoint"
                                            >
                                                {{ EndPointHelper::endPoint('PO Product') }}
                                            </a>
                                            <ul class="endpoint_ collapse" id="central-po-product-endpoint" style="padding: 0px;margin-left: 10px;">
                                                <li>
                                                    <a href="#central-get-all-po-product-get-by-customer">

                                                        <span class="badge-success">GET</span>
                                                        {{ EndPointHelper::getAll('Products by Customer') }}

                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#central-get-po-product-by-customer">

                                                        <span class="badge-success">GET</span>
                                                        {{ EndPointHelper::get('Product by Customer ID') }}

                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#create-central-po-product-by-customer">
                                                        <span class="badge-primary">POST</span>
                                                        {{ EndPointHelper::create('Product by Customer') }}
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="#update-central-po-product-by-customer"><span class="badge-info">PUT</span> {{ EndPointHelper::update('By Customer ID') }}</a>
                                                </li>
                                                <li>
                                                    <a href="#delete-central-po-product-by-customer"><span class="badge-danger">DELETE</span> {{ EndPointHelper::delete('By Customer ID') }}</a>
                                                </li>


                                            </ul>
                                        </li>
                                    </ul>
                                    <!-- end Product -->



                                    <!-- PO Purchase Order -->

                                    <ul class="endpoint_" style="padding: 0px; margin-left: 10px;">
                                        <li>
                                            <a href="#central-po-purchase-order"
                                               data-toggle="collapse"
                                               data-target="#central-po-purchase-order-endpoint"
                                               aria-expanded="false"
                                               aria-controls="central-po-purchase-order-endpoint"
                                            >
                                                {{ EndPointHelper::endPoint('PO Purchase Order') }}
                                            </a>
                                            <ul class="endpoint_ collapse" id="central-po-purchase-order-endpoint">
                                                <li>
                                                    <a href="#get-central-po-purchase-order-by-id">
                                                        <span class="badge-success">GET</span>
                                                        {{ EndPointHelper::get('Purchase Order by ID') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#create-po-purchase-order-sd">
                                                        <span class="badge-success">GET</span>
                                                        {{ EndPointHelper::create('Purchase Order') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                    <!-- end PO Purchase Order -->

                                    <!-- PO Purchase Order Main -->
                                    <ul class="endpoint_" style="padding: 0px; margin-left: 10px;">
                                        <li>
                                            <a href="#central-po-purchase-order-main"
                                               data-toggle="collapse"
                                               data-target="#central-po-purchase-order-main-endpoint"
                                               aria-expanded="false"
                                               aria-controls="central-po-purchase-order-main-endpoint"
                                            >
                                                {{ EndPointHelper::endPoint('PO Purchase Order Main') }}
                                            </a>
                                            <ul class="endpoint_ collapse" id="central-po-purchase-order-main-endpoint" style="padding: 0px; margin-left: 10px;">
                                                <li>
                                                    <a href="#gcp-purchase-order-main-by-customer-id">
                                                        <span class="badge-success">GET</span>
                                                        {{ EndPointHelper::get('Purchase Order by Customer ID') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#gcp-purchase-order-main-product">
                                                        <span class="badge-success">GET</span>
                                                        {{ EndPointHelper::get('Purchase Order by Product') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#get-purchase-order-by-shipment-id">
                                                        <span class="badge-success">GET</span>
                                                        {{ EndPointHelper::get('Purchase Order by Shipment ID') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#gcp-shipment-distribution-by-po-number">
                                                        <span class="badge-success">GET</span>
                                                        {{ EndPointHelper::get('Shipment Distribution by PO Number') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                    <!-- end PO Purchase Order -->

                                    <!-- Purchase Order -->
                                    <ul class="endpoint_" style="padding: 0px; margin-left: 10px;">
                                        <li>
                                            <a href="#purchase-order"
                                               data-toggle="collapse"
                                               data-target="#purchase-order-endpoint"
                                               aria-expanded="false"
                                               aria-controls="purchase-order-endpoint"
                                            >
                                                {{ EndPointHelper::endPoint('Purchase Order') }}
                                            </a>
                                            <ul class="endpoint_ collapse" id="purchase-order-endpoint" style="padding: 0px; margin-left: 10px;">
                                                <li>
                                                    <a href="#purchase-order-list">

                                                        <span class="badge-success">GET</span>
                                                        {{ EndPointHelper::getAll('Purchase Order') }}

                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#purchase-order-specified">

                                                        <span class="badge-success">GET</span>
                                                        {{ EndPointHelper::get('Purchase Order') }}

                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                    <!-- end Purchase Order -->




                                    <!-- Email report schedule version 1 -->
                                    <ul class="endpoint_ " style="padding: 0px; margin-left: 10px;">
                                        <li>
                                            <a href="#email-report-schedule"
                                               data-toggle="collapse"
                                               data-target="#email-report-schedule-endpoint"
                                               aria-expanded="false"
                                               aria-controls="email-report-schedule-endpoint"
                                            >
                                                {{ EndPointHelper::endPoint('Email Report Schedule') }}
                                            </a>
                                            <ul class="endpoint_ collapse" id="email-report-schedule-endpoint" style="padding: 0px; margin-left: 10px;">
                                                <li>
                                                    <a href="#get-email-reporting-by-user-id">
                                                        <span class="badge-success">GET</span>
                                                        {{ EndPointHelper::get('Email Reporting By User ID') }}
                                                   </a>
                                               </li>
                                                <li>
                                                    <a href="#get-all-email-reporting-default-values">
                                                        <span class="badge-success">GET</span>
                                                        {{ EndPointHelper::getAll('Email Reporting Default Values') }}
                                                    </a>
                                                </li>
                                               <li>
                                                   <a href="#create-email-reporting-new">
                                                       <span class="badge-primary">POST</span>
                                                       {{ EndPointHelper::create('Email Reporting') }}
                                                   </a>
                                              </li>
                                              <li>
                                                  <a href="#u-email-reporting">
                                                      <span class="badge-info">PUT</span> {{ EndPointHelper::update('Email Reporting') }}
                                                   </a>
                                               </li>
                                               <li>
                                                   <a href="#d-email-reporting-by-id">
                                                       <span class="badge-danger">DELETE</span>
                                                       {{ EndPointHelper::delete('Email Reporting By ID') }}
                                                   </a>
                                               </li>
                                           </ul>
                                       </li>
                                   </ul>

                                    <!-- Email report schedule version 2 -->
                                    <ul class="endpoint_ " style="padding: 0px; margin-left: 10px;">
                                        <li>
                                            <a href="#email-report-schedule-v2"
                                               data-toggle="collapse"
                                               data-target="#email-report-schedule-v2-endpoint"
                                               aria-expanded="false"
                                               aria-controls="email-report-schedule-v2-endpoint"
                                            >
                                                {{ EndPointHelper::endPoint('Email Report Schedule V2') }}
                                            </a>
                                            <ul class="endpoint_ collapse" id="email-report-schedule-v2-endpoint" style="padding: 0px; margin-left: 10px;">
                                                <li>
                                                    <a href="#display-shipment-default-column">
                                                        <span class="badge-success">GET</span>
                                                              Display Shipment Default Column
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#get-email-report-schedule-details">
                                                        <span class="badge-success">GET</span>
                                                        {{ EndPointHelper::get('Email Report Schedule Details') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#update-status">
                                                        <span class="badge-info">PUT</span>
                                                        {{ EndPointHelper::update('Status') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#delete-email-report-schedule">
                                                        <span class="badge-danger">DELETE</span>
                                                        {{ EndPointHelper::delete('Email Report Schedule') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#send-shipment-report-via-email">
                                                        <span class="badge-primary">POST</span>
                                                        Send Shipment Report Via Email
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#download-shipment-report-excel">
                                                        <span class="badge-success">GET</span>
                                                        Download Shipment Report Excel
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#create-new-email-report-schedule">
                                                        <span class="badge-primary">POST</span>
                                                        {{ EndPointHelper::create('New Email Report Schedule') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#edit-email-report-schedule">
                                                        <span class="badge-success">GET</span>
                                                        {{ EndPointHelper::edit('Email Report Schedule') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>



                                   <!-- Search -->
                                   <ul class="endpoint_" style="padding: 0px; margin-left: 10px;">
                                       <li>
                                           <a href="#search"
                                              data-toggle="collapse"
                                              data-target="#search-endpoint"
                                              aria-expanded="false"
                                              aria-controls="search-endpoint"
                                           >
                                               {{ EndPointHelper::endPoint('Search') }}

                                            </a>
                                            <ul class="endpoint_ collapse" id="search-endpoint" style="padding: 0px; margin-left: 10px;">
                                                <li>
                                                    <a href="#search-shipment">

                                                        <span class="badge-primary">POST</span>

                                                        Shipment Search</a>
                                                </li>
                                                <li>
                                                    <a href="#search-specified">
                                                        <span class="badge-primary">POST</span>

                                                        {{ EndPointHelper::get('Search') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#search-customer">

                                                        <span class="badge-primary">POST</span>

                                                        Customer Search</a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                    <!-- end Search -->

                                    <!-- Shipment -->
                                    <ul class="endpoint_" style="padding: 0px; margin-left: 10px;">
                                        <li>
                                            <a href="#shipment"
                                               data-toggle="collapse"
                                               data-target="#shipment-endpoint"
                                               aria-expanded="false"
                                               aria-controls="shipment-endpoint"
                                            >
                                                {{ EndPointHelper::endPoint('Shipment') }}
                                            </a>
                                            <ul class="endpoint_ collapse" id="shipment-endpoint" style="padding: 0px; margin-left: 10px;">
                                                {{--<li>--}}
                                                {{--<a href="#shipment-snooze">Snooze shipment</a>--}}
                                                {{--</li>--}}
                                                <li>
                                                    <a href="#get-shipment-po">

                                                        <span class="badge-success">GET</span>
                                                        {{ EndPointHelper::get('Po Shipment') }}

                                                    </a>
                                                </li>
                                                {{--<li>--}}
                                                {{--<a href="#shipment-schedule-option">Schedule option</a>--}}
                                                {{--</li>--}}
                                                {{--<li>--}}
                                                {{--<a href="#shipment-unsnooze">Unsnooze shipment</a>--}}
                                                {{--</li>--}}
                                                {{--<li>--}}
                                                {{--<a href="#shipment-select-schedule-app">Select schedule app</a>--}}
                                                {{--</li>--}}
                                                {{--<li>--}}
                                                {{--<a href="#shipment-req-new-sched">Request New Schedule</a>--}}
                                                {{--</li>--}}
                                                <li>
                                                    <a href="#shipment-event">

                                                        <span class="badge-success">GET</span>

                                                        {{ EndPointHelper::get('Shipment Events') }}
                                                    </a>
                                                </li>

                                                {{--<li>--}}
                                                {{--<a href="#shipment-specified">Specified resource</a>--}}
                                                {{--</li>--}}

                                                {{--<li>--}}
                                                {{--<a href="#shipment-transit">Transit</a>--}}
                                                {{--</li>--}}
                                                {{--<li>--}}
                                                {{--<a href="#shipment-expired">Expired</a>--}}
                                                {{--</li>--}}

                                                <li>
                                                    <a href="#shipment-get-shipment">
                                                        <span class="badge-success">GET</span>

                                                        {{ EndPointHelper::get('Shipment') }}
                                                    </a>
                                                </li>

                                                {{--<li>--}}
                                                {{--<a href="#shipment-pending">Pending</a>--}}
                                                {{--</li>--}}

                                                {{--<li>--}}
                                                {{--<a href="#shipment-completed">Completed</a>--}}
                                                {{--</li>--}}
                                                {{--<li>--}}
                                                {{--<a href="#shipment-search-customer-order">Search customer order</a>--}}
                                                {{--</li>--}}
                                                <li class="hidden">
                                                    <a href="#shipment-specified-v2">
                                                        <span class="badge-success">GET</span>

                                                        {{ EndPointHelper::get('Shipment V2') }}

                                                    </a>
                                                </li>

                                                <li class="hidden">
                                                    <a href="#shipment-po-v2">


                                                        <span class="badge-success">GET</span> {{ EndPointHelper::get('Shipment BY PO V2') }}
                                                    </a>
                                                </li>

                                                {{--<li>--}}
                                                {{--<a href="#pending-quote-shipments">Pending quote shipments</a>--}}
                                                {{--</li>--}}

                                                {{--<li>--}}
                                                {{--<a href="#snooze-shipment-v2">Snooze Shipments v2</a>--}}
                                                {{--</li>--}}
                                                {{--<li>--}}
                                                {{--<a href="#shipment-transit-completed">Transit/Completed</a>--}}
                                                {{--</li>--}}

                                            </ul>
                                        </li>
                                    </ul>
                                    <!-- end Shipment -->

                                    <ul class="endpoint_" style="padding: 0px; margin-left: 10px;"><!-- Tracking Shipment -->
                                        <li>
                                            <a href="#tracking-shipment"
                                               data-toggle="collapse"
                                               data-target="#tracking-shipment-endpoint"
                                               aria-expanded="false"
                                               aria-controls="tracking-shipment-endpoint">

                                                {{ EndPointHelper::endPoint('Tracking Shipment') }}
                                            </a>
                                            <ul class="endpoint_ collapse" id="tracking-shipment-endpoint" style="padding: 0px; margin-left: 10px;">
                                                <li>
                                                    <a href="#tracking-shipment-create">

                                                        <span class="badge-primary">POST</span>
                                                        {{ EndPointHelper::create('Tracking Shipment') }}

                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#tracking-shipment-destroy">

                                                        <span class="badge-danger">DELETE</span> {{ EndPointHelper::delete('Tracking Shipment') }}

                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul><!-- end Tracking Shipment -->

                                </ul><!-- end Available Api for Client  -->
                            </li>

                        </ul>

                        <!-- carrier -->
                        <ul class="endpoint_ hidden" style="padding: 0px; margin-left: 10px;">
                            <li>
                                <a href="#carrier" data-toggle="collapse" data-target="#carrier-endpoint" aria-expanded="false" aria-controls="carrier-endpoint">
                                    {{ EndPointHelper::endPoint('Carrier') }}
                                </a>
                                <ul class="endpoint_ collapse" id="carrier-endpoint" style="padding: 0px; margin-left: 10px;">
                                    <li>
                                        <a href="#carrier-list"><span class="badge-success">GET</span> {{ EndPointHelper::getAll('Carrier') }}</a>
                                    </li>
                                    <li>
                                        <a href="#carrier-specific"><span class="badge-success">GET</span> {{ EndPointHelper::get('Carrier') }}</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                        <!-- end carrier -->

                        <!-- carrier type-->
                        <ul class="endpoint_ hidden">
                            <li>
                                <a href="#carrier-type" data-toggle="collapse" data-target="#carrier-type-endpoint" aria-expanded="false" aria-controls="carrier-type-endpoint">
                                    {{ EndPointHelper::endPoint('Carrier Type') }}
                                </a>
                                <ul class="endpoint_ collapse" id="carrier-type-endpoint">
                                    <li>
                                        <a href="#carrier-type-list"><span class="badge-success">GET</span> {{ EndPointHelper::getAll('Carrier Type') }}</a>
                                    </li>
                                    <li>
                                        <a href="#carrier-type-specified"><span class="badge-success">GET</span> {{ EndPointHelper::get('Carrier Type') }}</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>

                        <ul class="endpoint_ hidden">
                            <li>
                                <a href="#container" data-toggle="collapse" data-target="#container-endpoint" aria-expanded="false" aria-controls="container-endpoint">
                                    {{ EndPointHelper::endPoint('Container') }}
                                </a>
                                <ul class="endpoint_ collapse" id="container-endpoint">
                                    <li>
                                        <a href="#container-list"><span class="badge-success">GET</span> {{ EndPointHelper::getAll('Container') }}</a>
                                    </li>
                                    <li>
                                        <a href="#container-specified"><span class="badge-success">GET</span> {{ EndPointHelper::get('Container') }}</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>

                        <ul class="endpoint_ hidden">
                            <li>
                                <a href="#container-size" data-toggle="collapse" data-target="#container-size-endpoint" aria-expanded="false" aria-controls="container-size-endpoint">
                                    {{ EndPointHelper::endPoint('Container Size') }}
                                </a>
                                <ul class="endpoint_ collapse" id="container-size-endpoint">
                                    <li>
                                        <a href="#container-size-list">
                                            <span class="badge-success">GET</span> {{ EndPointHelper::getAll('Container Size') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#container-size-specified"><span class="badge-success">GET</span> {{ EndPointHelper::get('Container Size') }}</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>

                        <ul class="endpoint_ hidden">
                            <li>
                                <a href="#custom-admin" data-toggle="collapse" data-target="#custom-admin-endpoint" aria-expanded="false" aria-controls="custom-admin-endpoint">
                                    {{ EndPointHelper::endPoint('Custom Admin') }}
                                </a>
                                <ul class="endpoint_ collapse" id="custom-admin-endpoint">
                                    <li>
                                        <a href="#custom-admin-list">
                                            <span class="badge-success">GET</span> {{ EndPointHelper::getAll('Custom Admin') }}
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>

                        <ul class="endpoint_ hidden">
                            <li>
                                <a href="#custom-supplier" data-toggle="collapse" data-target="#custom-supplier-endpoint" aria-expanded="false" aria-controls="custom-supplier-endpoint">
                                    Custom Supplier
                                </a>
                                <ul class="endpoint_ collapse" id="custom-supplier-endpoint">
                                    <li>
                                        <a href="#custom-supplier-specified">Specified resource</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>

                        <ul class="endpoint_ hidden">
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
                                        <a href="#custom-user-customer-details">Customer details</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>

                        <ul class="endpoint_ hidden">
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
                                        <a href="#customer-admin-specified">Specified resource</a>
                                    </li>
                                    <li>
                                        <a href="#customer-admin-destroy">Destroy</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>

                        <!-- eta logs -->
                        <ul class="endpoint_ hidden">
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

                        <!-- Item -->
                        <ul class="endpoint_ hidden">
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
                        <ul class="endpoint_ hidden">
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
                                        <a href="#juliverdevshifl-get-index-terminal" >Get Index Terminal</a>
                                    </li>
                                    <li>
                                        <a href="#juliverdevshifl-get-container" >Get Container</a>
                                    </li>
                                    <li>
                                        <a href="#juliverdevshifl-get-index" >Get Index</a>
                                    </li>
                                    <li>
                                        <a href="#juliverdevshifl-get-graph-terminal" >Get Graph Terminal</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                        <!-- end Juliverdevshifl -->

                        <!-- Profit Monitor -->
                        <ul class="endpoint_ hidden">
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
                                        <a href="#profit-monitor-qbo-companies">QBO Companies</a>
                                    </li>
                                    <li>
                                        <a href="#profit-monitor-sale-representatives">sale representatives</a>
                                    </li>
                                    <li>
                                        <a href="#profit-monitor-total-profit-by-request">Total profit by request</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                        <!-- end Profit Monitor -->

                        <!-- shipment document -->
                        <ul class="endpoint_ hidden">
                            <li>
                                <a href="#shipment-document"
                                   data-toggle="collapse"
                                   data-target="#shipment-document-endpoint"
                                   aria-expanded="false"
                                   aria-controls="shipment-document-endpoint"
                                >
                                    Shipment Documents
                                </a>
                                <ul class="endpoint_ collapse" id="shipment-document-endpoint">
                                    <li>
                                        <a href="#shipment-document-insert">Insert Documents</a>
                                    </li>
                                    <li>
                                        <a href="#shipment-document-get">Get Shipment Document</a>
                                    </li>
                                    <li>
                                        <a href="#shipment-document-delete">Delete Shipment Document</a>
                                    </li>
                                    <li>
                                        <a href="#shipment-document-update">Update Shipment Document</a>
                                    </li>
                                    <li>
                                        <a href="#shipment-document-fetch">Fetch Documents</a>
                                    </li>
                                    <li>
                                        <a href="#shipment-document-multiple-insert" >Insert Multiple Documents</a>
                                    </li>
                                    <li>
                                        <a href="#shipment-document-multiple-submit" >Submit Multiple Documents</a>
                                    </li>
                                    <li>
                                        <a href="#shipment-document-multiple-delete" >Delete Multiple Documents</a>
                                    </li>
                                    <li>
                                        <a href="#shipment-document-update-name-type" >Update Name Type</a>
                                    </li>

                                </ul>
                            </li>
                        </ul>
                        <!-- end shipment document -->

                        <ul class="endpoint_ hidden"><!-- statement -->
                            <li>
                                <a href="#statement"
                                   data-toggle="collapse"
                                   data-target="#statement-endpoint"
                                   aria-expanded="false"
                                   aria-controls="statement-endpoint">
                                    Statement
                                </a>
                                <ul class="endpoint_ collapse" id="statement-endpoint">
                                    <li>
                                        <a href="#statement-list">List resource</a>
                                    </li>
                                </ul>
                            </li>
                        </ul><!-- end statement -->

                        <ul class="endpoint_ hidden"><!-- Supplier -->
                            <li>
                                <a href="#supplier"
                                   data-toggle="collapse"
                                   data-target="#supplier-endpoint"
                                   aria-expanded="false"
                                   aria-controls="supplier-endpoint">
                                    Supplier
                                </a>
                                <ul class="endpoint_ collapse" id="supplier-endpoint">
                                    <li>
                                        <a href="#supplier-list">List resource</a>
                                    </li>
                                    <li>
                                        <a href="#supplier-list-v2">List resource version 2 </a>
                                    </li>
                                    <li>
                                        <a href="#supplier-create">Create resource</a>
                                    </li>
                                    <li>
                                        <a href="#supplier-update">Update resource</a>
                                    </li>
                                    <li>
                                        <a href="#supplier-delete">Delete resource</a>
                                    </li>
                                    <li>
                                        <a href="#supplier-shipment-supplier">shipment supplier</a>
                                    </li>
                                    <li>
                                        <a href="#supplier-vendor">Vendor</a>
                                    </li>
                                </ul>
                            </li>
                        </ul><!-- end Supplier -->


                        <ul class="endpoint_ hidden"><!-- Terminal 49 Shipment -->
                            <li>
                                <a href="#terminal-49-shipment"
                                   data-toggle="collapse"
                                   data-target="#terminal-49-shipment-endpoint"
                                   aria-expanded="false"
                                   aria-controls="terminal-49-shipment-endpoint">
                                    Terminal 149 Shipment
                                </a>
                                <ul class="endpoint_ collapse" id="terminal-49-shipment-endpoint">
                                    <li>
                                        <a href="#get-terminal-49-shipment">Get Terminal 49 Shipement</a>
                                    </li>
                                </ul>
                            </li>
                        </ul><!-- end Terminal 49 Shipment -->

                        <ul class="endpoint_ hidden"><!-- Terminal Region -->
                            <li>
                                <a href="#terminal-region"
                                   data-toggle="collapse"
                                   data-target="#terminal-region-endpoint"
                                   aria-expanded="false"
                                   aria-controls="terminal-region-endpoint">
                                    Terminal Region
                                </a>
                                <ul class="endpoint_ collapse" id="terminal-region-endpoint">
                                    <li>
                                        <a href="#terminal-region-specified">Specified resource</a>
                                    </li>
                                </ul>
                            </li>
                        </ul><!-- end Terminal Region -->


                        <ul class="endpoint_ hidden"><!-- user -->
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




                                    <li>
                                        <a href="#user-update-customer-preference">Update customer preference </a>
                                    </li>
                                    <li>
                                        <a href="#user-forgot-password">Forgot Password</a>
                                    </li>

                                    <li>
                                        <a href="#user-check-forgot-password-code">Check Forgot Password Code</a>
                                    </li>

                                    <li>
                                        <a href="#user-change-password">Change Password</a>
                                    </li>

                                    <li>
                                        <a href="#user-update-notification-token">Update notification token</a>
                                    </li>



                                </ul>
                            </li>
                        </ul><!-- end user -->

                        <ul class="endpoint_ hidden"><!-- Waiting List -->
                            <li>
                                <a href="#waiting-list"
                                   data-toggle="collapse"
                                   data-target="#waiting-list-endpoint"
                                   aria-expanded="false"
                                   aria-controls="waiting-list-endpoint">
                                    Waiting List
                                </a>
                                <ul class="endpoint_ collapse" id="waiting-list-endpoint">
                                    <li>
                                        <a href="#waiting-list-register">Register User</a>
                                    </li>
                                </ul>
                            </li>
                        </ul><!-- end Waiting List -->



                    </div>

                </li>
                <li class="hidden">
                    <a href="#po-warehouse-inventory"
                       data-toggle="collapse"
                       data-target="#po-warehouse-inventory-endpoint"
                       aria-expanded="false"
                       aria-controls="po-warehouse-inventory-endpoint"
                    >PO/Warehouse/Inventory</a>



                    <div id="po-warehouse-inventory-endpoint" class="collapse">



                        <ul class="endpoint_" style="padding: 0px; margin-left: 10px;">
                            <li>
                                <a href="#amazon"
                                   data-toggle="collapse"
                                   data-target="#amazon-endpoint"
                                   aria-expanded="false"
                                   aria-controls="amazon-endpoint"
                                   class="active"
                                >
                                    Amazon
                                </a>

                                <ul class="endpoint_ collapse" id="amazon-endpoint" style="padding: 0px; margin-left: 10px;"><!-- Amazon Store  -->

                                    <li>
                                        <a href="#amazon-create" >
                                            Create Amazon
                                        </a>
                                    </li>

                                </ul><!-- end Amazon Store  -->
                            </li>
                        </ul>






                        <ul class="endpoint_" style="padding: 0px; margin-left: 10px;">
                            <li>
                                <a href="#po-category"
                                   data-toggle="collapse"
                                   data-target="#po-category-endpoint"
                                   aria-expanded="false"
                                   aria-controls="po-category-endpoint"
                                   class="active"
                                >
                                    Category
                                </a>

                                <ul class="endpoint_ collapse" id="po-category-endpoint" style="padding: 0px; margin-left: 10px;"><!-- Category -->

                                    <li>
                                        <a href="#po-get-all-category" >
                                            Get All Category
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-get-category">
                                            Get Category
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-create-category" >
                                            Create Category
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-update-category" >
                                            Update Category
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-delete-category" >
                                            Delete Category
                                        </a>
                                    </li>
                                </ul><!-- end Category  -->
                            </li>
                        </ul>






                        <ul class="endpoint_" style="padding: 0px; margin-left: 10px;">
                            <li>
                                <a href="#po-country-state-city"
                                   data-toggle="collapse"
                                   data-target="#po-country-state-city-endpoint"
                                   aria-expanded="false"
                                   aria-controls="po-country-state-city-endpoint"
                                   class="active"
                                >
                                    Country State City
                                </a>

                                <ul class="endpoint_ collapse" id="po-country-state-city-endpoint" style="padding: 0px; margin-left: 10px;"><!-- Country State City -->

                                    <li>
                                        <a href="#po-get-all-country" >
                                            Get All Country
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-country-flag">
                                            Get Country with Flag
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-country-state" >
                                            Get Country State
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-state-city" >
                                            Get State Cities
                                        </a>
                                    </li>
                                </ul><!-- end Country State City  -->
                            </li>
                        </ul>





                        <ul class="endpoint_" style="padding: 0px; margin-left: 10px;">
                            <li>
                                <a href="#po-inbound"
                                   data-toggle="collapse"
                                   data-target="#po-inbound-endpoint"
                                   aria-expanded="false"
                                   aria-controls="po-inbound-endpoint"
                                   class="active"
                                >
                                    Inbound
                                </a>

                                <ul class="endpoint_ collapse" id="po-inbound-endpoint" style="padding: 0px; margin-left: 10px;"><!-- Inbound -->

                                    <li>
                                        <a href="#po-get-all-inbound" >
                                            Get All Inbound
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-get-inbound">
                                            Get Inbound
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-create-inbound" >
                                            Create Inbound
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-update-inbound" >
                                            Update Inbound
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-cancel-inbound" >
                                            Cancel Inbound
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-delete-inbound" >
                                            Delete Inbound
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-truck-arrived" >
                                            Truck Arrived
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-recieve-product" >
                                            Recieve Product
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-download-document" >
                                            Download Document
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-upload-document" >
                                            Upload Document
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-new-storeable-unit" >
                                            New Storeable Unit
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-place-storable-unit" >
                                            Place storable Unit
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-get-pending-inbound" >
                                            Get Pending Inbound
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-get-floor-inbound" >
                                            Get Floor Inbound
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-get-completed-inbound" >
                                            Get Completed Inbound
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-cancelled-inbound" >
                                            Get Cancelled Inbound
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-recieve-multiple-product" >
                                            Recieve Multiple Products
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-print-order" >
                                            Print Order
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-upload-multiple-document" >
                                            Upload Multiple Documents
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-mail-customer" >
                                            Mail Customer
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-edit-storable-unit" >
                                            Edit Storable Unit
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-truck-arrived-confirmation" >
                                            Truck Arrived Confirmation
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-cancel-multiple-inbound" >
                                            Cancel Multiple Inbound
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-place-multiple-storable-unit" >
                                            Place Multiple Storable Units
                                        </a>
                                    </li>
                                </ul><!-- end Inbound  -->
                            </li>
                        </ul>




                        <ul class="endpoint_" style="padding: 0px; margin-left: 10px;">
                            <li>
                                <a href="#po-inventory"
                                   data-toggle="collapse"
                                   data-target="#po-inventory-endpoint"
                                   aria-expanded="false"
                                   aria-controls="po-inventory-endpoint"
                                   class="active"
                                >
                                    Inventory
                                </a>

                                <ul class="endpoint_ collapse" id="po-inventory-endpoint" style="padding: 0px; margin-left: 10px;"><!-- Inventory -->

                                    <li>
                                        <a href="#po-get-all-inventory" >
                                            Get All Inventory
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-get-inventory">
                                            Get Inventory
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-create-inventory" >
                                            Create Inventory
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-update-inventory" >
                                            Update Inventory
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-delete-inventory" >
                                            Delete Inventory
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-storable-unit-inventory" >
                                            Get storable units
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#po-add-storable-unit-inventory" >
                                            Add storable units
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#po-active-storable-unit-inventory" >
                                            Get active storable units
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#po-history-storable-unit-inventory" >
                                            Get history storable units
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#po-storable-unit-inventory" >
                                            Get storable units
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#po-inventory-with-pagination" >
                                            Get Inventories With Pagination
                                        </a>
                                    </li>

                                </ul><!-- end Inventory  -->
                            </li>
                        </ul>





                        <ul class="endpoint_" style="padding: 0px; margin-left: 10px;">
                            <li>
                                <a href="#po-location"
                                   data-toggle="collapse"
                                   data-target="#po-location-endpoint"
                                   aria-expanded="false"
                                   aria-controls="po-location-endpoint"
                                   class="active"
                                >
                                    Location
                                </a>

                                <ul class="endpoint_ collapse" id="po-location-endpoint" style="padding: 0px; margin-left: 10px;"><!-- Location -->

                                    <li>
                                        <a href="#po-get-storage-location" >
                                            Get Storage Location
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-get-gate-location">
                                            Get Gates Location
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-get-all-location" >
                                            Get All Location
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-get-empy-location" >
                                            Get Empty Locarion
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-get-place-inbound-location" >
                                            Get place inbound location
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-get-all-location" >
                                            Get All Storage Location
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#po-get-empty-storage-location" >
                                            Get Empty Storage Location
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#po-get-filled-storage-location" >
                                            Get Filled Storage Location
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#po-get-all-gates-location" >
                                            Get All Gates Location
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#po-get-empty-gates-location" >
                                            Get Empty Gates Location
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#po-get-filled-gates-location" >
                                            Get Filled Gates Location
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-get-list-location" >
                                            Get List Location
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-get-specific-location" >
                                            Get Specific Location
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-create-location" >
                                            Create Location
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-update-location" >
                                            Update Location
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-delete-location" >
                                            Delete Location
                                        </a>
                                    </li>

                                </ul><!-- end Location -->
                            </li>
                        </ul>









                        <ul class="endpoint_" style="padding: 0px; margin-left: 10px;">
                            <li>
                                <a href="#po-outbound"
                                   data-toggle="collapse"
                                   data-target="#po-outbound-endpoint"
                                   aria-expanded="false"
                                   aria-controls="po-outbound-endpoint"
                                   class="active"
                                >
                                    Outbound
                                </a>

                                <ul class="endpoint_ collapse" id="po-outbound-endpoint" style="padding: 0px; margin-left: 10px;"><!-- Outbound -->

                                    <li>
                                        <a href="#po-get-all-outbound" >
                                            Get All Outbound
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-get-outbound">
                                            Get Outbound
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-create-outbound" >
                                            Create Outbound
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-update-outbound" >
                                            Update Outbound
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-cancel-outbound" >
                                            Cancel Outbound
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-pick-product-outbound" >
                                            Pick product
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#po-delete-outbound" >
                                            Delete Outbound
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#po-new-storable-unit-outbound" >
                                            New Storable Unit
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#po-download-document-outbound" >
                                            Download Document
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#po-upload-document-outbound" >
                                            Upload Document
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#po-load-storable-unit-outbound" >
                                            Load Storable Unit
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-get-pending-outbound" >
                                            Get Pending
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-get-floor" >
                                            Get Floor
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-get-completed-outbound" >
                                            Get Completed
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-get-cancelled-outbound" >
                                            Get Cancelled
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-print-order-outbound" >
                                            Print Order
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-pick-multiple-product-outbound" >
                                            Pick Multiple Products
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-upload-multiple-document-outbound" >
                                            Upload Multiple Documents
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-pick-outbound-product" >
                                            Pick Outbound Product
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-pick-multiple-outbound-product" >
                                            Pick Multiple Outbound Product
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-pick-storable-unit-outbound" >
                                            Pick Outbound Storable Unit
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-storable-unit-outbound-product" >
                                            Storable Unit Pick Outbound Products
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-location-pick-outbound-product" >
                                            Locations Pick Outbound Products
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-cancel-multiple-outbound" >
                                            Cancel Multiple Outbound
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-edit-storable-unit-outbound" >
                                            Edit Storable Unit
                                        </a>
                                    </li>

                                </ul><!-- end Outbound  -->
                            </li>
                        </ul>










                        <ul class="endpoint_" style="padding: 0px; margin-left: 10px;">
                            <li>
                                <a href="#po-product"
                                   data-toggle="collapse"
                                   data-target="#po-product-endpoint"
                                   aria-expanded="false"
                                   aria-controls="po-product-endpoint"
                                   class="active"
                                >
                                    Product
                                </a>

                                <ul class="endpoint_ collapse" id="po-product-endpoint" style="padding: 0px; margin-left: 10px;"><!--  Product -->

                                    <li>
                                        <a href="#po-get-all-product" >
                                            Get All Product
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-get-product">
                                            Get Product
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-create-product" >
                                            Create Product
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-update-product" >
                                            Update Product
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-delete-product" >
                                            Delete Product
                                        </a>
                                    </li>
                                </ul><!-- end Product -->
                            </li>
                        </ul>







                        <ul class="endpoint_" style="padding: 0px; margin-left: 10px;">
                            <li>
                                <a href="#po-purchase-order"
                                   data-toggle="collapse"
                                   data-target="#po-purchase-order-endpoint"
                                   aria-expanded="false"
                                   aria-controls="po-purchase-order-endpoint"
                                   class="active"
                                >
                                    Purchase Order
                                </a>

                                <ul class="endpoint_ collapse" id="po-purchase-order-endpoint" style="padding: 0px; margin-left: 10px;"><!-- Purchase Order -->

                                    <li>
                                        <a href="#po-download-multiple-purchase-order" >
                                            Download Multiple
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-get-all-purchase-order">
                                            Get All Purchase Order
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-get-all-purchase-order-product" >
                                            Get All Product
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-get-purchase-order" >
                                            Get Purchase Order
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-get-list-supplier-email-purchase-order" >
                                            Get list of supplier email
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-send-email-purchase-order" >
                                            Send Email
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#po-create-purchase-order" >
                                            Create Purchase Order
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#po-update-purchase-order" >
                                            Update Purchase Order
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#po-delete-purchase-order" >
                                            Delete Purchase Order
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#po-download-purchase-order" >
                                            Download
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#po-delete-multiple-purchase-order" >
                                            Delete Multiple Purchase Order
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-get-list-pending-purchase-order" >
                                            Get List of Pending
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-get-list-shipped-purchase-order" >
                                            Get List of Shipped
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-get-list-pending-purchase-order-v3" >
                                            Get List of Pending Version 3
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-get-list-shipped-purchase-order-v3" >
                                            Get List of Shipped Purchase Orders Version 3
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-get-all-purchase-order-v3" >
                                            Get All Purchase Order Version 3
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-search-purchase-order" >
                                            Search Purchase Order
                                        </a>
                                    </li>

                                </ul><!-- end Purchase Order  -->
                            </li>
                        </ul>


                        <ul class="endpoint_" style="padding: 0px; margin-left: 10px;">
                            <li>
                                <a href="#po-purchase-order-main"
                                   data-toggle="collapse"
                                   data-target="#po-purchase-order-main-endpoint"
                                   aria-expanded="false"
                                   aria-controls="po-purchase-order-main-endpoint"
                                   class="active"
                                >
                                    Purchase Order Main
                                </a>

                                <ul class="endpoint_ collapse" id="po-purchase-order-main-endpoint" style="padding: 0px; margin-left: 10px;"><!--  Purchase Order Main -->

                                    <li>
                                        <a href="#po-update-product-purchase-order-main" >
                                            Update Purchase
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-get-list-purchase-order-main">
                                            Get List
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-get-list-product-purchase-order-main" >
                                            Get List Products
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-get-shipment-purchase-order-main" >
                                            Get Shipment Purchase Orders
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-shipment-distribution-purchase-order-main" >
                                            Get Shipment Distribution
                                        </a>
                                    </li>
                                </ul><!-- end  Purchase Order Main  -->
                            </li>
                        </ul>


                        <ul class="endpoint_" style="padding: 0px; margin-left: 10px;">
                            <li>
                                <a href="#po-warehouse"
                                   data-toggle="collapse"
                                   data-target="#po-warehouse-endpoint"
                                   aria-expanded="false"
                                   aria-controls="po-warehouse-endpoint"
                                   class="active"
                                >
                                    Warehouse
                                </a>

                                <ul class="endpoint_ collapse" id="po-warehouse-endpoint" style="padding: 0px; margin-left: 10px;"><!-- Warehouse -->

                                    <li>
                                        <a href="#po-get-all-warehouse" >
                                            Get All Warehouse
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-get-product-warehouse">
                                            Get Warehouse products
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-get-warehouse" >
                                            Get Warehouse
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-create-warehouse" >
                                            Create Warehouse
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#po-update-warehouse" >
                                            Update Warehouse
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#po-delete-warehouse" >
                                            Delete Warehouse
                                        </a>
                                    </li>
                                </ul><!-- end Warehouse  -->
                            </li>
                        </ul>



                    </div>
                </li>

                <li class="hidden">
                    <a href="">Trucking</a>
                </li>

                <li class="hidden">
                    <a href="">Capital</a>
                </li>
                <li class="hidden">
                    <a href="">Chat</a>
                </li>


            </ul>

        </div>

    </li>

    <li>
        <a href="#how_to_create_client_credentials"  style="white-space: nowrap;">How to create Client Credentials?</a>
    </li>

    <li>
        <a href="#support-shifl">Support</a>
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


    <div id="resizer" style="cursor: ew-resize; float: right;  height: 100%;  width: 8px; background-color: transparent; position:relative"></div>



</div>
<!-- /#sidebar-wrapper -->


