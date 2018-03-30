(function () {
    'use strict';

    angular.module('app').controller('GeneralMessagesCtrl', ['$rootScope', '$scope', '$location', '$uibModal', 'request', 'langs', 'logger', GeneralMessagesCtrl]);

    function GeneralMessagesCtrl($rootScope, $scope, $location, $uibModal, request, langs, logger) {
        $scope.dialogs = [];
        $scope.messages = [];
        $scope.activeClient = {};
        $scope.messages_text = '';
        $scope.sent = false;
        $scope.inputs = [''];
        $scope.showPhonesBox = true;

        $scope.init = function () {
            $scope.get();
        };

        $scope.get = function () {
            request.send('/homeadvisor/general', {}, function (data) {
                $scope.dialogs = data;
                if ($scope.dialogs.length) {
                    var temp = $location.path().split('/');
                    if (temp[3]) {
                        for (var k in $scope.dialogs) {
                            if ($scope.dialogs[k].id == temp[3]) {
                                $scope.activeClient = $scope.dialogs[k];
                            }
                        }
                        $scope.getClientName($scope.activeClient.id);
                    } else if ($scope.dialogs.length) {
                        $scope.activeClient = angular.copy($scope.dialogs[0]);
                        $scope.getClientName($scope.activeClient.id);
                    }
                    $scope.setClient($scope.activeClient);
                }
            }, 'get');
        };

        $scope.getClientName = function (id) {
            for (var k in $scope.dialogs) {
                if ($scope.dialogs[k].id == id) {
                    $scope.activeClient.firstname = $scope.dialogs[k].firstname;
                    $scope.activeClient.lastname = $scope.dialogs[k].lastname;
                }
            }
            return $scope.activeClient;
        };

        $scope.createDate = function (string) {
            string = string.replace(' ', 'T');
            return string;
        };

        $scope.getSuffix = function (num) {
            if (num) {
                num = num.toString();
                if (num == '11' || '12' || '13') { return 'th'; }
                var res = '';
                switch (num.slice(num.length - 1)) {
                    case '1': res = 'st'; break;
                    case '2': res = 'nd'; break;
                    case '3': res = 'rd'; break;
                    default: res = 'th'; break;
                }
            }

            return res;
        };

        $scope.setClient = function (dialog) {
            $location.path('/inbox/list/' + dialog.id, false);
            $scope.activeClient.id = dialog.id;
            $scope.activeClient.firstname = dialog.firstname;
            $scope.activeClient.lastname = dialog.lastname;
            $scope.showPhonesBox = !$scope.showPhonesBox;
            request.send('/homeadvisor/general/' + dialog.phone, {}, function (data) {
                for (var k in data) {
                    data[k].text = data[k].text.replace('[$FirstName]', data[k].user_firstname);
                }
                $scope.messages = data;
            }, 'get');
        };

        $scope.maxChars = function () {
            return 500 - ' Txt STOP to OptOut'.length - ($scope.user.company_status == 'verified' ? $scope.user.company_name.length + 2 : 0);
        };

        $scope.maxOneText = function () {
            return 140 - ' Txt STOP to OptOut'.length - ($scope.user.company_status == 'verified' ? $scope.user.company_name.length + 2 : 0);
        };

        $scope.charsCount = function (text) {
            if (text) {
                return text.length;
            }
            return 0;
        };

        $scope.send = function () {
            if (!$scope.messages_text) {
                logger.logError(langs.get('Text is empty.'));
                return;
            }

            var date = new Date();
            var post_mas = {
                'text': $scope.messages_text,
                'time': {
                    'hour': date.getHours()
                }
            };
            
            $scope.sent = true;
            request.send('/general/send/' + $scope.activeClient.id, post_mas, function (data) {
                if (data) {
                    $scope.messages.push(data);
                    $scope.messages_text = '';
                }
                $scope.sent = false;
            }, 'put');
        };
    };
})();

;