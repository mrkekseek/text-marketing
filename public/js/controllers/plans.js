(function () {
    'use strict';

    angular.module('app').controller('PlansCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', PlansCtrl]);

    function PlansCtrl($rootScope, $scope, $uibModal, request, langs) {
        $rootScope.body_class = '';
    	$scope.request_finish = false;

        $scope.list = [];

        $scope.init = function() {
            $scope.get();
        };

        $scope.get = function() {
        	request.send('/plans/get', false, function(data) {
    			$scope.list = data;
    			$scope.request_finish = true;
			});
        };

        $scope.create = function() {
            var modalInstance = $uibModal.open({
                animation: true,
                size: 'sm',
                templateUrl: 'PlanCreate.html',
                controller: 'PlansCreateCtrl',
                resolve: {
                    items: function () {
                        return {};
                    }
                }
            });

            modalInstance.result.then(function(response) {
               $scope.list = response.data;
            }, function () {
                
            });
        };

        $scope.remove = function(plans_id) {
            if (confirm(langs.get('Do you realy want to remove this Plan?')))
            {
                request.send('/users/remove_plan/', {'plans_id': plans_id}, function(data) {
                    if (data.data)
                    {
                        $scope.list = data.data;
                    }
                    if (data.modals)
                    {
                        for (var j in data.modals)
                        {
                            $scope.get_modals(data.modals[j].id, data.modals[j].data);
                        }
                    }
                });
            }
        };

        $scope.get_modals = function(id, data) {
            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: id,
                controller: 'ChangeUsersPlansCtrl',
                resolve: {
                    items: function () {
                        return {'users': data.users, 'plans': data.plans};
                    }
                }
            });

            modalInstance.result.then(function(response) {
                $scope.list = response.data;
            }, function () {
                
            });
        };

        $scope.change_plan = function() {
            request.send('/users/change_plan/', {'plans_id': 'day-plan', 'users_sub_id': $rootScope.user.users_sub_id}, function(data) {
                $scope.list = data.data;
            });
        };
    };
})();

;

(function () {
    'use strict';

    angular.module('app').controller('PlansCreateCtrl', ['$rootScope', '$scope', '$uibModalInstance', 'request', 'validate', 'logger', 'langs', PlansCreateCtrl]);

    function PlansCreateCtrl($rootScope, $scope, $uibModalInstance, request, validate, logger, langs) {
        $scope.plan = {'interval': 'month'};

        $scope.save = function() {
            var error = 1;
            error *= validate.check($scope.form.plan_name, 'Plan Name');
            error *= validate.check($scope.form.plan_price, 'Plan Amount');
            if (error)
            {
                request.send('/users/create_plan/', $scope.plan, function(data) {
                    $uibModalInstance.close(data);
                });
            }
        };

        $scope.cancel = function() {
            $uibModalInstance.dismiss();
        };
    };
})();

;

(function () {
    'use strict';

    angular.module('app').controller('ChangeUsersPlansCtrl', ['$rootScope', '$scope', '$uibModalInstance', 'request', 'validate', 'logger', 'langs', 'items', ChangeUsersPlansCtrl]);

    function ChangeUsersPlansCtrl($rootScope, $scope, $uibModalInstance, request, validate, logger, langs, items) {
        $scope.users = angular.copy(items.users);
        $scope.plans = angular.copy(items.plans);
        $scope.plan_for_all_switch = '1';
        $scope.plan_for_all = $scope.plans[0].plan.id;

        $scope.change_plan = function() {
            if ($scope.plan_for_all_switch == '1')
            {
                for (var k in $scope.users)
                {
                    $scope.users[k].plans_id = $scope.plan_for_all;
                }
            }

            request.send('/users/change_plan/', $scope.users, function(data) {
                $uibModalInstance.close(data);
            });
        };

        $scope.cancel = function() {
            $uibModalInstance.dismiss('cancel');
        };
    };
})();

;