(function () {
    'use strict';

    angular.module('app').controller('HomeAdvisorCtrl', ['$rootScope', '$scope', '$timeout', 'request', 'langs', 'logger', 'validate', HomeAdvisorCtrl]);

    function HomeAdvisorCtrl($rootScope, $scope, $timeout, request, langs, logger, validate) {
        $scope.ha = {};
        $scope.inputs = [];
        $scope.list = [];
        $scope.companyChanged = false;
        $scope.oldCompany = angular.copy($scope.user.company_name);

        $scope.init = function() {
            $scope.get();
            $scope.getLeads();
        };

        $scope.get = function() {
            request.send('/homeadvisor', {}, function (data) {
                if (data) {
                    $scope.ha = data;
                    if ($scope.ha.additional_phones) {
                        $scope.inputs = $scope.ha.additional_phones.split(',');
                    }
                    $scope.inputs.push('');
                }
            }, 'get');
        };

        $scope.getLeads = function() {
            request.send('/clients/leads', {}, function (data) {
                $scope.list = data;
                for (var k in $scope.list) {
                    var date = new Date($scope.list[k].created_at);
                    $scope.list[k].created_at = date;
                }
            }, 'get');
        };

        $scope.getSuffix = function(day) {
            switch (day) {
                case '1': return 'st';
                case '2': return 'nd';
                case '3': return 'rd';
                default: return  'th';
            }
        };

        $scope.activate = function() {
            request.send('/homeadvisor/activate', {}, function (data) {
                $scope.ha.send_request = true;
            }, 'put');
        };

        $scope.add = function() {
            $scope.inputs.push('');
        };

        $scope.remove = function(index) {
            $scope.inputs.splice(index, 1);
        };

        $scope.save = function() {
            var error = 1;

            if ($scope.ha.text == '') {
                logger.logError(langs.get('SMS Text can\'t be blank'));
                error = 0;
            }

            if ($scope.user.company_name == '') {
                logger.logError(langs.get('Company Name is required'));
                error = 0;
            } else {
                if ($scope.user.company_status != 'verified' || $scope.companyChanged) {
                    logger.logError(langs.get('Company Name must be verified'));
                    error = 0;
                }
            }

            error *= validate.phone($scope.form_ha.phone, 'Number for alerts');
            for (var k in $scope.inputs) {
                if ($scope.inputs[k] != '') {
                    error *= validate.phone($scope.form_ha['phone_' + k], 'Additional phone');
                }
            }

            if (error) {
                $scope.ha.additional_phones = $scope.inputs.join(',');
                request.send('/homeadvisor' + ($scope.ha.id ? '/' + $scope.ha.id : ''), {'ha': $scope.ha, 'user': $scope.user}, false, ($scope.ha.id ? 'post' : 'put'));
            }
        };

        $scope.enable = function () {
            request.send('/homeadvisor/enable/' + $scope.ha.id, {}, false, 'put');
        };

        $scope.maxChars = function (field) {
            var max = 0;
            for (var k in $scope.list) {
                max = Math.max(max, $scope.list[k][field].length);
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
            request.send('/users/company', { 'company': $scope.user.company_name }, function (data) {
                if (data) {
                    $scope.user.company_status = data.status;
                    $scope.companyChanged = false;
                    $scope.checkCompany();
                }
            }, 'put');
        };

        $scope.checkCompany = function () {
            $timeout.cancel($scope.timer);
            if ($scope.user.company_status == 'pending') {
                $scope.timer = $timeout(function () {
                    request.send('/users/status', {}, function (data) {
                        if (data) {
                            $scope.user.company_status = data.status;
                        }
                        $scope.checkCompany();
                    }, 'get');
                }, 5000);
            }
        };
    };
})();

;