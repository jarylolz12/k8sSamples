let appAngular = angular.module('process-app', []);

appAngular.config(['$interpolateProvider',function($interpolateProvider){
  $interpolateProvider.startSymbol('<%').endSymbol('%>');
}]);


appAngular.controller('ProcessingController',[ '$scope','$http','$timeout','$interval',function($scope,$http,$timeout, $interval) {

	$scope.progressBar = 0
	$scope.totalSchedules = 0
	$scope.processing = false
	$scope.finish = false
	$scope.nextPageUrl = ''
	$scope.currentPage = 1
	$scope.loop = function(data, i) {

		$scope.progressBar = ((i + 1) / $scope.totalSchedules) * 100
		
		if (typeof data[i]!=='undefined') {

			//process the data
			$http(
			{ 
			 	method: 'GET',
			 	url: `${ajax_url}/custom/process-single-shipment/${data[i].shipment_id}`})
		    .then(function (response) {
		    	i++
		    	$scope.loop(data, i)
		    }).catch(e => {
		    	//keep trying when an error is found
		    	$scope.loop(data, i)
		    })
		 	
		} else {
			$scope.finish = true
			$scope.processing = false
		}
	}

	$scope.process = function() {

		if (!$scope.processing) {

			$scope.processing = true
			let token = document.head.querySelector('meta[name="csrf-token"]');
			$http(
			{ 
			 	method: 'GET',
			 	url: `${ajax_url}/custom/process-get-all-schedules`})
		    .then(function (response) {
		    	if (typeof response.data!=='undefined') {
					if (response.data.length > 0) {
						$scope.totalSchedules = response.data.length
						//$scope.totalSchedules = response.data.total
						//$scope.nextPageUrl = response.data.next_page_url
						let newData = response.data
						let index = 0
						$scope.loop(newData, index)
					} else {
						console.log(response.data)
					}
				}
		    }).catch(e => {

		    	console.log(e)
		    })
		}
		
	}
}]);
