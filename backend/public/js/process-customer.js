let appAngular = angular.module('customer-app', []);

appAngular.config(['$interpolateProvider',function($interpolateProvider){
  $interpolateProvider.startSymbol('<%').endSymbol('%>');
}]);


appAngular.controller('CustomerController',[ '$scope','$http','$timeout','$interval',function($scope,$http,$timeout, $interval) {

	$scope.page = 1;
	$scope.processing = false;
	$scope.total_pages = '';
	$scope.finished = false;
	$scope.total = 0;
	$scope.counter = 0;
	$scope.current_queue = [];
	$scope.index = 0;

	$scope.processQueue = function() {

		let getQueue = $scope.current_queue

		if (getQueue.length > 0) {
			
			function repeat(i) {

				if (getQueue[i]) {

					$http(
					{
					 	method: 'GET',
					 	url: `${ajax_url}/custom/process-single-customer/${getQueue[i].id}`
					})
				    .then(function (response) {
				    	i++
				    	$scope.counter = parseInt($scope.counter + 1)
						repeat(i)
					}).catch(e => {
						i++
						$scope.counter = parseInt($scope.counter + 1)
						repeat(i)
					})


				} else {
					$scope.page = parseInt($scope.page + 1)
					$scope.processCustomers()
				}
			}

			repeat(0)
		}


	}

	$scope.processCustomers = function() {
		$http(
			{
		 	method: 'GET',
		 	url: `${ajax_url}/custom/process-get-all-customers?page=${$scope.page}`})
	    .then(function (response) {
	    	let { results } = response.data
	    	if (typeof results!=='undefined') {
	    		if ($scope.total_pages=='') {
	    			$scope.total_pages = (typeof results.last_page!=='undefined') ? results.last_page : 0
	    		}

	    		if ($scope.total==0) {
	    			$scope.total = (typeof results.total!=='undefined') ? results.total : 0
	    		}

	    		if ( results.data.length > 0 ) {
    				$scope.current_queue = results.data
    				$scope.processQueue()
    			} else {
    				$scope.processing = false;
    				$scope.finished = true;
    			}

	    	}
	   	})
	}

	$scope.process = function() {
		if (!$scope.processing) {
			$scope.processing = true;
			let token = document.head.querySelector('meta[name="csrf-token"]');
			$scope.processCustomers()	
		}
		

	}

}]);
