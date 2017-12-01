(function () {
    'use strict';

    angular.module('app').controller('ReviewsAnalysisCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', ReviewsAnalysisCtrl]);

    function ReviewsAnalysisCtrl($rootScope, $scope, $uibModal, request, langs) {
    	   $scope.analysis = {};
    	   $scope.analysis.teams_id = 0;

    	   $scope.toggle_date = function(time) {
    	   		$scope.analysis.timeframe = time;
    	   		$scope.open_period = $scope.analysis.timeframe == 'custom' ? true : false;
    	   };
           
    };
})();

;

(function () {
    'use strict';

    angular.module('app').controller('CorporateReviewCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', CorporateReviewCtrl]);

    function CorporateReviewCtrl($rootScope, $scope, $uibModal, request, langs) {

           $scope.list = [{'firstName' : 'name', 'lastName' : 'surname'},{'firstName' : 'name2', 'lastName' : 'surname2'},{'firstName' : 'name3', 'lastName' : 'surname3'}];
           
    };
})();

;

(function () {
    'use strict';

    angular.module('app').controller('ReviewsSettingsCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', ReviewsSettingsCtrl]);

    function ReviewsSettingsCtrl($rootScope, $scope, $uibModal, request, langs) {
        $scope.inputs = [''];
        $scope.list = [];

        $scope.init = function() {
            request.send('/urls', {}, function (data) {
                $scope.inputs = [];
                for (var k in data) {
                    if (data[k].default) {
                        $scope.list.push({
                            'id': data[k].id,
                            'name': data[k].name,
                            'url': data[k].url,
                            'default': data[k].default,
                            'active': data[k].active == 1 ? true : false
                        });
                    } else {
                        $scope.inputs.push({
                            'id': data[k].id,
                            'name': data[k].name,
                            'url': data[k].url,
                            'default': data[k].default,
                            'active': data[k].active == 1 ? true : false
                        });
                    }
                }
                $scope.inputs.push('');
            }, 'get');
        };

        $scope.save = function () {
            var post_mas = $scope.list.concat($scope.inputs);
            request.send('/urls/save', post_mas, function (data) {

            });
        };

        $scope.changeActive = function(url) {
            if (url.id) {
                request.send('/urls/save' + url.id, url, function (data) {

                });
            }
        };

        $scope.addInput = function() {
            $scope.inputs.push('');
        };

        $scope.removeInput = function(key) {
            $scope.inputs.splice(key, 1);
        };
    };
})();

;