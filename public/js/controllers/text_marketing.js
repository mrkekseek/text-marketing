(function () {
    'use strict';

    angular.module('app').controller('MarketingSettingsCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', MarketingSettingsCtrl]);

    function MarketingSettingsCtrl($rootScope, $scope, $uibModal, request, langs) {
    	$scope.team = {};
        $scope.team.company_name = 'ContractorTexter';
        $scope.team.phones = [];
        $scope.emails = [];

        $scope.addInput = function(input) {
            $scope.team.phones.push(input);
            $scope.input = '';
        };

        $scope.removeInput = function(index) {
            $scope.team.phones.splice(index, 1);
        };

        $scope.addEmail = function(input) {
            $scope.emails.push(input);
            $scope.email = '';
        };

        $scope.removeEmail = function(index) {
            $scope.emails.splice(index, 1);
        };

    };
})();

;



(function () {
    'use strict';

    angular.module('app').controller('MarketingContactsCtrl', ['$rootScope', '$scope', '$uibModal', '$filter', '$location', 'request', 'langs', 'validate', MarketingContactsCtrl]);

    function MarketingContactsCtrl($rootScope, $scope, $uibModal, $filter, $location, request, langs, validate) {
        $scope.requestFinish = true;
        $scope.selected = -1;
        var oldContactList = [];
        $scope.contactList = [];
        
        $scope.numbers = [{'phoneNnumber' : '112313123', 'firstName' : 'name', 'lastName' : 'surname', 'selected' : false},
        {'phoneNnumber' : '2222222', 'firstName' : 'namee', 'lastName' : 'surname', 'selected' : false}];

        $scope.init = function() {
            $scope.get();
            $scope.copy();
        };

        $scope.get = function() {
             request.send('/clients', false, function (data) {
                $scope.contactList = data;
            }, 'get');
        };

        $scope.copy = function() {
            oldContactList = angular.copy($scope.contactList);
        };

        $scope.cancel = function(index) {
            $scope.contactList[index].editable = false;
            $scope.contactList =  angular.copy(oldContactList);
        };

        $scope.save = function(index) {
            $scope.contactList[index].editable = false;
            $scope.copy();
        };

        $scope.create = function(name) {
            $scope.selected = -1;
            $scope.contactList.unshift({
                'listName' : name,
                'editable': true,
                'phones' : []
            });
        };

        $scope.choose = function(index) {   
            $scope.selected = $scope.selected == index ? -1 : index;
        };

        $scope.edit = function(index) {
            $scope.contactList[index].editable = ! $scope.contactList[index].editable;
        };

        $scope.savePhone = function(itemIndex, index) {
            $scope.contactList[itemIndex].phones[index].editable = false;
            oldContactList[itemIndex].phones[index] =  angular.copy($scope.contactList[itemIndex].phones[index]);
        };

        $scope.createPhone = function(index) {
            $scope.contactList[index].phones = $scope.contactList[index].phones ? $scope.contactList[index].phones : [];
            $scope.contactList[index].phones.unshift({
                'editable' : true,
                'number' : '',
                'birthDay': new Date(),
                'firstName' : '',
                'lastName' : '',
                'source' : 'Manually'
            });
        };

        $scope.openImport = function() {

            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: 'ImportFile.html',
                controller: 'ImportFileCtrl'
            });

            modalInstance.result.then(function(response) {
            }, function () {
                
            });
        };
    };
})();

;

(function () {
    'use strict';

    angular.module('app').controller('ImportFileCtrl', ['$rootScope', '$scope', '$uibModalInstance', 'request', 'langs', 'logger', ImportFileCtrl]);

    function ImportFileCtrl($rootScope, $scope, $uibModalInstance, request, langs, logger) {

        $scope.csv = {'phones_firstname': 1,
                    'phones_lastname': 2,
                    'phones_number': 3,
                    'phones_email': "",
                    'starts_from': "0",
                    'upload_csv': false};

        $scope.upload_progress = false;
        $scope.upload_percent = 100;

       $scope.save = function() {
            var error = 1;
            if (! $scope.csv.upload_csv)
            {
                logger.logError('Please choose file');
                return;
            }

           if (error)
            {
                request.send('/phones/csv/', $scope.csv, function(data) {
                    if (data)
                    {
                        $uibModalInstance.close(data);
                    }
                });
            }
        };

        $scope.cancel = function() {
            $uibModalInstance.dismiss('cancel');
        };

        $scope.upload_csv = function(event) {
            var files = event.target.files;
            if (files.length)
            {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '/api/pub/upload/', true);
                xhr.onload = function(event)
                {
                    if (this.status == 200)
                    {
                        var response = JSON.parse(this.response);
                        if (response.data) {
                            var part = response.data.split('/data/');
                            var ext = part[1].split('.');
                            $timeout(function() { $scope.csv.upload_csv = '/data/' + part[1]; });
                        }
                        $scope.upload_progress = false;
                    }
                };

                xhr.upload.onprogress = function(event)
                {
                    if (event.lengthComputable)
                    {
                        $scope.upload_progress = true;
                        $scope.upload_percent = Math.round(event.loaded * 100 / event.total);
                    }
                };

                var fd = new FormData();
                fd.append("file", files[0]);

                xhr.send(fd);
                $scope.upload_progress = true;
            }
        };

        $scope.getFileName = function(path) {
            if (!path || path == "") return '';
            return path.replace(/^.*[\\\/]/, '')
        };
        
    };
})();

;