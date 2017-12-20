(function () {
    'use strict';

    angular.module('app').controller('PlansCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', PlansCtrl]);

    function PlansCtrl($rootScope, $scope, $uibModal, request, langs) {
        $scope.request_finish = false;
        $scope.list = [];

        $scope.init = function () {
            $scope.get();
        };

        $scope.get = function () {
        	request.send('/plans', {}, function (data) {
                $scope.list = data;
    			$scope.request_finish = true;
			}, 'get');
        };

        $scope.create = function (plans_id) {
            plans_id = plans_id || 0;
            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: 'ModalPlansCreate.html',
                controller: 'ModalPlansCreateCtrl',
                resolve: {
                    items: function () {
                        return {'plan': $scope.by_id(plans_id)};
                    }
                }
            });

            modalInstance.result.then(function (response) {
               $scope.get();
            }, function () {
                
            });
        };

        $scope.remove = function (plans_id) {
            if (confirm(langs.get('Do you realy want to remove this Plan?'))) {
                request.send('/plans/' + plans_id, {}, function (data) {
                    $scope.get();
                }, 'delete');
            }
        };

        $scope.by_id = function (plans_id) {
            for (var k in $scope.list) {
                if ($scope.list[k].id == plans_id) {
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

    angular.module('app').controller('ModalPlansCreateCtrl', ['$rootScope', '$scope', '$uibModalInstance', 'request', 'validate', 'logger', 'langs', 'items', ModalPlansCreateCtrl]);

    function ModalPlansCreateCtrl($rootScope, $scope, $uibModalInstance, request, validate, logger, langs, items) {
        $scope.plan = angular.copy(items.plan);
        if ( ! $scope.plan.id) {
            $scope.plan.interval = 'month';
        }

        $scope.save = function () {
            var error = 1;
            error *= validate.check($scope.form.name, 'Name');
            error *= validate.check($scope.form.amount, 'Amount');

            if (error) {
                request.send('/plans/' + ($scope.plan.id ? $scope.plan.id : 'save'), $scope.plan, function (data) {
                    $uibModalInstance.close(data);
                }, ($scope.plan.id ? 'post' : 'put'));
            }
        };

        $scope.cancel = function () {
            $uibModalInstance.dismiss();
        };
    };
})();

;