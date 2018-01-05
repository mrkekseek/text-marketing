(function () {
    'use strict';

    angular.module('app', ['ngRoute', 'ngSanitize', 'ui.bootstrap']);
})();

;

(function () {
    'use strict';

    angular.module('app').controller('SignInCtrl', ['$rootScope', '$scope', '$window', '$timeout', 'request', 'validate', 'langs', SignInCtrl]);

    function SignInCtrl($rootScope, $scope, $window, $timeout, request, validate, langs) {
        $scope.auth = {};

        $scope.signin = function () {
            var error = 1;
            error *= validate.check($scope.form.email, 'Email');
            error *= validate.check($scope.form.password, 'Password');
            if (error) {
                $rootScope.request_sent = true;
                request.send('/auth/signin', $scope.auth, function(data) {
                    if (data) {
                        $timeout(function () {
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