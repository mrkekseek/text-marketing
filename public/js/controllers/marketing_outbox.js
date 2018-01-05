(function () {
	'use strict';

	angular.module('app').controller('MarketingOutboxCtrl', ['$rootScope', '$scope', '$uibModal', '$timeout', '$location', 'request', 'langs', MarketingOutboxCtrl]);

	function MarketingOutboxCtrl($rootScope, $scope, $uibModal, $timeout, $location, request, langs) {
		$scope.list = [];
		$scope.selectedMessage = {};
		$scope.countList = 0;
		$scope.clients = {};
		$scope.selectedTexts = {};
		$scope.timer = false;

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
                	$scope.list[k].texts.splice(-1, 1);

                	var send_at = new Date($scope.list[k].lastText.send_at);
                	$scope.list[k].lastText.send_at = send_at.getTime();
                	var created_at = new Date($scope.list[k].lastText.created_at);
                	$scope.list[k].lastText.created_at = created_at.getTime();

                	for (var j in $scope.list[k].texts) {
                		var send_at = new Date($scope.list[k].texts[j].send_at);
                		$scope.list[k].texts[j].send_at = send_at;
                	}
                }
                
                $timeout.cancel($scope.timer);
                if ($location.path() == '/marketing/outbox/') {
                	$scope.timer = $timeout(function () {
	                    $scope.get();
	                }, 5000);
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

		$scope.changeActive = function(item) {
			request.send('/messages/changeActive/' + item.id, item, function (data) {
                
            });
		};

		$scope.getSuffix = function(num) {
			var res = '';
			num = num.toString();
			switch(num.slice(num.length - 1)) {
				case '1': res = 'st'; break;
				case '2': res = 'nd'; break;
				case '3': res = 'rd'; break;
				default: res = 'th'; break;
			}
			return res;
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

        $scope.textsToggle = function(index) {
        	if ( ! $scope.selectedTexts.id) {
        		$scope.selectedTexts = $scope.list[index];
        	} else {
        		$scope.selectedTexts = {};
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