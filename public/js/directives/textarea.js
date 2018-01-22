angular.module('app').directive('textArea', function(getShortUrl, logger) {
	return {
		scope: {
			model: '=ngModel'
		},
		restrict: 'CEAM',
		require: ['textArea', 'ngModel'],
		controller: ['$scope', function TextAreaCtrl($scope) {			
			this.init = function(model) {
				$scope.model = model;
			};

			this.result = function() {
				return $scope.model;
			};
		}],
		link: function(scope, element, attrs, ctrls) {
			var textAreaCtrl = ctrls[0],
				parentCtrl = ctrls[1];

			scope.$watch(attrs.ngModel, function(newValue, oldValue) {
	            if (newValue) {
	            	textAreaCtrl.init(newValue);
	            }
	        });
	        scope.max = 500;
		},
		replace: true,
		templateUrl: '/uib/template/textarea/textarea.html'

	};
});