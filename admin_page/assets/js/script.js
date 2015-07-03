// create the module and name it scotchApp
    var scotchApp = angular.module('scotchApp', ['ngRoute']);

    // configure our routes
    scotchApp.config(function($routeProvider) {
        $routeProvider

            // route for the home page
            .when('/', {
                templateUrl : 'pages/home.html',
                controller  : 'mainController'
            })

            // route for the about page
            .when('/prices', {
                templateUrl : 'prices.html',
                controller  : 'aboutController'
            })

            // route for the contact page
            .when('/dashboard', {
                templateUrl : 'dashboard.html',
                controller  : 'contactController'
            })

            .when('/holidaylettings', {
                template : '<iframe src="https://www.holidaylettings.co.uk/content/dashboard" frameborder="0"></iframe>',
                controller  : 'contactController'
            })
            ;
            
    });

    // create the controller and inject Angular's $scope
    scotchApp.controller('mainController', function($scope) {
        // create a message to display in our view
        $scope.message = 'Everyone come and see how good I look!';
    });

    scotchApp.controller('aboutController', function($scope) {
        $scope.message = 'Look! I am an about page.';
    });

    scotchApp.controller('contactController', function($scope) {
        $scope.message = 'Contact us! JK. This is just a demo.';
    });