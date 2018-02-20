(function () {
    'use strict';

    angular.module('app').controller('UsersCtrl', ['$rootScope', '$scope', '$uibModal', '$window', 'request', 'langs', 'validate', UsersCtrl]);

    function UsersCtrl($rootScope, $scope, $uibModal, $window, request, langs, validate) {
		$scope.request_finish = false;
    	$scope.list = [];
		$scope.plans_list = [];
		$scope.quickSearch = '';

    	$scope.get = function () {
    		request.send('/users', $scope.auth, function (data) {
    			$scope.list = data;
    			$scope.request_finish = true;
			}, 'get');
		};

    	$scope.plans = function () {
            request.send('/plans', false, function (data) {
                $scope.plans_list = data;
                $scope.plans_list.unshift({
    				'plans_id': '0',
    				'name': 'Select a Plan...'
    			});
            }, 'get');
        };

        $scope.initAdmin = function () {
			$scope.get();
			$scope.plans();
		};

    	$scope.create = function (users_id) {
            users_id = users_id || false;

			var modalInstance = $uibModal.open({
				animation: true,
				templateUrl: 'UsersCreate.html',
				controller: 'ModalUsersCreateCtrl',
				resolve: {
					items: function () {
				  		return {'user': $scope.by_id(users_id), 'plans': $scope.plans_list};
					}
				}
		    });

		    modalInstance.result.then(function (response) {
				$scope.get();
		    }, function () {
				
		    });
		};

		$scope.remove = function (users_id) {
            if (confirm(langs.get('Do you realy want to remove this user? It will also remove all account data'))) {
                request.send('/users/' + users_id, {}, function (data) {
                    $scope.get();
                }, 'delete');
            }
        };

		$scope.by_id = function (users_id) {
			for (var k in $scope.list) {
				if ($scope.list[k].id == users_id) {
					return $scope.list[k];
				}
			}

			return {};
		};

		$scope.magic = function (users_id) {
			request.send('/users/' + users_id + '/magic', {}, function (data) {
				$window.location.href = "/";
            }, 'get');
		};

		$scope.pass = {};
		$scope.password = function () {
			var error = 1;
			error *= validate.check($scope.form_password.old_password, 'Old Password');
			error *= validate.check($scope.form_password.password, 'New Password');
			error *= validate.check($scope.form_password.password_confirmation, 'Password Confirmation');

			if (error) {
				request.send('/users/password', $scope.pass, function (data) {
					if (data) {
						$scope.pass.old_password = '';
						$scope.pass.password = '';
						$scope.pass.password_confirmation = '';
					}
	            });
			}
		};

		$scope.profile = function () {
			request.send('/users/profile', $scope.user);
		};
    };
})();

;

(function () {
    'use strict';

    angular.module('app').controller('ModalUsersCreateCtrl', ['$rootScope', '$scope', '$uibModalInstance', 'request', 'validate', 'logger', 'langs', 'items', ModalUsersCreateCtrl]);

    function ModalUsersCreateCtrl($rootScope, $scope, $uibModalInstance, request, validate, logger, langs, items) {
        $scope.user = angular.copy(items.user);
        $scope.user.password = '';
        $scope.plans = angular.copy(items.plans);

        if ( ! $scope.user.id) {
        	$scope.user.plans_id = '0';
        }

    	$scope.save = function () {
			var error = 1;
			error *= validate.check($scope.form.plans_id, 'Payment Plan');
			error *= validate.check($scope.form.firstname, 'Name');
			error *= validate.check($scope.form.email, 'Email');

			if (error) {
				request.send('/users/' + ($scope.user.id ? $scope.user.id : ''), $scope.user, function (data) {
					if (data) {
						$uibModalInstance.close(data);
					}
				}, ( ! $scope.user.id ? 'put' : 'post'));
			}
		};

		$scope.cancel = function () {
			$uibModalInstance.dismiss('cancel');
		};
    };
})();

;