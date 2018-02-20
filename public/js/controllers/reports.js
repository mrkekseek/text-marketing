(function () {
    'use strict';

    angular.module('app').controller('ReportsCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', ReportsCtrl]);

    function ReportsCtrl($rootScope, $scope, $uibModal, request, langs) {
        $scope.request_finish = false;
        $scope.filter = {
            'type': '',
            'phone': '',
            'date': new Date()
        };
        $scope.list = [];
        $scope.phones = [];

        $scope.init = function () {
            $scope.getPhones();
        };

        $scope.getPhones = function () {
            request.send('/phones', {}, function (data) {
                $scope.phones = data;
                $scope.get();
            }, 'get');
        };

        $scope.get = function () {
            request.send('/reports', $scope.filter, function (data) {
                $scope.list = data.map(function (message) {
                    var date = new Date(message.created_at);
                    var hours = date.getHours();
                    var minutes = date.getMinutes();
                    var ampm = hours >= 12 ? 'PM' : 'AM';
                    hours = hours % 12;
                    hours = hours ? hours : 12;
                    message.created_at = ('0' + hours).slice(-2) + ':' + ('0' + minutes).slice(-2) + ' ' + ampm;
                    return message;
                });
                console.log($scope.list);
                $scope.request_finish = true;
            }, 'post');
        };

        $scope.dateOptions = {
            formatYear: 'yy',
            maxDate: new Date(),
            startingDay: 1
        };

        $scope.date = {
            opened: false
        };

        $scope.openDate = function() {
            $scope.date.opened = true;
        };

        $scope.trumpiaModal = function(client) {
            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: 'TrumpiaModal.html',
                controller: 'TrumpiaModalCtrl',
                resolve: {
                    items: function () {
                        return {
                            trumpia: client.trumpia
                        };
                    }
                }
            });

            modalInstance.result.then(function () {
                
            }, function () {
                $log.info('Modal dismissed at: ' + new Date());
            });
        };
    };
})();

;

(function () {
    'use strict';

    angular.module('app').controller('TrumpiaModalCtrl', ['$rootScope', '$scope', '$uibModalInstance', 'items', TrumpiaModalCtrl]);

    function TrumpiaModalCtrl($rootScope, $scope, $uibModalInstance, items) {
        $scope.trumpia = angular.copy(items.trumpia);
        
        $scope.cancel = function () {
            $uibModalInstance.dismiss('cancel');
        };
    };
})();

;