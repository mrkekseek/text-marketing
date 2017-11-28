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
				<h3>@{{ seance.survey.title }}</h3>
			</div>
		</div>
	</div>

	<div class="container text-center">
		<div class="form-group thanks-text" ng-show="show_thanks">
			<b>{{ __('Thanks so much!') }}</b>
		</div>
		<div class="form-group url-btn-box" ng-show="seance.show_reviews">
			<div class="form-group">
				<a href="" class="btn btn-primary btn-facebook"">Facebook</a>
			</div>
			<div class="form-group">
				<a href="" class="btn btn-primary btn-google"">Google</a>
			</div>
			<div class="form-group">
				<a href="" class="btn btn-primary btn-yelp"">Yelp</a>
			</div>
		</div>
	</div>

	<div class="questions-box" ng-show="! show_thanks">
		<div class="container">
			<div class="questions-item" ng-repeat="(key, question) in seance.survey.questions" ng-show="question.type == 'star' || bed_answer" ng-class="{'current': current == key, 'next': next == key, 'prev': prev == key, 'pre': pre == key}">
				<div class="questions-text">
					<span>@{{ question.text }}</span>
				</div>
				<div class="questions-answers">
					<div class="radio" ng-show="question.type == 'star'" ng-repeat="i in [5, 4, 3, 2, 1, 0]">
						<label>
							<input type="radio" value="@{{i}}" name="value" ng-model="question.value" ng-click="setAnswers(question)" />
							<span>
								<i class="fa fa-star surveys-stars" ng-repeat="s in repeatStars(i) track by $index"></i>
								<span ng-show="!i">{{ __('N/A') }}</span>
							</span>
						</label>
					</div>
					<textarea ng-show="question.type == 'essay'" ng-model="question.value" class="form-control"></textarea>
				</div>
			</div>
		</div>
	</div>
	<div class="container text-center">
		<button class="btn btn-primary" ng-show="bed_answer && ! show_thanks" ng-click="save()">{{ __('Finish') }}</button>
	</div>

	
	<script src="/js/libs/angular.js"></script>
	<script src="/js/libs/ui.js"></script>
	<script src="/js/libs/ng-file-upload.min.js"></script>
	<script src="/js/survey.js"></script>
	<script src="/js/factories.js"></script>
</body>
</html>