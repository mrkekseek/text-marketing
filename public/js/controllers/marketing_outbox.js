(function () {
	'use strict';

	angular.module('app').controller('MarketingOutboxCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', MarketingOutboxCtrl]);

	function MarketingOutboxCtrl($rootScope, $scope, $uibModal, request, langs) {
		$scope.list = [];
		$scope.selectedMessage = {};
		$scope.countList = 0;
		$scope.clients = {};

		$scope.init = function() {
			$scope.get();
			$scope.getClients();
		};

		$scope.get = function() {
			request.send('/messages', {}, function (data) {
                $scope.list = data;
                for (var k in $scope.list) {
                	$scope.list[k].active = $scope.list[k].active == 1 ? true : false;
                	$scope.list[k].countList = $scope.getCountList($scope.list[k].lists_id);
                	$scope.list[k].lastText = $scope.list[k].texts[$scope.list[k].texts.length - 1];
                	var send_at = new Date($scope.list[k].lastText.send_at);
                	$scope.list[k].lastText.send_at = send_at.getTime();
                	var created_at = new Date($scope.list[k].lastText.created_at);
                	$scope.list[k].lastText.created_at = created_at.getTime();
                }
            }, 'get');
		};

		$scope.getClients = function() {
			request.send('/clients', {}, function (data) {
                for (var k in data) {
                	if (data[k].id > 0) {
                		$scope.clients[data[k].id] = data[k];
                	}
                }
            }, 'get');
		};

		$scope.getSuffix = function(num) {
			switch(num.slice(-1)) {
				case '1': return 'st';
				case '2': return 'nd';
				case '3': return 'rd';
				default: return 'th';
			}
		};

		$scope.getCountList = function(lists_id) {
			return lists_id.split(',').length;
		};
		
		$scope.choose = function(index) {
			if ( ! $scope.selectedMessage.id) {
				$scope.selectedMessage = $scope.list[index];
			} else {
				$scope.selectedMessage = {};
			}
        };

        $scope.remove = function(index) {
        	if (confirm(langs.get('Do you realy want to remove this message?'))) {
                request.send('/messages/' + $scope.list[index].id, {}, function (data) {
                   $scope.list.splice(index, 1);
                }, 'delete');
            }
        };

	};

	})();

;