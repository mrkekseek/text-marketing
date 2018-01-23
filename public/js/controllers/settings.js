(function () {
    'use strict';

    angular.module('app').controller('SettingsCtrl', ['$scope', 'request', 'langs', 'logger', SettingsCtrl]);

    function SettingsCtrl($scope, request, langs, logger) {
        $scope.settings = {};
        $scope.companyName = '';

        $scope.init = function() {
            request.send('/settings', {}, function (data) {
                if (data) {
                    $scope.settings = data;
                }
            }, 'get');

            request.send('/settings/companyNames', {}, function (data) {
                if (data) {
                    for (var k in data) {
                        if (data[k].length > $scope.companyName.length) {
                            $scope.companyName = data[k];
                        }
                    }
                }
            }, 'get');
        };

        $scope.save = function() {
            request.send('/settings' + ($scope.settings.id ? '/' + $scope.settings.id : ''), $scope.settings, function (data) {
               $scope.settings.id = data;
            }, $scope.settings.id ? 'post' : 'put');
        };
    };
})();

;