(function () {
    'use strict';

    angular.module('app').controller('NewUsersCtrl', ['$rootScope', '$scope', '$http', '$location', '$uibModal', 'request', 'langs', NewUsersCtrl]);

    function NewUsersCtrl($rootScope, $scope, $http, $location, $uibModal, request, langs) {
        $scope.texts = {};
        $scope.file = {};

        $scope.init = function () {
            request.send('/settings', {}, function (data) {
                if (data) {
                        $scope.texts = data;
                }
            }, 'get');
        };

        $scope.save = function () {
            request.send('/settings/update', {'texts': $scope.texts}, function (data) {
            }, 'post');
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
    };
})();

;