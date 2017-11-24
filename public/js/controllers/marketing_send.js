
(function () {
	'use strict';

	angular.module('app').controller('MarketingSendCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', MarketingSendCtrl]);

	function MarketingSendCtrl($rootScope, $scope, $uibModal, request, langs) {
		$scope.totalContacts = 0;
		$scope.step = 1;
		$scope.minDate = new Date();
		$scope.open = false;
		$scope.message = {'messagesText' : '', 'messagesTextLength' : 0 ,
		'messagesSchedule' : '0', 'maxLength' : 130, 'xDay' : '2', 'messagesSwitch' : '1'};

		$scope.contactList =  [];
		var oldContactList = [];
		var mask = 0;

		$scope.totalCount = function() {
			$scope.totalContacts = 0;
			for (var i in $scope.contactList) {
				if ($scope.contactList[i].choosed && $scope.contactList[i].phones.length > 0) {
					$scope.totalContacts = $scope.totalContacts + $scope.contactList[i].phones.length;
				}
			}
		};
		
		$scope.getSuffix = function(day) {
			switch(day){
				case '1': return 'st';
				break;
				case '2': return 'nd';
				break;
				case '3': return 'rd';
				default: return  'th';
			}
		};

		$scope.dateOpt = {
			minDate: $scope.minDate,
			dateFormat: 'yyyy-MMMM-dd'
		};

		$scope.charCount = function(id) {
			mask = ($scope.message.messagesText.match(/\[\$FirstName\]|\[\$LastName\]/g) || []).length;
			$scope.message.messagesTextLength = mask * 18 + $scope.message.messagesText.length;
			
			$scope.message.maxLength = $scope.message.messagesTextLength < 130 ? 130 : 472;
		};

		$scope.insertMask = function(id, text) {
			$scope.insertAtCaret(id,text);
			$scope.charCount(id);
		};

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
			oldContactList[itemIndex].phones =  angular.copy($scope.contactList[itemIndex].phones);
		};

		$scope.createPhone = function(index) {
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

		$scope.openImport = function() {

			var modalInstance = $uibModal.open({
				animation: true,
				templateUrl: 'ImportFiles.html',
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

	angular.module('app').controller('ImportFilesCtrl', ['$rootScope', '$scope', '$uibModalInstance', 'request', 'langs', 'logger', ImportFilesCtrl]);

	function ImportFilesCtrl($rootScope, $scope, $uibModalInstance, request, langs, logger) {

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