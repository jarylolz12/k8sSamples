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

        ul.endpoint_ li a{
            white-space: nowrap;
        }
        ul.endpoint_ li a.active{
            text-decoration: none;
            color: #111;
            background: rgba(0, 0, 0, 0.05);
        }

        .table-bordered>tbody>tr>td,
        .table-bordered>thead>tr>th{
            padding: 8px;
            line-height: 1.42857143;
            text-align: left;
            border-bottom-width: 2px;
        }
        .badge-danger{
            background-color: #f2dede;
            border-color: #d43f3a;
            width: auto;
            border-radius: 20px;
            padding: 1px 5px;
        }
        .badge-success{
            background-color: #dff0d8;
            border-color: #4cae4c;
            width: auto;
            border-radius: 20px;
            padding: 1px 5px;
        }
        .badge-info{
            background-color: #d9edf7;
            border-color: #269abc;
            width: auto;
            border-radius: 20px;
            padding: 1px 5px;
        }
        .badge-primary{
            color:#fff;
            background-color: #337ab7;
            border-color: #269abc;
            width: auto;
            border-radius: 20px;
            padding: 1px 5px;
        }

        .api-route{
            width: 20%;
            position: absolute;
            top:0;
            right: 0;
            background-color: whitesmoke;
            color: #000;
            padding: 20px;
            box-shadow: 1px 1px 3px #ccc;
            height: auto;
            border-radius: 6px;
        }
        .api-route > div.flex-port{
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-direction: row;
            width: auto;
            padding: 10px;

        }

        .api-route > div.flex-port > div.copy-api{
             background-color: #e3e2e2;
             cursor: pointer;
             padding: 5px 10px;
             border-radius: 6px;
         }
        .api-route > div.flex-port > div.copy-api:hover{

            background-color: #d3d3d3;
            box-shadow: 0px 0px 2px #c3bcbc;
        }

        .api-route > div.api_{
            background-color: #e3e2e2;
            padding: 10px;
            overflow-wrap: break-word;
        }

        .api-route > div.flex-port > p{
            margin: 0 !important;
            font-weight: bold;
        }
        .noselect {
            -webkit-touch-callout: none; /* iOS Safari */
            -webkit-user-select: none; /* Safari */
            -khtml-user-select: none; /* Konqueror HTML */
            -moz-user-select: none; /* Old versions of Firefox */
            -ms-user-select: none; /* Internet Explorer/Edge */
            user-select: none; /* Non-prefixed version, currently
                                  supported by Chrome, Edge, Opera and Firefox */
        }
 
        pre{
            background-color: #292929;
            color: white;
        }
        span.slctrl-attr{
            color: #f92672;
        }

        .slctrl-string{
            color: #ec7600;
        }
        .slctrl-literal{
            color: #93c763;
        }
        .slctrl-number{
            color: #ffcd22;
        }

        /* Added width on table bordered*/
        .col-sm-12 div table.table-bordered {
            width: 50%;
        }
        tr.collapse > td > div > table.table-bordered{
            width: 100%;
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
    @include('api.documentation.v2.sidebar.index')

    <div style="cursor: ew-resize;"></div>


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
                                        <h2 class="page-header">Endpoints &amp; Requests </h2>

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




@include('api.documentation.v2.endpoints.shipment-central.shipment-central')

@include('api.documentation.v2.endpoints.shipment-central.client.available_api_client')


                                        <!-- not working @yield('content-endpoints') -->


@include('api.documentation.v2.endpoints.shipment-central.card')
@include('api.documentation.v2.endpoints.shipment-central.carrier')
@include('api.documentation.v2.endpoints.shipment-central.carrier_type')
<!-- client shipment -->
@include('api.documentation.v2.endpoints.shipment-central.container')
@include('api.documentation.v2.endpoints.shipment-central.container_size')
@include('api.documentation.v2.endpoints.shipment-central.custom_admin')
@include('api.documentation.v2.endpoints.shipment-central.custom_customer')
@include('api.documentation.v2.endpoints.shipment-central.custom_supplier')
@include('api.documentation.v2.endpoints.shipment-central.custom_user')
@include('api.documentation.v2.endpoints.shipment-central.customer')
@include('api.documentation.v2.endpoints.shipment-central.customer_admin')
@include('api.documentation.v2.endpoints.shipment-central.email_report_schedule')
@include('api.documentation.v2.endpoints.shipment-central.email_report_schedule_v2')
@include('api.documentation.v2.endpoints.shipment-central.eta_logs')
@include('api.documentation.v2.endpoints.shipment-central.incoterm')
@include('api.documentation.v2.endpoints.shipment-central.invoice')
@include('api.documentation.v2.endpoints.shipment-central.item')
@include('api.documentation.v2.endpoints.shipment-central.juliverdevshifl')
@include('api.documentation.v2.endpoints.shipment-central.milestone')
@include('api.documentation.v2.endpoints.shipment-central.profit_monitor')
@include('api.documentation.v2.endpoints.shipment-central.central-po-product')
@include('api.documentation.v2.endpoints.shipment-central.central-po-purchase-order')
@include('api.documentation.v2.endpoints.shipment-central.central-po-purchase-order-main')
@include('api.documentation.v2.endpoints.shipment-central.purchase_order')
@include('api.documentation.v2.endpoints.shipment-central.search')
@include('api.documentation.v2.endpoints.shipment-central.shipment')
@include('api.documentation.v2.endpoints.shipment-central.shipment_document')
@include('api.documentation.v2.endpoints.shipment-central.statement')
@include('api.documentation.v2.endpoints.shipment-central.supplier')
@include('api.documentation.v2.endpoints.shipment-central.terminal_149_shipment')
@include('api.documentation.v2.endpoints.shipment-central.terminal_region')
@include('api.documentation.v2.endpoints.shipment-central.tracking_shipment')
@include('api.documentation.v2.endpoints.shipment-central.user')
<div class="hidden">
    <!-- Purchase Order -->
    @include('api.documentation.v2.endpoints.po.po-warehouse-inventory')
    @include('api.documentation.v2.endpoints.po.amazon-store')
    @include('api.documentation.v2.endpoints.po.category')
    @include('api.documentation.v2.endpoints.po.country-state-city')
    @include('api.documentation.v2.endpoints.po.inbound')
    @include('api.documentation.v2.endpoints.po.inventory')
    @include('api.documentation.v2.endpoints.po.location')
    @include('api.documentation.v2.endpoints.po.outbound')
    @include('api.documentation.v2.endpoints.po.product')
    @include('api.documentation.v2.endpoints.po.purchase-order')
    @include('api.documentation.v2.endpoints.po.purchase-order-main')
    @include('api.documentation.v2.endpoints.po.warehouse')
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
                                    <div style="clear: both;"></div>
                                    <div>

                                        <div id="support-shifl" class="com-container" >
                                            <h3 class="page-header"> Support </h3>
                                            <p>For any kind of Support regarding Shifl Client Api, feel free to reach out us.</p>
                                            <p style="font-weight:bold"> Website : <a href="http://www.shifl.com" target="_blank">shifl.com</a> </p>
                                            <p style="font-weight:bold"> Email: <a href="mailto:hello@shifl.com">{{ __('hello@shifl.com') }}</a></p>
                                            <p style="font-weight:bold"> Call/Text/Whatsapp: <a href="https://api.whatsapp.com/send?phone=18884474435" target="_blank">888-HI-SHIFL (888-447-4435)</a></p>
                                            <p style="font-weight:bold"> Headquarters : 386 RT 59 Suite 300J Monsey, NY 10952</p>

                                        </div>
                                    </div>

                                    <div style="clear: both;"></div>
                                    <div class="text-center" style="position:relative">
                                        <p class="copy" style="font-weight:bold;cursor:pointer" onclick="window.location.href='http://www.shifl.com'">© {{ \Carbon\Carbon::now()->format('Y') }} Shifl</p>
                                    </div>
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

                $('ul.endpoint_ li a').on('click', function(){
                    $('ul.endpoint_ li a.active').removeClass('active');
                    $(this).addClass('active');
                })

            });
            function setWidth(x) {

                $("#sidebar-wrapper").css('width', x + 4 + 'px');

                var pl =  x - 235;
                $("#page-content-wrapper").css('padding-left', pl + 'px');

            }
            // May want to use lodash debounce to limit number of screen
            // redraws
            // let debounced = _.debounce( setWidth, 50, { maxWait: 50 } );
            jQuery("#resizer").on("mousedown", function() {
                jQuery("body").on("mousemove.resize", function(e) {
                    setWidth(e.pageX);

                });
                return false;
            });
            jQuery("body").on("mouseup", function() {
                jQuery("body").off("mousemove.resize");
            });

        </script>


        <script>
            // Add active class to the current button (highlight it)
           // var header = document.querySelectorAll("myDIV");
            var btns = document.getElementsByClassName("ul.endpoint_ li a");
            for (var i = 0; i < btns.length; i++) {
                btns[i].addEventListener("click", function() {
                    var current = document.getElementsByClassName("active");
                    current[0].className = current[0].className.replace(" active", "");
                    this.className += " active";
                });
            }




            $('.glyphicon1').click(function (e) {
                e.preventDefault();
                var gly = $(this).attr('rel');
                $('.'+gly+' .glyphicon').toggleClass("glyphicon-menu-right").toggleClass("glyphicon-menu-down");

            });




            $('div.copy-api').on('click touchstart', function(e){

                e.preventDefault();

                var rel = $(this).attr('rel');

                var $temp = $("<div>");
                $('#'+rel).append($temp);

                $temp.attr("contenteditable", true)
                    .html($('#'+rel).html()).select()
                    .on("focus", function() { document.execCommand('selectAll',false,null); })
                    .focus();

                document.execCommand("copy");
                $temp.remove();
                $('.'+rel).removeClass('hidden');

                var timer;
                setTimeout(function(){
                    clearTimeout(timer);
                    $('.'+rel).addClass('hidden');
                }, 1000);

            });



        </script>


</body>

</html>
