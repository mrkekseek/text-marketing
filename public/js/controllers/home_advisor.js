(function () {
    'use strict';

    angular.module('app').controller('HomeAdvisorCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', HomeAdvisorCtrl]);

    function HomeAdvisorCtrl($rootScope, $scope, $uibModal, request, langs) {
        //$scope.requestForHa = false;
        $scope.activateHa = function() {
            $scope.requestForHa = true;
        };

        $scope.TextCharSetOptions = {
            'id' : 'messageText' ,
            'title': 'Message Text',
            'buttons': [
                {'name': 'Short Link',
                'mask': '[$ShortLink]',
                'type': 'short-link',
                'icon': 'link'},
                {'name': 'First Name',
                'mask': '[$FirstName]',
                'type': 'insert',
                'icon': 'user'},
                {'name': 'Last Name',
                'mask': '[$LastName]',
                'type': 'insert',
                'icon': 'user-o'}
             ]
        };


    };
})();

;