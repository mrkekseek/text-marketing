angular.module('app').directive('charSet', function(getShortUrl, logger) {
  return {
  	require: 'ngModel',
  	scope: {
  		options: '=options',
  		result: '=ngModel'
  	},
  	controller: ['$scope', '$timeout', function CharSetCtrl($scope, $timeout) {
  		$scope.max_text_len = 140 - ' Txt STOP to OptOut'.length;
        $scope.max_lms_text_len = 500 - ' Txt STOP to OptOut'.length;
        $scope.showMessageTextUrl = false;

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
		}
  	}],
    templateUrl: '/uib/template/charset/charset.html'
	};
});