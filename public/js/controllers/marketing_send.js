
(function () {
    'use strict';

    angular.module('app').controller('MarketingSendCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', MarketingSendCtrl]);

    function MarketingSendCtrl($rootScope, $scope, $uibModal, request, langs) {
    	   $scope.step = 1;
           $scope.message= {};
           
           $scope.insertMask = function() {
           		
           };
           
    };
})();

;