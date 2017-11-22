
(function () {
	'use strict';

	angular.module('app').controller('MarketingSendCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', MarketingSendCtrl]);

	function MarketingSendCtrl($rootScope, $scope, $uibModal, request, langs) {
		$scope.step = 1;
		$scope.minDate = new Date();
		$scope.message = {'messagesFollowupEnable' : '0', 'messagesText' : '', 'messagesFollowupText' : '', 'messagesSchedule' : '0'};
		$scope.contactList =  [{'phones': [{'number' : '222222222', 'birthDay': new Date(), 'firstName' : 'FNAME', 'lastName' : 'LNAME', 'editable' : false, 'source' : 'Other'},
		{'number' : '111111111', 'birthDay': new Date(), 'firstName' : 'name', 'lastName' : 'surname', 'editable' : false, 'source' : 'Manually'}],
		'listName' : 'listsName1', 'editable' : false}];
		var oldContactList = []
		

		 $scope.init = function() {
            $scope.copy();
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
                'editable': true
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

        $scope.insertAtCaret = function(areaId,text) {
            var txtarea = document.getElementById(areaId);
            var scrollPos = txtarea.scrollTop;
            var strPos = 0;
            var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ? 
                'ff' : (document.selection ? 'ie' : false ) );
            if (br == 'ie')
            {
                txtarea.focus();
                var range = document.selection.createRange();
                range.moveStart('character', -txtarea.value.length);
                strPos = range.text.length;
            }
            else if (br == 'ff') strPos = txtarea.selectionStart;
            
            var front = (txtarea.value).substr(0,strPos);  
            var back = (txtarea.value).substr(strPos,txtarea.value.length);
            if(front.substr(-1) != ' ' && front.substr(-1) != '')
            {
                text = ' ' + text;
            }

            txtarea.value = front + text + back;

            strPos = strPos + text.length;
            if (br == 'ie')
            {
                txtarea.focus();
                var range = document.selection.createRange();
                range.moveStart('character', -txtarea.value.length);
                range.moveStart('character', strPos);
                range.moveEnd('character', 0);
                range.select();
            }
            else if (br == "ff")
            {
                txtarea.selectionStart = strPos;
                txtarea.selectionEnd = strPos;
                txtarea.focus();
            }
            txtarea.scrollTop = scrollPos;
            $scope.message[areaId] = txtarea.value;
        }

	};
})();

;