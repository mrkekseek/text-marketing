(function () {
    'use strict';

    angular.module('app').controller('TeamsCtrl', ['$rootScope', '$scope', '$uibModal', '$filter', 'request', 'langs', TeamsCtrl]);

    function TeamsCtrl($rootScope, $scope, $uibModal, $filter, request, langs) {
    	$scope.request_finish = false;

    	$scope.list = [];
    	$scope.levels_list = [];
    	$scope.groups_list = [];

    	/*setInterval(function() {
            request.send('/teams/get_company_status/', false, function(data) {
               for (var k in $scope.list)
               {
               		for (var j in data.data)
               		{
               			if ($scope.list[k].teams_name == data.data[j].org_names_name)
               			{
               				$scope.list[k].teams_status = data.data[j].org_names_status;
               			}
               		}
               }
            });
        }, 5000);*/

    	$scope.get = function () {
    		request.send('/teams/get', {}, function (data) {
    			$scope.list = data;
    			$scope.request_finish = true;
			});
    	};

    	$scope.levels = function () {
    		request.send('/levels/get', {}, function (data) {
    			$scope.levels_list = data;
			});
    	};

    	$scope.groups = function () {
    		request.send('/groups/get', {}, function (data) {
    			$scope.groups_list = data;
			});
    	};

    	$scope.initAdmin = function () {
			$scope.get();
			$scope.groups();
			$scope.levels();
    	};

    	$scope.create = function(teams_id) {
            teams_id = teams_id || false;

			var modalInstance = $uibModal.open({
				animation: true,
				templateUrl: 'TeamsCreate.html',
				controller: 'ModalTeamsCreateCtrl',
				resolve: {
					items: function () {
				  		return {'team': $scope.by_id(teams_id), 'levels': $scope.levels_list, 'groups': $scope.groups_list};
					}
				}
		    });

		    modalInstance.result.then(function(response) {
				$scope.list = response.data;
		    }, function () {
				
		    });
		};

		$scope.remove = function (teams_id) {
            if (confirm(langs.get('Do you really want to remove this team?'))) {
                request.send('/teams/remove', {'teams_id': teams_id}, function (data) {
                    if (data) {
                        $scope.list = data;
                    }
                });
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

		$scope.company_create = function (id, spanish) {
			spanish = spanish || 0;
			request.send('/teams/company_create/', {'teams_id': id}, function (data) {
	            if (data) {
	                $scope.list = data;
	            }
	        });
		}

		/*$scope.check_company = function() {
			request.send('/teams/company_status/', false, function(data) {
	           
	        });
		};*/
    };
})();

;

(function () {
    'use strict';

    angular.module('app').controller('ModalTeamsCreateCtrl', ['$rootScope', '$scope', '$uibModalInstance', 'request', 'validate', 'logger', 'langs', 'items', ModalTeamsCreateCtrl]);

    function ModalTeamsCreateCtrl($rootScope, $scope, $uibModalInstance, request, validate, logger, langs, items) {
        $scope.team = angular.copy(items.team);
        if ( ! $scope.team.id) {
        	$scope.team.groups_id = '0';
        	$scope.team.levels_id = '0';
        }

        $scope.levels = angular.copy(items.levels);
        $scope.groups = angular.copy(items.groups);

    	$scope.save = function() {
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

		$scope.cancel = function() {
			$uibModalInstance.dismiss('cancel');
		};
    };
})();

;