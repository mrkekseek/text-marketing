(function () {
    'use strict';

    angular.module('app').controller('LeadsCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', LeadsCtrl]);

    function LeadsCtrl($rootScope, $scope, $uibModal, request, langs) {
        $scope.request_finish = false;
        $scope.filter = {
            'user': '',
            'date': new Date()
        };
        $scope.list = [];
        $scope.phones = [];
        $scope.users = [];
        $scope.user = {};

        $scope.init = function () {
            $scope.getUsers();
            $scope.get();
        };

        $scope.getUsers = function () {
            request.send('/users', {}, function (data) {
                for (var k in data) {
                    $scope.users.push(data[k]);
                }
            }, 'get');
        };

        $scope.get = function () {
            request.send('/leads', $scope.filter, function (data) {
                $scope.list = data;
                $scope.request_finish = true;
            }, 'post');
        };

        $scope.dateOptions = {
            formatYear: 'yy',
            maxDate: new Date(),
            startingDay: 1
        };

        $scope.date = {
            opened: false
        };

        $scope.openDate = function () {
            $scope.date.opened = true;
        };
    };
})();

;