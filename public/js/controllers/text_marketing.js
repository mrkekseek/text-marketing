(function () {
    'use strict';

    angular.module('app').controller('MarketingSettingsCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', MarketingSettingsCtrl]);

    function MarketingSettingsCtrl($rootScope, $scope, $uibModal, request, langs) {
    	$scope.team = {};
        $scope.team.company_name = 'ContractorTexter';
        $scope.team.phones = [];

        $scope.addInput = function(input) {
            $scope.team.phones.push(input);
            $scope.input = '';
        };

        $scope.removeInput = function(index) {
                $scope.team.phones.splice(index, 1);
        };

        $scope.save = function() {
            console.log($scope.team);
        };
    };
})();

;



(function () {
    'use strict';

    angular.module('app').controller('MarketingContactsCtrl', ['$rootScope', '$scope', '$uibModal', 'validate', 'request', 'langs', MarketingContactsCtrl]);

    function MarketingContactsCtrl($rootScope, $scope, $uibModal, request, langs, validate) {
    	$scope.newList = true;
        $scope.requestFinish = true;
        $scope.selected = -1;

        $scope.contactList =  [{'phones': [{'number' : '222222222', 'birthDay': new Date(), 'firstName' : 'FNAME', 'lastName' : 'LNAME', 'editable' : false}], 'listName' : 'listsName1', 'editable' : false}];
        $scope.oldContactList = $scope.contactList;

    	$scope.create = function() {
    		$scope.newList = false;
    	};

    	$scope.cancel = function(index) {
    		$scope.contactList[index].editable = ! $scope.contactList[index].editable;
    	};

        $scope.cancelPhone = function(itemIndex,index) {
            console.log('aaaaa');
            $scope.contactList[itemIndex].phones[index] = $scope.oldContactList[itemIndex].phones[index];
        };

        $scope.saveList = function(name) {
             $scope.contactList.push({
                'listName' : name,
                'number' : '',
                'bDay' : '',
                'fName' : '',
                'lName' : '',
                'editable': false
            });
             $scope.list = '';
            $scope.newList = true;
        };

        $scope.choose = function(index) {   
            $scope.selected = $scope.selected == index ? -1 : index;
        };

        $scope.edit = function(index) {
            $scope.contactList[index].editable = ! $scope.contactList[index].editable;
        };

        $scope.edit_phone = function(itemIndex, index) {
            $scope.contactList[itemIndex].phones[index].editable = true;
        };
    };
})();

;