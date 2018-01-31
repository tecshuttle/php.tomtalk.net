var memo = angular.module('memoType', ['ngRoute']);

memo.controller('typeCtrl', ['$scope', '$http',
    function ($scope, $http) {
        $http.post('/memo_api/get_item_type').success(function (types, status, headers, config) {
            $scope.types = types;
        });

        $scope.edit = function (i) {
            console.log($scope.types[i].name);
        }
    }
]);

memo.controller('editCtrl', ['$scope', '$http', '$routeParams',
    function ($scope, $http, $routeParams) {
        $http.post('/memo_api/get_item_type', {
            id: $routeParams.id
        }).success(function (type, status, headers, config) {
                $scope.type = type;
            });

        $scope.save = function (type) {
            console.log(type);

            $http.post('/memo_api/save_item_type',
                    type
                ).success(function (result, status, headers, config) {
                    if (result.success) {
                        window.location.href = '#list';
                    }
                });
        }
    }
]);


memo.config(function ($routeProvider) {
    $routeProvider
        .when('/list', {
            templateUrl: 'templates/list.html',
            controller: 'typeCtrl'
        }).when('/edit/:id', {
            templateUrl: 'templates/edit.html',
            controller: 'editCtrl'
        }).otherwise({
            redirectTo: '/list'
        });
});


//end file
