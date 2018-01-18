angular.module('app').directive('bigStars', function() {
	return {
    	controller: function ($scope, $element, $attrs) {
    		$scope.width = 3;
        },
    	replace: true,
    	template: '<div class="question-stars"><div class="question-stars-inner" style="width: {{ width * 100 / 5 }}%"></div><img src="/img/stars.png" alt=""></div>'
	}
});