(function () {
    'use strict';

    angular.module('app').controller('HomeAdvisorCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', HomeAdvisorCtrl]);

    function HomeAdvisorCtrl($rootScope, $scope, $uibModal, request, langs) {
        $rootScope.body_class = '';
        $scope.request_finish = false;

        $scope.list = [];
        $scope.teams_list = [];

        $scope.init = function() {
            $scope.teams();
        };

        $scope.teams = function() {
            request.send('/teams/get', $scope.auth, function(data) {
                $scope.teams_list = data;
            });
        };

        $scope.get = function() {

        };


        $scope.create = function(teams_id) {
            teams_id = teams_id || false;

            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: 'AdvisorCreate.html',
                controller: 'AdvisorCtrl',
                resolve: {
                    items: function () {
                        return { 'teams': $scope.teams_list};                    }
                }
            });

            modalInstance.result.then(function(response) {
                $scope.get();
            }, function () {

            });
        };

    };
})();

;


(function () {
    'use strict';

    angular.module('app').controller('AdvisorCtrl', ['$rootScope', '$scope', '$uibModalInstance', 'request', 'validate', 'logger', 'langs', 'items', AdvisorCtrl]);

    function AdvisorCtrl($rootScope, $scope, $uibModalInstance, request, validate, logger, langs, items) {
        $scope.teams = angular.copy(items.teams);
        $scope.user.team_id = '0';

        $scope.save = function () {
            var error = 1;
            error *= validate.check($scope.form.firstname, 'Firstname');
            error *= validate.check($scope.form.team_id, 'Team');

            if (error) {
                /*request.send('/teams/save', $scope.team, function (data) {
                    if (data) {
                        $uibModalInstance.close(data);
                    }
                });*/
                console.log($scope.user);
            }
        };

        $scope.cancel = function () {
            $uibModalInstance.dismiss('cancel');
        };
    };
})();

;