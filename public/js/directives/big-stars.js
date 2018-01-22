angular.module('app').directive('bigStars', function() {
	return {
		//scope: {},
		transclude: true,
		require: ['bigStars', 'ngModel'],
		controller: ['$scope', function directiveController($scope){
			this.test = function(link) {
				for (var k in link) {
					link[k] = 5;
				}
				console.log(link);
				return link;
				/*link.on('click', function(event) {
					alert('hello');
				});*/
			};
		}],
		restrict: 'CEAM',
    	link: function (scope, element, attrs, controllers, transclude) {
    		var directiveCtrl = controllers[0],
    		modelCtrl = controllers[1];
    		//console.log(scope);
    		//scope.analysis.rating = 5
    		element.on('click', function() {
    			alert('hello');
    		});
    		transclude(scope, function(clone, scope) {
    			console.log(clone);
    		});
    		
    		directiveCtrl.test(scope.responses);
        },
    	//replace: true,
    	template: '<div ng-transclude></div><div>Big Stars</div>'
	}
});