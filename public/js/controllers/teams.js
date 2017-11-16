(function () {
    'use strict';

    angular.module('app').controller('TeamsCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', TeamsCtrl]);

    function TeamsCtrl($rootScope, $scope, $uibModal, request, langs) {
    	$scope.request_finish = false;

    	$scope.list = [];

    	$scope.get = function () {
    		request.send('/teams', {}, function (data) {
    			$scope.list = data;
    			$scope.request_finish = true;
			}, 'get');
    	};

    	$scope.initAdmin = function () {
			$scope.get();
			$scope.companies();
    	};

        $scope.companies = function () {
            request.send('/teams/companies', {}, function(data) {
               for (var k in $scope.list) {
                    for (var j in data) {
                        if ($scope.list[k].teams_name == data[j].org_names_name) {
                            $scope.list[k].teams_status = data[j].org_names_status;
                        }
                    }
               }
            });
        };

    	$scope.create = function(teams_id) {
            teams_id = teams_id || false;

			var modalInstance = $uibModal.open({
				animation: true,
				templateUrl: 'TeamsCreate.html',
				controller: 'ModalTeamsCreateCtrl',
				resolve: {
					items: function () {
				  		return {'team': $scope.by_id(teams_id)};
					}
				}
		    });

		    modalInstance.result.then(function(response) {
				$scope.get();
		    }, function () {
				
		    });
		};

		$scope.remove = function (teams_id) {
            if (confirm(langs.get('Do you really want to remove this team?'))) {
                request.send('/teams/' + teams_id, false, function (data) {
                    $scope.get();
                }, 'delete');
            }
        };

		$scope.by_id = function (teams_id) {
			for (var k in $scope.list) {
				if ($scope.list[k].id == teams_id) {
					return $scope.list[k];
				}
			}

			return {};
		};
    };
})();

;

(function () {
    'use strict';

    angular.module('app').controller('ModalTeamsCreateCtrl', ['$rootScope', '$scope', '$uibModalInstance', 'request', 'validate', 'logger', 'langs', 'items', ModalTeamsCreateCtrl]);

    function ModalTeamsCreateCtrl($rootScope, $scope, $uibModalInstance, request, validate, logger, langs, items) {
        $scope.team = angular.copy(items.team);

    	$scope.save = function () {
	    	var error = 1;
			error *= validate.check($scope.form.name, 'Name');

			if (error) {
				request.send('/teams/save', $scope.team, function (data) {
					if (data) {
						$uibModalInstance.close(data);
					}
				});
			}
		};

		$scope.cancel = function () {
			$uibModalInstance.dismiss('cancel');
		};
    };
})();

;