(function () {
    'use strict';

    angular.module('app').controller('NewUsersCtrl', ['$rootScope', '$scope', '$http', '$location', '$uibModal', 'request', 'langs', NewUsersCtrl]);

    function NewUsersCtrl($rootScope, $scope, $http, $location, $uibModal, request, langs) {
        $scope.texts = {};
        $scope.file = {};
        $scope.followup_hours = [
            {
                text: 15,
                value: 15
            }, {
                text: 120,
                value: 120
            }
        ];

        $scope.init = function () {
            request.send('/texts', {}, function (data) {
                if (data) {
                    $scope.texts = data;
                    $scope.texts.first_followup_delay = $scope.texts.first_followup_delay.toString();
                    $scope.texts.second_followup_delay = $scope.texts.second_followup_delay.toString();
                }
                $scope.generateHours();
            }, 'get');
        };

        $scope.save = function () {
            request.send('/texts' + ($scope.texts.id ? '/' + $scope.texts.id : ''), {'texts': $scope.texts}, function (data) {
            }, 'put');
        };

        $scope.uploadFile = function (file) {
            var size = file.size / 1024;
            if (size > 500) {
                logger.logError(langs.get('Image size limit is 500 KB'));
                return;
            }

            $scope.file.name = file.name;
            var fd = new FormData();
            fd.append('file', file);

            $http.post('/api/v1/upload/fileS3', fd, {
                transformRequest: angular.identity,
                headers: { 'Content-Type': undefined }
            }).then(function (response) {
                $scope.file.url = JSON.parse(response.data.data);
                $scope.request = false;
            });
        };

        $scope.removeFile = function () {
            $scope.file = $scope.file.url = '';
        };

        $scope.send = function () {
            request.send('/homeadvisor/lookup', {'url': $scope.file.url}, function (data) {
            }, 'post');
        };

        $scope.generateHours = function () {
            for (var i = 1; i <= 24; i++) {
                $scope.followup_obj = {};
                $scope.followup_obj.text = i;
                $scope.followup_obj.value = i * 60;
                $scope.followup_hours.push($scope.followup_obj);
            }
        };

        $scope.getHourText = function (amount) {
            switch (amount) {
                case 15:
                    return 'minutes after Instant Text';
                    break;
                case 30:
                    return 'minutes after Instant Text';
                    break;
                case 60:
                    return 'hour after Instant Text';
                    break;
                default:
                    return 'hours after Instant Text';
                    break;
            }
        };
    };
})();

;