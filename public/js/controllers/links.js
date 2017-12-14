(function () {
    'use strict';

    angular.module('app').controller('LinksCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', LinksCtrl]);

    function LinksCtrl($rootScope, $scope, $uibModal, request, langs) {
        $scope.request_finish = false;
        $scope.list = [];
        $scope.teams_list = [];

        $scope.init = function () {
            $scope.get();
            $scope.teams();
        };

        $scope.get = function () {
            request.send('/links', {}, function (data) { 
                $scope.request_finish = true;
                $scope.list = data;
            }, 'get');
        };

        $scope.teams = function () {
            request.send('/teams', {}, function (data) {
                $scope.teams_list = data;
                $scope.teams_list.unshift({
                    'id': '0',
                    'name': 'Select a Team...'
                });
            }, 'get');
        };

        $scope.by_id = function (links_id) {
            for (var k in $scope.list) {
                if ($scope.list[k].id == links_id) {
                    return $scope.list[k];
                }
            }
            return {};
        };

        $scope.remove = function (links_id) {
            if (confirm(langs.get('Do you realy want to remove this item?'))) {
                request.send('/links/' + links_id, {}, function (data) {
                    $scope.get();
                }, 'delete');
            }
        };
        
        $scope.create = function (links_id) {
            links_id = links_id || false;

            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: 'ModalLinksCreate.html',
                controller: 'ModalLinksCreateCtrl',
                resolve: {
                    items: function () {
                        return {'link': $scope.by_id(links_id), 'teams': $scope.teams_list};                    
                    }
                }
            });

            modalInstance.result.then(function (response) {
                $scope.get();
            }, function () {

            });
        };
    };
})();

;


(function () {
    'use strict';

    angular.module('app').controller('ModalLinksCreateCtrl', ['$rootScope', '$scope', '$uibModalInstance', 'request', 'validate', 'logger', 'langs', 'items', ModalLinksCreateCtrl]);
    function ModalLinksCreateCtrl($rootScope, $scope, $uibModalInstance, request, validate, logger, langs, items) {
        $scope.teams = angular.copy(items.teams);
        $scope.link = angular.copy(items.link);
        $scope.button = 'Save';

        if ( ! $scope.link.id) {
            $scope.link.teams_id = '0';
            $scope.button = 'Generate';
        }

        $scope.save = function () {
            var error = 1;
            error *= validate.check($scope.form.teams_id, 'Team');
            error *= validate.check($scope.form.firstname, 'Firstname');
            error *= validate.check($scope.form.lastname, 'Lastname');
            error *= validate.check($scope.form.phone, 'Phone');

            if (error) {
                request.send('/links/' + ($scope.link.id ? $scope.link.id : 'save'), $scope.link, function (data) {
                    if (data) {
                        $scope.isShown = true;
                        $uibModalInstance.close();
                    }
                }, ($scope.link.id ? 'post' : 'put'));
            }
        };

        $scope.cancel = function () {
            $uibModalInstance.dismiss('cancel');
        };
    };
})();

;