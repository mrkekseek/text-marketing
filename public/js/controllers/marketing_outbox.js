(function () {
	'use strict';

	angular.module('app').controller('MarketingOutboxCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', MarketingOutboxCtrl]);

	function MarketingOutboxCtrl($rootScope, $scope, $uibModal, request, langs) {
		$scope.list = [];
		$scope.selectedMessage = {};

		$scope.init = function() {
			$scope.get();
		};

		$scope.get = function() {
			request.send('/messages', {}, function (data) {
                $scope.list = data;
                for (var k in $scope.list) {
                	$scope.list[k].active = $scope.list[k].active == 1 ? true : false;
                }
                console.log($scope.list);
            }, 'get');
		};
		
		$scope.choose = function(index) {
            $scope.selectedMessage = $scope.list[index];
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