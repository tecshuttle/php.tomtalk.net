var myModule = angular.module('controllerExample', ['ngRoute']);

myModule.controller('helloCtrl', ['$scope',
    function ($scope) {
        $scope.greeting = {
            text: 'hello'
        };
    }
]);

myModule.controller('listCtrl', ['$scope',
    function ($scope) {
        $scope.list = ['Tom', 'John', 'Elle'];
    }
]);

myModule.controller('inputCtrl', ['$scope', '$http',
    function ($scope, $http) {
        $scope.list = ['Tom', 'John', 'Elle'];
        $scope.save = function () {
            console.log($scope.text);



            $http.post('/todo/test', {
                data: $scope.text,
                input: $scope.text
            }).success(function (data, status, headers, config) {
                    console.log(data);
                }).error(function (data, status, headers, config) {
                    // called asynchronously if an error occurs
                    // or server returns response with an error status.
                });
        }
    }
]);

myModule.config(function ($routeProvider) {
    $routeProvider.when('/input', {
        templateUrl: 'input.html',
        controller: 'inputCtrl'
    }).when('/list', {
            templateUrl: 'list.html',
            controller: 'listCtrl'
        }).when('/hello', {
            templateUrl: 'hello.html',
            controller: 'helloCtrl'
        }).otherwise({
            redirectTo: '/hello'
        });
});


//end file
