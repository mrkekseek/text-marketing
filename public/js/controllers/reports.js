(function () {
    'use strict';

    angular.module('app').controller('ReportsCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', ReportsCtrl]);

    function ReportsCtrl($rootScope, $scope, $uibModal, request, langs) {
        $scope.request_finish = false;
        $scope.filter = {};
        $scope.list = [];

        $scope.init = function () {
            $scope.get();
        };

        $scope.get = function () {
            request.send('/reports', $scope.filter, function (data) {
                $scope.list = data;
                $scope.request_finish = true;
            }, 'post');
        };
    };
})();

;