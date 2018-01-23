angular.module('app').directive('textArea', function($http, logger) {
	return {
		scope: {
			model: '=ngModel'
		},
		restrict: 'CEAM',
		require: ['textArea', 'ngModel'],
		controller: ['$scope', function TextAreaCtrl($scope) {
			$scope.size = 0;
			$scope.showUrl = false;
			$scope.id = 'test';

			$scope.charCount = function () {
				$scope.size = 0;
				if ($scope.model && $scope.model != '') {
					$scope.size = $scope.model.length;
				}
				return $scope.size;
			};

			$scope.insertShortLink = function(longUrl) {
               	$http.post('/getShortUrl', {'url': longUrl}).then(function(response) {
               		var shortUrl = response.data;
					$scope.insert(shortUrl);
					$scope.showUrl = false;
               	});
			};

			$scope.insert = function (tag) {
				var pos = $scope.caretPosition();
				var before = $scope.model.substr(0, pos);
				var after = $scope.model.substr(pos);
				if (before != '' && before.charAt(before.length - 1) != ' ') {
					tag = ' ' + tag;
				}

				if (after != '' && after.charAt(0) != ' ') {
					tag = tag + ' ';
				}
				$scope.model = before + tag + after;
			};

			$scope.caretPosition = function () {
				$scope.area = $('#' + $scope.id);
				return $scope.area.prop("selectionStart");
			};

			$scope.toggleUrl = function() {
				$scope.showUrl = ! $scope.showUrl;
			};

			$scope.$watch($scope.model, function(value) {
	            $scope.charCount();
	        });
		}],
		link: function(scope, element, attrs, ctrls) {
			var textAreaCtrl = ctrls[0],
			parentCtrl = ctrls[1];

	        scope.max = attrs.max || 140;
	        scope.firstname = attrs.btnFirstname == 'true' ? true : false;
	        scope.lastname = attrs.btnLastname == 'true' ? true : false;
	        scope.link = attrs.btnLink == 'true' ? true : false;
	        scope.shortLink = attrs.btnShortLink == 'true' ? true : false;
		},
		replace: true,
		templateUrl: '/uib/template/textarea/textarea.html'

	};
});