(function () {
    'use strict';

    angular.module('app').controller('SurveysCtrl', ['$rootScope', '$scope', '$uibModal', '$filter', '$location', '$timeout', 'request', 'langs', 'validate', 'logger', SurveysCtrl]);

    function SurveysCtrl($rootScope, $scope, $uibModal, $filter, $location, $timeout, request, langs, validate, logger) {
        $scope.clients = [];
        $scope.client = {};
        $scope.partners = [];
        $scope.partner = {};
        $scope.survey = {};
        $scope.open_edit = false;
        $scope.selectedClients = [];
        $scope.companyChanged = false;
        $scope.textChanged = false;
        $scope.emailChanged = false;
        $scope.oldCompany = angular.copy($scope.user.company_name);
        $scope.surveySchedule = '0';
        $scope.popup = {};
        $scope.timer = false;

        var date = new Date();
        $scope.seanceDate = date;

        var date = new Date();
        date.setHours(9, 0);
        $scope.seanceTime = date;
        $scope.timeMin = date;

        var date = new Date();
        date.setHours(21, 0);
        $scope.timeMax = date;

    	$scope.init = function() {
            $scope.getClients();
            $scope.getSurvey();
            $scope.checkCompany();
        };

        $scope.initPartners = function () {
            $scope.getClients();
            $scope.getPartners();
        };

        $scope.getClients = function() {
            request.send('/clients', false, function (data) {
                $scope.clients = data;
            }, 'get');
        };

        $scope.getPartners = function (partner_id) {
            partner_id = partner_id || false;
            request.send('/users/partners', false, function (data) {
                $scope.partners = data;
                if (partner_id) {
                    for (var k in $scope.partners) {
                        if ($scope.partners[k].id == partner_id) {
                            $scope.setPartner($scope.partners[k]);
                        }
                    }
                }
            }, 'get');
        };

        $scope.getSurvey = function(user_id) {
            user_id = user_id || false;
            request.send('/surveys' + (user_id ? '/' + user_id : ''), false, function (data) {
                $scope.survey = data;
                $scope.oldText = angular.copy($scope.survey.text);
                $scope.oldSender = angular.copy($scope.survey.sender);
                $scope.oldSubject = angular.copy($scope.survey.subject);
                $scope.oldEmail = angular.copy($scope.survey.email);
            }, 'get');
        };

        $scope.openClient = function() {
            $scope.open_edit = ! $scope.open_edit;
            $scope.client = {};
        };

        $scope.saveClient = function () {
            var error = 1;
            error *= validate.check($scope.form_client.firstname, 'First Name');
            error *= validate.phone($scope.form_client.phone, 'Phone');
            error *= validate.check($scope.form_client.email, 'Email');

            if (error) {
                $scope.client.phone = validate.phoneToNumber($scope.client.view_phone);
                request.send('/clients' + ($scope.client.id ? '/' + $scope.client.id : ''), $scope.client, function (data) {
                    $scope.getClients();
                    $scope.open_edit = false;
                }, ( ! $scope.client.id ? 'put' : 'post'));
            }
        };

        $scope.editClient = function (client) {
            $scope.open_edit = true;
            $scope.client = client;
        };

        $scope.removeClient = function (client_id) {
            if (confirm(langs.get('Do you realy want to remove this client?'))) {
                request.send('/clients/' + client_id, {}, function (data) {
                    $scope.open_edit = false;
                    $scope.getClients();
                }, 'delete');
            }
        };

        $scope.addPartner = function(partner_id) {
            partner_id = partner_id || false;

            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: 'PartnersCreate.html',
                controller: 'ModalPartnersCreateCtrl',
                resolve: {
                    items: function () {
                        return { 'partner': $scope.by_id(partner_id) };
                    }
                }
            });

            modalInstance.result.then(function (response) {
                $scope.getPartners(response.id);
            }, function () {

            });
        };

        $scope.by_id = function (partner_id) {
            for (var k in $scope.partners) {
                if ($scope.partners[k].id == partner_id) {
                    return $scope.partners[k];
                }
            }

            return {};
        };

        $scope.setPartner = function (partner) {
            $scope.partner = partner;

            if ($scope.partner.id) {
                $scope.getSurvey($scope.partner.id);
            }
        };

        $scope.removePartner = function (partner_id) {
            if (confirm(langs.get('Do you realy want to remove this partner?'))) {
                request.send('/users/partners/' + partner_id, {}, function (data) {
                    $scope.setPartner({});
                    $scope.getPartners();
                }, 'delete');
            }
        };

        $scope.openDate = function () {
            $scope.popup.popup_date = ! $scope.popup.popup_date;
        };

        $scope.selectClient = function (client) {
            var check = true;
            var newList = [];
            for (var k in $scope.selectedClients) {
                if ($scope.selectedClients[k].id == client.id) {
                    check = false;
                } else {
                    newList.push($scope.selectedClients[k]);
                }
            }
            $scope.selectedClients = newList;

            if (check) {
                $scope.selectedClients.push(client);
            }
        };

        $scope.isSelected = function (client) {
            for (var k in $scope.selectedClients) {
                if ($scope.selectedClients[k].id == client.id) {
                    return true;
                }
            }

            return false;
        };

        $scope.maxChars = function (field) {
            var max = 0;
            for (var k in $scope.clients) {
                max = Math.max(max, $scope.clients[k][field].length);
            }
            return max;
        };

        $scope.companyChange = function () {
            $scope.companyChanged = false;
            if ($scope.oldCompany != $scope.user.company_name) {
                $scope.companyChanged = true;
            }
        };

        $scope.companySave = function () {
            var user = $scope.partner.id ? $scope.partner : $scope.user;
            request.send('/users/company' + ($scope.partner.id ? '/' + $scope.partner.id : ''), {'company': user.company_name}, function (data) {
                if (data) {
                    user.company_status = data.status;
                    $scope.companyChanged = false;
                    $scope.checkCompany();
                }
            }, 'put');
        };

        $scope.checkCompany = function () {
            var user = $scope.partner.id ? $scope.partner : $scope.user;
            $timeout.cancel($scope.timer);
            if ($scope.user.company_status == 'pending') {
                $scope.timer = $timeout(function () {
                    request.send('/users/status' + ($scope.partner.id ? '/' + $scope.partner.id : ''), {}, function (data) {
                        if (data) {
                            user.company_status = data.status;
                        }
                        $scope.checkCompany();
                    }, 'get');
                }, 5000);
            }
        };

        $scope.$watch('survey.text', function(oldVal, newVal) {
            $scope.changeText();
        });

        $scope.changeText = function () {
            $scope.textChanged = false;
            if ($scope.oldText != $scope.survey.text) {
                $scope.textChanged = true;
            }
        };

        $scope.$watch('survey.email', function (oldVal, newVal) {
            $scope.changeEmail();
        });

        $scope.changeEmail = function () {
            $scope.emailChanged = false;
            if ($scope.oldSender != $scope.survey.sender) {
                $scope.emailChanged = true;
            }

            if ($scope.oldSubject != $scope.survey.subject) {
                $scope.emailChanged = true;
            }

            if ($scope.oldEmail != $scope.survey.email) {
                $scope.emailChanged = true;
            }
        };

        $scope.saveSurveyText = function () {
            request.send('/surveys/text' + ($scope.partner.id ? '/' + $scope.partner.id : ''), $scope.survey, function (data) {
                $scope.oldText = angular.copy($scope.survey.text);
                $scope.textChanged = false;
            }, 'post');
        };

        $scope.saveSurveyEmail = function () {
            request.send('/surveys/email' + ($scope.partner.id ? '/' + $scope.partner.id : ''), $scope.survey, function (data) {
                $scope.oldSender = angular.copy($scope.survey.sender);
                $scope.oldSubject = angular.copy($scope.survey.subject);
                $scope.oldEmail = angular.copy($scope.survey.email);
                $scope.emailChanged = false;
            });
        };

        $scope.saveUrls = function () {
            request.send('/urls/bulk/' + $scope.partner.id, {'urls' : $scope.partner.urls});
        };

        $scope.activeUrl = function (input) {
            request.send('/urls/' + input.id, input, false);
        };

        $scope.send = function() {
            var user = $scope.partner.id ? $scope.partner : $scope.user;
            var error = 1;
            if ( ! $scope.selectedClients.length) {
                logger.logError(langs.get('Choose clients from the list at the left'));
                error = 0;
            }

            if ( ! $scope.seanceText && ! $scope.seanceEmail) {
                logger.logError(langs.get('Choose type of Review message'));
                error = 0;
            }

            if ($scope.seanceText) {
                if ($scope.survey.text == '') {
                    logger.logError(langs.get('SMS Text can\'t be blank'));
                    error = 0;
                } else {
                    if ( ! ($scope.survey.text.indexOf('[$Link]') + 1)) {
                        logger.logError(langs.get('SMS Text must have [$Link] tag'));
                        error = 0;
                    }
                }

                if (user.company_name == '') {
                    logger.logError(langs.get('Company Name is required'));
                    error = 0;
                } else {
                    if (user.company_status != 'verified' || $scope.companyChanged) {
                        logger.logError(langs.get('Company Name must be verified'));
                        error = 0;
                    } 
                }
            }

            if ($scope.seanceEmail) {
                if ($scope.survey.email == '') {
                    logger.logError(langs.get('Email Text is required'));
                    error = 0;
                }

                if ($scope.survey.subject == '') {
                    logger.logError(langs.get('Subject Line is required'));
                    error = 0;
                }

                if ($scope.survey.sender == '') {
                    logger.logError(langs.get('Sender Name is required'));
                    error = 0;
                }
            }

            if (error) {
                var time = {
                    'year': $scope.seanceDate.getFullYear(),
                    'month': $scope.seanceDate.getMonth() + 1,
                    'date': $scope.seanceDate.getDate(),
                    'hours': $scope.seanceTime.getHours(),
                    'minutes': $scope.seanceTime.getMinutes()
                };

                var data = {
                    'clients': $scope.selectedClients,
                    'text': $scope.seanceText,
                    'email': $scope.seanceEmail,
                    'schedule': $scope.surveySchedule,
                    'time': time,
                    'survey': $scope.survey,
                    'company': user.company_name
                };
                
                request.send('/seances' + ($scope.partner.id ? '/' + $scope.partner.id : ''), data, function (data) {
                    $scope.oldText = angular.copy($scope.survey.text);
                    $scope.textChanged = false;

                    $scope.oldSender = angular.copy($scope.survey.sender);
                    $scope.oldSubject = angular.copy($scope.survey.subject);
                    $scope.oldEmail = angular.copy($scope.survey.email);
                    $scope.emailChanged = false;
                }, 'put');
            }
        };
    };
})();

;

(function () {
    'use strict';

    angular.module('app').controller('ModalPartnersCreateCtrl', ['$rootScope', '$scope', '$uibModalInstance', 'request', 'validate', 'logger', 'langs', 'items', ModalPartnersCreateCtrl]);

    function ModalPartnersCreateCtrl($rootScope, $scope, $uibModalInstance, request, validate, logger, langs, items) {
        $scope.partner = angular.copy(items.partner);

        $scope.save = function () {
            var error = 1;
            error *= validate.check($scope.form_partner.firstname, 'Name');
            error *= validate.check($scope.form_partner.email, 'Email');
            error *= validate.phone($scope.form_partner.view_phone, 'Phone');

            if (error) {
                request.send('/users/partners/' + ($scope.partner.id ? $scope.partner.id : ''), $scope.partner, function (data) {
                    if (data) {
                        $uibModalInstance.close(data);
                    }
                }, (!$scope.partner.id ? 'put' : 'post'));
            }
        };

        $scope.cancel = function () {
            $uibModalInstance.dismiss('cancel');
        };
    };
})();

;