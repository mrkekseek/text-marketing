<!DOCTYPE html>
<html data-ng-app="app" data-ng-controller="SurveyCtrl" ng-init="init({{ $seance or 0 }})">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>{{ config('app.name') }}</title>

	<link rel="stylesheet" type="text/css" href="/css/libs/bootstrap.min.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="/css/libs/font-awesome.min.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="/css/style.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="/css/main.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="/css/app.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="/css/{{ config('app.name') }}.css" media="screen" />
</head>
<body id="app" class="app">
	<div class="surveys-header">
		<div class="surveys_title">
			<div class="container">
			</div>
		</div>
	</div>

	
	<script src="/js/libs/angular.js"></script>
	<script src="/js/libs/ui.js"></script>
	<script src="/js/libs/ng-file-upload.min.js"></script>
	<script src="/js/survey.js"></script>
	<script src="/js/factories.js"></script>
</body>
</html>