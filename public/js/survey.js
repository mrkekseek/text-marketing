(function () {
    'use strict';

    angular.module('app', ['ngRoute', 'ngSanitize', 'ui.bootstrap']);
})();

(function () {
    'use strict';

    angular.module('app').controller('SurveyCtrl', ['$rootScope', '$scope', '$window', '$location', 'request', 'validate', 'langs', SurveyCtrl]);

    function SurveyCtrl($rootScope, $scope, $window, $location, request, validate, langs) {
        $scope.seance = {'show_reviews': false};
        $scope.questions = [];
        $scope.thanks = false;
        $scope.why = false;
        $scope.answers = [];
        $scope.autoReview = false;

        const BUTTON_ID="redirectClick";
        const PLATFORM_OTHER = 0;
        const PLATFORM_IOS = 1;
        const PLATFORM_ANDROID = 2;

        $scope.init = function (seance, questions) {
            $scope.seance = seance;
            $scope.questions = questions;
            $scope.thanks = $scope.questions == '0';
            $scope.why = $scope.questions != '0' && $scope.questions.length == 1;
            var count = 0;
            var activeGoogle = false;
            for (var k in $scope.seance.review.user.urls) {
                var url = $scope.seance.review.user.urls[k];
                count += url.active;
                if (url.name == 'Google' && url.active) {
                    activeGoogle = url;
                }
                
            }

            if (activeGoogle && count == 1) {
                $scope.autoReview = activeGoogle;
            }
        };

        $scope.set = function(question) {
            if (question.value == 5) {
                $scope.answers.push({
                    'question_id': question.id,
                    'value': question.value
                });

                $scope.send();
            } else {
                $scope.why = true;
            }
        };

        $scope.save = function() {
            $scope.answers = [];
            for (var k in $scope.questions) {
                $scope.answers.push({
                    'question_id': $scope.questions[k].id,
                    'value': $scope.questions[k].value
                });
            }
            $scope.send();
        };

        $scope.send = function () {
            request.send('/answers/' + $scope.seance.id, {'answers': $scope.answers}, function (data) {
                $scope.thanks = true;

                if ($scope.autoReview && ! $scope.why) {
                    $scope.urlClick($scope.autoReview);
                }
            }, 'put');
        };

        $scope.urlClick = function(url) {
            request.send('/seances/' + $scope.seance.id + '/tap', {'url_id': url.id}, false, 'put');
            
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

        $scope.reviewYelp = function(businessId) {
            const browserLink = 'https://www.yelp.com/writeareview/biz/' + businessId + '?return_url=%2Fbiz%2F' + businessId;
            switch ($scope.getPlatform()) {
                case PLATFORM_ANDROID:
                    $scope.redirect($scope.getEncodeAndroidIntent(browserLink));
                    break;
                case PLATFORM_IOS:
                    $scope.redirect($scope.getEncodeIOSLink(browserLink));
                    break;
                default:
                    $scope.redirect(browserLink);
                    break;
            }
        }

        $scope.reviewFacebook = function(pageId) {
            const browserLink = "https://www.facebook.com/pg/"+pageId+"/reviews/";
            switch ($scope.getPlatform()){
                case PLATFORM_ANDROID:
                    $scope.redirect($scope.getEncodeAndroidIntent(browserLink));
                    break;
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
            document.getElementById(BUTTON_ID).href = link;
            document.getElementById(BUTTON_ID).click();
        }

        $scope.getEncodeIOSLink = function(fallbackUrl, url, scheme) {
            const urlParts = fallbackUrl.split('://');
            if(urlParts.length > 1) {
                scheme = scheme || urlParts.shift();
                url = url || urlParts.join('');
                return scheme + "://" + url;
            }
            return fallbackUrl;
        }

        $scope.getEncodeAndroidIntent = function(fallbackUrl, url, scheme) {
            const urlParts = fallbackUrl.split('://');
            if(urlParts.length > 1) {
                scheme = scheme || urlParts.shift();
                url = url || urlParts.join('');
            }
            return "intent://" + url + "#Intent;action=android.intent.action.VIEW;scheme=" + scheme + ";S.browser_fallback_url=" + fallbackUrl + ";end"
        }
    };
})();

;