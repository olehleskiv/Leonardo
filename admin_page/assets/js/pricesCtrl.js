scotchApp.controller('pricesController', pricesController);

function pricesController($scope, $http) {
	$scope.serverMessage = false;

	$http.get('assets/js/singlePrices.json').
		success(function(data) {
		$scope.singlePrices = data;
	}).
		error(function(data, status, headers, config) {
			console.log(data);
	});

	$http.get('assets/js/doublePrices.json').
		success(function(data) {
		$scope.doublePrices = data;
	}).
		error(function(data, status, headers, config) {
			console.log(data);
	});

	$http.get('assets/js/apartmentPrices.json').
		success(function(data) {
		$scope.apartmentPrices = data;
	}).
		error(function(data, status, headers, config) {
			console.log(data);
	});

//------------------------------------------------------------------------

	$scope.editSinglePrice = function (idx) {
		$scope.backup = angular.copy($scope.singlePrices.single[idx]);
	};

	$scope.savePriceSingle = function (idx) {
		var data = $scope.singlePrices;
		$http.post('singlePrices.php', data).
		success(function(data, status) {
			if (status === 200){
				$scope.serverSingleMessage = data;
			}
		}).
		error(function(data, status, headers, config) {
			console.log(data);
		});
	};

	$scope.cancelEditSinglePrices = function (idx) {
		$scope.singlePrices.single[idx] = angular.copy($scope.backup);
		$scope.backup = false;
	};

//------------------------------------------------------------------------

	$scope.editDoublePrice = function (idx) {
		$scope.backup = angular.copy($scope.doublePrices.double[idx]);
	};

	$scope.savePriceDouble = function (idx) {
		var data = $scope.doublePrices;
		$http.post('doublePrices.php', data).
		success(function(data, status) {
			if (status === 200){
				$scope.serverDoubleMessage = data;
			}
		}).
		error(function(data, status, headers, config) {
			console.log(data);
		});
	};

	$scope.cancelEditDoublePrices = function (idx) {
		$scope.doublePrices.double[idx] = angular.copy($scope.backup);
		$scope.backup = false;
	};

//------------------------------------------------------------------------

	$scope.editApartmentPrice = function (idx) {
		$scope.backup = angular.copy($scope.apartmentPrices.apartment[idx]);
	};

	$scope.savePriceApartment = function (idx) {
		var data = $scope.apartmentPrices;
		$http.post('apartmentPrices.php', data).
		success(function(data, status) {
			if (status === 200){
				$scope.serverApartmentMessage = data;
			}
		}).
		error(function(data, status, headers, config) {
			console.log(data);
		});
	};

	$scope.cancelEditApartmentPrices = function (idx) {
		$scope.apartmentPrices.apartment[idx] = angular.copy($scope.backup);
		$scope.backup = false;
	};
}

