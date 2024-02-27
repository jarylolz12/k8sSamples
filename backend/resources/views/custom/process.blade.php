<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
		 <meta name="csrf-token" content="{{ csrf_token() }}"/>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
		<title>
			Database Processing
		</title>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.8.2/angular-csp.css" integrity="sha512-f7WVHp6iaZ7iOx9duYm67KLwCFJ9KirfMIK0MAOxf1wq5M8Ogdw5ljbCxlk/BtWIbyXWMsIaDaqwUpXttPQ/5g==" crossorigin="anonymous" />
	</head>
	<body>
		<div class="container" ng-app="process-app">
			<div ng-controller="ProcessingController">
				<div class="row" style="display: flex;justify-content: center; align-items: center; height: 900px;">
					<div class="col-md-12">
						<div ng-if="!finish">Progress</div>
						<div ng-if="finish">Processing completed. There are <% totalSchedules %> old shipment schedules updated.</div>
	  					<div class="progress" ng-if="!finish">
  							<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<% progressBar; %>" aria-valuemin="0" aria-valuemax="100" style="width: <% progressBar; %>%"></div>
						</div>
						<button style="margin-top: 1rem;" ng-if="processing" class="btn btn-primary">Processing...</button>
						<button style="margin-top: 1rem;" ng-if="!processing" class="btn btn-primary" ng-click="process()">Process</button>
					</div>
				</div>
			</div>
		</div>
		<script
  src="https://code.jquery.com/jquery-3.5.1.min.js"
  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
  crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
		<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/lodash@4.17.15/lodash.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.8.2/angular.min.js" integrity="sha512-7oYXeK0OxTFxndh0erL8FsjGvrl2VMDor6fVqzlLGfwOQQqTbYsGPv4ZZ15QHfSk80doyaM0ZJdvkyDcVO7KFA==" crossorigin="anonymous"></script>
		<script type="text/javascript">
			var ajax_url = "<?=  url('/') ; ?>"
			console.log(ajax_url)
		</script>
		<script src="{{ asset('js/process.js') }}"></script>
		<script type="text/javascript">
		</script>
	</body>
</html>