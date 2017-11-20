(function () {
    'use strict';

    angular.module('app').controller('UsersCtrl', ['$rootScope', '$scope', '$uibModal', '$window', 'request', 'langs', 'validate', UsersCtrl]);

    function UsersCtrl($rootScope, $scope, $uibModal, $window, request, langs, validate) {
    	$scope.list = [];
    	$scope.teams_list = [];
    	$scope.plans_list = [];

    	$scope.get = function() {
    		request.send('/users', $scope.auth, function(data) {
    			$scope.list = data;
    			$scope.request_finish = true;
			}, 'get');
    	};

    	$scope.teams = function() {
    		request.send('/teams', $scope.auth, function(data) {
    			$scope.teams_list = data;
    			$scope.teams_list.unshift({
    				'id': '0',
    				'name': 'Select a Team...'
    			});
			}, 'get');

    	};

    	$scope.getTeamById = function(teamsId) {
			for (var t in $scope.teams_list) {
				if ($scope.teams_list[t].id == teamsId) {
					return $scope.teams_list[t].name;
				}
			}
		};

    	$scope.plans = function() {
            request.send('/plans', false, function(data) {
                $scope.plans_list = data;
                $scope.plans_list.unshift({
    				'id': '0',
    				'name': 'Select a Plan...'
    			});
            }, 'get');
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

		$scope.remove = function(users_id) {
            if (confirm(langs.get('Do you realy want to remove this item? It will also remove all user account data'))) {
                request.send('/users/' + users_id, false, function(data) {
                    $scope.get();
                }, 'delete');
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

		$scope.teamsLeader = function(users_id) {
			var user = $scope.by_id(users_id);
			request.send('/users/' + users_id + '/teamsLeader', {}, function() {
				$scope.get();
			}, user.teams_leader == '1' ? 'post' : 'delete');
		};

		$scope.active = function(users_id) {
			var user = $scope.by_id(users_id);
			request.send('/users/' + users_id + '/active', {}, false, user.active == '1' ? 'post' : 'delete');
		};

		$scope.magic = function(users_id) {
			request.send('/users/' + users_id + '/magic', {}, function(data) {
				$window.location.href = "/";
            }, 'get');
		};

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
    };
})();

;

(function () {
    'use strict';

    angular.module('app').controller('ModalUsersCreateCtrl', ['$rootScope', '$scope', '$uibModalInstance', 'request', 'validate', 'logger', 'langs', 'items', ModalUsersCreateCtrl]);

    function ModalUsersCreateCtrl($rootScope, $scope, $uibModalInstance, request, validate, logger, langs, items) {
        $scope.user = angular.copy(items.user);
        $scope.teams = angular.copy(items.teams);
        $scope.plans = angular.copy(items.plans);
        $scope.user.password = '';

        if ( ! $scope.user.id) {
        	$scope.user.teams_id = '0';
        	$scope.user.teams_leader = 1;
        	$scope.user.active = 1;
        	$scope.user.plans_id = '0';
        }

    	$scope.save = function() {
	    	var error = 1;
			error *= validate.check($scope.form.firstname, 'Name');
			error *= validate.check($scope.form.email, 'Email');
			error *= validate.check($scope.form.teams_id, 'Team');

			if (error) {
				request.send('/users/' + ( ! $scope.user.id ? 'save' : $scope.user.id), $scope.user, function(data) {
					if (data) {
						$uibModalInstance.close(data);
					}
				}, ( ! $scope.user.id ? 'put' : 'post'));
			}
		};

		$scope.cancel = function() {
			$uibModalInstance.dismiss('cancel');
		};
    };
})();

;