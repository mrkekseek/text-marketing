(function () {
    'use strict';

    angular.module('app').controller('AuthCtrl', ['$rootScope', '$scope', '$window', '$timeout', '$location', 'request', 'validate', 'langs', AuthCtrl]);

    function AuthCtrl($rootScope, $scope, $window, $timeout, $location, request, validate, langs) {
    	$rootScope.body_class = 'body-wide body-auth';
        $scope.auth = {
        	'email': '',
    		'password': ''
    	};

	   	$scope.activate = false;

		$scope.signin = function () {
			var error = 1;
			error *= validate.check($scope.form.email, 'Email');
			error *= validate.check($scope.form.password, 'Password');
			if (error) {
				$rootScope.request_sent = true;
				request.send('/auth/signin', $scope.auth, function(data) {
					if (data) {
						$timeout(function () {
							$window.location.href = "/";
						}, 2000);
					} else {
						$rootScope.request_sent = false;
					}
				});
			}
		};
		
		$scope.terms = '0';
		$scope.new_user = {
			'users_email': '',
    		'users_password': '',
    		'users_firstname': '',
    		'users_lastname': ''
    	};

        $scope.signup = function () {
			var error = 1;
			error *= validate.check($scope.form.users_email, 'Email');
			error *= validate.check($scope.form.users_password, 'Password');
			if (error) {
				var temp = $location.path().split('/');
				$scope.new_user.param = temp[3];
				request.send('/users/signup', $scope.new_user, function(data) {
					if (data) {
						$timeout(function () {
							$window.location.href = "/";
						}, 3000);
					}
				});
			}
        };

        $scope.get_send_type = function () {
        	if ($location.path().split('/')[3]) {
        		switch ($location.path().split('/')[3] * 1) {
        			case 0: $scope.new_user.users_send_type = $location.path().split('/')[3]; break;
        			case 1: $scope.new_user.users_send_type = $location.path().split('/')[3]; break;
        			case 2: $scope.new_user.users_send_type = 0; $scope.new_user.users_free_type = 1; break;
        			case 3: $scope.new_user.users_send_type = $location.path().split('/')[3]; break;
        			default : '';
        		}
        	}
        };

		$scope.reset = function () {
			var error = 1;
			error *= validate.check($scope.form.users_email, 'Email');

			if (error) {
				$rootScope.request_sent = true;
				var post_mas = {users_email: $scope.users_email};
				request.send('/users/recovery', post_mas, function (data) {
					if (data.data) {
						$timeout(function () {
							$window.location.href = "/";
						}, 2000);
					} else {
						$rootScope.request_sent = false;
					}
				});
			}
		};

		$scope.support = function () {
			var support_mas = {
				'name': $scope.name,
				'email': $scope.email,
				'message': $scope.message
			};

			var error = 1;
			error *= validate.check($scope.form.email, 'Email');
			if (error) {
				request.send('/emails/contacts', support_mas, function (data) {
					if (data.data) {
						$timeout(function () {
							$window.location.href = "/";
						}, 2000);
					}
				});
			}
		};

		$scope.accept = function () {
			var post_mas = {users_name: $scope.users_name};
			var url = $location.url();
			var arrUrl =  url.split('/');
			post_mas.url = arrUrl[arrUrl.length - 1];

			var error = 1;
			error *= validate.check($scope.form.users_name, 'User name');
			error *= validate.check($scope.form.checkbox1, 'Chek up');

			if (error) {
				request.send('/auth/accept', post_mas, function (data) {
					if (data) {
						$timeout(function () {
							$window.location.href = "/";
						}, 2000);
					}
				});
			}
		};

		$scope.invite = {};
		$scope.inviteAlert = '';
		$scope.getInvite = function () {
			var hash = $location.path().split('/')[3];
			request.send('/auth/invite', {'hash': hash}, function (data) {
				if (data) {
					$scope.invite = data;
					$scope.inviteAlert = langs.get('You were invited into the team :team. To continue working with this team enter you personal data and click on Confirm button', {'team': $scope.invite.team.teams_name});
					$scope.auth.users_email = $scope.invite.users_email;
					$scope.auth.users_name = $scope.invite.users_name;
				}
			});
		};

		$scope.confirm = function () {
			var error = 1;
			error *= validate.check($scope.form.users_email, 'Email');
			if ($scope.invite.users_active == '0') {
				error *= validate.check($scope.form.password, 'Password');
			}

			error *= validate.check($scope.form.users_name, 'Username');
			if (error) {
				$scope.auth.users_id = $scope.invite.users_id;
				$scope.auth.teams_id = $scope.invite.team.teams_id;
				request.send('/auth/confirm', $scope.auth, function (data) {
					if (data) {
						$timeout(function () {
							$window.location.href = "/";
						}, 2000);
					}
				});
			}
		};

		$scope.get_activate = function () {
			var url = $location.path();
			var temp = url.split('/');
			if (temp[3]) {
				request.send('/users/activate/', {'hash': temp[3]}, function (data) {
    				$scope.activate = data.data;
				});
			}
		};

		$scope.skip = function() {
			$window.location.href = "/";
		};
    };
})();

;