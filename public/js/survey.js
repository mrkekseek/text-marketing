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
        $scope.class = '';
        $scope.answers = [];

        const BUTTON_ID="redirectClick";
        const PLATFORM_OTHER = 0;
        const PLATFORM_IOS = 1;
        const PLATFORM_ANDROID = 2;

        $scope.init = function(seance) {
            $scope.seance = seance;
            $scope.bed_answer = $scope.seance.bed_answers;
            $scope.class = $scope.seance.class;
            $scope.seance.show_reviews = $scope.show_thanks = $scope.seance.show_reviews;
        };

        $scope.repeatStars = function(key) {
            return new Array(key * 1);
        };

        $scope.setAnswers = function(question) {
            if (question.value == 5 && ! $scope.bed_answer) {
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
            request.send('/seances/' + $scope.seance.id + '/socialSave/', url, function(data) {

            }, 'put');
            
            if ( ! url.social_id) {
                $scope.redirect(url.url);
                return;
            }

            switch (url.name) {
                case 'Facebook': $scope.reviewFacebook(url.social_id); break;
                case 'Google': $scope.reviewGoogle(url.social_id); break;
                case 'Yelp': $scope.reviewYelp(url.social_id); break;
                default: return;
            }
        };

        $scope.sendAnswers = function() {
            request.send('/answers/save', {'answers': $scope.answers, 'seance': $scope.seance}, function(data) {

            }, 'put');
        };

        $scope.reviewYelp = function(businessId) {
            const browserLink = 'https://www.yelp.com/writeareview/biz/' + businessId + '?return_url=%2Fbiz%2F' + businessId;
            switch ($scope.getPlatform()) {
                case PLATFORM_ANDROID: $scope.redirect($scope.getEncodeAndroidIntent(browserLink)); break;
                case PLATFORM_IOS: $scope.redirect($scope.getEncodeIOSLink(browserLink)); break;
                default: $scope.redirect(browserLink); break;
            }
        }

        $scope.reviewFacebook = function(pageId) {
            const browserLink = "https://www.facebook.com/pg/"+pageId+"/reviews/";
            switch ($scope.getPlatform()){
                case PLATFORM_ANDROID: $scope.redirect($scope.getEncodeAndroidIntent(browserLink)); break;
                case PLATFORM_IOS:
                    $scope.redirect($scope.getEncodeIOSLink(browserLink));
                    break;
                default:
                    $scope.redirect(browserLink);
                    break;
            }
        }

        $scope.reviewGoogle = function(placeId) {
            //this link will send user directly to the review page however it is using browser
            const browserLink = "https://search.google.com/local/writereview?placeid="+placeId;

            //this link will send user to the maps application and will display the specific place
            //const browserLink = "https://www.google.com/maps/search/?api=1&query="+placeId+"&query_place_id="+placeId;
            
            $scope.redirect(browserLink);
        }

        $scope.getPlatform = function() {
            const userAgent = navigator.userAgent.toString().toLowerCase();
            if (userAgent.indexOf("iphone") > -1 || userAgent.indexOf("ipad") > -1) {
                return PLATFORM_IOS;
            } else if (userAgent.indexOf("android") > -1) {
                return PLATFORM_ANDROID;
            } else {
                return PLATFORM_OTHER;
            }
        }

        $scope.redirect = function(link) {
            document.getElementById(BUTTON_ID).href=link;
            document.getElementById(BUTTON_ID).click();
        }

        $scope.getEncodeIOSLink = function(fallbackUrl, url, scheme) {
            const urlParts = fallbackUrl.split('://');
            if(urlParts.length > 1) {
                scheme = scheme || urlParts.shift();
                url = url || urlParts.join('');
                return scheme+"://"+url;
            }
            return fallbackUrl;
        }

        $scope.getEncodeAndroidIntent = function(fallbackUrl, url, scheme) {
            const urlParts = fallbackUrl.split('://');
            if(urlParts.length > 1) {
                scheme = scheme || urlParts.shift();
                url = url || urlParts.join('');
            }
            return "intent://"+url+"#Intent;action=android.intent.action.VIEW;scheme="+scheme+";S.browser_fallback_url="+fallbackUrl+";end"
        }
    };
})();

;