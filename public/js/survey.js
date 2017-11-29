(function () {
    'use strict';

    angular.module('app', ['ngRoute', 'ngSanitize', 'ui.bootstrap', 'ngFileUpload']);
})();

(function () {
    'use strict';

    angular.module('app').controller('SurveyCtrl', ['$rootScope', '$scope', '$window', '$location', 'request', 'validate', 'langs', SurveyCtrl]);

    function SurveyCtrl($rootScope, $scope, $window, $location, request, validate, langs) {
        $scope.seance = {'show_reviews': false};
        $scope.bed_answer = false;
        $scope.show_thanks = false;
        $scope.answers = [];

        $scope.init = function(seance) {
            $scope.seance = seance;
            console.log($scope.seance);
        };

        $scope.repeatStars = function(key) {
            return new Array(key * 1);
        };

        $scope.setAnswers = function(question) {
            if (($scope.seance.survey.type == 1 && question.value >= 4) || ($scope.seance.survey.type == 0 && question.value == 5) && ! $scope.bed_answer) {
                $scope.seance.show_reviews = $scope.show_thanks = true;
                $scope.answers.push({
                    'users_id': $scope.seance.user.id,
                    'clients_id': $scope.seance.clients_id,
                    'seances_id': $scope.seance.id,
                    'surveys_id': $scope.seance.surveys_id,
                    'questions_id': question.id,
                    'questions_type': question.type,
                    'questions_text': question.text,
                    'value': question.value
                });
                $scope.sendAnswers();
            } else {
                $scope.bed_answer = true;
            }
        };

        $scope.save = function() {
            $scope.answers = [];
            for (var k in $scope.seance.survey.questions) {
                $scope.answers.push({
                    'users_id': $scope.seance.user.id,
                    'clients_id': $scope.seance.clients_id,
                    'seances_id': $scope.seance.id,
                    'surveys_id': $scope.seance.surveys_id,
                    'questions_id': $scope.seance.survey.questions[k].id,
                    'questions_type': $scope.seance.survey.questions[k].type,
                    'questions_text': $scope.seance.survey.questions[k].text,
                    'value': $scope.seance.survey.questions[k].value
                });
            }
            $scope.show_thanks = true;
            $scope.sendAnswers();
        };

        $scope.socialSave = function(url) {
            request.send('/seances/' + url.id + '/socialSave/', url, function (data) {

            }, 'put');
        };

        $scope.sendAnswers = function() {
            request.send('/answers/save', {'answers': $scope.answers, 'seance': $scope.seance}, function (data) {

            }, 'put');
        };
    };
})();

;