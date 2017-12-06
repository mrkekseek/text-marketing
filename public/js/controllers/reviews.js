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

    angular.module('app').controller('ReviewsSettingsCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', 'logger', ReviewsSettingsCtrl]);

    function ReviewsSettingsCtrl($rootScope, $scope, $uibModal, request, langs, logger) {
        $scope.inputs = [''];
        $scope.list = [];
        $scope.origin_input = {};

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
                $scope.inputs.push({'editable': true});
            }, 'get');
        };

        $scope.save = function (input) {
            var error = 1;
            if (! input.url || input.url == '') {
                logger.logError(langs.get('Url is empty.'));
                error = 0;
            }

            if (! input.name || input.name == '') {
                logger.logError(langs.get('Name is empty.'));
                error = 0;
            }

            if (error) {
                input.editable = false;
                if (input.id) {
                    request.send('/urls/' + input.id, input, function (data) {
                        input.icon = data.icon;
                    });
                } else {
                    request.send('/urls/save', input, function (data) {
                        input.id = data.id;
                        input.icon = data.icon;
                    }, 'put');
                    $scope.inputs.push({'editable': true});
                }
            }
        };

        $scope.changeActive = function(input) {
            if (input.id) {
                request.send('/urls/' + input.id, input, function (data) {

                });
            }
        };

        $scope.edit = function(input) {
            $scope.origin_input = angular.copy(input);
            input.editable = true
        }

        $scope.cancel = function(key) {
            $scope.inputs[key] = $scope.origin_input;
            $scope.inputs[key].editable = false;
        };

        $scope.addInput = function() {
            $scope.inputs.push('');
        };

        $scope.removeInput = function(input, key) {
            if (confirm(langs.get('Do you realy want to remove this url?'))) {
                $scope.inputs.splice(key, 1);
                request.send('/urls/' + input.id, {}, function (data) {

                }, 'delete');
            }
        };
    };
})();

;