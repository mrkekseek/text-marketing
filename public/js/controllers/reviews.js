(function () {
    'use strict';

    angular.module('app').controller('ReviewsAnalysisCtrl', ['$scope', 'request', 'langs', ReviewsAnalysisCtrl]);

    function ReviewsAnalysisCtrl($scope, request, langs) {
	   $scope.analysis = {};

        $scope.init = function() {
            $scope.get();
        };

        $scope.get = function() {
            request.send('/analysis', {}, function (data) {
               console.log(data);
            }, 'get');
        };
    };
})();

;