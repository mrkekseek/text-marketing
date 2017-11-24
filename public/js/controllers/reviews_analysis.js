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

(function () {
    'use strict';

    angular.module('app').controller('CorporateReviewCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', CorporateReviewCtrl]);

    function CorporateReviewCtrl($rootScope, $scope, $uibModal, request, langs) {

           $scope.list = [{'firstName' : 'name', 'lastName' : 'surname'},{'firstName' : 'name2', 'lastName' : 'surname2'},{'firstName' : 'name3', 'lastName' : 'surname3'}];
           
    };
})();

;