angular.module('app').directive('charSet', function(getShortUrl, logger) {
	return {
  		require: 'ngModel',
		scope: {
			company: '=company',
			firstname: '=btnFirstname',
			lastname: '=btnLastname',
			link: '=btnLink',
			shortlink: '=btnShortlink',
			lms: '=lms',
			maxFirstname: '=maxFirstname',
			maxLastname: '=maxLastname',
			id: '=uniqueId',
			result: '=ngModel'
		},
		controller: ['$scope', '$timeout', function CharSetCtrl($scope, $timeout) {
			$scope.optout = $scope.company ? ' Txt STOP to OptOut' : '';
			$scope.minLms = 140 - $scope.optout.length - ($scope.company ? $scope.company.length - 2 : 0);
			$scope.max = ($scope.lms ? 500 : 140) - $scope.optout.length - ($scope.company ? $scope.company.length - 2 : 0);
			$scope.firstnameTag = '[$FirstName]';
			$scope.lastnameTag = '[$LastName]';
			$scope.linkTag = '[$Link]';
			$scope.size = 0;

			$scope.charCount = function () {
				$scope.size = 0;
				if ($scope.result && $scope.result != '' && $scope.company && $scope.company != '') {
					$scope.size = $scope.result.length + $scope.company.length + 2;
					if ($scope.result.indexOf($scope.firstnameTag)) {
						$scope.size += $scope.maxFirstname - $scope.firstnameTag.length;
					}

					if ($scope.result.indexOf($scope.lastnameTag)) {
						$scope.size += $scope.maxLastname - $scope.lastnameTag.length;
					}

					if ($scope.result.indexOf($scope.linkTag)) {
						$scope.size += 14 - $scope.linkTag.length;
					}
				}
			};

			$scope.$watch('result', function (newValue, oldValue) {
				$scope.charCount();
			});

			$scope.insert = function (tag) {
				var pos = $scope.caretPosition();
				var before = $scope.result.substr(0, pos);
				var after = $scope.result.substr(pos);
				if (before != '' && before.charAt(before.length - 1) != ' ') {
					tag = ' ' + tag;
				}

				if (after != '' && after.charAt(0) != ' ') {
					tag = tag + ' ';
				}
				$scope.result = before + tag + after;
			};

			$scope.caretPosition = function () {
				$scope.area = $('#' + $scope.id);
				return $scope.area.prop("selectionStart");
			};
			/*
			$scope.chooseButtonFunc = function(type, id, mask) {
				type ==='insert' ?  $scope.insertMask(id,mask) : $scope.showMessageTextUrl = ! $scope.showMessageTextUrl;
			};

			$scope.insertMask = function(id, text) {
				$scope.insertAtCaret(id,text);
				$timeout($scope.charCount(), 10); //<--------fix			
			};

			$scope.charCount = function(text) {
				var firstname = 0;
				var lastname = 0;
				if (text) {
					if (text.indexOf('[$FirstName]') + 1) {
						firstname = 30 - '[$LastName]'.length;
					}
					if (text.indexOf('[$LastName]') + 1) {
						lastname = 30 - '[$LastName]'.length;
					}
					return text.length + firstname + lastname + ($scope.options.user.company_name ? $scope.options.user.company_name.length : 0);
				}
				return 0;
			};

			$scope.insertShortLink = function(longLink) {
				getShortUrl.getLink(longLink, function(shortUrl) {
					if (shortUrl) {
						shortUrl = shortUrl.replace('http://', '');
						$scope.insertMask($scope.options.id, shortUrl);
						$scope.shortLinkMessageText = '';
						$scope.showMessageTextUrl = false;
					} else {
						logger.logError('Inccorect link');
					}
				});
			};

			$scope.insertAtCaret = function(areaId,text) {
				var txtarea = document.getElementById(areaId);
				var scrollPos = txtarea.scrollTop;
				var strPos = 0;
				var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ? 
					'ff' : (document.selection ? 'ie' : false ) );
				if (br == 'ie') {
					txtarea.focus();
					var range = document.selection.createRange();
					range.moveStart('character', -txtarea.value.length);
					strPos = range.text.length;
				}
				else if (br == 'ff') strPos = txtarea.selectionStart;

				var front = (txtarea.value).substr(0,strPos);  
				var back = (txtarea.value).substr(strPos,txtarea.value.length);
				if (front.substr(-1) != ' ' && front.substr(-1) != '') {
					text = ' ' + text;
				}

				txtarea.value = front + text + back;

				strPos = strPos + text.length;
				if (br == 'ie') {
					txtarea.focus();
					var range = document.selection.createRange();
					range.moveStart('character', -txtarea.value.length);
					range.moveStart('character', strPos);
					range.moveEnd('character', 0);
					range.select();
				}
				else if (br == "ff") {
					txtarea.selectionStart = strPos;
					txtarea.selectionEnd = strPos;
					txtarea.focus();
				}
				txtarea.scrollTop = scrollPos;
				$scope.result = txtarea.value;
			}*/
		}],
		templateUrl: '/uib/template/charset/charset.html'
	};
});