(function () {
    'use strict';

    angular.module('app', ['ngRoute', 'ngSanitize', 'ui.bootstrap', 'ngFileUpload']);
})();

(function () {
    'use strict';

    angular.module('app').controller('SupportCtrl', ['$rootScope', '$scope', '$window', '$timeout', 'request', 'validate', 'langs', SupportCtrl]);

    function SupportCtrl($rootScope, $scope, $window, $timeout, request, validate, langs) {
        $scope.support = {
            'subject': 'Support'
        };

        $scope.send = function() {
            var error = 1;
            error *= validate.check($scope.form.email, 'Email');
            error *= validate.check($scope.form.message, 'Message'); 
            error *= validate.check($scope.form.name, 'Name');     
            if (error) {
                $rootScope.request_sent = true;
                request.send('/auth/support', $scope.support, function(data) {
                    if (data) {
                        $rootScope.request_sent = false;
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