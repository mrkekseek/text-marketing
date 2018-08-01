(function () {
    'use strict';

    angular.module('app', ['ngRoute', 'ngSanitize', 'ui.bootstrap']);
})();

(function () {
    'use strict';

    angular.module('app').controller('SignUpCtrl', ['$rootScope', '$scope', '$window', '$timeout', 'request', 'validate', 'langs', SignUpCtrl]);

    function SignUpCtrl($rootScope, $scope, $window, $timeout, request, validate, langs) {
        $scope.signUpPage = '';
        $scope.signUp = {};

        $scope.init = function(plansCode) {
            if (plansCode != 'error') {
                if (plansCode == 'ha-text') {
                    plansCode = 'home-advisor';
                }
                $scope.signUp.plans_id = plansCode;
                $scope.signUpPage = 'show';
            } else {
                $scope.signUpPage = 'error';
            }
        };

        $scope.signup = function() {
            var error = 1;
            error *= validate.check($scope.form.email, 'Email');
            error *= validate.check($scope.form.password, 'Password');
            error *= validate.check($scope.form.firstname, 'Name');

            if ($scope.signUp.plans_id == 'home-advisor' || $scope.signUp.plans_id == 'free') {
                error *= validate.check($scope.form.lastname, 'Last Name');
                error *= validate.check($scope.form.view_phone, 'Your Cell #');
            }

            if (error) {
                $rootScope.request_sent = true;
                request.send('/auth/signup', $scope.signUp, function(data) {
                    if (data) {
                        $timeout(function() {
                            $window.location.href = "/plans/info";
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