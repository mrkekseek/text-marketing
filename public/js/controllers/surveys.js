(function () {
    'use strict';

    angular.module('app').controller('SurveysCtrl', ['$rootScope', '$scope', '$uibModal', '$filter', '$location', 'request', 'langs', 'validate', 'logger', SurveysCtrl]);

    function SurveysCtrl($rootScope, $scope, $uibModal, $filter, $location, request, langs, validate, logger) {
        $scope.open_edit = false;
        $scope.popup_date = false;
        $scope.surveys_schedule = '0';
        $scope.team = {};
        $scope.client = {};
        $scope.survey = {};
        $scope.active_client = {};
        $scope.clients = [];
        $scope.popup = {};
        $scope.seance_date = new Date();
        $scope.seance_time = new Date();
        $scope.seance_text = '';
        $scope.seance_email = '';
        $scope.max_text_len = 140 - ' Txt STOP to OptOut'.length;
        $scope.max_lms_text_len = 500 - ' Txt STOP to OptOut'.length;
        $scope.check_firstname = false;
        $scope.check_link = false;
        $scope.seances = [];

    	$scope.init = function() {
            $scope.get();
            $scope.getSurvey();
        };

        $scope.get = function() {
            request.send('/clients', false, function (data) {
                $scope.clients = data;
            }, 'get');
        };

        $scope.getSurvey = function() {
            request.send('/surveys', false, function (data) {
                $scope.survey = data;
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

        $scope.setClient = function(client) {
            $scope.open_edit = false;
            client.send = ! client.send;
            request.send('/clients/' + client.id, false, function (data) {
                $scope.active_client = data.client;
                $scope.seances = data.seances;
            }, 'get');
        };

        $scope.send = function() {
            var error = 1;
            var send_clients = [];
            for (var k in $scope.clients) {
                if ($scope.clients[k].send) {
                    send_clients.push($scope.clients[k]);
                }
            }

            if (! send_clients.length) {
                logger.logError(langs.get('Choose clients, please.'));
                error = 0;
            }

            var type = [];
            if ($scope.seance_text) {
                type.push($scope.seance_text);
            }

            if ($scope.seance_email) {
                type.push($scope.seance_email);
            }

            if (! type.length) {
                logger.logError(langs.get('Choose type survey, please.'));
                error = 0;
            }

            if (! $scope.survey.text && (type.indexOf('text') + 1)) {
                logger.logError(langs.get('Text of SMS is empty.'));
                error = 0;
            }

            if (! $scope.survey.company_name && (type.indexOf('text') + 1)) {
                logger.logError(langs.get('Company Name is empty.'));
                error = 0;
            }

            if (! $scope.survey.email && (type.indexOf('email') + 1)) {
                logger.logError(langs.get('Text of Email is empty.'));
                error = 0;
            }

            if (! $scope.survey.subject && (type.indexOf('email') + 1)) {
                logger.logError(langs.get('Subject Line is empty.'));
                error = 0;
            }

            if (! $scope.survey.sender && (type.indexOf('email') + 1)) {
                logger.logError(langs.get('Sender Name is empty.'));
                error = 0;
            }

            if (error) {
                var post_mas = {
                    'clients': send_clients,
                    'date': $scope.seance_date,
                    'time': $scope.seance_time,
                    'type': type,
                    'survey': $scope.survey
                };

                request.send('/seances/save', post_mas, function (data) {

                }, 'put');
            }
        };

        $scope.insertMask = function(textarea, mask) {
           //$scope.survey.survey_text = charset.set(textarea, mask);
        };

        $scope.charsCount = function(text) {
            $scope.check_link = false;
            $scope.check_firstname = false;
            if (text) {
                var firstname = 0;
                var link = 0;
                if (text.indexOf('[$client_firstname]') + 1) {
                    $scope.check_firstname = true;
                    for (var k in $scope.clients) {
                        if ($scope.clients[k].send) {
                            if ($scope.clients[k].firstname.length > firstname) {
                                firstname = $scope.clients[k].firstname.length;
                            }
                        }
                    }
                    firstname -= '[$client_firstname]'.length;
                }
                if (text.indexOf('[$Link]') + 1) {
                    $scope.check_link = true;
                    link = 21 - '[$Link]'.length;
                }

                return ($scope.team.company_name ? $scope.team.company_name.length : 0) + ': '.length + text.length + firstname + link;
            }
            return 0;
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