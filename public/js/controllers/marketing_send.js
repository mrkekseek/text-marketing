
(function () {
	'use strict';

	angular.module('app').controller('MarketingSendCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', '$location', 'logger', MarketingSendCtrl]);

	function MarketingSendCtrl($rootScope, $scope, $uibModal, request, langs, $location, logger) {
		$scope.totalContacts = 0;
		$scope.step = 1;
		$scope.open = false;
		$scope.contactList =  [];
		$scope.listsList = [];
		$scope.list = [];

		$scope.message = {
			'text': '',
			'schedule': '0',
			'switch': '1',
			'date': new Date(),
			'time': new Date(),
			'day': '2',
			'finish': new Date()
		};
		
		$scope.TextCharSetOptions = {
			'id' : 'messageText' ,
			'title': 'Message Text',
			'user': $scope.user,
			'buttons': [
				{'name': 'Short Link',
				'mask': '[$ShortLink]',
				'type': 'short-link',
				'icon': 'link'},
				{'name': 'First Name',
				'mask': '[$FirstName]',
				'type': 'insert',
				'icon': 'user'},
				{'name': 'Last Name',
				'mask': '[$LastName]',
				'type': 'insert',
				'icon': 'user-o'}
			]
		};

		var oldContactList = [];

		$scope.dateOptions = {
			minDate: new Date()
		};

		$scope.finishOptions = {
			minDate: $scope.message.date,
			dateFormat: 'yyyy-MMMM-dd',
			dateDisabled: disabled
		};

		function disabled(data) {
			var date = data.date;
			var t = 0;
			switch ($scope.message.switch) {
				case '3': return date.getDay() !== $scope.message.date.getDay();
				case '4': return date.getDate() !== $scope.message.date.getDate();
				case '5': return (date.getDate() + 1) %  $scope.message.day;
				default: return false;
			}
		};

		$scope.getPhones = function() {
			$scope.getContacts();
			$scope.getList();
		};

		$scope.saveMessage = function() {
			if ( ! $scope.message.text || $scope.message.text == '') {
				logger.logError('Message text is required');
				return;
			}
			$scope.step++;
			/*request.send('/messages/' + ( ! $scope.message.id ? 'save' : $scope.message.id), $scope.message, function (data) {
				$scope.message.id = data.id;	
        	}, ( ! $scope.message.id ? 'put' : 'post'));*/
		};

		$scope.getContacts = function() {
            request.send('/clients', {}, function (data) {
                $scope.list = data;
            }, 'get');
        };

		$scope.getList = function() {
            request.send('/lists', {}, function (data) {
                $scope.listsList = data;
            }, 'get');
        };

		$scope.countTimes = function() {
			var from = $scope.message.date.getTime();
			var to = $scope.message.finish.getTime();
			switch ($scope.message.switch) {
				case '2': return (to - from) / 60 / 60 / 24 / 1000 + 1;
				case '3': return (to - from) / 60 / 60 / 24 / 1000 / 7 + 1;
				case '4': return ($scope.message.finish.getMonth() + (($scope.message.finish.getFullYear() - $scope.message.date.getFullYear()) * 12) - $scope.message.date.getMonth()) + 1;
				case '5': return Math.floor(((to - from) / 60 / 60 / 24 / 1000 + 1) / $scope.message.day + 1);
			}
		};

		$scope.totalCount = function() {
			$scope.totalContacts = 0;
			for (var i in $scope.contactList) {
				if ($scope.contactList[i].choosed && $scope.contactList[i].phones.length > 0) {
					$scope.totalContacts = $scope.totalContacts + $scope.contactList[i].phones.length;
				}
			}
		};
		
		$scope.getSuffix = function(day) {
			switch (day) {
				case '1': return 'st';
				case '2': return 'nd';
				case '3': return 'rd';
				default: return  'th';
			}
		};

		$scope.init = function() {
			$scope.get();
			$scope.copy();
		};

		$scope.get = function() {
			var url = $location.path();
			var temp = url.split('/');
			if (temp[3]) {
				request.send('/messages/' + temp[3], {}, function (data) {
					$scope.message = data;	
            	}, 'get');
			}
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