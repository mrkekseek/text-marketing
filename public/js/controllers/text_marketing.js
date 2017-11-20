(function () {
    'use strict';

    angular.module('app').controller('MarketingSettingsCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', MarketingSettingsCtrl]);

    function MarketingSettingsCtrl($rootScope, $scope, $uibModal, request, langs) {
    	$scope.show = false;
    	$scope.step = 1;

    	$scope.init = function() {

    	};

    	$scope.choose = function() {
    		$scope.show = ! $scope.show;
    	};

    	$scope.next = function() {
    		if ($scope.step < 3) {
    			$scope.step += 1;
    		}
    	};
    };
})();

;



(function () {
    'use strict';

    angular.module('app').controller('MarketingContactsCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', MarketingContactsCtrl]);

    function MarketingContactsCtrl($rootScope, $scope, $uibModal, request, langs) {
    	$scope.newList = true;

    	$scope.create = function() {
    		$scope.newList = false;
    	};

    	$scope.cancel = function() {
    		$scope.newList = true;
    	};
    };
})();

;