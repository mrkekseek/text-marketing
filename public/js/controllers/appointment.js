(function () {
    'use strict';

    angular.module('app').controller('AppointmentCtrl', ['$scope', '$uibModal', '$timeout', '$location', 'request', 'langs', 'validate', AppointmentCtrl]);

    function AppointmentCtrl($scope, $uibModal, $timeout, $location, request, langs, validate) {
        $scope.clients = [];
        $scope.employees = [];
        $scope.client = {};
        $scope.selectedClient = {};
        $scope.employee = {};
        $scope.time = new Date();
        $scope.date = new Date();
        $scope.file = {};
        $scope.popup = {};
        $scope.activeDate = false;
        $scope.appointmentSchedule = '0';
        $scope.months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $scope.appointmentDate = new Date();

        var date = new Date();
        date.setHours(9, 0);
        $scope.appointmentTime = date;
        $scope.timeMin = date;

        var date = new Date();
        date.setHours(21, 0);
        $scope.timeMax = date;

        $scope.init = function() {
            $scope.getClients();
            $scope.getEmployees();
        };

        $scope.openDate = function (type) {
            $scope.popup[type] = ! $scope.popup[type];
        };

        $scope.toggleDate = function() {
        };

        $scope.getClients = function(){
            request.send('/clients', false, function (data) {
                $scope.clients = data;
            }, 'get');
        };

        $scope.getEmployees = function (employee_id) {
            employee_id = employee_id || false;
            request.send('/users/employees', false, function (data) {
                $scope.employees = data;
                if (employee_id) {
                    for (var k in $scope.employees) {
                        if ($scope.employees[k].id == employee_id) {
                            $scope.setEmployee($scope.employees[k]);
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

        $scope.setEmployee = function (employee) {
            $scope.file.url = '';
            $scope.employee = employee;
            if ($scope.employee.avatar) {
                $scope.file.url = $location.protocol() + '://' + $location.host() + '/' + $scope.employee.avatar;
            }
        };

        $scope.addEmployee = function(employee_id) {
            employee_id = employee_id || false;

            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: 'EmployeesCreate.html',
                controller: 'ModalEmployeeCreateCtrl',
                resolve: {
                    items: function () {
                        return { 'employee': $scope.by_id(employee_id) };
                    }
                }
            });

            modalInstance.result.then(function (response) {
                $scope.getEmployees(response.id);
            }, function () {

            });
        };

        $scope.removeEmployee = function (employee_id) {
            if (confirm(langs.get('Do you realy want to remove this employee?'))) {
                request.send('/users/employees/' + employee_id, {}, function (data) {
                    $scope.setEmployee({});
                    $scope.getEmployees();
                }, 'delete');
            }
        };

        $scope.by_id = function (employee_id) {
            for (var k in $scope.employees) {
                if ($scope.employees[k].id == employee_id) {
                    return $scope.employees[k];
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
            var user = $scope.employee.id ? $scope.employee : $scope.user;
            request.send('/users/company' + ($scope.employee.id ? '/' + $scope.employee.id : ''), {'company': user.company_name}, function (data) {
                if (data) {
                    user.company_status = data.status;
                    $scope.companyChanged = false;
                    $scope.checkCompany();
                }
            }, 'put');
        };

        $scope.checkCompany = function () {
            var user = $scope.employee.id ? $scope.employee : $scope.user;
            $timeout.cancel($scope.timer);
            if ($scope.user.company_status == 'pending') {
                $scope.timer = $timeout(function () {
                    request.send('/users/status' + ($scope.employee.id ? '/' + $scope.employee.id : ''), {}, function (data) {
                        if (data) {
                            user.company_status = data.status;
                        }
                        $scope.checkCompany();
                    }, 'get');
                }, 5000);
            }
        };

        $scope.createText = function() {
            return 'Hi ' + $scope.selectedClient.firstname + ', your technician ' + $scope.employee.firstname + ' will be there at ' + $scope.createTime() + $scope.createDate() + '. If there is an issue please text back, thanks!';
        };

        $scope.createDate = function() {
            var date = '';
            if ($scope.activeDate) {
                var year = $scope.date.getFullYear().toString();
                date = ' on ' + $scope.date.getDate() + '/' + ($scope.date.getMonth() + 1) + '/' + year.slice(2);
            }
            return date;
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
            var time = {
                'year': $scope.appointmentDate.getFullYear(),
                'month': $scope.appointmentDate.getMonth() + 1,
                'date': $scope.appointmentDate.getDate(),
                'hours': $scope.appointmentTime.getHours(),
                'minutes': $scope.appointmentTime.getMinutes()
            };

            var data = {
                'text': $scope.createText(),
                'date': time,
                'schedule': $scope.appointmentSchedule
            };

            request.send('/appointment/' + $scope.employee.id + '/' + $scope.selectedClient.id, data, function (data) {
                
            }, 'put');
        };
    };
})();

;

(function () {
    'use strict';

    angular.module('app').controller('ModalEmployeeCreateCtrl', ['$rootScope', '$scope', '$uibModalInstance', '$http', '$location', 'request', 'validate', 'logger', 'langs', 'items', ModalEmployeeCreateCtrl]);

    function ModalEmployeeCreateCtrl($rootScope, $scope, $uibModalInstance, $http, $location, request, validate, logger, langs, items) {
        $scope.employee = angular.copy(items.employee);
        $scope.file = {};

        if ($scope.employee.avatar) {
            $scope.file.url = $location.protocol() + '://' + $location.host() + '/' + $scope.employee.avatar;
        }

        $scope.uploadFile = function(file) {
            var size = file.size / 1024;
            if (size > 500) {
                logger.logError(langs.get('Image size limit is 500 KB'));
                return;
            }

            $scope.file.name = file.name;
            var fd = new FormData();
            fd.append('file', file);

            $http.post('/api/v1/upload/file', fd, {
                transformRequest: angular.identity,
                headers: {'Content-Type': undefined}
            }).then(function(response) {
                $scope.employee.avatar = response.data.data;
                $scope.file.url = $location.protocol() + '://' + $location.host() + '/' + response.data.data;
                $scope.request = false;
            });
        };

        $scope.removeMMS = function() {
            $scope.employee.avatar = $scope.file.url = '';
        };

        $scope.save = function () {
            var error = 1;
            error *= validate.check($scope.form_employee.firstname, 'Name');
            error *= validate.check($scope.form_employee.email, 'Email');
            error *= validate.phone($scope.form_employee.view_phone, 'Phone');

            if (error) {
                request.send('/users/employees/' + ($scope.employee.id ? $scope.employee.id : ''), $scope.employee, function (data) {
                    if (data) {
                        $uibModalInstance.close(data);
                    }
                }, (!$scope.employee.id ? 'put' : 'post'));
            }
        };

        $scope.cancel = function () {
            $uibModalInstance.dismiss('cancel');
        };
    };
})();

;