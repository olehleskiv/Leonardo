// create the module and name it scotchApp
    var scotchApp = angular.module('scotchApp', ['ngRoute']);

    // configure our routes
    scotchApp.config(function($routeProvider) {
        $routeProvider

            // route for the home page
            .when('/', {
                templateUrl : 'dashboard.html',
                controller  : 'mainController'
            })

            .when('/ui', {
                templateUrl : 'ui.html',
                controller  : 'contactController'
            })

            .when('/tab-panel', {
                templateUrl : 'tab-panel.html',
                controller  : 'contactController'
            })

            .when('/chart', {
                templateUrl : 'chart.html',
                controller  : 'contactController'
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

            .when('/visitors', {
                template : '<iframe src="http://s07.flagcounter.com/more30/tgoy/" frameborder="0"></iframe>',
                controller  : 'contactController'
            })

            .when('/club', {
                template : '<iframe src="http://www.chernogoriya-club.ru/chastniy-sector/4369/" frameborder="0"></iframe>',
                controller  : 'contactController'
            })

            .when('/wakacyjnywynajem', {
                template : '<iframe src="http://www.wakacyjnywynajem.pl/noclegi/9206-apartament-w-willi--leonardo-zelenika/szczegoly" frameborder="0"></iframe>',
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