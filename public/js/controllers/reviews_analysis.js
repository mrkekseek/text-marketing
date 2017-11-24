(function () {
    'use strict';

    angular.module('app').controller('ReviewsAnalysisCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', ReviewsAnalysisCtrl]);

    function ReviewsAnalysisCtrl($rootScope, $scope, $uibModal, request, langs) {
    	   $scope.analysis = {};
    	   $scope.analysis.teams_id = 0;

    	   $scope.toggle_date = function(time) {
    	   		$scope.analysis.timeframe = time;
    	   		$scope.open_period = $scope.analysis.timeframe == 'custom' ? true : false;
    	   };
           
    };
})();

;