angular.module('app').directive('stars', function() {
	return {
    	controller: function ($scope, $element, $attrs) {
    		$scope.starsColor = $attrs.starsColor || '#FEA40C';
    		$scope.starsAlign = $attrs.starsAlign || 'none';
    		$scope.starsSize = $attrs.starsSize || '16';
    		$scope.starsPadding = $attrs.starsPadding || '1';
    		$scope.starsWrap = $attrs.starsWrap || 'nowrap';
    		$scope.starsSpin = $attrs.starsSpin == 'true' ? 'fa-spin' : '';
    		$scope.starsOrientation = $attrs.starsOrientation == 'vertical' ? true : false;
    		
    		$scope.options = {
    			'align': 'float:' + $scope.starsAlign,
    			'size': 'font-size:' + $scope.starsSize + 'px',
    			'color': 'color:' + $scope.starsColor,
    			'padding': 'padding:' + $scope.starsPadding + 'px',
    			'white-space': 'white-space:' + $scope.starsWrap
    		};

    		$scope.getStyle = function() {
    			var temp = [];
    			for (var k in $scope.options) {
    				if ($scope.options[k] == 'color:gold') {
    					$scope.options[k] = $scope.options[k].replace('gold', '#FEA40C'); 
    				}
    				temp.push($scope.options[k]);
    			}
    			return temp.join(';');
    		};

    		$scope.getStars = function() {
	            return new Array($attrs.stars * 1);
	        }
        },
    	replace: true,
    	template: '<span style="{{getStyle()}};"><span ng-repeat="s in getStars() track by $index"><i class="fa fa-star {{starsSpin}}"></i><br ng-show="starsOrientation" /></span></span>'
	}
});