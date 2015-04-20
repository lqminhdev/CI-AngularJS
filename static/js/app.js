var app = angular.module('myApp', ['ngRoute']);
app.factory("services", ['$http', function($http) {

    var obj = {};
    obj.getCustomers = function(){
		return $http.get(base_url + 'customer/list_customers');
    }
    
	obj.getCustomer = function(customerID){
        return $http.get(base_url + 'customer/get_customer/' + customerID);
    }

    obj.insertCustomer = function (customer) {
    return $http.post(base_url + 'customer/submit_customer/0', customer).then(function (results) {
        return results;
    });
	};

	obj.updateCustomer = function (customerID,customer) {
	    return $http.post(base_url + 'customer/submit_customer/' + customerID, customer).then(function (results) {
	        return results;
	    });
	};

	obj.deleteCustomer = function (customerID) {
	    return $http.delete(base_url + 'customer/delete_customer/' + customerID).then(function (results) {
	        return status.data;
	    });
	};

    return obj;   
}]);

app.controller('listCtrl', function ($scope, services) {
    services.getCustomers().then(function(results){
		//console.log(results);
        $scope.customers = results.data;
    });
});

app.controller('editCtrl', function ($scope, $rootScope, $location, $routeParams, services) {
    var customerID = ($routeParams.customerID) ? parseInt($routeParams.customerID) : 0;
    $rootScope.title = (customerID > 0) ? 'Edit Customer' : 'Add Customer';
    $scope.buttonText = (customerID > 0) ? 'Update Customer' : 'Add New Customer';
	
	var original={};
	
	if(customerID > 0)
	{
		services.getCustomer(customerID).then(function(results){
	
			//console.log(results.data);
			var original = results.data;
			original._id = customerID;
			$scope.customer = angular.copy(original);
			$scope.customer._id = customerID;					
		});				
	}
	
      

      $scope.isClean = function() {
        return angular.equals(original, $scope.customer);
      }

      $scope.deleteCustomer = function(customer) {
        
        if(confirm("Are you sure to delete customer number: "+$scope.customer._id)==true)
        services.deleteCustomer(customer.customerNumber).then(function(results){
			$location.path('/');
		});
      };

      $scope.saveCustomer = function(customer) {
       
        if (customerID <= 0) {
            services.insertCustomer(customer).then(function(results){
				 $location.path('/');
			});
        }
        else {
            services.updateCustomer(customerID, customer).then(function(results){
				 $location.path('/');
			});
        }
    };
});

app.config(['$routeProvider',
  function($routeProvider) {
    $routeProvider.
      when('/', {
        title: 'Customers',
        templateUrl: base_url + 'customer/load_list_customers',
        controller: 'listCtrl'
      })
      .when('/edit-customer/:customerID', {
        title: 'Edit Customers',
        templateUrl: base_url + 'customer/load_edit_customer',
        controller: 'editCtrl'
//        resolve: {
//          customer: function(services, $route){
//            var customerID = $route.current.params.customerID;
//            return services.getCustomer(customerID);
//          }
//        }
      })
      .otherwise({
        redirectTo: '/'
      });
}]);
app.run(['$location', '$rootScope', function($location, $rootScope) {
    $rootScope.$on('$routeChangeSuccess', function (event, current, previous) {
        //$rootScope.title = current.$$route.title;
    });
}]);