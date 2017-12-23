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

    angular.module('app').controller('ReviewsSettingsCtrl', ['$rootScope', '$scope', '$uibModal', 'request', 'langs', 'logger', 'validate', ReviewsSettingsCtrl]);

    function ReviewsSettingsCtrl($rootScope, $scope, $uibModal, request, langs, logger, validate) {
        $scope.inputs = [''];
        $scope.list = [];
        $scope.origin_input = {};

        $scope.init = function() {
            $scope.get();
        };

        $scope.get = function () {
            request.send('/urls', {}, function (data) {
                if (data) {
                    $scope.inputs = data;
                    $scope.inputs.push({'editable': true});
                }
            }, 'get');
        };

        $scope.save = function (input) {
            var error = 1;
            error *= validate.check($scope.form.name, 'Name');
            error *= validate.check($scope.form.url, 'Url');

            if (error) {
                request.send('/urls/' + (input.id ? input.id : ''), input, function (data) {
                    if (data) {
                        input.editable = false;
                        if ( ! input.id) {
                            $scope.inputs.push({'editable': true});
                        }
                        input = data;
                    }
                }, (input.id ? 'post' : 'put'));
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