(function () {
    'use strict';

    angular.module('app').controller('HomeAdvisorCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', HomeAdvisorCtrl]);

    function HomeAdvisorCtrl($rootScope, $scope, $uibModal, request, langs) {
        $rootScope.body_class = '';
    	$scope.request_finish = false;

        $scope.list = [];

        $scope.init = function() {
            $scope.get();
        };

        $scope.get = function() {
        	
        };
    };
})();

;