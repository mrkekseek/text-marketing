angular.module('app').directive('charSet', function() {
  return {
  	transclude: true,
  	replace: true,
  	require: 'ngModel',
  	scope: {
  		options: '=options',
  		result: '=ngModel'
  	},
  	controller: ['$scope', function CharSetCtrl($scope) {
  		$scope.max_text_len = 140 - ' Txt STOP to OptOut'.length;
        $scope.max_lms_text_len = 500 - ' Txt STOP to OptOut'.length;
        $scope.showMessageTextUrl = false;
        $scope.totalLength = 0;
  		var maskFirstName = 0;
  		var maskLastName = 0;

  		$scope.chooseButtonFunc = function(type, id, mask) {
  			 type ==='insert' ?  $scope.insertMask(id,mask) : $scope.showMessageTextUrl = true;
  		};

  		$scope.insertMask = function(id, text) {
			$scope.insertAtCaret(id,text);
			$scope.charCount();
		};

		$scope.charCount = function() {
			maskFirstName = ($scope.result.match(/\[\$FirstName\]/g) || []).length * 18;
			maskLastName = ($scope.result.match(/\[\$LastName\]/g) || []).length * 19;
			$scope.totalLength = maskFirstName + maskLastName + $scope.result.length;
  			$scope.max_text_len = $scope.max_text_len < 121 ? $scope.max_text_len : $scope.max_lms_text_len;
			
		};

		$scope.insertAtCaret = function(areaId,text) {
			var txtarea = document.getElementById(areaId);
			var scrollPos = txtarea.scrollTop;
			var strPos = 0;
			var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ? 
				'ff' : (document.selection ? 'ie' : false ) );
			if (br == 'ie')
			{
				txtarea.focus();
				var range = document.selection.createRange();
				range.moveStart('character', -txtarea.value.length);
				strPos = range.text.length;
			}
			else if (br == 'ff') strPos = txtarea.selectionStart;

			var front = (txtarea.value).substr(0,strPos);  
			var back = (txtarea.value).substr(strPos,txtarea.value.length);
			if(front.substr(-1) != ' ' && front.substr(-1) != '')
			{
				text = ' ' + text;
			}

			txtarea.value = front + text + back;

			strPos = strPos + text.length;
			if (br == 'ie')
			{
				txtarea.focus();
				var range = document.selection.createRange();
				range.moveStart('character', -txtarea.value.length);
				range.moveStart('character', strPos);
				range.moveEnd('character', 0);
				range.select();
			}
			else if (br == "ff")
			{
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