<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Shifl - Internal API Documentation</title>
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
                <a href="#internal-docs-generate-token" style="white-space: nowrap;">Internal Documentation Generate Token</a>
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
                                        <h2 class="page-header">Fixed login Internal Documentation</h2>
                                    </div>

                                    <div class="col-sm-12" id="internal-docs-generate-token">
                                        <h3 class="page-header"> Internal Documentation </h3>

                                        <div class="col-sm-12">

                                            STEPS:
                                            <p>1. php artisan migrate</p>
                                            <p>
                                               Create migration by using command in your terminal: php artisan migrate
                                            </p>
                                            <pre style="font-weight:bold">
project\folder> php aritsan migrate </pre>
                                            <p>2. php artisan passport install</p>
                                            <p>
                                                Lets install passport to our project using terminal
                                            </p>
                                             <pre style="font-weight:bold">
project\folder> php artisan passport:install

<span class="slctrl-literal">Personal access client created successfully.</span>
<span class="slctrl-attr">Client ID:</span> <span class="slctrl-number">23</span>
<span class="slctrl-attr">Client secret:</span> <span class="slctrl-string">SH2x9s4zMJdagcOaku3N2Piud49dKQeQjS4UCV8B</span>

<span class="slctrl-literal">Password grant client created successfully.</span>
<span class="slctrl-attr">Client ID:</span> <span class="slctrl-number">18</span>
<span class="slctrl-attr">Client secret:</span> <span class="slctrl-string">Y8wOBNcD5vFJT34ZnHjqJwrLi58R6HOmjE8mi8z8 </span> </pre>


                                            <p>3. Check secret and id both personal client and password client</p>
                                            <p>
                                                Data must be added in you oauth_clients table after installing passport
                                            </p>
                                                <pre style="font-weight:bold">
<span class="slctrl-attr">PERSONAL_CLIENT_ID</span> = <span class="slctrl-number">23</span>
<span class="slctrl-attr">PERSONAL_CLIENT_SECRET</span> = <span class="slctrl-string">SH2x9s4zMJdagcOaku3N2Piud49dKQeQjS4UCV8B</span>

<span class="slctrl-attr">PASSWORD_CLIENT_ID=</span> <span class="slctrl-number">24</span>
<span class="slctrl-attr">PASSWORD_CLIENT_SECRET</span> = <span class="slctrl-string">Y8wOBNcD5vFJT34ZnHjqJwrLi58R6HOmjE8mi8z8</span> </pre>

                                            <p>4. Generate package</p>
<p>
    Let us generate our package called scribe
</p>
                                            <pre style="font-weight:bold">
project\folder> php artisan scribe:generate </pre>

                                            <p>5. Clear Cache </p>
<pre style="font-weight:bold">
project\folder> php artisan cache:clear
<span class="slctrl-literal">Application cache cleared!</span> </pre>
                                            <p>6. Clear Configuration</p>
                                            <pre style="font-weight:bold">
project\folder> php artisan config:clear
<span class="slctrl-literal">Configuration cache cleared!</span> </pre>


                                            <p>6. composer dump-autoload </p>

                                            <pre style="font-weight:bold">
<span class="slctrl-literal">Generating optimized autoload files</span>
...,
<span class="slctrl-literal">Package manifest generated successfully.</span>
<span class="slctrl-literal">Generated optimized autoload files containing 8056 classes</span> </pre>

                                            <p>7. restart the server</p>
                                            <p>If you running the server, you can stop the server and run again </p>
                                            <pre style="font-weight:bold">
project\folder> php artisan serve </pre>
                                            <p>8. Change Password (optional)</p>
                                            <p>
                                                We can change password of the user by using tinker command
                                            </p>

                                            <pre style="font-weight:bold">
php artisan tinker </pre>
                                            <p>
                                                It should look like this.
                                            </p>

                                            <pre style="font-weight:bold">
<span class="slctrl-blue">Psy Shell v0.11.8 (PHP 7.4.23 — cli) by Justin Hileman</span>
>>> </pre>

                                            <p>
                                                We can now update our user password
                                            </p>
                                            <pre style="font-weight:bold">
<span class="slctrl-blue">Psy Shell v0.11.8 (PHP 7.4.23 — cli) by Justin Hileman</span>
>>> <span class="slctrl-string">User::where('email','anthony@shifl.com')->update(['password'=>bcrypt('password')]);</span> </pre>
                                            {{--<img src="{{ asset("/images/documentation/client-api/install-passport.png") }}" width="1000px">--}}
                                            <br/>

                                        </div>

                                        <div class="col-sm-12">
                                            ISSUE:
                                            <p>During the configuration we might encounter issue that will make us delay</p>
                                            1.
<pre style="font-weight:bold">
<span class="slctrl-string">No sql connection:
HY000] [2002] php_network_getaddresses: getaddrinfo failed: No such host is known.  (SQL: select * from `users` where `id` = 5 limit 1)"</span> </pre>
<p>The error indicate that we have problem with database connection. </p>

                                            2.
<pre style="font-weight:bold">
<span class="slctrl-string">SQLSTATE[42S22]: Column not found: 1054 Unknown column 'provider' in 'field list' (SQL: insert into `oauth_clients` (`user_id`, `name`, `secret`, `provider`, `redirect`, `personal_access_
client`, `password_client`, `revoked`, `updated_at`, `created_at`) values (?, ShiflAdmin Personal Access Client, loUzYmmtuZlceIhz78IyKbL8pj8XlQ4ehD5m5mEi, ?, http://localhost, 1, 0, 0, 2022-09-12 17:50:45, 2022-09-12 17:50:45)) </span></pre>
                                            <p>The error indicate that we have database table problem and we should add <code>provider</code> column. </p>

                                            3.
<pre style="font-weight:bold">
<span class="slctrl-string">"message": "Trying to get property 'refresh_token' of non-object", </span> </pre>
    <p>This error indicate that we need to setup and install our passport, check and review step 1 - step 7</p>
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
