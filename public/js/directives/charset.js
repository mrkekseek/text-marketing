angular.module('app').directive('charSet', function() {
  return {
  	transclude: true,
  	scope: {
  		id: '=id'
  	},
  	controller: ['$scope', function CharSetCtrl($scope) {
  		$scope.messages = {};
  		$scope.insertMask = function(id, text) {
			$scope.insertAtCaret(id,text);
		};

		$scope.insertAtCaret = function(areaId,text) {
			var txtarea = document.getElementById(areaId);
			console.log(txtarea);
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
			$scope.messages[areaId] = txtarea.value;
		}



  	}],
    templateUrl: '/uib/template/charset/charset.html'
	};
});