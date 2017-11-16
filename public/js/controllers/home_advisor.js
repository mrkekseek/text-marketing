(function () {
    'use strict';

    angular.module('app').controller('HomeAdvisorCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', HomeAdvisorCtrl]);

    function HomeAdvisorCtrl($rootScope, $scope, $uibModal, request, langs) {
        $scope.list = [];
        $scope.teams_list = [];

        $scope.init = function() {
            $scope.teams();
            $scope.get();
        };

        $scope.teams = function() {
            request.send('/teams/get', false, function(data) {
                $scope.teams_list = data;
            });
        };

        $scope.get = function() {
            request.send('/teams', $scope.auth, function(data) {
                $scope.teams_list = data;
                $scope.teams_list.unshift({'id':'0' ,'name':'Select a Team...'});
            }, 'get');
        };

        $scope.getTeamById = function(teamsId) {
            for (var t in $scope.teams_list) {
                if ($scope.teams_list[t].id == teamsId) {
                    return $scope.teams_list[t].name;
                }
            }
        };

        $scope.create = function(teams_id) {
            teams_id = teams_id || false;

            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: 'AdvisorCreate.html',
                controller: 'AdvisorCtrl',
                resolve: {
                    items: function () {
                        return {'teams': $scope.teams_list};                    
                    }
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
        $scope.requestEnd = false;
        
        if (! $scope.user.id) {
            $scope.user.team_id = '0';            
        }

        $scope.getLinks = function () {
            var error = 1;
            error *= validate.check($scope.form.firstname, 'Firstname');
            error *= validate.check($scope.form.team_id, 'Team');
            error *= validate.check($scope.form.lastname, 'Lastname');
            error *= validate.check($scope.form.phone, 'Phone');

            if (error) {
                request.send('/homeadvisor/linksSave', $scope.user, function (data) {
                    if (data) {
                        $scope.user.code = data;
                        $scope.user.linkHa = data;
                        $scope.user.success = data;
                        $scope.user.payPlan = data;
                        $scope.requestEnd = true;
                    }
                });
            }
        };

        $scope.save = function () {
            var error = 1;
            error *= validate.check($scope.form.firstname, 'Firstname');
            error *= validate.check($scope.form.team_id, 'Team');
            error *= validate.check($scope.form.lastname, 'Lastname');
            error *= validate.check($scope.form.phone, 'Phone');

            if (error) {
                request.send('/homeadvisor/linksSave', $scope.user, function (data) {
                    if (data) {
                        console.log("End");
                    }
                });
                $uibModalInstance.close();
            }
        };

        $scope.cancel = function () {
            $uibModalInstance.dismiss('cancel');
        };
    };
})();

;