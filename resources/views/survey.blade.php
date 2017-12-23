<!DOCTYPE html>
<html data-ng-app="app" data-ng-controller="SurveyCtrl" ng-init="init({{ $seance or 0 }}, {{ $questions or 0 }})">
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
					<h3>{{ empty($seance->review->user->company_name) ? $seance->review->user->teams->name : $seance->review->user->company_name }}</h3>
				</div>
			</div>
		</div>

		<a id="redirectClick" style="display: none" href="#"></a>
		
		<div class="container text-center" ng-show="thanks">
			<div class="form-group thanks-text">
				<b>{{ __('Thanks so much!') }}</b>
			</div>

			<div class="form-group url-btn-box" ng-show=" ! why">
				<div class="form-group" ng-repeat="url in seance.review.user.urls">
					<a href="javascript:;" ng-if="url.active" class="btn btn-default btn-@{{ url.name.toLowerCase() }}" ng-click="urlClick(url)">@{{ url.name }}</a>
				</div>
			</div>
		</div>

		<div class="questions-box" ng-show=" ! thanks">
			<div class="container">
				<div class="row">
					<div class="col-sm-4 col-sm-offset-4 col-xs-12" ng-repeat="question in questions" ng-show="question.type == 'star' || why">
						<div class="questions-text">
							<span>@{{ question.text }}</span>
						</div>

						<div class="questions-answers">
							<div class="radio" ng-show="question.type == 'star'" ng-repeat="i in [5, 4, 3, 2, 1, 0]">
								<label>
									<input type="radio" value="@{{ i }}" name="value" ng-model="question.value" ng-click="set(question)" />
									<span>
										<i class="fa fa-star surveys-stars" ng-repeat="s in range(i) track by $index"></i>
										<span ng-show=" ! i">{{ __('N/A') }}</span>
									</span>
								</label>
							</div>

							<textarea ng-show="question.type == 'essay'" ng-model="question.value" class="form-control"></textarea>
						</div>
					</div>
				</div>

				<div class="container text-center">
					<button class="btn btn-primary" ng-click="save()">{{ __('Finish') }}</button>
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