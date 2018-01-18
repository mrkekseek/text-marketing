(function () {
    'use strict';

    angular.module('app').controller('AppointmentCtrl', ['$scope', '$uibModal', '$timeout', 'request', 'langs', 'validate', AppointmentCtrl]);

    function AppointmentCtrl($scope, $uibModal, $timeout, request, langs, validate) {
        $scope.clients = [];
        $scope.partners = [];
        $scope.client = {};
        $scope.selectedClient = {};
        $scope.partner = {};
        $scope.time = new Date();

        $scope.init = function() {
            $scope.getClients();
            $scope.getPartners();
        };

        $scope.getClients = function(){
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

        $scope.setClient = function(client) {
            $scope.selectedClient = client;
        };

        $scope.openClient = function() {
            $scope.open_edit = ! $scope.open_edit;
            $scope.client = {};
        };

        $scope.saveClient = function () {
            var error = 1;
            error *= validate.check($scope.form_client.firstname, 'First Name');
            error *= validate.phone($scope.form_client.phone, 'Phone');

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

        $scope.setPartner = function (partner) {
            $scope.partner = partner;

            if ($scope.partner.id) {
                
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

        $scope.removePartner = function (partner_id) {
            if (confirm(langs.get('Do you realy want to remove this partner?'))) {
                request.send('/users/partners/' + partner_id, {}, function (data) {
                    $scope.setPartner({});
                    $scope.getPartners();
                }, 'delete');
            }
        };

        $scope.by_id = function (partner_id) {
            for (var k in $scope.partners) {
                if ($scope.partners[k].id == partner_id) {
                    return $scope.partners[k];
                }
            }
            return {};
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

        $scope.createText = function() {
            return 'Hi ' + $scope.selectedClient.firstname + ', your technician ' + $scope.partner.firstname + ' will be there at ' + $scope.createTime() + '. If there is an issue please text back, thanks!';
        };

        $scope.createTime = function() {
            var ampm = $scope.time.getHours() >= 12 ? 'PM' : 'AM';
            var hours = $scope.time.getHours() > 12 ? $scope.time.getHours() - 12 : $scope.time.getHours();
            hours = hours.toString().length < 2 ? '0' + hours : hours;
            var minutes = $scope.time.getMinutes();
            minutes = minutes.toString().length < 2 ? '0' + minutes : minutes;

            return hours + ':' + minutes + ' ' + ampm;
        }

        $scope.send = function() {
            var data = {
                'text': $scope.createText()
            };

            request.send('/appointment/' + $scope.partner.id + '/' + $scope.selectedClient.id, data, function (data) {
                
            }, 'put');
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