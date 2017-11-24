(function () {
    'use strict';

    angular.module('app', ['ngRoute', 'ngSanitize', 'ui.bootstrap', 'ngFileUpload']);
})();

(function () {
    'use strict';

    angular.module('app').controller('SurveyCtrl', ['$rootScope', '$scope', '$window', '$timeout', 'request', 'validate', 'langs', SurveyCtrl]);

    function SurveyCtrl($rootScope, $scope, $window, $timeout, request, validate, langs) {
        $scope.seance = {};
        $scope.init = function(seance) {
            $scope.seance = seance;
            console.log($scope.seance);
        };
    };
})();

;