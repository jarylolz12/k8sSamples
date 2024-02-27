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
        .slctrl-blue{
            color: #b5f5ff;
        }
        .slctrl-gray{
            color: gray;
        }
        .slctrl-purple{
            color: #eb2feb;
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
                <a href="#structure">Shipments By PO </a>
            </li>
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

                            <div id="structure" class="com-container">


                                <div class="row p-t-10">
                                    <div class="col-sm-12" id="endpoints_and_request">
                                        <h2 class="page-header">How to fix shipments by po if is not working</h2>
                                    </div>

                                    <div class="col-sm-12" id="how_to_create_client_credentials">
                                        <h3 class="page-header"> Shipments By PO </h3>
                                        <div class="col-sm-12">

STEPS:
                                            <p>1. Routes API</p>
     <p>
         Open
         <code>routes/api.php </code> check the ff: ,
         we notice that the <code> Route::get('shipment-by-po/{po}', 'API\CLIENT\v1\ShipmentController@shipmentByPo'); </code>  </code>
         is under to client user middleware
     </p>

    <pre style="font-weight:bold">
    Route::<span class="slctrl-blue">middleware</span>([<span class="slctrl-literal">'client'</span>, <span class="slctrl-literal">'client.user'</span>])-><span class="slctrl-blue">prefix</span>(<span class="slctrl-literal">'client'</span>)-><span class="slctrl-blue">group</span>(<span class="slctrl-blue">function</span> () {
        Route::<span class="slctrl-blue">prefix</span>(<span class="slctrl-literal">'v1'</span>)-><span class="slctrl-blue">group</span>(<span class="slctrl-blue">function</span> () {
        Route::<span class="slctrl-blue">get</span>(<span class="slctrl-literal">'shipment-by-po/{po}'</span>, <span class="slctrl-literal">'API\CLIENT\v1\ShipmentController@shipmentByPo'</span>);
        Route::<span class="slctrl-blue">get</span>(<span class="slctrl-literal">'shipment/status/{id}'</span>, <span class="slctrl-literal">'API\CLIENT\v1\ShipmentController@shipmentStatus'</span>);
        Route::<span class="slctrl-blue">get</span>(<span class="slctrl-literal">'shipment/documents/download'</span>, <span class="slctrl-literal">'API\CLIENT\v1\ShipmentController@download'</span>);
    });

    Route::<span class="slctrl-blue">get</span>(<span class="slctrl-literal">'shipment/do/download/{id}'</span>, <span class="slctrl-literal">'API\CLIENT\FileDownloadController@downloadDO'</span>);
});  </pre>

                                            <p>2. ClientUserMiddleware</p>
                <p>
                    CTRL+SHIFT+N for Php storm editor and search <code>middleware/clientusermiddleware.php</code> or
                    go to your app navigate and navigate <code>app/http/middleware/clientusermiddleware.php</code> and you will able to see this line of code
                </p>


      <pre style="font-weight:bold">

<span class="slctrl-blue">class</span> ClientUserMiddleware
{
          <span class="slctrl-gray">
    /**
     * Handle an incoming request.
     *
     * <code>@param</code>  \Illuminate\Http\Request  $request
     * <code>@param</code>  \Closure  $next
     * <code>@return</code> mixed
     */
          </span>
    <span class="slctrl-blue">public function</span> handle(<span class="slctrl-string">$request</span>, Closure <span class="slctrl-string">$next</span>)
    {
        <span class="slctrl-string">$bearerToken</span> = request()->bearerToken();
        <span class="slctrl-string">$tokenId</span> = (<span class="slctrl-blue">new</span> Parser())->parse(<span class="slctrl-string">$bearerToken</span>)->getClaim(<span class="slctrl-literal">'jti'</span>);
        <span class="slctrl-string">$client</span> = Token::find(<span class="slctrl-string">$tokenId</span>)-><span class="slctrl-blue">client</span>;

        <span class="slctrl-string">$auth_user</span> = \Laravel\Passport\Client::findOrFail(<span class="slctrl-string">$client</span>-><span class="slctrl-blue">id</span>)-><span class="slctrl-blue">user</span>;

          if(<span class="slctrl-string">$auth_user</span>){
          Auth::login(<span class="slctrl-string">$auth_user</span>);
          <span class="slctrl-blue">return</span> <span class="slctrl-string">$next</span>(<span class="slctrl-string">$request</span>);
        }

        <span class="slctrl-blue">return</span> response([
          <span class="slctrl-literal">'message'</span> => <span class="slctrl-literal">'User not found. No user is associated with this Client'</span>
        ],<span class="slctrl-blue">404</span>);
    }
}
         </pre>
<p>3. Shipment Controller</p>
<p>
    For the controller navigate the path <code>app/http/controllers/API/CLIENT/v1/ShipmentController</code> and search for the function name <code> shipmentByPo </code> and you will able to see this line of code
</p>
              <pre style="font-weight:bold">

<span class="slctrl-blue">public function</span> shipmentByPo(Request <span class="slctrl-gray">$request</span>, <span class="slctrl-string">$po</span>)
{
    <span class="slctrl-string">$customers</span> = auth()->user()->customersApi()->get();
    <span class="slctrl-string">$customer_ids</span> =  <span class="slctrl-string">$customers</span>->pluck([<span class="slctrl-literal">'id'</span>]);
    <span class="slctrl-string">$shipments</span> = <span class="slctrl-blue">new</span> Collection();


              <span class="slctrl-blue">foreach</span> (<span class="slctrl-string">$customers</span> <span class="slctrl-blue">as</span> <span class="slctrl-string">$customer</span>) {
              <span class="slctrl-string">$shipments</span> = <span class="slctrl-string">$shipments</span>->merge(<span class="slctrl-string">$customer</span>-><span class="slctrl-purple">shipments</span>);
    }
        if (<span class="slctrl-string">$shipments</span>) {
          <span class="slctrl-string">$shipments</span> = <span class="slctrl-string">$shipments</span>->filter(<span class="slctrl-blue">function</span> (<span class="slctrl-string">$shipment</span>) <span class="slctrl-blue">use</span> (<span class="slctrl-string">$po</span>) {
          <span class="slctrl-string">$suppliers</span> = json_decode(<span class="slctrl-string">$shipment</span>-><span class="slctrl-purple">suppliers_group_bookings</span>);
          if (<span class="slctrl-string">$suppliers</span>) {
              <span class="slctrl-blue">foreach</span> (<span class="slctrl-string">$suppliers</span> <span class="slctrl-blue">as</span> <span class="slctrl-string">$supplier</span>) {
              if (<span class="slctrl-string">$supplier</span>-><span class="slctrl-purple">po_num</span> === <span class="slctrl-string">$po</span>) {
                        <span class="slctrl-blue">return true</span>;
                    }
                }
            }
              <span class="slctrl-blue">return false</span>;
        });
    }

    <span class="slctrl-string">$jwtToken</span> = CustomJWTGenerator::generateToken();
                    <span class="slctrl-string">$response</span> = Http::<span class="slctrl-literal">withHeaders</span>([
                    <span class="slctrl-literal">'Content-Type'</span> => <span class="slctrl-literal">'application/json'</span>,
                    <span class="slctrl-literal">'Accept'</span>     => <span class="slctrl-literal">'application/json'</span>,
                    <span class="slctrl-literal">'Authorization'</span>  => <span class="slctrl-literal">'Bearer '</span> . <span class="slctrl-string">$jwtToken</span>,
              ])->baseUrl(getenv(<span class="slctrl-string">'PO_URL'</span>))
              ->get(<span class="slctrl-literal">"/api/po/shipments/</span><span class="slctrl-string">$po</span><span class="slctrl-literal">?customer_ids="</span>.json_encode(<span class="slctrl-string">$customer_ids</span> ?? []));
              if(<span class="slctrl-literal">$response</span>->status() == <span class="slctrl-blue">200</span>){
              <span class="slctrl-string">$shipmentDistribution</span> = <span class="slctrl-string">$response</span>->json()[<span class="slctrl-literal">'data'</span>];
              if(count(<span class="slctrl-string">$shipmentDistribution</span>)){
              <span class="slctrl-string">$shipments</span> = <span class="slctrl-string">$shipments</span>->merge(Shipment::whereIn(<span class="slctrl-string">'id'</span>,collect(<span class="slctrl-string">$shipmentDistribution</span>)->pluck([<span class="slctrl-literal">'shipment_id'</span>]))->whereNotIn(<span class="slctrl-literal">'id'</span>,<span class="slctrl-literal">$shipments</span>-><span class="slctrl-purple">pluck</span>([<span class="slctrl-literal">'id'</span>]))->get());
        }
    }

    <span class="slctrl-string">$shipments</span> = <span class="slctrl-string">$shipments</span>->merge(Shipment::where(<span class="slctrl-literal">'is_tracking_shipment'</span>,<span class="slctrl-blue">1</span>)->where(<span class="slctrl-literal">'po_num'</span>, <span class="slctrl-string">$po</span>)->whereNotIn(<span class="slctrl-literal">'id'</span>,<span class="slctrl-string">$shipments</span>->pluck([<span class="slctrl-literal">'id'</span>]))->get());

    if (<span class="slctrl-string">$shipments</span>->first()) {
       <span class="slctrl-blue">return</span> ShipmentResource::Collection(<span class="slctrl-string">$shipments</span>);
    } else {
       <span class="slctrl-blue">return</span> response()->json([<span class="slctrl-literal">'status'</span>=><span class="slctrl-literal">'error'</span>,<span class="slctrl-literal">'message'</span>=><span class="slctrl-literal">'Shipment Not Found'</span>], <span class="slctrl-blue">404</span>);
    }
} </pre>

<p>4. Postman</p>

        <p>
            We will use Postman for testing. <br>
            The api require one url parameter, request type should be <code>Get</code> and paste the url api
            <code>api/client/v1/shipment-by-po/{po}</code> to send.
            change the {po} to po number value.
         </p>


         <img src="{{ asset("/images/documentation/client-api/postman1.png") }}" width="1000px">


        <p>
            Headers, authentication bearer token is required.
        </p>


          <p>
              The parameter should exist to shipment database
          </p>

<p>5. Database</p>
          <img src="{{ asset("/images/documentation/client-api/db-shipment1.png") }}" width="1000px">

            <p>
                The customer id as you have seen on the image above is foreign key and required to display data.
            </p>
<p>6. Response Sample</p>
<pre style="font-weight:bold">
{
    <span class="slctrl-attr">"data"</span>: [

    {
            <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">2</span>,
            <span class="slctrl-attr">"shifl_ref"</span>: <span class="slctrl-string">"60717"</span>,
            <span class="slctrl-attr">"customer"</span>: <span class="slctrl-string">"SOUND AROUND"</span>,
            <span class="slctrl-attr">"mbl_num"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"is_tracking_shipment"</span>: <span class="slctrl-literal">true</span>,
            <span class="slctrl-attr">"vessel"</span>: <span class="slctrl-string">""</span>,
            <span class="slctrl-attr">"booking_confirmed"</span>: <span class="slctrl-literal">false</span>,
            <span class="slctrl-attr">"booking_confirmed_at"</span>: <span class="slctrl-literal">null</span>,

            <span class="slctrl-attr">"schedules"</span>: {
                <span class="slctrl-attr">"etd"</span>: <span class="slctrl-string">""</span>,
                <span class="slctrl-attr">"eta"</span>: <span class="slctrl-string">""</span>,
                <span class="slctrl-attr">"location_from"</span>: <span class="slctrl-string">""</span>,
                <span class="slctrl-attr">"location_to"</span>: <span class="slctrl-string">""</span>,
                <span class="slctrl-attr">"mode"</span>: <span class="slctrl-string">""</span>,
                <span class="slctrl-attr">"carrier"</span>: <span class="slctrl-string">""</span>,
                <span class="slctrl-attr">"type"</span>: <span class="slctrl-string">""</span>,
                <span class="slctrl-attr">"legs"</span>: []
            },
            <span class="slctrl-attr">"suppliers"</span>: [],
            <span class="slctrl-attr">"containers"</span>: [],
            <span class="slctrl-attr">"terminal"</span>: {
                <span class="slctrl-attr">"name"</span>: <span class="slctrl-string">""</span>,
                <span class="slctrl-attr">"firms_code"</span>: <span class="slctrl-string">""</span>
            },
            <span class="slctrl-attr">"customs_documents"</span>: []
        },
        {
            <span class="slctrl-attr">"id"</span>: <span class="slctrl-number">3</span>,
            <span class="slctrl-attr">"shifl_ref"</span>: <span class="slctrl-string">"60718"</span>,
            <span class="slctrl-attr">"customer"</span>: <span class="slctrl-string">"AM TRADING LLC"</span>,
            <span class="slctrl-attr">"mbl_num"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"is_tracking_shipment"</span>: <span class="slctrl-literal">true</span>,
            <span class="slctrl-attr">"vessel"</span>: <span class="slctrl-string">""</span>,
            <span class="slctrl-attr">"booking_confirmed"</span>: <span class="slctrl-literal">false</span>,
            <span class="slctrl-attr">"booking_confirmed_at"</span>: <span class="slctrl-literal">null</span>,
            <span class="slctrl-attr">"schedules"</span>: {
                <span class="slctrl-attr">"etd"</span>: <span class="slctrl-string">""</span>,
                <span class="slctrl-attr">"eta"</span>: <span class="slctrl-string">""</span>,
                <span class="slctrl-attr">"location_from"</span>: <span class="slctrl-string">""</span>,
                <span class="slctrl-attr">"location_to"</span>: <span class="slctrl-string">""</span>,
                <span class="slctrl-attr">"mode"</span>: <span class="slctrl-string">""</span>,
                <span class="slctrl-attr">"carrier"</span>: <span class="slctrl-string">""</span>,
                <span class="slctrl-attr">"type"</span>: <span class="slctrl-string">""</span>,
                <span class="slctrl-attr">"legs"</span>: []
            },
            <span class="slctrl-attr">"suppliers"</span>: [],
            <span class="slctrl-attr">"containers"</span>: [],
            <span class="slctrl-attr">"terminal"</span>: {
                <span class="slctrl-attr">"name"</span>: <span class="slctrl-string">""</span>,
                <span class="slctrl-attr">"firms_code"</span>: <span class="slctrl-string">""</span>
            },
            <span class="slctrl-attr">"customs_documents"</span>: []
        }
    ]
} </pre>


<h3>
    Fixed issue:
</h3>

                                            <p>
                                                Once we already have setup and you see the response with issue <code> User not found. No user is associated with this Client </code>
                                                <br>
                                                means that the user was not reach to the controller of the api and filter to middleware as we can see in reference number (2)

                                            </p>

                                            <p>
                                                To fix: we have to Dump and Die <code>dd()</code> from middleware, check each variable to find issue.
                                            </p>
                                            <p>In Postman check the <code> Preview </code> to see the result: </p>
                                            <table class="table-bordered">
                                                    <tbody>
                                                    <tr>
                                                        <td><code>$bearerToken</code></td>
                                                        <td> Check if bearer token is not null</td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="2">

                                                            <p>Example Result:</p>
<pre style="font-weight:bold;">
    <span class="slctrl-attr">eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxOCIs...</span> </pre>

                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td><code>$tokenId</code></td>
                                                        <td>Check if not null</td>

                                                    </tr>

                                                    <tr>
                                                        <td colspan="2">

                                                            <p>Example Result:</p>
<pre style="font-weight:bold">
    <span class="slctrl-attr">"3e6f08106d9978da02a2b67971b65ce83dcf4..."</span> </pre>

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><code>$client</code></td>
                                                        <td>Object response with your password client id and secret from the oauth_clients table in your database.</td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="2">

                                                            <p>Example Result:</p>
<pre style="font-weight:bold">
    [
        <span class="slctrl-attr">"id"</span> => <span class="slctrl-number">16</span>
        <span class="slctrl-attr">"user_id"</span> => <span class="slctrl-number">3</span>
        <span class="slctrl-attr">"name"</span> => <span class="slctrl-string">"ShiflAdmin Password Grant Client"</span>
        <span class="slctrl-attr">"secret"</span> => <span class="slctrl-string">"S834MXyLqvmNoKtNYv7c8ganOIqKBUHlacuhXiKJ"</span>
        <span class="slctrl-attr">"redirect"</span> => <span class="slctrl-string">"http://localhost"</span>
        <span class="slctrl-attr">"personal_access_client"</span> => <span class="slctrl-number">0</span>
        <span class="slctrl-attr">"provider"</span> => <span class="slctrl-string">"users"</span>
        <span class="slctrl-attr">"password_client"</span> => <span class="slctrl-number">1</span>
        <span class="slctrl-attr">"revoked"</span> => <span class="slctrl-number">0</span>
        <span class="slctrl-attr">"created_at"</span> => <span class="slctrl-string">"2022-07-29 09:15:16"</span>
        <span class="slctrl-attr">"updated_at"</span> => <span class="slctrl-string">"2022-07-29 09:15:16"</span>
    ] </pre>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td><code>$auth_user</code></td>
                                                        <td>Object response and should be able to see the data information of the user we used.</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <br/>
                                            <p>
                                                After resolve the issue we mention above we might have encounter another error again and this time
                                                it should be inside of our controller reference number (3).
                                            </p>
                                            <p> The error message say:
                                                <code>cURL error 6: Could not resolve host: api (see https://curl.haxx.se/libcurl/c/libcurl-errors.html)</code>
                                                <br>
                                            </p>


                                            <p>To fix the error, check this code and add something to make it working</p>
<pre style="font-weight:bold">
    <span class="slctrl-string">$response</span> = Http::<span class="slctrl-blue">withHeaders</span>([
    <span class="slctrl-literal">'Content-Type'</span> => <span class="slctrl-literal">'application/json'</span>,
    <span class="slctrl-literal">'Accept'</span>     => <span class="slctrl-literal">'application/json'</span>,
    <span class="slctrl-literal">'Authorization'</span>  => <span class="slctrl-literal">'Bearer '</span> . <span class="slctrl-string">$jwtToken</span>,
    ])->baseUrl(getenv(<span class="slctrl-literal">'PO_URL'</span>))
    ->get(<span class="slctrl-literal">"/api/po/shipments/</span><span class="slctrl-string">$po</span><span class="slctrl-literal">?customer_ids="</span>.json_encode(<span class="slctrl-string">$customer_ids</span> ?? [])); </pre>

    <p>The main reason we encounter the error, because we forgot to setup the PO_URL to our env</p>
    <p>Add this code to our env.</p>

<pre style="font-weight:bold">
    <span class="slctrl-attr">PO_URL=http://po.shifl.test:82/</span> </pre>
                                        </div>
                                        <br/>
                                    </div>

                                    <br/><br/>

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

        $('ul.endpoint_ li a').on('click', function(){
            $('ul.endpoint_ li a.active').removeClass('active');
            $(this).addClass('active');
        })

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
