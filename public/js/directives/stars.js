angular.module('app').directive('stars', function() {
    function getTemplate() {
        var template = '';
        var star = {
            'svg': '<svg aria-label="star" style="fill: {{optionsStar.color}};" height="{{optionsStar.size}}" role="img" version="1.1" viewBox="0 0 14 16" width="{{optionsStar.size}}"><path fill-rule="evenodd" d="M14 6l-4.9-.64L7 1 4.9 5.36 0 6l3.6 3.26L2.67 14 7 11.67 11.33 14l-.93-4.74z"></path></svg>',
            'fontAweSome': '<i class="fa fa-star {{starsSpin}}"></i>',
            'font': '<span>&#10032;</span>'
        };
        
        template += '<span style="{{mainOptions}};">';
        template +=     '<span ng-repeat="s in stars track by $index" class="spiner">';
        template +=         star.fontAweSome;
        template +=         '<br ng-show="starsOrientation" />';
        template +=     '</span>';
        template += '</span>';
        return template;
    };

    function link(scope, element, attrs, ctrl) {
        scope.starsSpin = attrs.starsSpin == 'true' ? 'fa-spin' : '';
        scope.starsOrientation = attrs.starsOrientation == 'vertical' ? true : false;
        scope.starType = attrs.starsType;
        
        scope.options = {
            'align': 'float:' + (attrs.starsAlign || 'none'),
            'size': 'font-size:' +  (attrs.starsSize || '16') + 'px',
            'color': 'color:' + (attrs.starsColor || '#FEA40C'),
            'padding': 'padding:' + (attrs.starsPadding || '1') + 'px',
            'white-space': 'white-space:' + (attrs.starsWrap || 'nowrap')
        };

        scope.optionsStar = {
            'color': attrs.starsColor || '#FEA40C',
            'size': attrs.starsSize || '16'
        };

        scope.mainOptions = ctrl.getStyle(scope.options);
        scope.stars = ctrl.getStars(attrs.stars);
    };
	return {
        restrict: 'A',
        scope: {},
        controller: ['$scope', function directiveController($scope) {
            this.getStyle = function(options) {
                var temp = [];
                for (var k in options) {
                    if (options[k] == 'color:gold') {
                        options[k] = options[k].replace('gold', '#FEA40C'); 
                    }
                    temp.push(options[k]);
                }
                return temp.join(';');
            };

            this.getStars = function(stars) {
                return new Array(stars * 1);
            }
        }],
    	link: link,
    	replace: true,
    	template: getTemplate
	}
});