(function () {
    'use strict';

    angular.module('app').controller('UsersCtrl', ['$rootScope', '$scope', '$uibModal', '$window', 'request', 'langs', 'validate', UsersCtrl]);

    function UsersCtrl($rootScope, $scope, $uibModal, $window, request, langs, validate) {
		$scope.request_finish = false;
    	$scope.list = [];
		$scope.plans_list = [];
		$scope.quickSearch = '';

        $scope.initLive = function () {
			$scope.getLiveUsers();
			$scope.plans();
		};

		$scope.initFree = function () {
			$scope.getFreeUsers();
			$scope.plans();
		};

		$scope.initCanceled = function () {
			$scope.getCanceledUsers();
			$scope.plans();
		};

		$scope.getLiveUsers = function () {
			request.send('/users/live', $scope.auth, function (data) {
				$scope.list = data;
				$scope.request_finish = true;
			}, 'get');
		};

		$scope.getFreeUsers = function () {
			request.send('/users/free', $scope.auth, function (data) {
				$scope.list = data;
				$scope.request_finish = true;
			}, 'get');
		};

		$scope.getCanceledUsers = function () {
			request.send('/users/canceled', $scope.auth, function (data) {
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

		$scope.confirmSubscription = function (user, action) {
			var modalInstance = $uibModal.open({
				animation: true,
				templateUrl: 'ModalConfirmPlan.html',
				controller: 'ModalConfirmPlanCtrl',
				resolve: {
					items: function () {
						return {'action': action,
								'user': user,
						};
					},
				}
			});

			modalInstance.result.then(function (response) {
				$scope.request_finish = false;
				if (response == 'downgrade' || response == 'cancel' || response == 'assign') {
					$scope.getLiveUsers();
				} else {
					$scope.getCanceledUsers();
				}
			}, function () {

			});

		};

		$scope.viewFullCancelReason = function (reason) {
			var modalInstance = $uibModal.open({
				animation: true,
				templateUrl: 'ModalViewCancelReason.html',
				controller: 'ModalViewCancelReasonCtrl',
				resolve: {
					items: function () {
						return {'reason': reason};
					},
				}
			});

			modalInstance.result.then(function (response) {
				$scope.request_finish = false;
				if (response == 'downgrade' || response == 'cancel') {
					$scope.getLiveUsers();
				} else {
					$scope.getCanceledUsers();
				}
			}, function () {

			});
		};

		$scope.allowAccess = function (id) {
			request.send('/users/access/' + id, {}, false, 'put');
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

(function () {
	'use strict';

	angular.module('app').controller('ModalConfirmPlanCtrl', ['$rootScope', '$scope', '$uibModalInstance', '$window', 'request', 'items', ModalConfirmPlanCtrl]);

	function ModalConfirmPlanCtrl($rootScope, $scope, $uibModalInstance, $window, request, items) {
		$scope.user = items.user;
		$scope.action = items.action;
		$scope.request_finish = true;

		$scope.aprove = function() {
			$scope.request_finish = false;
			if ($scope.action == 'downgrade') {
				request.send('/plans/free/' + $scope.user.id, {}, function (data) {
					$scope.request_finish = true;
					$uibModalInstance.close($scope.action);
				}, 'post');
			}

			if ($scope.action == 'cancel') {
				request.send('/plans/unsubscribe/' + $scope.user.id, {}, function (data) {
					$scope.request_finish = true;
					$uibModalInstance.close($scope.action);
				}, 'post');
			}

			if ($scope.action == 'reactivate') {
				request.send('/plans/reactivate/' + $scope.user.id, {}, function (data) {
					$scope.request_finish = true;
					$uibModalInstance.close($scope.action);
				}, 'post');
			}

			if ($scope.action == 'upgrade') {
				request.send('/plans/free/' + $scope.user.id, {}, function (data) {
					$scope.request_finish = true;
					$uibModalInstance.close($scope.action);
				}, 'post');
			}
		};

		if ($scope.action == 'assign') {
			request.send('/plans', {}, function (data) {
				$scope.list = [];
				for (var k in data) {
					if (data[k].plans_id.indexOf('home-advisor') + 1) {
						$scope.list.push(data[k]);
					}
				}
				$scope.plans_id = $scope.list[0].id.toString();
			}, 'get');
		}

		$scope.assign = function(plans_id) {
			$scope.request_finish = false;
			request.send('/plans/assign/' + $scope.user.id, { 'plans_id': plans_id }, function (data) {
				$scope.request_finish = true;
				$uibModalInstance.close($scope.action);
			}, 'post');
		};

		$scope.cancel = function () {
			$uibModalInstance.dismiss();
		};
	};
})();

;

(function () {
	'use strict';

	angular.module('app').controller('ModalViewCancelReasonCtrl', ['$rootScope', '$scope', '$uibModalInstance', '$window', 'request', 'items', ModalViewCancelReasonCtrl]);

	function ModalViewCancelReasonCtrl($rootScope, $scope, $uibModalInstance, $window, request, items) {
		$scope.reason = items.reason;

		$scope.cancel = function () {
			$uibModalInstance.dismiss();
		};
	};
})();

;