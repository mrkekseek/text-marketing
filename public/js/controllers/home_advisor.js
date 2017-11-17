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
            request.send('/teams', false, function(data) {
                $scope.teams_list = data;
                $scope.teams_list.unshift({'id':'0' ,'name':'Select a Team...'});
            }, 'get');
        };

        $scope.by_id = function(users_id) {
            for (var k in $scope.list) {
                if ($scope.list[k].id == users_id) {
                    return $scope.list[k];
                }
            }

            return {};
        };

        $scope.remove = function(users_id) {
            if (confirm(langs.get('Do you realy want to remove this item?'))) {
                request.send('/homeadvisor/' + users_id, false, function(data) {
                    $scope.get();
                }, 'delete');
            }
        };

        $scope.get = function() {
            request.send('/homeadvisor', false, function(data) { 
                $scope.list = data;
                $scope.request_finish = true;
            }, 'get');
        };

        $scope.getTeamById = function(teamsId) {
            for (var t in $scope.teams_list) {
                if ($scope.teams_list[t].id == teamsId) {
                    return $scope.teams_list[t].name;
                }
            }
        };

        $scope.create = function(users_id) {
            users_id = users_id || false;

            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: 'AdvisorCreate.html',
                controller: 'AdvisorCtrl',
                resolve: {
                    items: function () {
                        return {'teams': $scope.teams_list, 'user': $scope.by_id(users_id)};                    
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
        $scope.user = angular.copy(items.user);
        $scope.type = 'Save';

        if (! $scope.user.id) {
            $scope.user.teams_id = '0';  
            $scope.type = 'Generate';        
        }

        $scope.save = function () {
            var error = 1;
            error *= validate.check($scope.form.firstname, 'Firstname');
            error *= validate.check($scope.form.team_id, 'Team');
            error *= validate.check($scope.form.lastname, 'Lastname');
            error *= validate.check($scope.form.phone, 'Phone');
            if (! $scope.user.id) {
                if (error) {
                    request.send('/homeadvisor/saveLink', $scope.user, function (data) {
                        if (data) {
                            $scope.user = data;
                            $scope.isShown = true;
                        }
                    });
                }
            } else {
                console.log('Save');
            }

        };

        $scope.cancel = function () {
            $uibModalInstance.close();
            $scope.isShown = false;
        };
    };
})();

;