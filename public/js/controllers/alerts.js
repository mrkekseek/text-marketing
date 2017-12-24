(function () {
    'use strict';

    angular.module('app').controller('AlertsCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', AlertsCtrl]);

    function AlertsCtrl($rootScope, $scope, $uibModal, request, langs) {
        $scope.inputs = [];

        $scope.init = function() {
            $scope.get();
        };

        $scope.get = function() {
            request.send('/surveys', {}, function (data) {
                $scope.survey = data;
                if ($scope.survey.alerts_emails) {
                    $scope.inputs = $scope.survey.alerts_emails.split(',');
                } else {
                    $scope.inputs.push('');
                }

                $scope.survey.alerts_stars = $scope.survey.alerts_stars.toString();
                $scope.survey.alerts_often = $scope.survey.alerts_often.toString();
            }, 'get');
        };

        $scope.add = function() {
            $scope.inputs.push('');
        };

        $scope.remove = function(index) {
            $scope.inputs.splice(index, 1);
        };

        $scope.save = function() {
            $scope.survey.alerts_emails = $scope.inputs.join(',');
            request.send('/surveys', $scope.survey, false, 'put');
        };
    };
})();

;