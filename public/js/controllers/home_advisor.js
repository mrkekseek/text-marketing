(function () {
    'use strict';

    angular.module('app').controller('HomeAdvisorCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', HomeAdvisorCtrl]);

    function HomeAdvisorCtrl($rootScope, $scope, $uibModal, request, langs) {
        $scope.inputs = [];
        $scope.list = [];
        $scope.user = $scope.user;

        $scope.init = function() {
            $scope.get();
            $scope.getLeads();
        };

        $scope.get = function() {
            request.send('/homeadvisor/' + $scope.user.id, {}, function (data) {
                $scope.homeadvisor = data;
                $scope.homeadvisor.active == 1 ? $scope.homeadvisor.active = true : false;
                $scope.requestForHa = $scope.homeadvisor.send_request;
                if ($scope.homeadvisor.additional_phones) {
                    $scope.inputs = $scope.homeadvisor.additional_phones.split(',');
                }
                $scope.inputs.push('');

            }, 'get');
        };

        $scope.getLeads = function() {
            request.send('/clients/leads', {}, function (data) {
                $scope.list = data;
            }, 'get');
        };

        $scope.activateHa = function() {
            $scope.requestForHa = true;
            request.send('/homeadvisor/activate', {}, function (data) {

            }, 'put');
        };

        $scope.TextCharSetOptions = {
            'id' : 'messageText',
            'title': 'Message Text',
            'user': $scope.user,
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

        $scope.addInput = function() {
            $scope.inputs.push('');
        };

        $scope.removeInput = function(index) {
            $scope.inputs.splice(index, 1);
        };

        $scope.save = function() {
            var post_mas = {
                'text': $scope.homeadvisor.text,
                'additional_phones': $scope.inputs.join(','),
                'active': $scope.homeadvisor.active,
                'company_name': $scope.user.company_name,
                'phone': $scope.user.phone
            };

            request.send('/homeadvisor/' +  $scope.homeadvisor.id, post_mas, function (data) {

            });
        };
    };
})();

;