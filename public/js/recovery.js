(function () {
    'use strict';

    angular.module('app', ['ngRoute', 'ngSanitize', 'ui.bootstrap', 'ngFileUpload']);
})();

(function () {
    'use strict';

    angular.module('app').controller('RecoveryCtrl', ['$rootScope', '$scope', '$window', '$timeout', 'request', 'validate', 'langs', RecoveryCtrl]);

    function RecoveryCtrl($rootScope, $scope, $window, $timeout, request, validate, langs) {
        $scope.recovery = {};

        $scope.send = function() {
            var error = 1;
            error *= validate.check($scope.form.email, 'Email');
    
            if (error) {
                $rootScope.request_sent = true;
                request.send('/auth/recovery', $scope.recovery, function(data) {
                    if (data) {
                        $timeout(function() {
                            $window.location.href = "/";
                        }, 1000);
                    } else {
                        $rootScope.request_sent = false;
                    }
                });
            }
        };
    };
})();

;