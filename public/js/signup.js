(function () {
    'use strict';

    angular.module('app', ['ngRoute', 'ngSanitize', 'ui.bootstrap', 'ngFileUpload']);
})();

(function () {
    'use strict';

    angular.module('app').controller('SignUpCtrl', ['$rootScope', '$scope', '$window', '$timeout', 'request', 'validate', 'langs', SignUpCtrl]);

    function SignUpCtrl($rootScope, $scope, $window, $timeout, request, validate, langs) {
        $scope.signUp = {};

        $scope.init = function(plansCode) {
           $scope.signUp.plans_code = plansCode;
        };

        $scope.signup = function() {
            var error = 1;
            error *= validate.check($scope.form.email, 'Email');
            error *= validate.check($scope.form.password, 'Password');
            error *= validate.check($scope.form.firstname, 'Name');

            if ($scope.signUp.plans_code == 'home-advisor') {
                error *= validate.check($scope.form.ha_rep, 'HomeAdvisor Rep');
            }

            if (error) {
                request.send('/auth/signup', $scope.signUp, function(data) {
                    if (data) {
                        $timeout(function() {
                            $window.location.href = "/";
                        }, 1000);
                    }
                }); 
            }
        };
    };
})();

;