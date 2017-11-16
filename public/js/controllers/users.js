(function () {
    'use strict';

    angular.module('app').controller('UsersCtrl', ['$rootScope', '$scope', '$uibModal', '$window', 'request', 'langs', 'validate', UsersCtrl]);

    function UsersCtrl($rootScope, $scope, $uibModal, $window, request, langs, validate) {
    	$rootScope.body_class = '';
    	$scope.request_finish = false;

    	$scope.list = [];
    	$scope.teams_list = [];
    	$scope.plans_list = [];
    	$scope.activate = false;
    	$scope.plan = false;

    	$scope.get = function() {
    		request.send('/users/get', $scope.auth, function(data) {
    			$scope.list = data;
    			$scope.request_finish = true;
			});
    	};

    	$scope.teams = function() {
    		request.send('/teams/get', $scope.auth, function(data) {
    			$scope.teams_list = data;
			});
    	};

    	$scope.getTeamById = function(teamsId) {
			for (var t in $scope.teams_list) {
				if ($scope.teams_list[t].id == teamsId) {
					return $scope.teams_list[t].name;
				}
			}
		};

    	$scope.plans = function() {
            request.send('/plans/get', false, function(data) {
                $scope.plans_list = data;
            });
        };

        $scope.initAdmin = function () {
			$scope.get();
			$scope.teams();
			$scope.plans();
		};

/*        $scope.get_plan_info = function() {
        	if ($rootScope.user.plans_code)
        	{
        		request.send('/users/get_plan_info/', {'plans_code': $rootScope.user.plans_code}, function(data) {
        			if (data.data)
        			{
        				$scope.plan = data.data;
        			}
	            });
        	}
        };*/

/*        $scope.cancel_plan = function() {
        	request.send('/users/cancel_subscription/', {'users_sub_id': $rootScope.user.users_sub_id, 'users_id': $rootScope.user.users_id}, function(data) {
                $window.location.href = "/";
            });
        };*/

    	$scope.create = function(users_id) {
            users_id = users_id || false;

			var modalInstance = $uibModal.open({
				animation: true,
				templateUrl: 'UsersCreate.html',
				controller: 'ModalUsersCreateCtrl',
				resolve: {
					items: function () {
				  		return {'user': $scope.by_id(users_id), 'teams': $scope.teams_list, 'plans': $scope.plans_list};
					}
				}
		    });

		    modalInstance.result.then(function(response) {
				$scope.get();
		    }, function () {
				
		    });
		};

		$scope.settings = function(users_id) {
            users_id = users_id || false;

			var modalInstance = $uibModal.open({
				animation: true,
				size: 'md',
				templateUrl: 'UsersSettings.html',
				controller: 'ModalUsersSettingsCtrl',
				resolve: {
					items: function () {
				  		return {'user': $scope.by_id(users_id)};
					}
				}
		    });

		    modalInstance.result.then(function(response) {
				$scope.list = response.data;
		    }, function () {
				
		    });
		};

		$scope.remove = function(users_id) {
            if (confirm(langs.get('Do you realy want to remove this item? It will also remove all user account data'))) {
                request.send('/users/remove', {'id': users_id}, function(data) {
                    $scope.get();
                });
            }
        };

		$scope.by_id = function(users_id) {
			for (var k in $scope.list) {
				if ($scope.list[k].id == users_id) {
					return $scope.list[k];
				}
			}

			return {};
		};

		$scope.sign_in = function(users_id) {
			request.send('/users/magic', {'id': users_id}, function(data) {
				$window.location.href = "/";
            });
		}

		$scope.teams_leader = function(users_id, teams_leader) {
			var leader = true;
			if (teams_leader === '1') {
				leader = false;
			}

			request.send('/users/teams_leader', {'id': users_id, 'teams_leader': leader}, function(data) {
                $scope.get();
            });
		}

		$scope.change_password = function() {
			var error = 1;
			error *= validate.check($scope.form.old_password, 'Old Password');
			error *= validate.check($scope.form.new_password, 'New Password');
			error *= validate.check($scope.form.confirm_password, 'Confirm Password');

			var post_mas = {'old_password': $scope.form.old_password.$viewValue,
							'new_password': $scope.form.new_password.$viewValue,
							'confirm_password': $scope.form.confirm_password.$viewValue};
			if (error)
			{
				request.send('/users/password', post_mas, function(data) {
	                if (data)
	                {
	                	$scope.old_password = '';
	                	$scope.new_password = '';
	                	$scope.confirm_password = '';
	                }
	            });
			}
		};

		$scope.save = function() {
			var post_mas = {
				'firstname': $rootScope.user.firstname,
				'lastname': $rootScope.user.lastname,
				'email': $rootScope.user.email,
				'phone': $rootScope.user.phone
			};
			
			request.send('/users/profile', post_mas);
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
        if ( ! $scope.user.id) {
        	$scope.user.teams_id = '0';
        	$scope.user.teams_leader = '0';
        	$scope.user.active = '0';
        	$scope.user.send = '1';
        }
        
        if ( ! $scope.user.plans_code) {
        	$scope.user.plans_code = '0';
        }

        $scope.teams = angular.copy(items.teams);
        $scope.plans = angular.copy(items.plans);

    	$scope.save = function() {
	    	var error = 1;
			error *= validate.check($scope.form.firstname, 'Name');
			error *= validate.check($scope.form.email, 'Email');
			error *= validate.check($scope.form.teams_id, 'Team');

			if (error) {
				request.send('/users/save', $scope.user, function(data) {
					if (data) {
						$uibModalInstance.close(data);
					}
				});
			}
		};

		$scope.cancel = function() {
			$uibModalInstance.dismiss('cancel');
		};
    };
})();

;

(function () {
    'use strict';

    angular.module('app').controller('ModalUsersSettingsCtrl', ['$rootScope', '$scope', '$uibModalInstance', 'request', 'validate', 'logger', 'langs', 'items', ModalUsersSettingsCtrl]);

    function ModalUsersSettingsCtrl($rootScope, $scope, $uibModalInstance, request, validate, logger, langs, items) {
	    $scope.user = angular.copy(items.user);

    	$scope.save = function() {
			request.send('/users/settings_personal', $scope.user, function(data) {
				if (data)
				{
					$uibModalInstance.close(data);
				}
			});
		};

		$scope.cancel = function() {
			$uibModalInstance.dismiss('cancel');
		};
    };
})();

;