(function () {
	'use strict';

	angular.module('app').controller('MarketingOutboxCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', MarketingOutboxCtrl]);

	function MarketingOutboxCtrl($rootScope, $scope, $uibModal, request, langs) {
		$scope.messages = [{'messagesText' : 'Message text', 'isSelected' : false, 'active' : false, 'sendDate' : new Date(), 'isSended' : false},
		{'messagesText' : 'Message text2', 'isSelected' : false, 'active' : true, 'sendDate' : new Date(), 'isSended' : false}];
		$scope.selected = -1;
		
		$scope.choose = function(index) {   
            $scope.selected = $scope.selected == index ? -1 : index;
        };

	};

	})();

;