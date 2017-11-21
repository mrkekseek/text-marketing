(function () {
    'use strict';

    angular.module('app').controller('SurveysCtrl', ['$rootScope', '$scope', '$uibModal', '$filter', '$location', 'request', 'langs', 'validate', SurveysCtrl]);

    function SurveysCtrl($rootScope, $scope, $uibModal, $filter, $location, request, langs, validate) {
        $scope.open_edit = false;
        $scope.popup_date = false;
        $scope.surveys_schedule = '0';
        $scope.client = {};
        $scope.active_client = {};
        $scope.clients = [];
        $scope.popup = {};

    	$scope.init = function() {
            $scope.get();
        };

        $scope.get = function() {
            request.send('/clients', false, function (data) {
                $scope.clients = data;
            }, 'get');
        };

        $scope.openClient = function() {
            $scope.open_edit = ! $scope.open_edit;
            $scope.client_firstname = '';
            $scope.client_lastname = '';
            $scope.client_phone = '';
            $scope.client_email = '';
            $scope.client_id = false;
        };

        $scope.openDate = function() {
            $scope.popup.popup_date = ! $scope.popup.popup_date;
        };

        $scope.setClient = function(client_id) {
            $scope.open_edit = false;
            request.send('/clients/' + client_id, false, function (data) {
                $scope.active_client = data;
            }, 'get');
        };

        $scope.edit = function(client) {
            $scope.open_edit = true;
            $scope.client_firstname = client.firstname;
            $scope.client_lastname = client.lastname;
            $scope.client_phone = client.view_phone;
            $scope.client_email = client.email;
            $scope.client_id = client.id;
        };

        $scope.save = function() {
            var error = 1;
            var post_mas = {
                'firstname': $scope.client_firstname,
                'lastname': $scope.client_lastname,
                'phone': $scope.client_phone,
                'email': $scope.client_email
            };

            error *= validate.check($scope.form_client.firstname, 'First Name');
            error *= validate.check($scope.form_client.phone, 'Phone');
            error *= validate.check($scope.form_client.email, 'Email');

            if (error) {
                request.send('/clients/' + (! $scope.client_id ? 'save' : $scope.client_id), post_mas, function (data) {
                    $scope.clients = data;
                    $scope.open_edit = false;
                }, ( ! $scope.client.id ? 'put' : 'post'));
            }
        };

        $scope.remove = function(client_id) {
            if (confirm(langs.get('Do you realy want to remove this client?'))) {
                request.send('/clients/' + client_id, false, function (data) {
                    $scope.open_edit = false;
                    $scope.active_client = {};
                    $scope.client_firstname = '';
                    $scope.client_lastname = '';
                    $scope.client_phone = '';
                    $scope.client_email = '';
                    $scope.client_id = false;
                    $scope.get();
                }, 'delete');
            }
        };
    };
})();

;