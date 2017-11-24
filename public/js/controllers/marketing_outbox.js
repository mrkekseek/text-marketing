(function () {
	'use strict';

	angular.module('app').controller('MarketingOutboxCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', MarketingOutboxCtrl]);

	function MarketingOutboxCtrl($rootScope, $scope, $uibModal, request, langs) {
		$scope.messages = [{'messagesText' : 'Message text', 'isSelected' : false, 'active' : false},
		{'messagesText' : 'Message text2', 'isSelected' : false, 'active' : true}];
		$scope.selected = -1;
		
		$scope.choose = function(index) {   
			$scope.messages[index].isSelected = ! $scope.messages[index].isSelected;
            $scope.selected = $scope.selected == index ? -1 : index;
        };

	};

	})();

;