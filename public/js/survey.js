(function () {
    'use strict';

    angular.module('app', ['ngRoute', 'ngSanitize', 'ui.bootstrap', 'ngFileUpload']);
})();

(function () {
    'use strict';

    angular.module('app').controller('SurveyCtrl', ['$rootScope', '$scope', '$window', '$location', 'request', 'validate', 'langs', SurveyCtrl]);

    function SurveyCtrl($rootScope, $scope, $window, $location, request, validate, langs) {
        $scope.seance = {};
        $scope.bed_answer = false;
        $scope.show_thanks = false;

        $scope.init = function(seance) {
            $scope.seance = seance;
        };

        $scope.repeatStars = function(key) {
            key = key * 1;
            return new Array(key);
        };

        $scope.repeatAnswers = function() {
            return new Array(6);
        }

        $scope.setAnswers = function(question) {
            if (($scope.seance.survey.type == 1 && question.value >= 4) || ($scope.seance.survey.type == 0 && question.value == 5)) {
                $scope.show_thanks = true;
            } else {
                $scope.bed_answer = true;
            }
        };
    };
})();

;